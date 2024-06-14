<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'capacity',
        'beds',
        'name',
        'description',
        'price_per_night',
    ];

    /**
     * Get the bookings for the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Whether the room is available or not.
     *
     * @param string $checkInDate The booking check in date.
     * @param string $checkOutDate The booking check out date.
     * @return bool True if the room is available, false otherwise.
     */
    public function isAvailable($checkInDate, $checkOutDate): bool
    {
        $today = Carbon::today()->toDateString();

        // Check if the booking starts after or equal to today
        if ($checkInDate < $today || $checkOutDate < $today) {
            return false; // Booking cannot start in the past
        }

        return !$this->bookings()->where('status', BookingStatus::CONFIRMED)
            ->where('check_in_date', '>=', $today)
            ->where(function($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                      ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                      ->orWhere(function($query) use ($checkInDate, $checkOutDate) {
                          $query->where('check_in_date', '<', $checkInDate)
                                ->where('check_out_date', '>', $checkOutDate);
                      });
            })
            ->exists();
    }

    /**
     * Get the room's unavailable dates.
     *
     * @return array<string, string>
     */
    public function getUnavailableDates(): array
    {
        // Fetch overlapping dates directly from the database for all confirmed bookings
        $bookings = $this->bookings()
            ->where('room_id', $this->id)
            ->where('status', BookingStatus::CONFIRMED)
            ->where('check_out_date', '>=', Carbon::today()->toDateString())
            ->get(['check_in_date', 'check_out_date']);

        $unavailableDates = [];

        // Process each booking to collect all dates within the range
        foreach ($bookings as $booking) {
            $checkInDate = Carbon::parse($booking->check_in_date);
            $checkOutDate = Carbon::parse($booking->check_out_date);

            // Generate an array of dates within the range of each booking
            while ($checkInDate->lte($checkOutDate)) {
                $unavailableDates[] = $checkInDate->toDateString();
                $checkInDate->addDay();
            }
        }

        // Remove duplicates and return the array of unique unavailable dates
        return array_values(array_unique($unavailableDates));
    }
}
