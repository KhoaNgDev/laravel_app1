<!DOCTYPE html>
<html lang="en">

<head>
    @include('errors.partials.head')
    @section('title', '503 Page')

</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>503</h1>
                        <div class="page-description">Be right back.</div>
                        <div class="page-search">
                            <div class="mt-3">
                                <a href="{{ route('welcome') }}">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.partials.copyright')
            </div>
        </section>
    </div>

    @include('errors.partials.foot')

</body>

</html>
