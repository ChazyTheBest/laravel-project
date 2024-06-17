<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
        $jsonResponse = $request->json()->all();

        // TODO: implement vendor security validation checks with the provider key
        // to make sure the requests are legitimate
        if (!$this->isValidVendorRequest($jsonResponse)) {
            return response()->json(['error' => 'Invalid vendor request'], 400);
        }

        // Check if the user owns the booking associated with the payment
        $booking = $payment->booking()->with('profile.user')->first(); // Eager loading
        if ($booking === null || $booking->profile->user->id !== Auth::id()) {
            $payment->handleResponseData($jsonResponse);
            $payment->save();

            return response()->json(['error' => 'Unauthorized user or booking not found'], 403);
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
            $payment->handleResponseData($jsonResponse);
            $payment->save();

            return response()->json(['error' => 'Invalid payment status value'], 400);
        }

        // Important check for room availability and cancel the booking if it's not available
        if (!$booking->room->isAvailable($booking->check_in_date, $booking->check_out_date)) {
            $payment->status = $paymentStatus;
            $payment->handleResponseData($jsonResponse);
            $payment->save();

            $booking->status = BookingStatus::CANCELED;
            $booking->save();

            return response()->json(['error' => 'The room is no longer available for the chosen dates.'], 409);
        }

        // Check if the booking has already been paid for
        // is this really necessary?
        /*if ($payment->status === PaymentStatus::SUCCESS) {
            $payment->handleResponseData($jsonResponse);
            $payment->save();
            return response()->json(['error' => 'This booking has already been paid for.'], 409);
        }*/

        // Update payment status and set response_data
        $payment->status = $paymentStatus;
        $payment->handleResponseData($jsonResponse);
        $payment->save();

        // Map payment status to booking status
        $bookingStatus = match ($paymentStatus) {
            PaymentStatus::SUCCESS => BookingStatus::CONFIRMED,
            PaymentStatus::FAILED => BookingStatus::PAYMENT_FAILED,
            default => BookingStatus::PENDING,
        };

        // Update booking status
        $booking->status = $bookingStatus;
        $booking->save();

        return response()->json(
            $booking->status === BookingStatus::CONFIRMED
                ? ['success' => 'Booking success']
                : ['error' => 'Booking failed']
        );
    }

    /**
     * Validate the vendor request.
     *
     * @param array $jsonResponse
     * @return bool
     */
    private function isValidVendorRequest(array $jsonResponse): bool
    {
        // Implement your vendor validation logic here
        // Example: validate HMAC or signature in the request
        return true; // Placeholder
    }
}
