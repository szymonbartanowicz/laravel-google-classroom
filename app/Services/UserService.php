<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getUser($user)
    {
        return $user->only('id', 'name');
    }

    public function getStatuses($user)
    {
        $statuses = User::STATUSES;

        foreach ($statuses as $key => $value) {
            $status['name'] = $value;

            switch ($value) {
                case 'away':
                    $status['class'] = 'bg-yellow-300';
                    break;
                case 'busy':
                    $status['class'] = 'bg-red-500';
                    break;
                case 'invisible':
                    $status['class'] = 'bg-gray-300';
                    break;
                default:
                    $status['class'] = 'bg-green-400';
            }

            $statuses[$key] = $status;
        };

        $currentStatus['name'] = $user->status()->value('name');

        foreach ($statuses as $status) {
            if ($status['name'] === $currentStatus['name']) {
                $currentStatus['class'] = $status['class'];
            }
        }

        return ['currentStatus' => $currentStatus, 'statuses' => $statuses];
    }

    public function updateStatus($user, $status)
    {
        DB::beginTransaction();

        try {
            $updated = $user->status()->update($status);
            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function getUnreadNotificationsNumber()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->count() ? Auth::user()->notifications()->select('data')->get() : null;

        return ['notifications' => $notifications];
    }

    public function markNotificationsAsRead()
    {
        return Auth::user()->unreadNotifications->markAsRead();
    }
}