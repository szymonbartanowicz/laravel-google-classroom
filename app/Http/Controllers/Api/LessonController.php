<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Services\LessonService;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\JoinLessonRequest;


class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LessonService $service)
    {
        $lessons = $service->index(Auth::user());

        return response()->json($lessons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonRequest $request, LessonService $service)
    {
        $lesson = $service->create($request->validated(), Auth::user());

        return response()->json($lesson);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, LessonService $service)
    {
        $lesson = $service->show($id);
        return response()->json($lesson);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function join(JoinLessonRequest $request, LessonService $service)
    {
        $lesson = $service->join($request->validated(), Auth::user());

        return response()->json($lesson);
    }

    public function getPeople($id, LessonService $service)
    {
        $people = $service->getPeople($id);

        return response()->json($people);
    }

    public function getTasks($id, LessonService $service)
    {
        $tasks = $service->getTasks($id, Auth::user());

        return response()->json($tasks);
    }

    public function isTeacher(Request $request, LessonService $service)
    {
        return response()->json($service->isTeacher($request->id));
    }

    public function getPosts($id, LessonService $service)
    {
        $posts = $service->getPosts($id, Auth::user());

        return response()->json($posts);
    }
}
