<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Event;
use Illuminate\Http\Request;
use Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->get('term');

        $users = User::with('profile')
                    ->where('id', '!=', Auth::user()->id)
                    ->where(function($query) use ($term) {
                        $query->where('name', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%")
                        ->orWhere('nickname', 'like', "%$term%");
                    })->get();

        $events = Event::with([
            'address',
            'address.city',
            'address.city.state',
            'owner',
            'owner.profile'
        ])->where('is_active', true)->where(function($query) use ($term) {
            $query->where('name', 'like', "%$term%")
                  ->orWhere('description', 'like', "%$term%")
                  ->orWhereHas('owner', function($query) use ($term) { $query->where('name', 'like', "%$term%")->orWhere('nickname', 'like', "%$term%"); })
                  ->orWhereHas('serie', function($query) use ($term) { $query->where('name', 'like', "%$term%"); })
                  ->orWhereHas('address', function($query) use ($term) {
                        $query->where('name', 'like', "%$term%")
                        ->orWhereHas('city', function ($query) use ($term) {
                            $query->where('name', 'like', "%$term%")
                                ->orWhereHas('state', function($query) use ($term) {
                                    $query->where('name', 'like', "%$term%");
                                });
                        });
                  });
        })->get();

        return response()->json(['users' => $users, 'events' => $events], 200);
    }
}
