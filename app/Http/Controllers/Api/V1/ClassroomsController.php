<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomCollection;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Response;
use Throwable;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {     if(!Auth::guard('sunctum')->user()->tokenCan('classrooms.read')){
       abort(403);

    }
         $classrooms=Classroom::with('user:id,name','topics')
        ->withCount('students as students')
         ->get();  //->paginate(2 );//all();
        return  new ClassroomCollection($classrooms);//ClassroomResource::collection($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {if(!Auth::guard('sunctum')->user()->tokenCan('classroom.create')){
        abort(403);

     }
        try{
        $request->validate([
            'name'=>['required'],
        ]);}
        catch(Throwable $e){
            return Response::json([
                'message'=>$e->getMessage(),
            ],422);
        }
        $classroom=Classroom::create($request->all());
        return Response::json(
        [
            'code'=>100,
            'message'=>__('Classroom created.'),
            'classroom'=>$classroom]
            ,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        if(!Auth::guard('sunctum')->user()->tokenCan('classrooms.read')){
            abort(403);

         }
        $classroom->load('user')->loadCount('students'); //Classroom::findOrFail($id);
       return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {if(!Auth::guard('sunctum')->user()->tokenCan('classrooms.update')){
        abort(403);

     }
        $request->validate([
            'name'=>['sometimes','required',Rule::unique('classrooms','name')->ignore($classroom->id)],
            'section'=>['sometimes', 'required'],
        ]);
        $classroom->update($request->all());
        return [
            'code'=>100,
            'message'=>__('Classroom updated.'),
            'classroom'=>$classroom
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!Auth::guard('sunctum')->user()->tokenCan('classrooms.delete')){
            abort(403,'You cannot delete this user');

         }
       Classroom::destroy($id);
    return Response::json([],204);

       //    return // [
    //     'code'=>100,
    //     'message'=>__('Classroom deleted.'),

    //    ];
    }
}
