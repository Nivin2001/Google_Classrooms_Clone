<x-main-layout title="Show Classwork">
    <div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
@endif
    <h1> {{ $classroom->name }} (# {{ $classroom->id }})</h1>
    <h1> {{ $classwork->title }}</h1>
    <x-form.alert name="success" class="alert-success"></x-form.alert>
    <x-form.alert name="error" class="alert-danger"></x-form.alert>
    <hr>
    <div class="row">
        <div class="col-md-8">

            <div>
                <p>
                    {{ $classwork->description }}
                </p>
            </div>
            <h4>{{ __('Comments') }}</h4>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $classwork->id }}">
                <input type="hidden" name="type" value="classwork">
                <div class="d-flex">
                    <div class="col-8">
                        <x-form.floating-control name="comment">
                            <x-slot name="label">
                                <label for="comment">{{ __('Comments(optional)') }}</label>
                            </x-slot>
                            <x-form.textarea name="content" placeholder="Comment"></x-form.textarea>
                        </x-form.floating-control>
                    </div>

                    <div class="ms-1">
                        <button type="submit" class="btn btn-primary">{{ __('Add Comment') }}</button>
                    </div>
                </div>
            </form>
            <div class="mt-4">
                @foreach ($classwork->comments as $comment)
                    <div class="d-flex align-items-center">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="">
                            </div>
                            <div class="col-md-10">
                                <p>By:
                                    {{ $comment->user->name }}.{{ __('Time') }}:{{ $comment->created_at->diffForHumans(null, true, true) }}
                                </p>
                                <p>{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">
            @can('submissions.create',[$classwork])
                <div class="border rounded p-3 bg-light">
                    <h4>{{ __('Submissions') }}</h4>
                    @if ($submissions->count())
                        {{--  استخدم بيرجع دايما ترو نا كاونت لانها مصفوفة فبدونها --}}
                        <ul>
                            @foreach ($submissions as $submission)
                                <li><a href="{{ route('file', $submission->id) }}">{{ __('File') }} #{{ $loop->iteration }}</a></li>
                                {{-- <li><a href="{{ $submission->content }}">{{ $submission->content }}</a></li> --}}
                            @endforeach
                        </ul>
                    @else
                    <form action="{{ route('submissions.store', $classwork->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <x-form.floating-control name="files">
                            <x-slot name="label">
                                <label for="files">{{ __('Upload Files') }}</label>
                            </x-slot>
                            <x-form.input type="file" name="files[]" multiple accept="image/*,application/pdf"
                                placeholder="Select Files"></x-form.input>
                            <x-form.input-error name="files"></x-form.input-error>
                        </x-form.floating-control>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </form>
                    @endif
                </div>
        </div>
        @endcan
    </div>
   
</div>

</x-main-layout>