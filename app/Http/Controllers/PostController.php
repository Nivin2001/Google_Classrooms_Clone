<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Classroom;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Classroom $classroom)
    {

        $posts =$classroom->posts()
        ->orderBy('created_at')
        ->filter(request(['search']))
        ->get();
        return view('posts.index',[
            'classroom' => $classroom, 
            'posts' => $posts
        ]);
    }

    public function create(Classroom $classroom)
    {

        return view('posts.create',compact('classroom'));
    }

    public function store(PostRequest $request,Classroom $classroom,Post $post)
    {
        $validated =$request->validated();
        // $request->merge([
        //     'classroom_id' => $classroom->id
        // ]);
        $post =$classroom->posts()->create($request->all());
        return redirect()->route('classroom.post.index', $classroom->id)
        ->with('success', __("Post Created♥"));
    }

    public function show(Classroom $classroom,Post $post)
    {
        // $post->load('comments.user');//eagr loading with model pinding
        return view('posts.show',compact('classroom','post'));
    }

    public function edit(Classroom $classroom,Post $post)
    {
        return view('posts.edit',compact('classroom','post'));
    }

    public function update(PostRequest $request,Classroom $classroom,Post $post)
    {
        $validated = $request->validated();
        $post->update($validated);
        return redirect()->route('classroom.post.show',compact('classroom','post'))
        ->with('success', __('Post Updated♥'));
    }

    public function destroy(Classroom $classroom,Post $post)
    {
        $post = $classroom->posts()->findOrFail($post->id);
        $post->delete();
        return redirect(route('calssrooms.index'))
        ->with('success', __("post deleted successfully"));
    
    }
}
