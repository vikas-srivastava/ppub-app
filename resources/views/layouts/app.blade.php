<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Packt Pub App</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="/css/app.css" rel="stylesheet">
    </head>

	<body>

        @include('partials.header')

        @yield('content')

        @include('partials.footer')

	</body>

</html>
