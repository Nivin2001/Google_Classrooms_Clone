<x-main-layout title="Create Topic">
        <div class="container">
        <h1>{{ __('Create Topic') }}</h1>
        <form action="{{ route("classroom.topic.store" ,$classroom) }}" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Topic Name">
                <label for="name">Topic Name</label>
            </div>
            <div class="form-floating mb-3">
                <label for="topic_id"></label>
                {{-- <select name="topic_id" id="topic_id" class="form-control">
                    <option value=""></option>
                    @foreach ($topics as $topic )
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select> --}}
            </div>
           
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </form>
    </div>
</x-main-layout>