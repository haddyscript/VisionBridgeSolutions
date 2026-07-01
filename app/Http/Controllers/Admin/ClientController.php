<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = User::where(function ($q) {
                $q->where('role', '!=', 'admin')->orWhereNull('role');
            })
            ->with(['projects' => fn ($q) => $q->select('id', 'user_id', 'name', 'status')])
            ->when($search, fn ($q) => $q->where(fn ($s) =>
                $s->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.clients.index', [
            'clients' => $clients,
            'search'  => $search,
        ]);
    }
}
