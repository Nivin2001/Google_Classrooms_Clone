<x-main-layout title="Join Classroom">

<div class="d-flex justify-content-center align-items-center vh100">
<div class="border p-5">
<h2>{{ $classroom->name }}</h2>
    <form class="border p-5" action="{{ route('classrooms.join',['classroom'=> $classroom->id]) }}" method="POST">
@csrf
<button type="submit" class="btn btn-primary">{{ __('join') }}</button>
</form>
</div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
@endpush
</x-main-layout>