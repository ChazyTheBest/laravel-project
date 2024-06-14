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
}
