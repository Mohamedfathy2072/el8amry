<?php

namespace App\Http\Controllers;

use App\Const\NotificationTypes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    public function getForUser(): JsonResponse
    {
        $user = Auth::user();


        if (!$user) {
            return ApiResponse::error('Unauthenticated.', 401);
        }

        $notifications = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('type', 'topic');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponse::success(
            NotificationResource::collection($notifications),
            'Notifications retrieved successfully'
        );
    }
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id',
        ]);

        Notification::whereIn('id', $request->notification_ids)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notifications marked as read successfully'
        ]);
    }
}
