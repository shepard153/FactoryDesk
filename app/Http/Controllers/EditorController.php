<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Position;
use App\Models\Problem;

class EditorController extends Controller
{
    /**
     * Render view for form editor.
     *
     * @return Response
     */
    public function index()
    {
        $pageTitle = 'Edytor formularza';

        $zones = Zone::all();
        $positions = Position::all();
        $problems = Problem::all();

        return view('dashboard/form_editor', [
            'pageTitle' => $pageTitle,
            'zones' => $zones,
            'positions' => $positions,
            'problems' => $problems,
        ]);
    }
}
