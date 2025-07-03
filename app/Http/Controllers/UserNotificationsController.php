<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class UserNotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->notifications()->with('user')->latest();

        if ($request->filter === 'unread') {
            $query->where('read', false);
        } elseif ($request->filter === 'read') {
            $query->where('read', true);
        }

        $notifications = $query->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if (auth()->id() === $notification->user_id) {
            $notification->update(['read' => true]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 403);
    }
    public function markAllAsRead(Request $request)
{
    $user = auth()->user();
    $user->notifications()->update(['read' => true]);

    return redirect()->back()->with('success', 'All notifications marked as read.');
}
}
