<?php

namespace App\Services;

use App\Entities\Task;

class TasksValidator implements ValidatorInterface
{

    private const REQUIRED = [
        'title'
    ];

    public static function isValid($data)
    {
        foreach (self::REQUIRED as $field) {
            if ( ! isset($data[$field])) {
                throw new \BadMethodCallException(
                    "The {$field} is required and is missing"
                );
            }

            $validStatus = [Task::STATUS_NEW, Task::STATUS_DONE];

            if (isset($data['status']) && ! in_array((int)$data['status'], $validStatus, true)) {
                throw new \BadMethodCallException(sprintf('Invalid status passed. Possible values are %s', implode(', ', $validStatus)));
            }
        }
    }
}