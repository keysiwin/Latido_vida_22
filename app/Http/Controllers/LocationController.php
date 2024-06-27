<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Person;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request, $personId)
    {
        $person = Person::findOrFail($personId);

        // Validar los datos del request
        $validatedData = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        // Crear un nuevo registro de ubicación
        $location = new Location();
        $location->latitud = $validatedData['latitud'];
        $location->longitud = $validatedData['longitud'];
        $location->person_id = $person->id;
        $location->save();

        return response()->json(['message' => 'Location added successfully', 'location' => $location], 201);
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, $personId, $locationId)
    {
        $person = Person::findOrFail($personId);
        $location = Location::findOrFail($locationId);

        // Validar los datos del request
        $validatedData = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        // Actualizar el registro de ubicación
        $location->latitud = $validatedData['latitud'];
        $location->longitud = $validatedData['longitud'];
        $location->save();

        return response()->json(['message' => 'Location updated successfully', 'location' => $location], 200);
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy($personId, $locationId)
    {
        $person = Person::findOrFail($personId);
        $location = Location::findOrFail($locationId);

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully'], 200);
    }
}
