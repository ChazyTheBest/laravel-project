<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile';

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings for the profile.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the payment attributes for the profile.
     */
    public function paymentAttributes()
    {
        return $this->hasMany(ProfilePaymentAttributes::class);
    }

    /**
     * Get the payment methods for the profile.
     */
    public function paymentMethods()
    {
        return $this->paymentAttributes()->with('name.paymentMethod')
                                         ->groupBy('name.paymentMethod.name');
    }
}
