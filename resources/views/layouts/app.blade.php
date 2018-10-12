<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ScatterJS -->
    <script src="https://rawgit.com/GetScatter/scatter-js/master/bundles/scatterjs-core.min.js"></script>
    <script src="https://rawgit.com/GetScatter/scatter-js/master/bundles/scatterjs-plugin-eosjs.min.js"></script>

    <!-- EOSJS -->
    <script src="https://cdn.jsdelivr.net/npm/eosjs@15.0.3/lib/eos.min.js" integrity="sha512-QX0dPq5pyX33coEuy5x1UqKHFDeveQYMp7Sz+qOUwRL9mol4QDvViU+QAjd+k6P7QjPjrDCoyhK1kz2GDxCP9A==" crossorigin="anonymous"></script>

    <script>
        const EOS_NODE = '{{ getEnv('EOS_NODE')}}';
        const EOS_PORT = '{{ getEnv('EOS_PORT')}}';
        const EOS_PROT = '{{ getEnv('EOS_PROT')}}';
    </script>
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/voteos.js') }}"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    <title>voteos.info | @yield('title')</title>
  </head>
  <body>
    <div class="container border-left border-bottom border-right border-primary">

        <nav class="navbar navbar-expand-md navbar-light bg-faded">
            <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 1.5em;">
                <i class="far fa-check-square" style="transform: rotate(-20deg);"></i>
                voteos.info
                <img src="{{ asset('images/alpha.jpg') }}"  style="height: 50px; margin: -25px -15px -25px -5px;" />
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/pages/about') }}">block producers</a>
                    </li>
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/proposals?type=constitution') }}">constitutions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/proposals?type=working_proposal') }}">working proposals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/proposals?type=general') }}">general discusions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/proposals/create') }}">
                            <i class="fa fa-plus"></i>
                            create proposal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ page_url('about') }}">about</a>
                    </li>
                </ul>
            </div>
        </nav>


        <h1 class="text-center m-4">
            <a href="{{ url('/') }}">
                <i class="far fa-check-square" style="transform: rotate(-20deg);"></i>
                voteos.info
            </a><br />
            <small class="text-muted">vote informed, vote smart</small>
        </h1>

        <div class="content">

            <div class="alert alert-warning no-eos d-none">
                <p>
                    <img src="{{ asset('images/scatter.jpeg') }}" class="rounded float-left" style="height: 1.5em; margin-right: 0.5em;" />
                    <strong class="h4">We‘ve detected you don‘t have Scatter installed. </strong>
                </p>
                <p>
                    Scatter is is an EOS tool to allow you to autenticate your EOS account to be able
                    to vote or comment, signing any interaction within the site. Please install and
                    configure your EOS account using Scatter to be able to post or vote in the site.
                </p>
                <p>
                    <a href="https://get-scatter.com/" target="_blank" class="btn btn-primary">Get Scatter!</a>
                </p>
            </div>

            @yield('content')
        </div>

        <footer class="text-center pb-2">
            <hr />
            <p>
                <a href="https://github.com/eosMeso/voteos.info" target="_blank">
                    <i class="far fa-check-square" style="transform: rotate(-20deg);"></i> voteos.info
                    <i class="fab fa-github"></i>
                </a>
                is another service provided by <a target="_blank" href="https://eosmeso.io">eosmeso.io</a> © 2018
            </p>
        </footer>
    </div>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122661947-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-122661947-1');
    </script>
  </body>
</html>