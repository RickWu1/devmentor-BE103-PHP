<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventNotifyChannel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Transformer\GetEventsTransformer;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Service\EventService;

class EventController extends Controller
{

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    public function index(GetEventsTransformer $transformer)
    {
        $events = Event::with('eventNotifyChannels')->get();
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
}