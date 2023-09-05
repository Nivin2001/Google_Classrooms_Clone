<x-main-layout title="People">
    <x-form.alert name="success" class="alert-success"></x-form.alert>
    <x-form.alert name="error" class="alert-danger"></x-form.alert>
    <div class="container">
        <h1> {{ $classroom->name }} {{ __('People') }}</h1>
        <h3>{{ $classroom->section }}</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classroom->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->pivot->role }}</td>
                        {{-- <td>{{ $user->join->role }}</td>//لما غيرنا اسم الpivot ل join --}}
                        <td>
                            <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-danger ">{{ __('Delete') }}</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>

            </tbody>
            @endforeach
        </table>
    </div>
</x-main-layout>
