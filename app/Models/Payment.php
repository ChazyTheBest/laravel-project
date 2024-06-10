<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'response_data' => 'array',
        ];
    }

    /**
     * Get the booking that owns the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the payment status.
     */
    public function status()
    {
        return $this->hasOne(PaymentStatus::class);
    }

    /**
     * Get the payment billing information.
     */
    public function billingInfo()
    {
        return $this->hasOne(BillingInfo::class);
    }

    /**
     * Store or append response data.
     */
    public function handleResponseData(array $response)
    {
        $responses = $this->payment_responses ?? [];
        $responses[] = $response;
        $this->payment_responses = $responses;
    }
}
