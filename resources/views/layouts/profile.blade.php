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
            <div class="content-wrapper container">
                <div class="page-content">
                    <section class="row m-3">
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <img
                                        src="image source"
                                        class="img-fluid rounded-top"
                                        alt=""
                                    />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    Test
                                </div>
                            </div>
                        </div>
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
