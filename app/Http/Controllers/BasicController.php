<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $albums = Albums::paginate(10); 
        return view('static.home', compact('albums'));
    }
}
