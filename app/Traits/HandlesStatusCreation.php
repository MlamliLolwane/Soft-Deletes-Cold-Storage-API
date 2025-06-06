<?php

namespace App\Traits;

use App\Models\ArchivedStatus;
use App\Models\Status;

trait HandlesStatusCreation
{
    public function createStatus(array $data): Status
    {
        return Status::create($data);
    }

    public function createArchivedStatus(array $data): ArchivedStatus
    {
        return ArchivedStatus::create($data);
    }
}
