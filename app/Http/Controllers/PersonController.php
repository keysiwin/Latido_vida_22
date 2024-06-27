<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::with('latestLocation')->get();
        return response()->json($persons);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|unique:persons',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'celular' => 'required|string',
            'estado' => 'required|string',
        ]);

        $person = new Person();
        $person->dni = $request->dni;
        $person->nombres = $request->nombres;
        $person->apellidos = $request->apellidos;
        $person->celular = $request->celular;
        $person->estado = $request->estado;
        $person->save();

        return response()->json(['message' => 'Persona creada correctamente'], 201);
    }

    public function show($id)
    {
        $person = Person::findOrFail($id);
        return response()->json($person);
    }

    public function update(Request $request)
    {
        $id = $request->query('id');
        $latitud = $request->query('latitud');
        $longitud = $request->query('longitud');
        
        $person = Person::where('dni', $id)->firstOrFail();

        $request->validate([
            'id' => 'required|string|exists:persons,dni',
            'estado' => 'required|string',
        ]);

        $person->latitud = $latitud;
        $person->longitud = $longitud;
        $person->save();

        return response()->json(['message' => 'Persona actualizada correctamente']);
    }

    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
        return response()->json(['message' => 'Persona eliminada correctamente']);
    }
}
