<x-main-layout title="Create Classroom ">
    <div class="container">
        <h1> create classrooms</h1>
        <form action="{{ route('classrooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('classrooms._form', [
                'button_label' => __('Create')
                ])
        </form>
    </div>
</x-main-layout>
