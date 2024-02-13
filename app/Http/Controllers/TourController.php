<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use App\Services\TourService;

class TourController extends Controller
{
    protected $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    /**
     * @OA\Get(
     *    path="/api/tours/{slug}",
     *    summary="Retrieve a list of tours",
     *    tags={"Tours"},
     *    @OA\Parameter(
     *       name="slug",
     *       in="path",
     *       required=true,
     *       description="The slug of the travel",
     *       @OA\Schema(type="string")
     *    ), 
     *    @OA\Parameter(
     *       name="dateFrom",
     *       in="query",
     *       description="optional starting date filter",
     *       @OA\Schema(type="string", format="date")
     *    ), 
     *    @OA\Parameter(
     *       name="dateTo",
     *       in="query",
     *       description="optional ending date filter",
     *       @OA\Schema(type="string", format="date")
     *    ), 
     *    @OA\Parameter(
     *       name="priceFrom",
     *       in="query",
     *       description="optional starting price filter",
     *       @OA\Schema(type="string", format="float")
     *    ), 
     *    @OA\Parameter(
     *       name="priceTo",
     *       in="query",
     *       description="optional ending price filter",
     *       @OA\Schema(type="string", format="float")
     *    ), 
     *    @OA\Parameter(
     *       name="sort",
     *       in="query",
     *       description="Define the sort order",
     *       @OA\Schema(type="string", enum={"asc", "desc"})
     *    ),
     *    @OA\Response(
     *      response="200", 
     *      description="Successfully retrieve the tours list"
     *    ),
     *    @OA\Response(
     *      response="421", 
     *      description="Error retrievie the tours list"
     *    )
     * )
     */

     public function index(Request $request, $slug)
     {
         $query = Tour::query()->whereHas('travel', function ($query) use ($slug) {
             $query->where('slug', $slug);
         });
         
         if ($request->has('priceFrom')) {
             $query->where('price', '>=', $request->priceFrom * 100);
         }
         if ($request->has('priceTo')) {
             $query->where('price', '<=', $request->priceTo * 100);
         }
         if ($request->has('dateFrom')) {
            $dateFrom = \DateTime::createFromFormat('d/m/Y', $request->dateFrom);
            if ($dateFrom) {
                $query->where('startingDate', '>=', $dateFrom->format('Y-m-d'));
            }
        }
        if ($request->has('dateTo')) {
            $dateTo = \DateTime::createFromFormat('d/m/Y', $request->dateTo);
            if ($dateTo) {
                $query->where('endingDate', '<=', $dateTo->format('Y-m-d'));
            }
        }
 
         $sortDirection = $request->input('sort', 'asc');
         $query->orderBy('price', $sortDirection);
 
         $query->orderBy('startingDate', 'asc');
 
         $inPage = $request->input('inPage', 10);
         $tours = $query->paginate($inPage);

         return response()->json($tours);
    }

    /**
     * @OA\Post(
     *    path="/api/travels/{slug}/tours",
     *    summary="Create a new tour for a given travel",
     *    tags={"Tours"},
     *    security={{"bearerAuth":{}}},
     *    @OA\Parameter(
     *       name="slug",
     *       in="path",
     *       required=true,
     *       description="The slug of the travel to which the tour belongs",
     *       @OA\Schema(type="string")
     *    ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"name", "startingDate", "endingDate", "price"},
     *          @OA\Property(property="name", type="string", description="Unique name of the tour"),
     *          @OA\Property(property="startingDate", type="string", format="date", description="Starting date of the tour"),
     *          @OA\Property(property="endingDate", type="string", format="date", description="Ending date of the tour"),
     *          @OA\Property(property="price", type="number", description="Price of the tour in cents"),
     *       ),
     *    ),
     *    @OA\Response(
     *       response="200",
     *       description="Tour successfully created"
     *    ),
     *    @OA\Response(
     *       response="403",
     *       description="Forbidden - User not authorized to create tours"
     *    ),
     *    @OA\Response(
     *       response="422",
     *       description="Unprocessable Entity - Validation failed for the tour data"
     *    )
     * )
     */
    public function store(Request $request, $slug)
    {
        // Check if the user is authorized to create tours
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden - User not authorized to create tours'], 403);
        }

        // Validate the request data
        $validated = $request->validate([
            'travelId' => 'required|exists:api_travels,id',
            'name' => 'required|string|unique:api_tours',
            'startingDate' => 'required|date_format:Y-m-d',
            'endingDate' => 'required|date_format:Y-m-d|after_or_equal:startingDate',
            'price' => 'required|numeric',
        ]);

        // Find the travel by slug
        $travel = \App\Models\Travel::where('slug', $slug)->first();
        
        if (!$travel) {
            return response()->json(['message' => 'Travel not found'], 422);
        }

        // Create a new tour linked to the travel
        $validated['travel_id'] = $travel->id;
        $tour = $this->tourService->createTour($validated);

        return response()->json($tour, 200);
    }

}
