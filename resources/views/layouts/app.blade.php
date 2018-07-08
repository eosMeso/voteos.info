<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/eosconstitution.css') }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/eosjs@15.0.3/lib/eos.min.js" integrity="sha512-QX0dPq5pyX33coEuy5x1UqKHFDeveQYMp7Sz+qOUwRL9mol4QDvViU+QAjd+k6P7QjPjrDCoyhK1kz2GDxCP9A==" crossorigin="anonymous"></script>
    <script>
        const EOS_NODE = '{{ getEnv('EOS_NODE')}}';
        const EOS_PORT = '{{ getEnv('EOS_PORT')}}';
        const EOS_PROT = '{{ getEnv('EOS_PROT')}}';
    </script>

    <title>eosconstitution.io | @yield('title')</title>
  </head>
  <body>
    <div class="container">
        <center class="flex-center pt-5">
            <h1>
                <a href="{{ url('/') }}">We the EOS People</a>
                <br />
                <small class="text-muted">eosconstitution.io</small>
            </h1>
        </center>



        <div class="position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                @yield('content')
            </div>
        </div>

        <footer class="text-center">
            <hr />
            <p>
                eosconstitution.io is another service provided by <a target="_blank" href="https://eosmeso.io">eosmeso.io</a> © 2018
            </p>
        </footer>
    </div>
  </body>
</html>