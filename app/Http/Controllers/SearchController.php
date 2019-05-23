<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search($query)
    {
        $users = User::with('profile')
                    ->where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->orWhere('nickname', 'like', "%$query%")->get();

        return response(['users' => $users], 200);
    }
}
