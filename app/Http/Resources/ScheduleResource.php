<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'backgroundColor' => $this->schedule_color,
            'textColor' => $this->getTextColor($this->schedule_color),
            'className' => 'px-2 py-1 fw-bold',
            'use_datetime' => $this->use_datetime,
            'resizable' => false
        ];

        $data['start'] = $this->use_datetime ?
        Carbon::parse($this->start_datetime)->toIso8601String() :
        $this->start;

        $data['end'] = $this->use_datetime ?
        Carbon::parse($this->end_datetime)->toIso8601String() :
        $this->end;

        // $data['start'] = Carbon::parse($this->start_datetime)->format('Y-m-d\TH:i:s');
        // $data['end'] = Carbon::parse($this->end_datetime)->format('Y-m-d\TH:i:s');

        $this->link ? $data['url'] = $this->link: '';
        $this->description ? $data['description'] = $this->description : '';

        return $data;
    }

    private function getTextColor($bgColor) {
        if (strpos($bgColor, '#') === 0) {
            $bgColor = substr($bgColor, 1);
        }
    
        $r = hexdec(substr($bgColor, 0, 2)) / 255;
        $g = hexdec(substr($bgColor, 2, 4)) / 255;
        $b = hexdec(substr($bgColor, 4, 6)) / 255;

        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b);
        return $luminance > 0.5 ? 'black' : 'white';
    }
}
