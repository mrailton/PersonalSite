<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @production
        <script async src="https://analytics.markrailton.com/tracker.js" data-ackee-server="https://analytics.markrailton.com" data-ackee-domain-id="43f9ae3a-584e-4d9b-a6fa-2657e59c2c21"></script>
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
