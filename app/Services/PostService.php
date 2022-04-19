<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Post;
use App\Notifications\NewMention;
use App\Notifications\NewTask;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostService
{
    public function create($input, $creator)
    {
        $input['user_id'] = $creator->id;

        DB::beginTransaction();

        try {
            $post = Post::create($input)->load('user:id,name');

            DB::commit();

            $this->notifyAboutMention($post->id, $input);

            return $post;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function notifyAboutMention($post_id, $input)
    {
        $computedUsers = $input['computedUsers'];
        $lesson = Lesson::find($input['lesson_id']);
        $users = $lesson->users()->select('id', 'name')->get()->toArray();

        $usersWithoutPivot = [];
        foreach ($users as $user) {
            $user = Arr::except($user, ['pivot']);
            $usersWithoutPivot = Arr::prepend($usersWithoutPivot, $user);
        }

        foreach ($computedUsers as $computedUser) {
            if (!in_array(Arr::except($computedUser, ['computedName']), $usersWithoutPivot)) {
                Post::destroy($post_id);
                exit();
            }
        }

        $words = array_unique(explode(' ', $input['body']));
        $mentions = [];
        foreach ($words as $word) {
            if (substr($word, 0, 1) === '@') {
                $mention = substr($word, 1);
                if (in_array($mention, Arr::pluck($computedUsers, 'computedName')) && $mention !== Auth::user()->name) {
                    $mentions = Arr::prepend($mentions, $mention);
                }
            }
        }

        $ids = [];
        foreach ($mentions as $mention) {
            foreach ($computedUsers as $computedUser) {
                if ($computedUser['computedName'] === $mention) {
                    $ids = Arr::prepend($ids, $computedUser['id']);
                }
            }
        }

        DB::beginTransaction();

        try {
            Notification::send(User::find($ids), new NewMention(Post::find($post_id)->user, $lesson));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}