<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Company;
use App\Models\CourtWorkingHour;
use App\Models\Event;
use App\Repositories\Interfaces\CategoryInterface;
use App\Traits\CheckBookingOverlapTrait;
use Illuminate\Http\JsonResponse;

class FetchCategoryAvailableTimesService
{
    use CheckBookingOverlapTrait;
    /**
     * Fetch Category Available Time
     * @param string $date
     * @param int $duration
     * @return JsonResponse
     */
    public function fetchAvailableTime(string $date, int $duration): JsonResponse
    {
        $courts = Category::find(1)->Courts;
        $timeArray = [];
        foreach ($courts as $court) {
            $checkIfDayExist = CourtWorkingHour::where([
                'court_id' => $court->id,
                'day_of_week_text' => date('l', strtotime($date))
            ]);
            if($checkIfDayExist->exists()){
                $slotTime = Company::find(1)->slot_time * 60;
                $startTimeInString = strtotime(date('Y-m-d', strtotime($date)) . ' ' . $checkIfDayExist->first()->start_time);

                if($checkIfDayExist->first()->end_time < $checkIfDayExist->first()->start_time) {
                    $endTimeInString = strtotime(date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d', strtotime($date))))) . ' ' . $checkIfDayExist->first()->end_time);
                }else {
                    $endTimeInString = strtotime(date('Y-m-d', strtotime($date)) . ' ' . $checkIfDayExist->first()->end_time);
                }
                for ($time = $startTimeInString; $time <= $endTimeInString - ($duration * 60); $time+=$slotTime) {
/*                    if (date('Y-m-d H:i:s', strtotime($date . ' ' . date('H:i:s',  $time))) > date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime(now())))) {*/
                    if ($time > strtotime('-30 minutes', strtotime(now())) || (auth('api')->check() && auth('api')->user()->hasRole('super_admin'))) {


                        $returnedEndDate = date('Y-m-d H:i:s', strtotime('+' . $duration . ' minutes', $time));

                        $data = [
                            'court_id' => $court->id,
                            'start_time' => date('Y-m-d H:i:s', $time),
                            'end_time' => $returnedEndDate
                        ];

                        array_push($timeArray, [
                            'court_id' => $court->id,
                            'court_name' => $court->name_en,
                            'time' => date('h:i A', $time),
                            'time24Format' => date('H:i:s', $time),
                            'time24FormatString' => $time,
                            'date' => date('Y-m-d', strtotime($date)),
                            'duration' => $duration,
                            'now' => date('Y-m-d H:i:s', strtotime(now())),
                            'time2' => date('Y-m-d H:i:s', $time),
                            'disabled' => !$this->checkBookingOverlap($data),
                            'end_date' => $returnedEndDate,
                            'matching_courts' => []
                        ]);
                    }
                }
            }
        }

        dd($timeArray);

        $testArray = $timeArray;
        $finalArray = [];

        foreach ($testArray as $testItem) {
            $filteredArray = array_filter($timeArray, function ($item) use($testItem){
                return $item["time2"] === $testItem['time2'];
            });
            $filteredArray = array_values($filteredArray);
            if (count($filteredArray) > 0) {
                $filteredArrayWithDisabled = array_filter($filteredArray, function ($item) {
                    return $item["disabled"] === true;
                });

                if (count($filteredArrayWithDisabled) == count($filteredArray)) { // all disabled, unique any, matching null
                    array_push($finalArray, $filteredArrayWithDisabled[0]);

                }elseif (count($filteredArrayWithDisabled) === 0) {
                    $matchingArray = [];
                    foreach ($filteredArray as $matchingItem) {
                        array_push($matchingArray, $matchingItem['court_id']);
                    }
                    $filteredArray[0]['matching_courts'] = $matchingArray;
                    array_push($finalArray, $filteredArray[0]);
                }elseif ((count($filteredArrayWithDisabled) < count($filteredArray))) {
                    $matchingArray = [];

                    $filterExcludeDisabled = array_filter($filteredArray, function ($item) {
                        return $item["disabled"] === true;
                    });

                    foreach ($filterExcludeDisabled as $key => $value) {
                        unset($filteredArray[$key]);
                    }

                    foreach ($filteredArray as $filteredItem) {
                        array_push($matchingArray, $filteredItem['court_id']);
                    }
                    $filteredArray = array_values($filteredArray);

                    $filteredArray[0]['matching_courts'] = $matchingArray;

                    array_push($finalArray, $filteredArray[0]);
                }
            }
            $timeArray = $this->unsetValue($timeArray, $testItem['time2']);
        }

        usort($finalArray, function ($item1, $item2) {
            return $item1['time24FormatString'] <=> $item2['time24FormatString'];
        });

        $output = ([
            'data' => $finalArray
        ]);
        return response()->json($output);
    }

    function unsetValue(array $array, $value)
    {
        $filteredArray = array_filter($array, function ($item) use($value){
            return $item["time2"] === $value;
        });
        foreach ($filteredArray as $key => $value) {
            unset($array[$key]);
        }
        return $array;
    }

    /*public function checkBookingsAfter(array $data): bool
    {
        $bookings = Booking::where('court_id', $data['court_id'])
            ->where('end_time', '>=', $data['start_time'])
            ->where('start_time', '<=', $data['end_time'])
            ->get();

        if (count($bookings) == 0) {
            $events = Event::where('court_id', $data['court_id'])
                ->where('end_time', '>=', $data['start_time'])
                ->where('start_time', '<=', $data['end_time'])
                ->get();
            if (count($events) == 0) {
                return true;
            }
        }
        return false;
    }

    function searchForTime($time, $date, $array) {
        $duplicatedArray = [];
        foreach ($array as $key => $val) {
            if ($val['time'] === $time && $val['date'] === $date) {
                array_push($duplicatedArray, $val);
            }
        }
        return $duplicatedArray;
    }*/
}
