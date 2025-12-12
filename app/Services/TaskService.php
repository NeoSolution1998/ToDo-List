<?php

namespace App\Services;

use App\Models\Task;
use App\DTO\TaskData;

class TaskService
{
    public function create(TaskData $data): Task
    {
        return Task::create([
            'title' => $data->title,
            'description' => $data->description,
            'status' => $data->status,
        ]);
    }

    public function update(Task $task, TaskData $data): Task
    {
        $task->update([
            'title' => $data->title,
            'description' => $data->description,
            'status' => $data->status,
        ]);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
