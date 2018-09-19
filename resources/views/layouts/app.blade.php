<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF=8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Monolist</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <!-- href="{{ secure_asset('css/style.css') }}" のコードにより自身のドメイン直下(例：micropost.com/)を指す
        　 　href="http://micropost/css/style.css" となる　publicフォルダ直下にファルダやファイルを置けば良い-->
        <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
    </head>
    <body>
        @include('commons.navbar')
        
        @yield('cover')
        
        <div class="container">
            @include('commons.error_messages')
            @yield('content')
        </div>
        @include('commons.footer')
    </body>
</html>