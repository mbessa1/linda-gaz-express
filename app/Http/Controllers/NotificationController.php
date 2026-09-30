<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    public function marquerLue($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['lu' => true]);

        return response()->json(['success' => true]);
    }

    public function marquerToutesLues()
    {
        Notification::where('user_id', Auth::id())
            ->where('lu', false)
            ->update(['lu' => true]);

        return response()->json(['success' => true]);
    }

    public function compter()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}