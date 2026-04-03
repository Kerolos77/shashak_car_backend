<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\room;
use Illuminate\Http\Request;

class ChatController extends BaseController
{
    public function __construct(room $model)
        {
            parent::__construct($model);
        }
        public function index()  {
            $rooms = $this->model->with([
                'chat', 
                'trip', 
                'trip.driver',
                'latest_message',
                'trip.user', 
                'chat.sender', 
                'chat.receiver'
            ])
            ->whereHas('trip') // Only get rooms that have a trip
            ->orderBy('created_at', 'DESC')
            ->paginate(40);
            
            return view('admin.rooms.index' , compact(
                'rooms'
            ));
        }
    public function single($id)  {
        $row = $this->model->with([
            'chat', 
            'trip', 
            'trip.driver',
            'trip.user',
            'chat.sender', 
            'chat.receiver'
        ])->findOrFail($id);
        
        $moduleName = $this->getModelName();
        $pageTitle = "Chat Conversation";
        $pageDes = "View chat conversation details";
        $folderName = $this->getClassNameFromModel();
        $routeName = $folderName;
        
        return view('admin.rooms.single' , compact(
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
