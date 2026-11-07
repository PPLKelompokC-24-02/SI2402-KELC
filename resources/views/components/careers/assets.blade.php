@push('head')
    <link rel="stylesheet" href="{{ asset('css/careers.css') }}?v={{ filemtime(public_path('css/careers.css')) }}">
    <script src="{{ asset('js/careers.js') }}?v={{ file_exists(public_path('js/careers.js')) ? filemtime(public_path('js/careers.js')) : 1 }}" defer></script>
@endpush
