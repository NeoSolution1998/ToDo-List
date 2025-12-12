<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\DTO\TaskData;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->service->create(TaskData::fromArray($request->validated()));
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated() + [
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status
        ];
        $task = $this->service->update($task, TaskData::fromArray($data));
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->service->delete($task);
        return response()->json(['message' => 'Task deleted'], 200);
    }
}
