<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Position;
use App\Models\Problem;

class EditorController extends Controller
{
    /**
     * @var string $pageTitle
     */
    public string $pageTitle;

    /**
     * @return null
     */
    public function __construct()
    {
        $this->pageTitle = __('dashboard_editor.page_title');

        return null;
    }

    /**
     * Render view for form editor.
     *
     * @return view
     */
    public function index()
    {
        $zones = Zone::all();
        $positions = Position::all();
        $problems = Problem::all();

        return view('dashboard/form_editor', [
            'pageTitle' => $this->pageTitle,
            'zones' => $zones,
            'positions' => $positions,
            'problems' => $problems,
        ]);
    }
}
