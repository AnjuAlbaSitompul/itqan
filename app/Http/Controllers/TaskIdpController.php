<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskIdpController extends Controller
{
    public function index()
    {
        return view('task.idp.index');
    }
}
