@extends('app')

@section('styles')
<link href="{{ asset('css/uploads.css') }}" rel="stylesheet" type="text/css" >
@stop

@section('content')
<div class="container">

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Recent Media</div>

				<div class="panel-body">
				
					<div class="row">
						<div class="col-xs-12">
							<div class="ng-scope" ng-app="Uploads" ng-controller="AppController">
								<a id="refresh" ng-click="getSource()">Refresh</a>

								<h2>Photos</h2>
								
								<ul class="photos" ng-cloak class="ng-cloak">
									<li style="text-align:center" class="photo" ng-repeat="photo in data.photos">[[photo]]
										<a href="{{URL::to('/uploads')}}/[[photo]]" target="_blank" ><img bn-photo-src="{{URL::to('/uploads')}}/[[photo]]" width="150" height="115" alt="[[photo]]"/></a>
									</li >
								</ul>
								
								<h2>Videos</h2>
								
								<ul class="videos" ng-cloak class="ng-cloak">
									<li style="text-align:center" class="video" ng-repeat="video in data.videos">[[video]]
										<a href="{{URL::to('/uploads')}}/[[video]]" target="_blank" ><img bn-video-src="{{URL::to('/uploads')}}/[[video]]" src="images/video.png" width="150" height="115" alt="[[video]]"/></a>
									</li >
								</ul>
								
							</div>
						</div>	
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script src="js/angular-1.2.16.min.js"></script>

<script type="text/javascript">
	// Create an application module for our data.
	var app = angular.module( "Uploads", [] );
	
	app.config(function($interpolateProvider) {
	  $interpolateProvider.startSymbol('[[');
	  $interpolateProvider.endSymbol(']]');
	});
	
	if (!String.prototype.includes) {
	  String.prototype.includes = function() {'use strict';
		return String.prototype.indexOf.apply(this, arguments) !== -1;
	  };
	}
	
	// This controls the root of the application.
	app.controller(
		"AppController",
		function($scope, $http) {
			
			$scope.getSource = function() {

				//load remote data
				$http.get('upload')
					.success(function(data) {
						$scope.data = data;
						console.log(data);
					});
				
				setTimeout(function(){
					$("img[bn-photo-src]").each(function(){
						$(this).attr('src',$(this).attr('bn-photo-src'));
					})
				},1000);

			};
			
			$scope.getSource();
			
		}
	);
</script>
@stop