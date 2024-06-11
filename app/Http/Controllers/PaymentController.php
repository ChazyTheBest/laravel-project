<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Enums\PaymentStatus;
use App\Enums\BookingStatus;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function mockup(Payment $payment)
    {
        return view('payment.mockup', [
            'payment' => $payment
        ]);
    }

    public function callback(Request $request, Payment $payment)
    {
        // Check if the user owns the booking associated with the payment
        $booking = $payment->booking()->with('profile.user')->first(); // Eager loading
        if ($booking === null || $booking->profile->user->id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized user or booking not found'], 403);
        }

        // Read response JSON
        $jsonResponse = $request->json()->all();

        // TODO: implement vendor security validation checks with the provider key
        // to make sure the requests are legitimate

        // Check if the booking has alreday been paid for
        if ($payment->status === PaymentStatus::SUCCESS) {
            $payment->handleResponseData($jsonResponse); // Append additional data if needed
            $payment->save();
            return response()->json(['error' => 'This booking has already been paid for.'], 403);
        }

        // Get the payment status from $jsonResponse
        $paymentStatusValue = $jsonResponse['payment_status'] ?? null;

        // Map payment status string to int values
        $paymentStatusValue = match ($paymentStatusValue) {
            'success', '1' => 1,
            'failed', '2' => 2,
            default => null, // Handle other cases if needed
        };

        // Validate payment status
        $paymentStatus = PaymentStatus::fromValue($paymentStatusValue);
        if ($paymentStatus === null) {
            $payment->status = PaymentStatus::FAILED;
            $payment->handleResponseData($jsonResponse); // Append additional data if needed
            $payment->save();
            return response()->json(['error' => 'Invalid payment status value'], 400);
        }

        // Map payment status to booking status
        $bookingStatus = match ($paymentStatus) {
            PaymentStatus::SUCCESS => BookingStatus::CONFIRMED,
            PaymentStatus::FAILED => BookingStatus::PAYMENT_FAILED,
            default => BookingStatus::PENDING,
        };

        // Update payment status and set response_data
        $payment->status = $paymentStatus;
        $payment->handleResponseData($jsonResponse); // Append additional data if needed
        $payment->save();

        // Update booking status
        $booking->status = $bookingStatus;
        $booking->save();

        return response()->json(
            $booking->status === BookingStatus::CONFIRMED
                ? ['success' => 'Booking success']
                : ['error' => 'Booking failed']
        );

        /*return $booking->status === BookingStatus::CONFIRMED
                    ? view('booking.confirmed')
                    : view('booking.failed');*/
    }
}
