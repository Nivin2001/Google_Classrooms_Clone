<x-main-layout title="Trashed Topics">
<x-form.alert name="success"></x-form.alert>
{{-- <x-alert name="success" /> --}}
    <div class="row">
    
    @foreach ($topics as $topic)
        {{-- حجزنا 3 اعمدة * 4صفوف يعني 12   --}}
        <div class="col-md-3">
            <div class="card-body ">
                <h5 class="card-title"> {{ $topic->name }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('classroom.topic.show', ['classroom'=>$classroom,'topic'=>$topic->id]) }}" class="btn btn-sm btn-primary">View</a>
                    <div class="col">
                        <form action="{{ route('topics.restore', $topic->id) }}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-danger ">Restore</button>
                        </form>
                    </div>
                     <div class="col">
                        <form action="{{ route('topics.force-delete', $topic->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger ">Delete Forever</button>
                        </form>
                    </div>                </div>
        
            </div>
           
        </div>
    @endforeach
</div>
</x-main-layout>