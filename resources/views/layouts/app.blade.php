<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/favicon.png') }}" type="image/x-icon">
    <title>{{ env('APP_NAME') }}</title>

    @include('layouts.partials.css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="app">
        <div id="main" class="layout-horizontal">
            @include('layouts.partials.header')
            <div class="content-wrapper container">
                <div class="page-content">
                    <section class="row">
                        @yield('content')
                    </section>
                </div>
            </div>
        </div>
        @include('layouts.partials.footer')
    </div>
    @vite('resources/js/landing.js')
    @include('layouts.partials.js')
</body>

</html>
