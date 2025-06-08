<?php

namespace App\Traits;

use App\Models\ArchivedTask;
use App\Models\Task;

trait HandleTaskCreation
{
    function createTask(array $data): Task
    {
        return Task::create($data);
    }

    function createArchivedTask(array $data): ArchivedTask
    {
        return ArchivedTask::create($data);
    }
}
