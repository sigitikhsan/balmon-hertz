<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;

class SettingsManagementController extends Controller
{
    public function index()
    {
        return view('internal.settings.index');
    }
}
