<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
    
        $request->validate([
            'content' => 'required|string',
            'id' => 'required|int',
            'type' => 'required|in:classwork,post',
                 
        ]);

        try {
            Auth::user()->comments()->create([
                'commentable_id' => $request->input('id'),
                'commentable_type' => $request->input('type'),
                'content' => $request->input('content'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success','Comment Added♥');
        } catch (Exception $e) {
            return back()->with('error',__('Error adding comment: ').$e->getMessage());
        }
    }
}
