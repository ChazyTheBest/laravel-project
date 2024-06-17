<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    //use SoftDeletes;

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the payment billing information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billingInfo(): HasOne
    {
        return $this->hasOne(BillingInfo::class);
    }

    /**
     * Store or append response data.
     *
     * @return void
     */
    public function handleResponseData(array $response): void
    {
        $responses = $this->response_data ?? [];
        $responses[] = $response;
        $this->response_data = $responses;
    }
}
