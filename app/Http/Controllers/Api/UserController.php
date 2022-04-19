<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(UserService $service)
    {
        $data = $service->getUser(Auth::user());
        return response()->json($data);
    }

    public function getStatuses(UserService $service)
    {
        $data = $service->getStatuses(Auth::user());
        return response()->json($data);
    }

    public function updateStatus(UpdateStatusRequest $request, UserService $service)
    {
        $data = $service->updateStatus(Auth::user(), $request->validated());

        return response()->json($data);
    }

    public function getUnreadNotificationsNumber(UserService $service)
    {
        return response()->json($service->getUnreadNotificationsNumber());
    }

    public function getNotifications(UserService $service)
    {
        $notifications = $service->notifications();

        return response()->json($notifications);
    }

    public function markNotificationsAsRead(UserService $service)
    {
        $data = $service->markNotificationsAsRead();

        return response()->json($data);
    }
}
