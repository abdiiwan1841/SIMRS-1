<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/popper.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ asset('js/jHtmlArea-0.8.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vertical-tabs.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-timepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jHtmlArea.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    </head>
    <div class="d-block d-sm-none">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark" style="margin-bottom: 20px">
            <a class="navbar-brand" href="/">SIMRS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @include('layouttemplate::sidebar-mobile')
                </ul>
            </div>
        </nav>
    </div>
    <body style="background-image: url({{ asset('img/grey.png') }})">
        <div class="d-none d-sm-block container-fluid" style="padding: 1% 20px 1% 20px; background-color: white; margin-bottom: 15px; border-bottom: 1px solid lightgrey;">
            <a href="{{ route('logout') }}" class="btn btn-outline-danger float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <a href="{{ route('setting.index') }}" class="btn btn-outline-info float-right" style="margin-right: 10px"><i class="fa fa-cogs"></i> Pengaturan Akun</a>
            <h4>Sistem Informasi Manajemen Rumah Sakit | <small style="font-size: 17px">{{ \Illuminate\Support\Facades\Auth::user()->nama }}</small></h4>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                    @include('layouttemplate::alert')

                    <div class="d-block d-sm-none">
                        @yield('content-mobile')
                    </div>

                    <div class="d-none d-sm-block">
                        <div class="d-flex">
                            <ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left">
                                @include('layouttemplate::sidebar')
                            </ul>
                            <div class="tab-content">
                                <div class="row pre-scrollable" style="padding: 10px 25px 0 10px; max-height: 85vh; min-width: 100%">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <th class="w-100 hidden"></th>
                                            <td class="w-100 hidden"></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @yield('script')
    </body>
    <footer class="d-none" style="padding-bottom: 2%"></footer>
</html>
