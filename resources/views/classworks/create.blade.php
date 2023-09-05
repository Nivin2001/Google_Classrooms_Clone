<x-main-layout title="Create Classwork">
    <div class="container">
    <h1>{{ __('Create Classwork')}}</h1>
    <hr>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif    <form action="{{ route('classroom.classwork.store', ['classroom' => $classroom->id, 'type' => $type]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @include('classworks._form')




        <button type="submit" class="btn btn-primary">{{ __('Create Classwork') }}</button>
    </form>
</div>
</x-main-layout>