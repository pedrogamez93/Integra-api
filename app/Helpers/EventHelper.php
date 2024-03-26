<?php

namespace App\Helpers;

use App\Event;
use DB;

class EventHelper
{
    public function numberTicketsAvailable($eventId)
    {
        $event = Event::select(DB::raw('tickets-number_reservations as available_events'))->where('id', $eventId)->first();
        if ($event != null) {
            return $event->available_events;
        }
        return 0;
    }
}
