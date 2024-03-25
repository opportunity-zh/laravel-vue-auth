<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Resources\UserResource;

class UserController extends Controller
{



    public function show(){
        return new UserResource(User::findOrFail(auth()->id()));
    }


}
