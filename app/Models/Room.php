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
        $bookings = $this->bookings()->where('status', BookingStatus::CONFIRMED);

        $today = Carbon::now(config('app.timezone'));
        $latestBookedDate = $bookings->where('check_out_date', '>=', $today)
                                    ->orderBy('check_out_date', 'asc')
                                    ->value('check_out_date');

        if (!$latestBookedDate) {
            return [];
        }

        $unavailableDates = $bookings->where('check_in_date', '>=', $today)
                                    ->where('check_out_date', '<=', $latestBookedDate)
                                    ->pluck('check_in_date')
                                    ->merge($bookings->pluck('check_out_date'))
                                    ->unique()
                                    ->toArray();

        return $unavailableDates;
    }
}
