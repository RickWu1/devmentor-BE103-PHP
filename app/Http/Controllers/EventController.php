<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Service\EventService;
use App\Http\Transformer\GetEventsTransformer;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(GetEventsTransformer $transformer)
    {
        $events   = Event::with('eventNotifyChannels')->get();
        $response = $transformer->transform($events);
        return response()->json($response);
    }

    public function create(CreateEventRequest $request)
    {
        $event = $this->eventService->create($request->all());
        return response()->json($event);
    }

    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventService->update($id, $request->all());
        return response()->json($event);
    }

    public function get($event_id)
    {
        $event = $this->eventService->get($event_id);
        return response()->json($event);
    }

    public function delete($id, Request $request)
    {
        $event = $this->eventService->delete($id, $request);
        return response()->json($event);
    }

    public function creatUser(Request $request)
    {
        $event = $this->eventService->creatUser($request->all());
        return response()->json($event);

    }

    public function deleteUser($id, Request $request)
    {
        $event = $this->eventService->deleteUser($id, $request);
        return response()->json($event);
    }

    public function subscribe($id, Request $request)
    {
        $event = $this->eventService->subscribe($id, $request->all());
        return response()->json($event);
    }

}
