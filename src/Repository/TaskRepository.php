<?php

namespace App\Repository;

use App\Controller\SerializerInterface;
use Doctrine\Persistence\ObjectRepository;

class TaskRepository
{

    private ObjectRepository        $repository;
    private SerializerInterface     $responseParser;

    public function __construct(
        ObjectRepository $repository,
        SerializerInterface $responseParser
    ) {
        $this->repository     = $repository;
        $this->responseParser = $responseParser;
    }

    public function getAll()
    {
        $res = array_map(
            static function ($item) {
                return [
                    'id'          => $item->getId(),
                    'title'       => $item->getTitle(),
                    'description' => $item->getDescription(),
                    'status'      => $item->getStatus(),
                ];
            },
            $this->repository->findAll()
        );

        return $this->responseParser->parse($res);
    }

    public function getById($id)
    {
        $task = $this->repository->findOneBy(['id' => $id]);
        $res = [
            'id'          => $task->getId(),
            'title'       => $task->getTitle(),
            'description' => $task->getDescription(),
            'status'      => $task->getStatus(),
        ];

        return $this->responseParser->parse($res);
    }
}