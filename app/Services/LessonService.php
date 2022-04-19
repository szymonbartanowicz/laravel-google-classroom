<?php

namespace App\Services;

use App\Models\Lesson;
use App\Notifications\NewStudent;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LessonService
{
    public function index($user)
    {
        $createdLessons = $user->createdLessons()->count() ? $user->createdLessons()->with('user:id,name')->latest()->get() : null;
        $joinedLessons = $user->joinedLessons()->count() ? $user->joinedLessons()->with('user:id,name')->latest()->get() : null;

        return ['createdLessons' => $createdLessons, 'joinedLessons' => $joinedLessons];
    }

    public function create(array $input, $creator)
    {
        $input['user_id'] = $creator->id;

        $key = $this->createKey();
        $input['key'] = $key;

        $input['background'] = $this->pickBackground();


        DB::beginTransaction();

        try {
            $data = Lesson::create($input)->load('user:id,name');

            $creator->lessons()->attach($data->id, ['role' => 'teacher', 'created' => true]);

            DB::commit();

            return $data;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function createKey()
    {
        do {
            $key = Str::random(14);
        } while (Lesson::where('key', $key)->exists());

        return $key;
    }

    public function join(array $input, $newcomer)
    {
        $key = $input['key'];

        $lesson = Lesson::byKey($key)->first();

        if ($lesson) {

            $creator_id = $lesson->user()->value('id');
            $newcomer_id = $newcomer->id;

            $alreadyJoined = $lesson->users()->where('id', $newcomer_id)->exists();

            if ($alreadyJoined) {
                $error = ValidationException::withMessages(['key' => 'You have already joined to this lesson.']);

                throw $error;
            }

            if ($creator_id === $newcomer_id) {
                $error = ValidationException::withMessages(['key' => 'You are the creator of this lesson.']);

                throw $error;
            }

            DB::beginTransaction();

            try {
                $lesson->users()->attach($newcomer->id);

                DB::commit();

                $lesson = $newcomer->lessons()->where('lesson_id', $lesson->id)->with('user:id,name')->first();

                $this->notifyTeacherAboutNewStudent($creator_id, $newcomer);

                return $lesson;
            } catch (\Exception $e) {
                DB::rollBack();

                throw $e;
            }
        } else {
            $error = ValidationException::withMessages(['key' => 'Incorrect key!']);

            throw $error;
        }
    }

    public function notifyTeacherAboutNewStudent($creator_id, $newcomer)
    {
        $creator = User::find($creator_id);

        DB::beginTransaction();

        try {
            $creator->notify(new NewStudent($newcomer));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function pickBackground()
    {
        $classes = [
            'bg-gray-400',
            'bg-red-400',
            'bg-orange-400',
            'bg-yellow-400',
            'bg-green-400',
            'bg-teal-400',
            'bg-blue-400',
            'bg-indigo-400',
            'bg-purple-400',
            'bg-pink-400'
        ];

        $background = Arr::random($classes);

        return $background;
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            return 404;
        }

        $lesson = Lesson::find($id);

        if ($this->hasPermissionForLesson($id)) {
            $numberOfStudents = $lesson->users()->count();

            $people = $lesson->users()->select('id', 'name')->get();

            return ['lesson' => $lesson, 'numberOfStudents' => $numberOfStudents, 'people' => $people];
        } else {
            return 404;
        }
    }

    public function getPeople($id)
    {
        if (!is_numeric($id)) {
            return 404;
        }
        $lesson = Lesson::find($id);

        if ($this->hasPermissionForLesson($lesson)) {
            $creator = $lesson->user()->select('id', 'name')->get();
            $people = $lesson->users()->wherePivot('created', 0)->count() ? $lesson->users()->wherePivot('created', 0)->select('id', 'name')->get() : null;

            return ['creator' => $creator, 'people' => $people];
        } else {
            return 404;
        }
    }

    public function getTasks($id, $user)
    {
        if (!is_numeric($id)) {

            return 404;
        }

        $lesson = Lesson::find($id);

        if ($this->hasPermissionForLesson($lesson)) {
            $publicTasks = $lesson->tasks()->byAccess('public')->latest()->get();
            $privateTasks = $user->tasks()->wherePivot('lesson_id', $id)->latest()->get();
            $tasks = $publicTasks->merge($privateTasks)->sortBy('created_at');

            return $tasks->values()->all();

        } else {

            return 404;
        }
    }

    public function isTeacher($id)
    {
        if (Lesson::find($id)->user()->value('id') === Auth::id()) {

            return true;
        } else {

            return false;
        }
    }

    public function hasPermissionForLesson($lesson)
    {
        $lessons = Auth::user()->lessons;

        if ($lessons->contains($lesson)) {

            return true;
        } else {

            return false;
        }
    }

    public function getPosts($id)
    {
        if ($this->hasPermissionForLesson($id)) {
            $posts = Lesson::find($id)->posts()->with('user:id,name')->latest()->get();

            return $posts;
        }
    }

}