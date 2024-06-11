<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Http\Requests\StoreBookingRequest;

class RoomController extends Controller
{
    public function index()
    {
        return view('room.index', [
            'rooms' => Room::all()
        ]);
    }

    public function show(Room $room)
    {
        $request_validation = new StoreBookingRequest;
        $rules = $request_validation->rules();
        $messages = $request_validation->messages();

        return view('room.show', [
            'room' => $room,
            'rules' => [
                'check_in_date' => $rules['check_in_date'],
                'check_out_date' => $rules['check_out_date'],
            ],
            'messages' => [
                'check_in_date' => array_filter($messages, function($key) {
                    return strpos($key, 'check_in_date') !== false;
                }, ARRAY_FILTER_USE_KEY),
                'check_out_date' => array_filter($messages, function($key) {
                    return strpos($key, 'check_out_date') !== false;
                }, ARRAY_FILTER_USE_KEY),
            ]
        ]);
    }
}
