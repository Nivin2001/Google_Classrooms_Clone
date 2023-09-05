<?php

namespace App\Http\Controllers;

use App\Enums\ClassworkType;
use App\Events\ClassworkCreated;
use App\Http\Requests\ClassworkRequest;
use App\Models\Classroom;
use App\Models\Classwork;
use Error;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ClassworkController extends Controller
{
    protected function getType(Request $request): string
    {
        // try {
        //     $type = ClassworkType::from($request->query('type'));
        //     return $type;
        // } catch (Error $e) {
        //     return ClassworkType::ASSAIGNMENT; // Assuming 'ASSIGNMENT' is a constant defined in ClassworkType
        // }
    
        // $type = ClassworkType::from($request->query('type'));
        $type =request()->query('type') ;
        $allowed_types = [
            Classwork::TYPE_ASSAIGNMENT, Classwork::TYPE_MATERIAL, Classwork::TYPE_QUESTION
        ];
        if (!in_array($type, $allowed_types)) {
            $type = Classwork::TYPE_ASSAIGNMENT;
        }
        return $type;
    }

    // protected function 
    /**
     * Display a listing of the resource.
     */
    public function index(Classroom $classroom)
    {
        $this->authorize('view-any', [Classwork::class, $classroom]);

        // $classworks =$classroom->classworks()->get();
        // $classworks =$classroom->classworks;//هنا لحاله حيعمل get لما استدعيها كانها property
        // $assignments =$classroom->classworks()//هنا رح يرجع اوبجكت الريليشن
        // ->where('type','=',Classwork::TYPE_ASSAIGNMENT)
        // ->get();
        $classworks = $classroom->classworks()
            ->with('topic') //Eager load
            ->withCount([
                'users as turnedin_count' => function($query){
                    $query->where('classwork_user.status','submitted');
                },
                'users as assigned_count' => function($query){
                    $query->where('classwork_user.status','assigned');
                },
                'users as graded_count' => function($query){
                    $query->whereNotNull('classwork_user.grade');
                },
                ])
            ->orderBy('published_at')
            ->filter(request(['search']))
            ->WhereHas('users', function ($query) {
                $query->where("user_id", Auth::id());
            })->orWhereHas('classroom.teachers', function ($query) {
                $query->where("id", Auth::id());
            })
            /*->where(function($query){
                $query->whereRaw('EXISTS (SELECT 1 FROM classwork_user 
                WHERE classwork_user.classroom_id = classworks.id
                AND classwork_user.user_id = ?
                )',[Auth::id()]);

                $query->orWhereRaw('EXISTS (SELECT 1 FROM classroom_user 
                WHERE classroom_user.classroom_id = classworks.classroom_id
                AND classroom_user.user_id = ?
                AND classroom_user.role = ?
                )',[Auth::id(), 'teacher']);
            })*/

            ->get();
        // event('classwork.created',[$classroom,$classworks]);

        return view('classworks.index', [
            'classroom' => $classroom,
            'classworks' => $classworks->groupBy('topic_id')
        ]);
    }


    /**+
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Classroom $classroom)
    {
        // dd($request->type);
        $this->authorize('create', [Classwork::class, $classroom]);
        // $response = Gate::inspect('classworks.create',[$classroom]);
        // if($response->denied()){
        //     abort (403,$response->message() ?? '');
        // }
        // Gate::authorize('classworks.create',[$classroom]);
        // if(Gate::denies('classworks.create',[$classroom])){
        //     abort (403,'you are not allowed to do this action ');
        // }
        // $type =$request->query('type') ;
        // // $type =request()->query('type') ;
        // $allowed_types = [
        //     Classwork::TYPE_ASSAIGNMENT , Classwork::TYPE_MATERIAL , Classwork::TYPE_QUESTION
        // ];
        // if(!in_array($type,$allowed_types)) {
        //     $type =Classwork::TYPE_ASSAIGNMENT ;
        // }
        $type = $this->getType($request);
        $classwork = new Classwork();
        return view('classworks.create', compact('classroom', 'classwork', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassworkRequest $request, Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('create', [Classwork::class, $classroom]);

        // if(Gate::denies('classworks.create',[$classroom])){
        //     abort (403,'you are not allowed to do this action ');
        // }
        $type = $this->getType($request);
        // dd($type);
        $validated = $request->validated();
        $request->merge([
            'user_id' => Auth::id(),
            'type' => $type,
        ]);
        // $typo = $type->value;
        try {
            strip_tags($request->post('description'), ['h1', 'p', 'ol', 'li']); //رح يحدف ال tags وبعدها يعرضلي المحتوى بالستايل تبعهم
            DB::transaction(function () use ($classroom, $request, $type) {
                // Classwork::create($request->all());
                // $data= [
                //     'user_id' => Auth::id(),
                //     // 'classroom_id' =>$classroom->id,
                //     'type' => $type,
                //     'title' => $request->input('title'),
                //     'description' => $request->input('description'),
                //     'topic_id' => $request->input('topic_id'),
                //     // 
                //     // 'options' => json_encode([
                //     //     'grade' => $request->input('grade'),
                //     //     'due' => $request->input('due'),
                //     // ]),  
                //     //بعد ما ضفنا الcast على المودل
                //     'published_at' => $request->input('published_at'),
                //     'options' => $request->input('options'),//طالما خلينا اسماء الحقول بدلاة مصفوفة لل options
                //     // 'options' => [
                //     //     'grade' => $request->input('grade'),
                //     //     'due' => $request->input('due'),
                //     // ],
                // ];
                $classwork = $classroom->classworks()->create($request->all()); //هنا صار يمرر قيمة الكلاس روم لحاله بدون ما اعمل الها merge


                $classwork->users()->attach($request->input('students'));
                // event('classwork.created',[$classroom,$classwork]);
                // event(new ClassworkCreated($classwork));
                ClassworkCreated::dispatch($classwork);
            });
        } catch (\Exception $e) {
            throw $e;
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('classroom.classwork.index', $classroom->id)
            ->with('success', "Classwork $classwork->title Created♥");
    }
    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
        // $this->authorize('view',$classwork);
        //    Gate::authorize('classworks.view',[$classwork]);
        $submissions = Auth::user()
            ->submissions()
            ->where('classwork_id', $classwork->id)
            ->get();
        // $classwork->load('comments.user');//eagr loading with model pinding
        return view('classworks.show', compact('classroom', 'classwork', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);

        // $classwork = $classroom->classworks()->findOrFail($classwork->id);
        $type = $classwork->type; //لانه نوعه صار enum
        $assigned = $classroom->users()->pluck('id')->toArray(); //pluckبترجعلنا collection واحنا بنتعامل مع array
        return view('classworks.edit', compact('classroom', 'classwork', 'type', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassworkRequest $request, Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);
        $validated = $request->validated();
        $classwork->update($validated);
        $classwork->users()->sync($request->input('students'));
        return back()
            ->with('success', __('Classwork Updated♥'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('delete', $classwork);

        $classwork = $classroom->classworks()->findOrFail($classwork->id);
        $classwork->delete();
        return redirect(route('calssrooms.index'))
            ->with('success', "your classwork $classwork->title deleted successfully");
    }
}
