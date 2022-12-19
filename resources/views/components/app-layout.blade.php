<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <title>Mark Railton</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<x-header />
<main class="py-12">
    {{ $slot }}
</main>

@production
    <script defer data-domain="markrailton.com" src="https://analytics.markrailton.com/js/plausible.js"></script>
@endproduction
</body>
</html>
