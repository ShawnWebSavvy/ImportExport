<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<!DOCTYPE html>
<html>
<head>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

li {
  display: inline;
}
</style>

<!DOCTYPE html>
<html>
<head>
    <title>ImportExport</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
</head>
<body>

<ul>
  <li><a href="{{ route('file-import') }}">Home</a></li> | 
  <li><a href="{{ route('file-export-namibia-index') }}">Namibia</a></li> | 
  <li><a href="{{ route('file-export-botswana-index') }}">Botswana</a></li> | 
  @if (Route::has('login'))
    @auth
        <li><a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a></li> | 
      @else
        <li><a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a></li> | 

        @if (Route::has('register'))
          <li><a href="{{ route('register') }}" >Register</a></li> | 
        @endif

    @endauth

  @endif
  <li><a href="{{ route('logout') }}" >qq</a></li> | 

  <li>
  <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                            </li> | 

  <li>
  <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Outt') }}
                            </x-dropdown-link>
                            </li> | 

  <!--
  <li><a href="{{ route('file-export-botswana-install-headers-index') }}">Install Header</a></li> | 
  <li><a href="{{ route('file-export-botswana-user-headers-index') }}">User Header</a></li> | 
  <li><a href="{{ route('file-export-botswana-contras-index') }}">Contras</a></li> | 
  <li><a href="{{ route('file-export-botswana-transactions-index') }}">Transactions</a></li> | 
  <li><a href="{{ route('file-export-botswana-install-trailers-index') }}">Install Trailers</a></li> | 
  <li><a href="{{ route('file-export-botswana-user-trailers-index') }}">User Trailers</a></li> | 
  -->
</ul>
  
<div class="container">
    @yield('content')
</div>
   
</body>
</html>