<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'trigger_time' => 'date_format:Y-m-d H:i:s',
            'event_notify_channels' => 'array',
            'event_notify_channels.*' => 'integer|exists:notify_channels,id'
        ];
    }
}
