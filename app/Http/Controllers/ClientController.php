<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = \App\Models\Client::when($search, function ($query, $search) {
                return $query->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('contact_number', 'LIKE', "%{$search}%")
                            ->orWhere('address', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search'));
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