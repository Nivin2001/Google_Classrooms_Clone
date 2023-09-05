<x-main-layout title="Edit Post">
    <div class="container">
    <h1>{{ __('Edit Post') }}</h1>
    <x-form.alert name="success" class="alert-success"></x-form.alert>
    <x-form.alert name="error" class="alert-danger"></x-form.alert>
    <form action="{{ route('classroom.post.update', ['classroom' => $classroom->id, 'post' => $post->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

        <x-form.floating-control name="content">
            <x-slot name="label">
                <label for="content">{{ __('Content') }}</label>
            </x-slot>
            <x-form.textarea name="content" :value="$post->content" placeholder="Post Content"></x-form.textarea>
        </x-form.floating-control>
        <button type="submit" class="btn btn-primary">{{ __('Edit Post') }}</button>
    </form>
    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/9yxnm98wmonlf1oab3n1lhgh54sz3jo6uxnib3w9dq64uzf3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
          selector: '#content',
          plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
          mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
          ],
          ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant"))
        });
      </script>
    @endpush
</div>
</x-main-layout>