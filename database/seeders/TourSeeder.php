<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tour;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tour::create([
            "travelId" => "d408be33-aa6a-4c73-a2c8-58a70ab2ba4d",
            "name" => "ITJOR20211101",
            "startingDate" => "2021-11-01",
            "endingDate" => "2021-11-09",
            "price" => 199900
        ]);

        Tour::create([
            "travelId" => "d408be33-aa6a-4c73-a2c8-58a70ab2ba4d",
            "name" => "ITJOR20211112",
            "startingDate" => "2021-11-12",
            "endingDate" => "2021-11-20",
            "price" => 189900
        ]);

        Tour::create([
            "travelId" => "d408be33-aa6a-4c73-a2c8-58a70ab2ba4d",
            "name" => "ITJOR20211125",
            "startingDate" => "2021-11-25",
            "endingDate" => "2021-12-03",
            "price" => 214900
        ]);

        Tour::create([
            "travelId" => "4f4bd032-e7d4-402a-bdf6-aaf6be240d53",
            "name" => "ITICE20211101",
            "startingDate" => "2021-11-01",
            "endingDate" => "2021-11-08",
            "price" => 199900
        ]);

        Tour::create([
            "travelId" => "cbf304ae-a335-43fa-9e56-811612dcb601",
            "name" => "ITARA20211221",
            "startingDate" => "2021-12-21",
            "endingDate" => "2021-12-28",
            "price" => 189900
        ]);

        Tour::create([
            "travelId" => "cbf304ae-a335-43fa-9e56-811612dcb601",
            "name" => "ITARA20211221",
            "startingDate" => "2022-01-03",
            "endingDate" => "2022-01-10",
            "price" => 149900
        ]);
    }
}
