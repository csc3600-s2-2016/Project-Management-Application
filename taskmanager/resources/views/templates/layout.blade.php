<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="An Online Project Management Application">
    <meta name="author" content="Team FooBar">
    <meta name="keywords" content="Project, Management, Software, Task, App">
    <link rel="icon" href="/assests/images/favicon.ico">

    <title>{{ config('app.name') }}</title>
    <link href="/assests/css/style.css" rel="stylesheet">
  </head>

  <body>

    @yield('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/assests/js/vendorbundle.js"></script>
  </body>
</html>