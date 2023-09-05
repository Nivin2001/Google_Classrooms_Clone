<x-main-layout title="Classworks">
    <x-form.alert name="success" class="alert-success"></x-form.alert>
    <x-form.alert name="error" class="alert-danger"></x-form.alert>

    <div class="container">
        <x-search />
        <br>
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>{{ __('Classworks') }}
            @can('classworks.create', [$classroom])
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        + Create
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="{{ route('classroom.classwork.create', [$classroom->id, 'type' => 'assignment']) }}">{{ __('Assignment') }}</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ route('classroom.classwork.create', [$classroom->id, 'type' => 'material']) }}">{{ __('Material') }}</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ route('classroom.classwork.create', [$classroom->id, 'type' => 'question']) }}">{{ __('Question') }}</a>
                        </li>
                    </ul>
                </div>
            @endcan
        </h3>
        <hr>
        @forelse ( $classworks as $group)

            <h3>{{ $group->first()->topic->name ?? '' }}</h3>

            <div class="accordion accordion-flush" id="accordionFlushExample">

                @foreach ($group as $classwork)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $classwork->id }}" aria-expanded="false"
                                aria-controls="flush-collapse{{ $classwork->id }}">
                                {{ $classwork->title }}
                            </button>
                        </h2>
                        <div id="flush-collapse{{ $classwork->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! $classwork->description !!}

                                    </div>
                                    <div class="col-md-6 row">
                                        <div class="col-md-4">
                                            <div class="fs-3">
                                                {{ $classwork->assigned_count }}
                                            </div>
                                            <div class="text-muted">{{ __('Assigned') }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="fs-3">
                                                {{ $classwork->turnedin_count }}
                                            </div>
                                            <div class="text-muted">{{ __('Turned In') }}</div>
                                        </div>
                                         <div class="col-md-4">
                                            <div class="fs-3">
                                                {{ $classwork->graded_count }}
                                            </div>
                                            <div class="text-muted">{{ __('Graded') }}</div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-outline-dark"
                                href="{{ route('classroom.classwork.edit', [$classroom->id, $classwork->id]) }}">{{ __('EDIT') }}</a>
                            <a class="btn btn-sm btn-outline-dark"
                                href="{{ route('classroom.classwork.show', [$classroom->id, $classwork->id]) }}">{{ __('Show') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>

        @empty
            <p class="text-center fs-4"> {{ __('No Classworks Found.') }}</p>
        @endforelse
       
    </div>
</x-main-layout>
