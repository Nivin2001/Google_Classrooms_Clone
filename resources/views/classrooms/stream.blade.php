<x-main-layout title="Stream">
<div class="d-flex justify-content-center">

    <div class="container mt-4">
        <div class="row d-flex justify-content-center cover">
            <div class="col-md-12">
                @if ($classroom->cover_image_path)
                    <div class="my-5" style="position: relative;">
                        <img src="{{ $classroom->cover_image_url }}"
                            class="card-img-top rounded-3 img-fluid" style="height: 250px; object-fit: cover;"
                            alt="Classroom Cover Image">
                        <div
                            style="position: absolute; bottom: 20px; left: 20px; color: white; text-shadow: 1px 1px 3px #000000b3;">
                            <h4 style="font-size: 35px">{{ $classroom->name }} ( # {{ $classroom->id }}) </h4>
                            <h4>{{ $classroom->section }}</h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
    
        <br>
    
        <div class="row align-items-center">
            <div class="col-md-3">
                {{-- left side  --}}
                <div class="card p-1 rounded d-flex" style="height: 100vh;">
                    <p>this classroom is special one ♥</p>
                </div>
            </div>
            <div class="col-md-9">
                {{-- right side --}}
                <div class="p-3" style="height: 100vh;">
                    @foreach ($streams as $stream)
    
                        <div class="col-11">
                            <div class="card">
                                <div class="row">
                                    <div
                                        class="col-4 col-sm-2 bg-green rounded-circle d-flex justify-content-center align-items-center">
                                        <svg focusable="false" width="40" height="40" viewBox="0 0 24 24"
                                            class=" NMm5M hhikbc" style="background-color:green;border-radius:50%;">
                                            <path d="M7 15h7v2H7zm0-4h10v2H7zm0-4h10v2H7z" fill="white"></path>
                                            <path
                                                d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-.14 0-.27.01-.4.04a2.008 2.008 0 0 0-1.44 1.19c-.1.23-.16.49-.16.77v14c0 .27.06.54.16.78s.25.45.43.64c.27.27.62.47 1.01.55.13.02.26.03.4.03h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7-.25c.41 0 .75.34.75.75s-.34.75-.75.75-.75-.34-.75-.75.34-.75.75-.75zM19 19H5V5h14v14z"
                                                fill="white">
                                            </path>
                                        </svg>
                                    </div>

                                    <div class="col">
                                        <div class="card-title">
                                            <h5> {{ $stream->content }} </h5>
                                        </div>
                                        <div class="card-text">
                                            <small> Created {{ $stream->created_at }} </small>
                                        </div>
                                    </div>

                                    <div class="col col-sm-1">
                                        <a href="#" class="dropdown-toggle-no-arrow  text-dark"
                                            data-bs-toggle="dropdown" aria-expanded="true">
                                            <i class="fas fa-ellipsis-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item copy-link" href="#" onclick="copyLink()">Copy
                                                    Link</a></li>
                                            <p class="d-none" id="text-to-copy">{{ $stream->link }}</p>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function copyLink() {
            const textToCopy = document.getElementById('text-to-copy').textContent;
            const tempInput = document.createElement('input');
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
    
            // You can also show a message to indicate that the link has been copied
            alert('Link copied to clipboard: ' + textToCopy);
        }
    </script>
    {{-- <script src="{{ asset('js/copy.js') }}"></script> --}}
    {{-- <script src="resources/js/copy.js"></script> --}}
    {{-- @vite(['resources/js/copy.js']) --}}
    @vite(['resources/js/app.js'])
@endpush
</x-main-layout>