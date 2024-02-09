<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;
use App\Models\Travel;

class TravelController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/travels",
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
        $travel = Travel::create($validatedData);

        return response()->json($travel, 201);
    }
}
