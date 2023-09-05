<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use Exception;
use Illuminate\Database\QueryException;
// use App\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as BaseView;
use Illuminate\Support\Str;

class ClassroomsController extends Controller
{
    
    // public function index(Request $requset , Test $test)
    public function index(Request $requset): BaseView
    {

        $classrooms = Classroom::active()
            ->recent()
            ->orderBy('created_at', 'DESC')
            ->filter(request(['search']))
            ->where('user_id', Auth::id())
            // ->withoutGlobalScopes()//بتلغي تطيق كل الجلوبال بما فيهم الsoft delete
            //->withoutGlobalScope('user')//بنلغي واحد محدد
            ->simplePaginate(2);
        // $classroom = Classroom::orderBy('name','DESC')->first();
        // session()->get('success');//ممكن يكون null
        // session()->has('success');
        // Session::put('success','');
        $success = session('success');
        return view('classrooms.index', compact('classrooms', 'success'));


        // echo $requset->url();
        // echo $test->print();
        // return 'Hello';
        // $name='Nareman';
        // $title='Laravel Training';
        // return view('classrooms.index',compact('name','title'));
        // return Redirect::away('باث خارجي');
        // return Redirect::to('باث داخل الموقع نفسه');
        // return view('classrooms.index',[
        //     'name' => 'Nareman',
        //     'title' => 'Laravel Training'
        // ]);
    }

    public function create()
    {
        return View()->make('classrooms.create', [
            'classroom' => new Classroom(),
        ])
            ->with('success', __('Classroom Created successfully'));
        // return view('classrooms.create');
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        // $validated = $request->validate([
        //     'name' => 'required|max:255',
        //     'section' => 'nullable|string|max:255',
        //     'subject' => 'nullable|string|max:255',
        //     'room' => 'nullable|string|max:255',
        //     'cover_image' => [
        //         'image',
        //         'dimensions:min_width:200 ,min_height=100,max_width:4000 ,max_height=4000',
        //     ],
        // ]);
        //method 1
        // $classroom = new Classroom();
        // $classroom->name = $request->post('name');
        // $classroom->subject = $request->post('subject');
        // $classroom->section = $request->post('section');
        // $classroom->room = $request->post('room');
        // $classroom->code = Str::random(8);
        // $classroom->save();

        //method 2
        // $data= $request->all();
        // $data['code'] = Str::random(8);
        // Classroom::create([$data]);
        // $classroom = new Classroom();
        //  $classroom->fill($request->all())->save();

        //method 3
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            //  $path=$file::uploadCoverImage($file);
            $path = Classroom::uploadCoverImage($file);
            // $path=$file->storeAs('/covers','uploads');//عنا هنا بنختار الاسم بس بدنا نركز على فكرىة تكرار الاسماء ونحلهاا           
            $request->merge([
                'cover_image_path' => $path,
            ]);
        }

        // $request->merge([
        //     'code' => Str::random(8),
        // ]);
        // $classroom =Classroom::create($request->all());
        // $validated['code'] = Str::random(8);
        // $validated['user_id'] = Auth::id();
        // $validated['user_id'] = Auth::user()->id;
        // DB::beginTransaction();

        // $validated['user_id'] = $request->user()->id();
        // $validated['cover_image_path'] =  $path;
         try{
        DB::beginTransaction();
        //بتعمل return back لحالها
        // DB::transaction(function() use ($validated){
        //     $classroom = Classroom::create($validated);
        // DB::table('classroom_user')->insert([
        //     'classroom_id' => $classroom->id,
        //     'user_id' => Auth::id(),
        //     'role' => 'teacher',
        //     'created_at' => now(),
        // ]);
        // });

        $classroom = Classroom::create($validated);
        // DB::table('classroom_user')->insert([
            //     'classroom_id' => $classroom->id,
            //     'user_id' => Auth::id(),
            //     'role' => 'teacher',
            //     'created_at' => now(),
            // ]);
        $classroom->join(Auth::id(),'teacher');

                 DB::commit();
            }catch(QueryException $e){
            DB::rollBack();
            return back()
            ->withErrors('error' ,$e->getMessage())
            ->withInput();
        }



        return redirect()->route('classrooms.index')
            ->with('success', __('your classroom created successfully'));


