<?php

namespace App\Http\Controllers\Admin\Villege;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VillegeController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Villege/Index');
    }
}
