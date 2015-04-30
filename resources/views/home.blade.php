@extends('app')

@section('styles')
<link href="{{ asset('css/uploadfile.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/uploads.css') }}" rel="stylesheet" type="text/css" >
@stop

@section('content')
<div class="container">

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Upload new media</div>

				<div class="panel-body">
				
					<div class="row">
						<div class="col-xs-12"><span class="_color2 option"></span>
							<form id="myform" method="post" enctype="multipart/form-data">
								<input type="hidden" name="preview_image" id="preview_image" value="">
								<div id="mulitplefileuploader">Upload</div>
								<br />
							</form>
							<div class="hide col-xs-12 col-md-3"><button type="button" class="btn btn-default submit_form">Start Upload</button></div>
							<form class="hide" enctype="multipart/form-data" method="post" action="upload">
								<label>Choose a zip file to upload: <input type="file" name="upload" /></label>
								<br />
								<input type="submit" name="submit" value="Upload" />
							</form>
							
							@if(Session::has('success'))
								<div class="alert alert-success">
									{!! Session::get('success') !!}
								</div>							
							@endif
							@if(Session::has('error'))
							<p class="errors">{!! Session::get('error') !!}</p>
							@endif
	
							<form class="hide" action="upload" method="post" enctype="multipart/form-data">
								<!--<input type="hidden" name="_token" value="{{ csrf_token() }}">-->
								<input type="file" name="upload">
								<input type="submit">
							</form>
							
							<div class="ng-scope" ng-app="Uploads" ng-controller="AppController">
								<a id="refresh" ng-click="getSource()">Refresh</a>
								
								<h2>Photos - Allowed types: jpg,jpeg,gif,png</h2>
								
								<ul class="photos" ng-cloak class="ng-cloak">
									<li style="text-align:center" class="photo" ng-repeat="photo in data.photos">[[photo]]
										<a href="{{URL::to('/uploads')}}/[[photo]]" target="_blank" ><img bn-photo-src="{{URL::to('/uploads')}}/[[photo]]" width="150" height="115" alt="[[photo]]"/></a>
										
										<form  style="display:inline;" action="{{URL::to('/upload/crop')}}" method="get">
											<!--<input type="hidden" name="_token" value="{{ csrf_token() }}">-->
											<input type="hidden" name="filename" value="[[photo]]">
											<input class="btn btn-primary" type="submit" value="Crop"/>
										</form>
										
										<form style="display:inline;" action="{{URL::to('/upload/delete')}}" method="post" onSubmit="return confirm('Are you sure you want to delete this?');">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="filename" value="[[photo]]">
											<input class="btn btn-danger" type="submit" value="Delete"/>
										</form>
									</li >
								</ul>
								
								<h2>Videos - Allowed types: mp4,mpg,mpeg,mov</h2>
								
								<ul class="videos" ng-cloak class="ng-cloak">
									<li style="text-align:center" class="video" ng-repeat="video in data.videos">[[video]]
										<a href="{{URL::to('/uploads')}}/[[video]]" target="_blank" ><img bn-video-src="{{URL::to('/uploads')}}/[[video]]" src="images/video.png" width="150" height="115" alt="[[video]]"/></a>
										<form class="hide" style="display:inline;" action="{{URL::to('/upload/public')}}" method="post">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="filename" value="[[video]]">
											<input class="btn btn-primary" type="submit" value="Public"/>
										</form>
										
										<form style="display:inline;" action="{{URL::to('/upload/delete')}}" method="post" onSubmit="return confirm('Are you sure you want to delete this?');">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="filename" value="[[video]]">
											<input class="btn btn-danger" type="submit" value="Delete"/>
										</form>
									</li >
								</ul>
								
							</div>
									
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="hide row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Raw Data</div>

				<div class="panel-body">
					<pre>{{ var_dump($users->toArray()) }}</pre>
					<pre>{{ var_dump($media->toArray()) }}</pre>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="http://campaignstudio.matthewbullweb.co.uk/js/jquery.uploadfile.min.js"></script>
<script src="http://campaignstudio.matthewbullweb.co.uk/js/jquery.validationEngine-en.js"></script>
<script src="http://campaignstudio.matthewbullweb.co.uk/js/jquery.validationEngine.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		var settings = $("#mulitplefileuploader").uploadFile({
			url: "upload",
			method: "POST",
			allowedTypes:"jpg,jpeg,gif,png,mp4,mpg,mpeg,mov",
			fileName: "upload",
			singleFileUploads : false,
			dragDrop: true,
			multiple:true,
			autoSubmit:true,
			showStatusAfterSuccess:false,
			onSubmit:function(files)
			{
				$('<input>').attr({
					type: 'hidden',
					name: 'upload[]',
					value: files
				}).appendTo('#myform');
			},
			onSuccess:function(files,data,xhr)
			{
				//$("#status").html("<ul class=\"nav navbar-nav list-unstyled alert bg-success\"><li>Success</li></ul>");

				var out = {
					_files: files,
					_data: data,
					_xhr: xhr
				};

				console.log(out);
				//window.location.reload();
				
				$('#refresh').click();
			},
			onError: function(files,status,errMsg)
			{
				//$("#status").html("<ul class=\"nav navbar-nav list-unstyled alert bg-danger\"><li>Error - " + errMsg + "</li></ul>");

				var out = {
					_files: files,
					_status: status,
					_errMsg: errMsg
				};

				console.log(out);
			}
		});

		$('.submit_form').click(function() {
			var validate = $("#myform").validationEngine('validate');
			var has_file = $(".ajax-file-upload-statusbar").length //check if there files need upload

			if(validate){
				if(has_file != false){
					settings.startUpload();
				}
			}
		});
	});
</script>


<script src="js/angular-1.2.16.min.js"></script>

<script type="text/javascript">
	// Create an application module for our data.
	var app = angular.module( "Uploads", [] );
	
	app.config(function($interpolateProvider) {
	  $interpolateProvider.startSymbol('[[');
	  $interpolateProvider.endSymbol(']]');
	});
	
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