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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">

    <title>{{ config('app.name') }}</title>
    <link href="/css/app.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
  </head>

  <body>
  
      <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
          
            <ul class="nav navbar-nav">
              @if (Auth::guest())
              @else
              <li 
              @if (Request::is('project'))
              { class="active" } 
              @endif
              ><a href="/projects">Projects</a></li>
              <li
              @if (Request::is('tasks'))
              { class="active" } 
              @endif
              ><a href="/tasks">Tasks</a></li>
              <li
              @if (Request::is('review'))
              { class="active" } 
              @endif
              ><a href="/review">Review</a></li>
              @endif
              
            </ul>
      
            <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li
                        @if (Request::is('login'))
                          { class="active" } 
                        @endif
                        ><a href="{{ url('/login') }}">Login</a></li>
                        <li
                        @if (Request::is('register'))
                          { class="active" } 
                        @endif
                        ><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li class="dropdown-header">Projects</li>
                                @foreach ( Auth::user()->projects as $project  )
                                    @if ($project->active == true && $project->pivot->invite_accepted)
                                    <li><a href="/tasks/{{$project->id}}">

                                        @if ($project->id == session("project"))
                                        <i class="fa fa-btn fa-star"></i>
                                        @endif

                                     {{$project->name}}</a></li>
                                    @endif
                                @endforeach
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i> My Profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      <div id="app">
    @yield('content')
      </div>
    <script src="/js/app.js"></script>
  </body>
</html>
