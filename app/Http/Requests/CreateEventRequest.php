<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'trigger_time' => 'required|date_format:Y-m-d H:i:s',
            'event_notify_channels' => 'required|array',
            'event_notify_channels.*' => 'integer|exists:notify_channels,id'
        ];
    } 

}