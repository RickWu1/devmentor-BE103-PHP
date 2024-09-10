<?php

namespace App\Http\Service;
use App\Http\Repository\EventRepository;

class EventService 
{
    private $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
    public function create(array $input)
    {
        return $this->eventRepository->create($input);
    }

    public function update($id,array $input)
    {
        return $this->eventRepository->update($id, $input);
    }
}