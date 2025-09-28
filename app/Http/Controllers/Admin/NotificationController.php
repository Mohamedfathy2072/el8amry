<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\Firebase\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private FirebaseNotificationService $fcm;

    public function __construct(FirebaseNotificationService $fcm)
    {
        $this->fcm = $fcm;
    }

    /**
     * Show the notification creation form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('pages.notifications.create', compact('users'));
    }

    /**
     * Send a notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'send_type' => 'required|in:topic,users',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'topic' => 'required_if:send_type,topic',
            'user_ids' => 'required_if:send_type,users|array',
        ]);
        $dataPayload = ['type' => $request->send_type];
        try {

            if ($request->send_type === 'topic') {
                // Send to Topic
                $this->fcm->sendToTopic($request->topic, $request->title, $request->body, $dataPayload);
                Notification::create([
                    'title' => $request->title,
                    'body' => $request->body,
                    'type' => 'topic',
                    'topic'   => $request->topic,
                    'sent_at' => now(),
                    'user_id' => null,

                ]);

            } else {
                $users = User::whereIn('id', $request->user_ids)->get();

                foreach ($users as $user) {
                    if ($user->fcm_token) {
                        $this->fcm->sendToToken($user->fcm_token, $request->title, $request->body, $dataPayload);
                    }

                    Notification::create([
                        'title' => $request->title,
                        'body' => $request->body,
                        'type' => 'individual',
                        'sent_at' => now(),
                        'user_id' => $user->id
                    ]);
                }
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Notification sent successfully']);
            }

            return redirect()
                ->route('admin.notifications.create')
                ->with('success', 'Notification sent successfully');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display a listing of notifications.
     *
     * @return \Illuminate\View\View
     */
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest('sent_at')
            ->paginate(20);


        if (Auth::guard('admin')->check()) {
            Notification::where('user_id', Auth::guard('admin')->id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('pages.notifications.index', compact('notifications'));
    }
}
