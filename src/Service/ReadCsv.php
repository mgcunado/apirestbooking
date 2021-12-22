<?php

namespace App\Service;

class ReadCsv
{
    public function getDataFromcsv($user_id, $file): array
    {
        $csvdata = array_map(function ($v) {
            return str_getcsv($v, ";");
        }, file($file));

        $key_userid = array_search('user_id', $csvdata[0]);
        $key_accommodationid = array_search('accommodation_id', $csvdata[0]);
        $key_name = array_search('accommodation_name', $csvdata[0]);
        $key_type = array_search('accommodation_type', $csvdata[0]);
        $key_distribution = array_search('distribution', $csvdata[0]);
        $key_maxguests = array_search('max_guests', $csvdata[0]);
        $key_updateat = array_search('last_update', $csvdata[0]);

        $data = [];

        for ($i = 1; $i < count($csvdata); $i++) {
            $dayofweek = date('w', strtotime($csvdata[$i][$key_updateat]));
            $weekend = [0, 6];

            if ($csvdata[$i][$key_userid] == $user_id && in_array($dayofweek, $weekend)) {
                $id = $csvdata[$i][$key_accommodationid];
                $name = $csvdata[$i][$key_name];
                $type = $csvdata[$i][$key_type];
                $maxguests = $csvdata[$i][$key_maxguests];
                $updateat = (explode(" ", $csvdata[$i][$key_updateat]))[0];

                $distribution = json_decode($csvdata[$i][$key_distribution], true);
                $livingrooms = $distribution["living_rooms"];

                $bedrooms = count($distribution["bed_rooms"]);
                $beds_sum = 0;

                for ($j = 0; $j < $bedrooms; $j++) {
                    foreach ($distribution["bed_rooms"][$j] as $beds) {
                        $beds_sum += $beds;
                    }
                }
                $beds = $beds_sum;

                $data[] = [
                    'id' => $id,
                    'trade_name' => $name,
                    'type' => $type,
                    'distribution' => [
                        'living_rooms' => $livingrooms,
                        'bedrooms' => $bedrooms,
                        'beds' => $beds
                    ],
                    'max_guests' => $maxguests,
                    'updated_at' => $updateat,
                ];
            }
        }
        return $data;
    }
}
