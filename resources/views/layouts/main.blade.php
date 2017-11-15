<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('img/location-marker-icon.png') }}">

    <title>i SearcPlaces</title>

    <!-- Bootstrap core CSS -->    
    <link href="{{asset('../vendor/twbs/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/site.css')}}" rel="stylesheet">
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--     <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->

    <!-- Custom styles for this template -->
<!--     <link href="jumbotron.css" rel="stylesheet"> -->
    
    
    @yield('head')

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<!--     <script src="../../assets/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!--  PROJECT NAME BELOW -->
         
          <a class="navbar-brand" href="{{url('/home')}}">
	          <img id="companyLogo" src="{{ asset('img/location-marker-icon.png') }}" title="SearchPlaces" />
	          <span>i&nbsp;SearchPlaces</span>
          </a>
        </div>
<!--         <div id="navbar" class="navbar-collapse collapse"> -->
<!--           <form class="navbar-form navbar-right"> -->
<!--             <div class="form-group"> -->
<!--               <input type="text" placeholder="Email" class="form-control"> -->
<!--             </div> -->
<!--             <div class="form-group"> -->
<!--               <input type="password" placeholder="Password" class="form-control"> -->
<!--             </div> -->
<!--             <button type="submit" class="btn btn-success">Sign in</button> -->
<!--           </form> -->
<!--         </div> -->
	
		@if (! Request::is('home'))
         <div class="searchBar"> 
          {{ Form::open(array('id' => 'search', 'url' => '/search')) }}
              <input type="text" name="searchTerm"  placeholder="Search Places" value="{{isset($searchTerm) ? $searchTerm : '' }}" class="form-control" />
              <input type="text" name="location" value="{{isset($locationTerm) ? $locationTerm : '' }}" class="form-control" />
              <input type="hidden" name="locationId"  value="{{isset($locationId) ? $locationId : '' }}"/>
              <button type="submit" class="btn btn-success">Search</button>
           {{ Form::close() }}
          </div>
          @endif


      </div>
    </nav>
    

    <!-- Main jumbotron for a primary marketing message or call to action -->
    @if (Request::is('home'))
    <div class="jumbotron">
      <div class="container">
        <h1>Search Places</h1>
        <p>Search Places and compare reviews from trusted sites like Yelp and Google!</p>
         <div class="searchBar">
         {{ Form::open(array('id' => 'search', 'url' => '/search')) }}
              <input type="text" name="searchTerm"  placeholder="Search Places" value="{{isset($searchTerm) ? $searchTerm : '' }}" class="form-control" />
              <input type="text" name="location" value="{{isset($locationTerm) ? $locationTerm : '' }}" class="form-control" />
              <input type="hidden" name="locationId"  value="{{isset($locationId) ? $locationId : '' }}"/>
              <button type="submit" class="btn btn-success">Search</button>
          {{ Form::close() }}
          </div>
      </div>
      	  @if(isset($placeResults['result'][0]['name']))
      	  	@php $placeId = $placeResults['result'][0]['place_id']; @endphp
	          <div id="photoFootnote">
	          	<a href="place/{{$placeId}}">{{$placeResults['result'][0]['name'] . ', ' . $placeResults['result'][0]['vicinity']}}</a>
	          </div>
          @endif
    </div>
    @endif

    <div class="container main-container">
	  @yield('content')
      <footer>
<!--         <p>&copy; 2017 Company, Inc.</p> -->
      </footer>
    </div> <!-- /container -->

	    <!-- The Gallery as lightbox dialog, should be a child element of the document body -->
	<div id="blueimp-gallery" class="blueimp-gallery">
	    <div class="slides"></div>
	    <h3 class="title"></h3>
	    <a class="prev">&lsaquo;</a>
	    <a class="next">&rsaquo;</a>
	    <a class="close">&times;</a>
	    <a class="play-pause"></a>
	    <ol class="indicator"></ol>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
    <script src="{{asset('../vendor/twbs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--     <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
    @yield('footer')
    

  </body>
</html>
