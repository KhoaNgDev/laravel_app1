<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layouts.partials.head')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('admin.layouts.partials.navbar')
            @include('admin.layouts.partials.main-sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @include('admin.layouts.partials.section-header')
                    <div class="section-body">
                        @yield('admin-content')
                    </div>
                </section>
            </div>

            @include('admin.layouts.partials.footer')
        </div>
        <form id="delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @include('admin.layouts.partials.foot')
    @yield('modals')
    
</body>

</html>
