<x-main-layout title="Edit Classroom">
    <div class="container">
        <h1>Edit classrooms</h1>

        <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('classrooms._form', [
                'button_label' => __('Edit')
            ])
        </form>
    </div>
</x-main-layout>
