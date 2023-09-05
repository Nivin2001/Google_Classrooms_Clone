<x-main-layout title="Trashed Classrooms">
<x-form.alert name="success"></x-form.alert>
<div class="container">
    <div class="row">
    
    @foreach ($classrooms as $classroom)
        {{-- حجزنا 3 اعمدة * 4صفوف يعني 12   --}}
        <div class="col-md-3">
           <x-form.card :classroom="$classroom">
            
            <div >
                <form action="{{ route('classrooms.restore', $classroom->id) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-sm btn-danger ">{{ __('Restore') }}</button>
                </form>
            </div>
             <div >
                <form action="{{ route('classrooms.force-delete', $classroom->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-danger ">{{ __('Delete Forever') }}</button>
                </form>
            </div>
           </x-form.card>
        </div>
    @endforeach
</div>
</div>
</x-main-layout>
{{-- @pushIf('true','script')
 <script>console.log('@@stack')</script>رح يطبع بالكونسول @stack  
@endpushIf --}}