<?php

namespace App\Http\Controllers;

use App\Models\Roll;

class RollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Roll::ended()->orderBy('id', 'desc')->take(10)->get();
    }
}
