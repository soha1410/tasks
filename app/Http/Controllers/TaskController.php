<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Mention;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function lists()
    {
        $user = auth()->user();
        if ($user->role == 'admin')
            $tasks = Task::all();
        else
            $tasks = $user->tasks;
        return TaskResource::collection($tasks);
    }
    public function create(CreateTaskRequest $request)
    {
        $user = auth()->user();
        //create task
        $task = new Task;
        $task->title = $request->title;
        $task->desc = $request->desc;
        $task->save();

        $task->attachMentionedUsers();
        $task->attachUser($user);
        return (new TaskResource($task))->response()->setStatusCode(201);
    }
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $user = auth()->user();
        if (!$task->isAccessibleForUser($user)) {
            return response('you are now allowed to update this task', 403);
        }
        $task->title = $request->title;
        $task->desc = $request->desc;
        $task->update();
        $task->attachMentionedUsers();
        return (new TaskResource($task))->response()->setStatusCode(201);
    }
    public function delete(Task $task)
    {
        $user = auth()->user();
        if (!$task->isAccessibleForUser($user)) {
            return response('you are now allowed to delete this task', 403);
        }
        $task->delete();
        return response('task deleted', 201);
    }
    public function mention(Task $task)
    {
        $user = auth()->user();
        if (!$user->isAdministrator()) {
            return response('you are now allowed to join this task', 403);
        }
        $task->attachUser($user);

        return new TaskResource($task);
    }
}
