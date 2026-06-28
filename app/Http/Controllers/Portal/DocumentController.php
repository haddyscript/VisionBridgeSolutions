<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        return view('portal.documents', [
            'agreementSignatures' => $project
                ? $project->agreementSignatures()->with('template')->get()
                : collect(),
        ]);
    }
}
