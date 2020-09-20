<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Repositories\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $model;

    /**
     * Construct an instance of NotificationController
     *
     * @param  Notification $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->model = new NotificationRepository($notification);
    }

    /**
     * Return all user's notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $notifications = $this->model->allByNotifiable('App\Profile', Auth('api')->id());

        return $notifications
            ? response()->json($notifications)
            : null;
    }

    /**
     * Return all user's unread notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUnread()
    {
        $notifications = $this->model->allByNotifiable('App\Profile', Auth('api')->id(), true);

        return $notifications
            ? response()->json($notifications)
            : null;
    }

    /**
     * Mark a notification as read.
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($id)
    {
        $notification = $this->model->show($id);

        if ($notification && $notification->notifiable_id != Auth('api')->id()) {
            return response()->json(config('messages.401'), 401);
        }

        return response()->json(
            $this->model->update(['read_at' => Carbon::now()], $id)
        );
    }
}
