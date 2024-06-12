<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\BookingStatus;
use Carbon\Carbon;

class Room extends Model
{
    use HasFactory;

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
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable($startDate, $endDate)
    {
        $bookings = $this->bookings()->where('status', BookingStatus::CONFIRMED)
                                      ->where(function ($query) use ($startDate, $endDate) {
                                          $query->whereBetween('check_in_date', [$startDate, $endDate])
                                                ->orWhereBetween('check_out_date', [$startDate, $endDate])
                                                ->orWhere(function ($query) use ($startDate, $endDate) {
                                                    $query->where('check_in_date', '<', $startDate)
                                                          ->where('check_out_date', '>', $endDate);
                                                });
                                      })
                                      ->exists();

        return !$bookings;
    }

    public function getUnavailableDates()
    {
        // Fetch all confirmed bookings related to the instance calling the method
        $bookings = $this->bookings()
                        ->where('status', BookingStatus::CONFIRMED)
                        ->where('check_out_date', '>=', Carbon::now(config('app.timezone'))->toDateString())
                        ->get();

        $unavailableDates = [];

        // Iterate through each booking to check for overlaps
        foreach ($bookings as $booking) {
            // Get the check-in and check-out dates of the current booking
            $checkInDate = $booking->check_in_date;
            $checkOutDate = $booking->check_out_date;

            // Iterate through the dates in the range of the current booking
            $currentDate = Carbon::parse($checkInDate);
            $endDate = Carbon::parse($checkOutDate);
            while ($currentDate->lte($endDate)) {
                // Check if the current date is already marked as unavailable
                if (!in_array($currentDate->toDateString(), $unavailableDates)) {
                    // Mark the current date as unavailable
                    $unavailableDates[] = $currentDate->toDateString();
                }
                // Move to the next date
                $currentDate->addDay();
            }
        }

        return $unavailableDates;
    }
}
