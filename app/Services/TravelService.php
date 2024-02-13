<?php

namespace App\Services;

use \App\Models\Travel;

class TravelService
{
    public function createTravel(array $data): Travel
    {
        $data['numberOfNights'] = $data['numberOfDays'] - 1;
        if (isset($data['moods']) && is_array($data['moods'])) {
            $data['moods'] = json_encode($data['moods']);
        }

        $travel = Travel::create($data);

        return $travel;
    }

    public function updateTravel(Travel $travel, array $data): Travel
    {
        if (isset($data['numberOfDays'])) {
            $data['numberOfNights'] = $data['numberOfDays'] - 1;
        }

        if (isset($data['moods']) && is_array($data['moods'])) {
            $data['moods'] = json_encode($data['moods']);
        }

        $travel->update($data);

        return $travel;
    }
}