<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\Announcements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = User::all(); // You can filter users based on roles or other criteria if needed

        Notification::send($users, new Announcements($request->title, $request->message));

        return response()->json(['message' => 'Announcement created successfully'], 201);

    }
}
