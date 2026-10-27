@push('head')
    <link rel="stylesheet" href="{{ asset('css/careers.css') }}?v={{ filemtime(public_path('css/careers.css')) }}">
@endpush
