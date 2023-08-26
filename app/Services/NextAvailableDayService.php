<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Company;
use App\Models\CourtWorkingHour;
use App\Traits\CheckBookingOverlapTrait;
use Illuminate\Http\JsonResponse;

class NextAvailableDayService
{
    use CheckBookingOverlapTrait;
    /**
     * Fetch Category Available Time
     * @param string $date
     * @param int $duration
     * @return JsonResponse
     */
    public function nextAvailableDay(string $date, int $duration): JsonResponse
    {
        for ($i=1; $i<30; $i++) {
            $nextDay = date('Y-m-d', strtotime('+ ' . $i . ' days', strtotime($date)));
            $nextDayOfWeek = date('l', strtotime('+ ' . $i . ' days', strtotime($date)));
            $courts = Category::find(1)->Courts;
            foreach ($courts as $court) {
                $checkIfDayExist = CourtWorkingHour::where([
                    'court_id' => $court->id,
                    'day_of_week_text' => $nextDayOfWeek
                ]);
                if($checkIfDayExist->exists()){
                    $slotTime = Company::find(1)->slot_time * 60;
                    $startTimeInString = strtotime($checkIfDayExist->first()->start_time);
                    $endTimeInString = strtotime($checkIfDayExist->first()->end_time);

                    for ($time = $startTimeInString; $time <= $endTimeInString - ($duration * 60); $time+=$slotTime) {
                        if (date('Y-m-d H:i:s', strtotime($nextDay . ' ' . date('H:i:s',  $time))) > date('Y-m-d H:i:s', strtotime(now()))) {
                            $data = [
                                'court_id' => $court->id,
                                'start_time' => date('Y-m-d H:i:s', strtotime($nextDay . ' ' . date('H:i:s',  $time))),
                                'end_time' => date('Y-m-d H:i:s', strtotime($nextDay . ' ' . date('H:i:s',  $time + ($duration * 60))))
                            ];
                            if ($this->checkBookingOverlap($data)) {
                                $output = ([
                                    'data' => $nextDay
                                ]);
                                return response()->json($output);
                            }
                        }
                    }
                }
            }
        }

        $output = ([
            'data' => null
        ]);
        return response()->json($output);
    }
}