        // echo $request->query('name');//رح ترجع القيم من ال url
        // echo $request->input('name');//بترجع القيمة من ال body |url
        // echo $request->post('name');//بترجع من الفورم حس الname تبع الحقل
        // // echo $request->name;
        // $request->all();//كل الحقول
        // $request->only();
        // $request->except();


    }

    public function show($id)
    {
        // $classrooom =Classroom::where('id' , '=' , $id)->first();
        // $classroom = Classroom::where('user_id',Auth::id())->findOrFail($id);//رح عمل سكوب جلوبال لكل المودل بشكل عام بيتنفذ على كل الاكشنز وخلص
        $classroom = Classroom::findOrFail($id);
        // $invitation_link = URL::signedRoute('classrooms.join',[
        //     'classroom' => $id ,
        //     'code' => $classroom->code ,
        // ]);
        return View::make('classrooms.show')
            ->with([
                'classroom' => $classroom,
                // 'invitation_link' => $invitation_link,
            ]);
        // return view('classrooms.show',[
        //     'id' => $id,
        //     'edit' => $edit
        // ]);
    }

    public function edit($id)
    {

        $classroom = Classroom::findOrFail($id);
        //Fail بتغنينا عنها 
        // if(!$classroom)
        // {
        //     abort(404);
        // }
        return view('classrooms.edit', [
            'classroom' => $classroom,
        ]);
    }
    public function update(ClassroomRequest $request, $id)
    {
        //method 1
        // $validated = $request->validate([
        //     'name' => 'required|max:255',
        //     'section' => 'nullable|string|max:255',
        //     'subject' => 'nullable|string|max:255',
        //     'room' => 'nullable|string|max:255',
        //     'cover_image' => [
        //         'image',
        //         'dimensions:min_width:200 ,min_height=100,max_width:4000 ,max_height=4000',
        //     ],
        // ]);
        // $rules = [
        //     'name' => 'required|max:255',
        //     'section' => 'nullable|string|max:255',
        //     'subject' => 'nullable|string|max:255',
        //     'room' => 'nullable|string|max:255',
        //     'cover_image' => [
        //         'image',
        //         'dimensions:min_width:200,min_height=100,max_width:4000 ,max_height=4000',
        //     ],
        // ];
        // $messages =[
        //     'required' => ' :attribue is important',
        //     'name.required' => 'The name is required',
        //     'cover_image.max' => 'Image size is greater than 1M',
        // ];
        // $validated = $request->validate($rules,$messages);

        //التحقق كله حيصير جوا الريكوست ضمنيا  بس لو بدنا نستخدم الداتا  بنستدعيها كالتالي
        $validated = $request->validated();
        $classroom = Classroom::findOrFail($id);
        //بدنا نعمل جملة if لانه ممكن اساسا ما تكون الها صورة قديمة
        // $file = $request->file('cover_image');
        // $name=$classroom->cover_image_path?? Str::random(40).'.'.$file->getClientOriginalExtension();
        // $new_image = $file->storeAs('/covers',basename($name));//لو بدنا نخزن الصورة الجديدة بنفس اسم القديمة
        if ($request->hasFile('cover_image')) {
            $new_image = $request->file('cover_image');

            // $request->store('/covers', [
            //     'disk' => Classroom::$disk
            // ]);
            $path = Classroom::uploadCoverImage($new_image);
            //  $request->merge([
            //     'cover_image_path' => $path,
            //  ]);
            $validated['cover_image_path'] = $path;
        }
        $old_image = $classroom->cover_image_path;

        // $validated['cover_image_path'] = $path;
        $classroom->update($validated);

        if ($old_image && $old_image != $classroom->cover_image_path) {
            Classroom::deleteCoverImage($old_image);

            // Storage::disk(Classroom::$disk)->delete($old_image);
        }

        // $classroom->name = $request->post('name');
        // $classroom->subject = $request->post('subject');
        // $classroom->section = $request->post('section');
        // $classroom->room = $request->post('room');
        // $classroom->save();   
        //mass Assignment
        //   $classroom->fill($request->all())->save();
        Session::flash('success', __('your classroom updated successfully'));
        return Redirect::route('classrooms.index');
    }

    public function destroy($id)
    {
        // $classroom->destroy($id);
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        // if ($classroom->cover_image_path) {     // Storage::disk(Classroom::$disk)->delete($classroom->cover_image_path);
        //     Classroom::deleteCoverImage($classroom->cover_image_path);
        //     //Flash Message
        // }
        return redirect(route('classrooms.index'))
            ->with('success', __('your classroom deleted successfully'));
        // Classroom::where('id','=',$id)->delete();
        // $classroom = Classroom::find($id);
        // $classroom->delete();
    }

    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()
            ->latest('deleted_at')
            ->get();
        return view('classrooms.trashed', compact('classrooms'));
        // $classroom = Classroom::onlyTrashed()->latest('deleted_at')->get();
        // return view('classrooms.trashed' ,compact('classrooms'));
    }

    public function restore($id)
    {
        // $classroom = Classroom::findOrFail($id);//هان رح يبحث عنه داخل الموجود بس مش عالمحدوف فلازم نحددله وين يبحث
        $classrooms = Classroom::onlyTrashed()->findOrFail($id);
        $classrooms->restore(); //بترجع حقل الحدف ل null
        return redirect(route('classrooms.index'))
            ->with('success', 'Classroom ({$classrooms->name}) restored');
    }

    public function forceDelete($id)
    {
        $classrooms = Classroom::withTrashed()->findOrFail($id);
        $classrooms->forceDelete();
        // if ($classrooms->cover_image_path) {     // Storage::disk(Classroom::$disk)->delete($classroom->cover_image_path);
        //     Classroom::deleteCoverImage($classrooms->cover_image_path);
        // }
        return redirect(route('classrooms.trashed'))
            ->with('success',  'Classroom ({$classroom->name}) restored');
    }

    public function streams(Classroom $classroom)
    {
        $streams = $classroom->streams()->get();
        return view('classrooms.stream',compact('streams','classroom'));
    }
}
