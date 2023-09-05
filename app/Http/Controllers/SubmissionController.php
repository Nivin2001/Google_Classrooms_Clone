<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SubmissionController extends Controller
{
    public function store(Request $request, Classwork $classwork)
    {
        Gate::authoriza('submissions.create',[$classwork]);
        $request->validate([
            'files.*' => ['file', new ForbiddenFile('text/x-app')]
        ]);

        $assigned = $classwork->users()->where('id', Auth::id())->exists();
        if (!$assigned) {
            abort(403);
        }

        DB::beginTransaction(); // Start the transaction
        try {
            $submissionData = []; // Initialize an array to collect submission data
        
            foreach ($request->file('files') as $file) {
                $submissionData[] = [
                    // 'user_id' => Auth::id(),
                    'classwork_id' => $classwork->id,
                    'content' => $file->store("submissions/{$classwork->id}"),
                    'type' => 'file',
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ];
            }
            Auth::user()->submissions()->createMany($submissionData);


            ClassworkUser::where([
                'user_id' => Auth::id(),
                'classwork_id' => $classwork->id,
            ])->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            DB::commit(); // Commit the transaction on success
            return back()->with('success', __('Work Submitted'));
        } catch (Throwable $e) {
            DB::rollback(); // Rollback the transaction on failure
            return back()->with('error', $e->getMessage());
        }
    }

    public function file(Submission $submission)
    {
        $user =Auth::user();
        /*
        SELECT * FROM classroom_user
        WHERE user_id = ?
        AND role=?
        AND EXISTS (
            SELECT 1 FROM classworks WHERE classworks.classroom_id =classroom_user.classroom_id 
            AND EXISTS(
                SELECT 1 from submissions WHERE submissions.classwork_id = classworks.id id=?
            )
            )        
        */
        $collection = DB::select(
            'SELECT * FROM classroom_user
            WHERE user_id = ?
            AND role = ?
            AND EXISTS (
                SELECT 1 FROM classworks WHERE classworks.classroom_id = classroom_user.classroom_id 
                AND EXISTS (
                    SELECT 1 FROM submissions WHERE submissions.classwork_id = classworks.id AND id = ?
                )
            )',
            [$user->id, 'teacher', $submission->id]
        );
        
        // dd($collection);
        $isTeacher= $submission->classwork->classroom->teachers()->where('id',$user->id)->exists();
        $isOwner= $submission->user_id == $user->id;
        if(!$isOwner && !$isTeacher)
        {
            abort(403);
        }
        // return Storage::disk('local')->download($submission->content);
        return response()->file(storage_path('app/'. $submission->content));
    }


}
