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
        // Fetch overlapping dates directly from the database
        $overlappingDates = DB::table('bookings')
            ->where('room_id', $this->id)
            ->where('status', BookingStatus::CONFIRMED)
            ->where('check_out_date', '>=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                            $query->where('check_in_date', '<', $checkInDate)
                                    ->where('check_out_date', '>', $checkOutDate);
                        })
                        ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                            $query->where('check_in_date', '<=', $checkInDate)
                                    ->where('check_out_date', '>=', $checkOutDate);
                        });
                });
            })
            ->pluck('check_in_date', 'check_out_date');

        $unavailableDates = [];

        // Process each overlapping date range
        foreach ($overlappingDates as $start => $end) {
            $currentDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            while ($currentDate->lte($endDate)) {
                $unavailableDates[] = $currentDate->toDateString();

                // Move to the next date
                $currentDate->addDay();
            }
        }

        return array_unique($unavailableDates);
    }
}
