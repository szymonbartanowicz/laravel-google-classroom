<?php

namespace App\Services;


use App\Models\Lesson;
use App\Models\Task;
use App\Notifications\NewTask;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function create(array $input)
    {
        $students = Lesson::find($input['lesson_id'])->users()->wherePivot('role', 'student')->select('id', 'name')->get()->toArray();

        if (array_unique($input['pickedStudents'], SORT_REGULAR) === $input['pickedStudents']) {
            foreach ($input['pickedStudents'] as $pickedStudent) {
                if (!in_array($pickedStudent, $students)) {
                    $error = ValidationException::withMessages(['pickedStudents' => 'Are you sure all those students joined to your lesson?']);

                    throw $error;
                }
            }
        } else {
            $error = ValidationException::withMessages(['pickedStudents' => 'Hey, there are no 2 the same people on the world!']);

            throw $error;
        }

        if ($input['allStudents']) {
            $access = 'public';
        } else {
            $access = 'private';
        }

        return $this->createTask($input, $access);
    }

    public function createTask(array $input, $access)
    {
        $input['access'] = $access;

        DB::beginTransaction();

        try {
            $data = Task::create($input);

            DB::commit();

            if ($input['access'] === 'private') {
                $this->assignStudentsToTask($input['pickedStudents'], $data, $input['lesson_id']);
            }

            $this->notifyStudentsAboutTask($input['pickedStudents'], $input['lesson_id']);

            return $data;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function assignStudentsToTask($students, $task, $lesson_id)
    {
        foreach ($students as $student) {
            $task->students()->attach($student['id'], ['lesson_id' => $lesson_id]);
        }
    }

    public function notifyStudentsAboutTask($students, $lesson_id)
    {
        $ids = Arr::pluck($students, 'id');
        $students = User::find($ids);

        $lesson = Lesson::find($lesson_id);

        DB::beginTransaction();

        Notification::send($students, new NewTask($lesson));

        try {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}