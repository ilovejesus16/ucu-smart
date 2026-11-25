<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;

class PublicBuildingController extends Controller
{
    /**
     * Return buildings for the public Flutter campus map.
     *
     * This endpoint intentionally does not require authentication.
     */
    public function index()
    {
        $buildings = Building::with('campusLocation')
            ->orderBy('building_name')
            ->get()
            ->map(function ($building) {
                $location = $building->campusLocation;

                return [
                    'id' => $building->id,
                    'code' => $building->building_code,
                    'name' => $building->building_name,

                    'category' => $location?->type ?? 'Building',

                    'description' => $building->description,

                    'floor_info' => null,

                    'location_details' => $location?->description,

                    // Campus map coordinates are stored as percentages.
                    'map_x_pct' => $location?->map_x,
                    'map_y_pct' => $location?->map_y,

                    // New buildings are not automatically connected
                    // to the navigation graph.
                    'route_node_id' => null,
                    'route_connection_verified' => false,

                    // Public campus-map visibility policy.
                    'is_active' => true,
                    'is_public' => true,
                ];
            });

        return response()->json([
            'data' => $buildings,
        ]);
    }
}
