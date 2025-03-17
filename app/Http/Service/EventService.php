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

    public function update($id, array $input)
    {
        return $this->eventRepository->update($id, $input);
    }

    public function get($event_id)
    {
        return $this->eventRepository->get($event_id);
    }

    public function delete($id, $input)
    {
        return $this->eventRepository->delete($id);
    }

    public function createUser(array $input)
    {
        return $this->eventRepository->createUser($input);
    }

    public function deleteUser($id, $input)
    {
        return $this->eventRepository->deleteUser($id);
    }

    public function subscribe($id, array $input)
    {
        return $this->eventRepository->subscribe($id, $input);

    }
}
