<x-main-layout title="Edit Classwork">
    <div class="container">
    <h1> Edit Classwork</h1>
    <form action="{{ route('classroom.classwork.update', ['classroom' => $classroom->id, 'type' => $type,'classwork'=> $classwork->id]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('classworks._form')




        <button type="submit" class="btn btn-primary">{{ __('Edit Classwork') }}</button>
    </form>
   
</div>
</x-main-layout>