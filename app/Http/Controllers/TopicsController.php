<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as BaseView;
use Illuminate\Support\Str;
use PhpParser\Builder\Class_;

class TopicsController extends Controller
{
    public function index($classroom): BaseView
    {
       
        // $topics =Topic::where('classroom_id', '=', $classroom)->get();
        $topics =Topic::myClassroom($classroom)->get();
        $cls = Classroom::findOrFail($classroom);
        // Topic::join('classrooms', 'topics.topic_id', '=', 'topics.id')
        //     ->select([
        //         'topics.*',
        //         'topics.name as topic_name',
        //     ])->paginate();
            $success = session('success');
        return view('topics.index', [
            'topics' => $topics,
            'success' => $success,
            'classroom' => $cls,
        ]);
    }

    public function create($classroom)
    {
        $topics = Topic::all();
        // $classrooms = Classroom::all();
        return view('topics.create', [
            'topics' => $topics,
            'classroom' => $classroom,
        ]);
        
    }

    public function store(Request $request,$classroom)
    {
        $request['classroom_id'] = $classroom;
        $topics = Topic::create($request->all());
        return redirect(route('classroom.topic.index',$classroom))
        ->with( 'success', 'Topic Created' );
    }

    public function show($classroom ,$topic)
    {
        $topic =Topic::myClassroom($classroom)->findOrFail($topic);
        $cls = Classroom::findOrFail($classroom);
        return view('topics.show', [
            'topic' => $topic,
            'classroom' => $cls,
        ]);
    }

    public function edit($classroom_id, $id)
    {
        $topic = Topic::findOrFail($id);
        return view('topics.edit', [
            // 'topics' => Topic::all(),
            'topic' => $topic
        ]);
    }

    public function update(Request $request, $classroom,$topic)
    {
        $topics = Topic::myClassroom($classroom)->findOrFail($topic);
        $topics->update($request->all());
        return redirect()->route('classroom.topic.index')
        ->with('success', __('Topic updated'));
        
    }

    public function destroy($topic,$classroom)
    {
        $topic =Topic::myClassroom($classroom)->get();
        Topic::destroy($topic);
        return redirect(route('classroom.topic.index',['classroom'=>$classroom,'topic'=>$topic]))
        ->with('success', __('Topic deleted'));
        

    }
    public function trashed($classroom)
    {
        $topics =Topic::myClassroom($classroom)->onlyTrashed()->get();
        return view('topics.trashed' ,compact('topics'));
    }

    public function restore($topic,$classroom)
    {
        // $topic = Topic::findOrFail($id);//هان رح يبحث عنه داخل الموجود بس مش عالمحدوف فلازم نحددله وين يبحث
        $topics =Topic::myClassroom($classroom)->onlyTrashed()->findOrFail($topic);
        $topics->restore();//بترجع حقل الحدف ل null
        return redirect(route('classroom.topic.index'))
            ->with('success', 'Topic ({$topic->name}) restored');
    }

    public function forceDelete($topic,$classroom)
    {
        $topic = Topic::withTrashed()->findOrFail($topic);
        $topic->forceDelete();

        return redirect(route('topics.trashed'))
        ->with('success', 'Topic ({$topic->name}) restored');
    }
}
