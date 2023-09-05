<x-main-layout title="Topics">
@if(session()->has('success'))
    
<div class="alert alert-success">{{ $success }}</div>
@endif
    @foreach ($topics as $topic)
        <div class="row m-4">

            <div class="alert alert-light border-success" role="alert">
                <h4 class="alert-heading">{{ $topic->name }}</h4>
                <p>Topic of {{ $classroom->name }}</p>
                <hr style="height: 2px;
                background-color: #ff0000; /* Set your desired color */
                background-image: linear-gradient(to right, #ff0000, #00ff00); /* Set your desired gradient */>
                <div class="row">
                    <div class="col">
                        <a href="{{ route('classroom.topic.show',['classroom'=>$classroom,'topic'=>$topic->id]) }}" class="btn btn-sm btn-primary">View</a>
                    </div>
                    <div class="col">
                        <a href="{{ route('classroom.topic.edit', ['classroom'=>$classroom,'topic'=>$topic->id]) }}" class="btn btn-sm btn-dark">Edit</a>
                    </div>
                    <div class="col">
                        <form action="{{ route('classroom.topic.destroy', ['classroom'=>$classroom,'topic'=>$topic->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endforeach
    
</x-main-layout>ut