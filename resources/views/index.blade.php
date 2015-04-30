@extends('app')

@section('styles')
<link href="{{ asset('css/photos.css') }}" rel="stylesheet" type="text/css" >
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
								
								<ul class="photos" ng-cloak class="ng-cloak">
									<li style="text-align:center"  class="photo" ng-repeat="upload in data">[[upload]]
										<a href="{{URL::to('/uploads')}}/[[upload]]" target="_blank" ><img bn-lazy-src="{{URL::to('/uploads')}}/[[upload]]" width="150" height="115" alt="[[upload]]"/></a>
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
					$("img[bn-lazy-src]").each(function(){
						$(this).attr('src',$(this).attr('bn-lazy-src'));
					})
				},1000);

			};
			
			$scope.getSource();
			
		}
	);
</script>
@stop