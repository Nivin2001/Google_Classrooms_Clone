<x-form.alert name="success" class="alert-success"></x-form.alert>
<x-form.alert name="error" class="alert-danger"></x-form.alert>
<div class="row">
    <div class="col-md-8">
        <x-form.floating-control name="title">
            <x-slot name="label">
                <label for="title">Classwork Title</label>
            </x-slot>
            <x-form.input name="title" :value="$classwork->title" placeholder="Classwork title"></x-form.input>
            <x-form.input-error name="title"></x-form.input-error>
        </x-form.floating-control>

        <x-form.floating-control name="description">
            <x-slot name="label">
                <label for="description">Description (optional)</label>
            </x-slot>
            <x-form.textarea name="description" :value="$classwork->description" placeholder="Classwork Description"></x-form.textarea>
            <x-form.input-error name="description" ></x-form.input-error>
        </x-form.floating-control>
        <x-form.floating-control name="topic_id">
            <x-slot name="label">
                <label for="topic">Topic (optional)</label>
            </x-slot>
            <select class="form-select" name="topic_id" id="topic_id">
                <option value="">No Topic</option>
                @foreach ($classroom->topics as $topic)
                    <option @selected($topic->id == $classwork->topic_id) value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
            </select>
        </x-form.floating-control>
        <x-form.floating-control name="published_at">
            <x-slot name="label">
                <label for="published_at">Publish Date</label>
            </x-slot>
            <x-form.input type="date" name="published_at" value="{{ $classwork->published_date }}" placeholder="Publish Time"></x-form.input>
        </x-form.floating-control>
    </div>
    <div class="col-md-4 text-md-start"> 
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                Students
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @foreach ($classroom->students as $student)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}"
                            id="std-{{ $student->id }}" @checked(!isset($assigned) || in_array($student->id, $assigned))>
                        <label class="form-check-label" for="std-{{ $student->id }}">{{ $student->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($type == 'assignment')
            <x-form.floating-control name="options.grade">
                <x-slot name="label">
                    <label for="grade"> Grade</label>
                </x-slot>
                <x-form.input type="number" min="0" name="options[grade]" :value="$classwork->options['grade']?? '' " placeholder="Grade"></x-form.input>
                {{-- <x-form.input type="number" min="0" name="grade" :value="json_decode($classwork->options)->grade" placeholder="Grade"></x-form.input> --}}
                <x-form.input-error name="grade"></x-form.input-error>
            </x-form.floating-control>
            <x-form.floating-control name="options.due">
                <x-slot name="label">
                    <label for="due">Due</label>
                </x-slot>
                <x-form.input type="date" name="options[due]" :value="$classwork->options['due'] ?? ''" placeholder="Due"></x-form.input>
                <x-form.input-error name="due"></x-form.input-error>
            </x-form.floating-control>
        @endif
    </div>
</div>
@push('scripts')
<script src="https://cdn.tiny.cloud/1/9yxnm98wmonlf1oab3n1lhgh54sz3jo6uxnib3w9dq64uzf3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#description',
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
