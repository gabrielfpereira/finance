<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App Financeiro</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script src="{{ asset('js/app.js') }}" defer></script>
   

    @livewireStyles
</head>
<body>

    {{  $slot }}

    @livewireScripts

    <script src="{{ asset('js/jquery.js') }}" ></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}" ></script>

    <script>
        $(document).ready(function(){
            $('.valor').mask('000.000.000.000.000,00', {reverse: true});
        });
    </script>
</body>
</html>