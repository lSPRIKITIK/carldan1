<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'New client added successfully!');
    }
    public function destroy(Client $client)
    {
        if ($client->orders()->exists()) {
            return redirect()->route('clients.index')->with('error', 'Cannot delete this client because they are linked to an existing order.');
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }
}