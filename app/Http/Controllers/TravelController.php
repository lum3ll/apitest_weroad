<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;
use App\Models\Travel;
use App\Services\TravelService;

class TravelController extends Controller
{

    private $travelService;

    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    /**
     * @OA\Post(
     *     path="/api/addTravel",
     *     summary="Create a new travel",
     *     tags={"Travels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"isPublic", "slug", "name", "description", "numberOfDays", "moods"},
     *             @OA\Property(property="isPublic", type="boolean", example=true),
     *             @OA\Property(property="slug", type="string", example="amazing-trip"),
     *             @OA\Property(property="name", type="string", example="Amazing Trip"),
     *             @OA\Property(property="description", type="string", example="Description of the amazing trip"),
     *             @OA\Property(property="numberOfDays", type="integer", example=10),
     *             @OA\Property(property="moods", type="object", example={"adventure": 80, "relaxation": 20}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created new travel"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not authorized to create travel"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Check if the user is an admin
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden - User not authorized to create travel'], 403);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'isPublic'      => 'required|boolean',
            'slug'          => 'required|string|unique:api_travels',
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'numberOfDays'  => 'required|integer',
            'moods'         => 'required|array',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $validatedData = $validator->validated();

        // Create and save the new travel
        $travel = $this->travelService->createTravel($validatedData);

        return response()->json($travel, 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/travels/{id}",
     *     summary="Update an existing travel",
     *     tags={"Travels"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the travel to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="isPublic", type="boolean", example=true),
     *             @OA\Property(property="slug", type="string", example="updated-amazing-trip"),
     *             @OA\Property(property="name", type="string", example="Updated Amazing Trip"),
     *             @OA\Property(property="description", type="string", example="Updated description of the amazing trip"),
     *             @OA\Property(property="numberOfDays", type="integer", example=12),
     *             @OA\Property(property="moods", type="object", example={"adventure": 90, "relaxation": 10}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully updated the travel",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *             @OA\Property(property="isPublic", type="boolean", example=true),
     *             @OA\Property(property="slug", type="string", example="updated-amazing-trip"),
     *             @OA\Property(property="name", type="string", example="Updated Amazing Trip"),
     *             @OA\Property(property="description", type="string", example="Updated description of the amazing trip"),
     *             @OA\Property(property="numberOfDays", type="integer", example=12),
     *             @OA\Property(property="moods", type="object", example={"adventure": 90, "relaxation": 10}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not authorized to update travel"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found - The travel could not be found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Validation error on one or more fields"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Check if the user is an admin or editor
        if (!Auth::check() || !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))) {
            return response()->json(['message' => 'Forbidden - User not authorized to update travel'], 403);
        }

        $travel = Travel::findOrFail($id);

        $validatedData = $request->validate([
            'isPublic'      => 'sometimes|boolean',
            'slug'          => 'sometimes|string|unique:api_travels,slug,' . $travel->id,
            'name'          => 'sometimes|string|max:255',
            'description'   => 'sometimes|string',
            'numberOfDays'  => 'sometimes|integer',
            'moods'         => 'sometimes|array',
        ]);

        $updatedTravel = $this->travelService->updateTravel($travel, $validatedData);

        return response()->json($updatedTravel, 200);
    }
}
