<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class DashboardController extends Controller
{
    public function dashboard(){
        $usuarios = User::where('id', '!=', Auth::id())->get();
        return view("dashboard",compact('usuarios'));
    }
}
