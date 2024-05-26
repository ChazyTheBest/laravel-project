<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_method';

    /**
     * Get the payments made with this method.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the method attribute names.
     */
    public function attributeNames()
    {
        return $this->hasMany(PaymentMethodAttribute::class);
    }
}
