<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marche;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MarcheImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarcheController extends Controller
{
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new MarcheImport, $request->file('file'));
        return redirect()->back()->with('success', 'Importation réussie');
    }
}
