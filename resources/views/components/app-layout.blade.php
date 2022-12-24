<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @production
        <script async defer data-website-id="84334b64-d3d9-48cd-8c4b-610c7fb19b16" src="https://analytics.markrailton.com/umami.js"></script>
    @endproduction

    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <title>Mark Railton</title>
</head>
<body>
<x-header />
<main class="py-12">
    {{ $slot }}
</main>
</body>
</html>
