<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('profile'); // Harus ada file resources/views/profile.blade.php
    }
}
