<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'billing_info';

    /**
     * Get the payment that owns the billing information.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
