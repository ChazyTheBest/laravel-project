<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodAttribute extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_method_attribute';

    /**
     * Get the method that owns the attribute name.
     */
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the values for the attribute.
     */
    public function values()
    {
        return $this->hasMany(ProfilePaymentAttribute::class);
    }
}
