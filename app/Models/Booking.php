<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\BookingStatus;

class Booking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'room_id',
        'check_in_date',
        'check_out_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
        ];
    }

    /**
     * Get the profile that owns the booking.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the booked room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the payment for the booking.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
