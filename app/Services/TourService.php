<?

namespace App\Services;

use App\Models\Tour;

class TourService
{
    public function createTour(array $data): Tour
    {
        $tour = Tour::create($data);

        return $tour;
    }

    public function updateTour(Tour $tour, array $data): Tour
    {
        $tour->update($data);

        return $tour;
    }
}