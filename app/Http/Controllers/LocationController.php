<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function states(Request $request): JsonResponse
    {
        $request->validate(['country_id' => ['required', 'exists:countries,id']]);

        $states = State::where('country_id', $request->integer('country_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    public function cities(Request $request): JsonResponse
    {
        $request->validate(['state_id' => ['required', 'exists:states,id']]);

        $cities = City::where('state_id', $request->integer('state_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
