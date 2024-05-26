<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePaymentAttribute extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile_payment_attribute';

    /**
     * Get the profile that owns the attribute.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the attribute name.
     */
    public function name()
    {
        return $this->belongsTo(PaymentMethodAttribute::class);
    }
}
