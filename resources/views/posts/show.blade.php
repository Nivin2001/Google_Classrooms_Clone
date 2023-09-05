<x-main-layout title="Post">
    <div class="container">
    <h1> {{ $classroom->name }} (# {{ $classroom->id }})</h1>
    <x-form.alert name="success" class="alert-success"></x-form.alert>
    <x-form.alert name="error" class="alert-danger"></x-form.alert>
    <hr>

    <div class="post">
        <div class="post-header">
            <div class="user-info">
                <h3>{{ $post->user->name }}</h3>
                <small>Posted {{ $post->created_at->diffForHumans(null, true, true) }}</small>
            </div>
        </div>
        <div class="post-content">
            <p>{{ $post->content }}</p>
        </div>
        <div class="post-actions">
            <div class="comments">
                <span>({{ count($post->comments) }}){{ __('Comment') }}</span>
            </div>
        </div>
    </div>

    <h4>{{ __('Comments') }}</h4>
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $post->id }}">
        <input type="hidden" name="type" value="post">
        <div class="d-flex">
            <div class="col-8">
                <x-form.floating-control>
                    <x-slot:label>
                        <label for="content">{{ __('Comments(optional)') }}</label>
                    </x-slot:label>
                    <x-form.textarea name="content" placeholder="Commment" />
                    <x-form.input-error name="commment"></x-form.input-error>
                </x-form.floating-control>
            </div>

            <div class="ms-1">
                <button type="submit" class="btn btn-primary">{{ __('Add Comment') }}</button>
            </div>
        </div>
    </form>
    <div class="mt-4">
        @foreach ($post->comments as $comment)
            <div class="d-flex align-items-center">
                <div class="row">
                    <div class="col-md-2">
                        <img src="">
                    </div>
                    <div class="col-md-10">
                        <p>By:
                            {{ $comment->user->name }}.Time:{{ $comment->created_at->diffForHumans(null, true, true) }}
                        </p>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</x-main-layout>