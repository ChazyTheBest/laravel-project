<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function mockup()
    {
        return view('payment.mockup');
    }

    public function callback(Payment $payment)
    {
        // read response json
        $json_response = [];

        // get the payment status from $json_response
        // write logic to map different statusses and format to an int
        // 0 pending, 1 success, 2 failed
        $payment_status = 1;
        // booking status is usually the same id order
        // 0 pending, 1 confirmed, 2 payment failed, 3 cancelled
        // so in this case a payment success means the booking is confirmed
        $booking_status = 1;

        $payment->status = PaymentStatus::fromValue($payment_status);
        $payment->handleResponseData($json_response); // appends it in case other transaction took place in the past
        $payment->save();

        $booking = $payment->booking();
        $booking->status = BookingStatus::fromValue($booking_status);
        $booking->save();

        // redirect user to booking success or fail
        return $booking->status === BookingStatus::CONFIRMED
                    ? view('booking.confirmed')
                    : view('booking.failed');
    }
}
