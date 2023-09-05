<x-main-layout title="Edit Topic">
    <div class="container">
    <form action="{{ route('classroom.topic.update', $topic->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-floating mb-3">
            <input type="text" class="form-control" value="{{ $topic->name }}" id="name" name="name"
                placeholder="Class Name">
            <label for="name"> Topic Name</label>
        </div>
        <button type="submit" class="btn btn-primary">{{__('Edit')}}</button>
    </form>
</div>
</x-main-layout>