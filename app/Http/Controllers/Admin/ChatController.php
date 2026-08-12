<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\room;
use App\Models\Chat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatController extends BaseController
{
    public function __construct(room $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request = null)
    {
        $query = $this->model->with([
            'chat', 
            'trip', 
            'trip.driver',
            'latest_message',
            'trip.user', 
            'chat.sender', 
            'chat.receiver'
        ])
        ->whereHas('trip'); // Only get rooms that have a trip

        // Search by Driver (name or phone)
        if ($request->filled('driver')) {
            $driverSearch = $request->input('driver');
            $query->whereHas('trip.driver', function ($q) use ($driverSearch) {
                $q->where('name', 'like', "%{$driverSearch}%")
                  ->orWhere('phone_number', 'like', "%{$driverSearch}%");
            });
        }

        // Search by User (name or phone)
        if ($request->filled('user')) {
            $userSearch = $request->input('user');
            $query->whereHas('trip.user', function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                  ->orWhere('phone_number', 'like', "%{$userSearch}%");
            });
        }

        // Search by Trip ID
        if ($request->filled('trip')) {
            $tripId = $request->input('trip');
            $query->where('trip_id', $tripId);
        }

        $rooms = $query->orderBy('created_at', 'DESC')->paginate(30);

        // Stats calculation
        $totalRooms    = room::whereHas('trip')->count();
        $totalMessages = Chat::count();
        $todayRooms    = room::whereHas('trip')->whereDate('created_at', Carbon::today())->count();
        
        return view('admin.rooms.index', compact(
            'rooms',
            'totalRooms',
            'totalMessages',
            'todayRooms'
        ));
    }

    public function single($id)
    {
        $row = $this->model->with([
            'chat', 
            'trip', 
            'trip.driver',
            'trip.user',
            'chat.sender', 
            'chat.receiver'
        ])->findOrFail($id);
        
        $moduleName = $this->getModelName();
        $pageTitle  = "Chat Conversation";
        $pageDes    = "View chat conversation details";
        $folderName = $this->getClassNameFromModel();
        $routeName  = $folderName;
        
        return view('admin.rooms.single', compact(
            'pageTitle',
            'moduleName',
            'pageDes',
            'folderName',
            'routeName',
            'id',
            'row'
        ));
    }
}

