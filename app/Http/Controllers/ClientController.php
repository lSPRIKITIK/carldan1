<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'street' => 'required|string',
            'city' => 'required|string',
        ]);

        Client::create($request->only(['first_name','middle_name','last_name','contact_number','street','city']));

        return redirect()->route('orders.index')->with('success', 'New client added successfully!');
    }
    public function destroy(Client $client)
    {
        if ($client->orders()->exists()) {
            return redirect()->route('orders.index')->with('error', 'Cannot delete this client because they are linked to an existing order.');
        }

        $client->delete();
        return redirect()->route('orders.index')->with('success', 'Client deleted successfully!');
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'street' => 'required|string',
            'city' => 'required|string',
        ]);

        $client->update($request->only(['first_name','middle_name','last_name','contact_number','street','city']));

        return redirect()->route('orders.index')->with('success', 'Client updated successfully!');
    }
}