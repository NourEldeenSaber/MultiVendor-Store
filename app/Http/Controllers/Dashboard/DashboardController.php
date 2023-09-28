<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(){
        // $this->middleware('auth');
    }
    public function index(){
        $user = 'Ayman';
        return view('dashboard.index',compact('user'));
    }
}
