@extends('app')

@section('styles')
<link href="{{ asset('css/jquery.Jcrop.css') }}" rel="stylesheet" type="text/css" >

<style type="text/css">

/* Apply these styles only when #preview-pane has
   been placed within the Jcrop widget */
.jcrop-holder #preview-pane {
  display: block;
  position: absolute;
  z-index: 2000;
  top: 10px;
  right: -280px;
  padding: 6px;
  border: 1px rgba(0,0,0,.4) solid;
  background-color: white;

  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;

  -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
}

/* The Javascript code will set the aspect ratio of the crop
   area based on the size of the thumbnail preview,
   specified here */
#preview-pane .preview-container {
  width: 250px;
  height: 170px;
  overflow: hidden;
}

#target {max-width: 70%;}

</style>
@stop

@section('content')
<div class="container">

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">@if(isset($filename)){{'Crop Image '.$filename}}@endif</div>

				<div class="panel-body">
				
					<div class="row">
						<div class="col-xs-12"><span class="_color2 option"></span>

							@if(Session::has('success'))
								<div class="alert alert-success">
									{!! Session::get('success') !!}
								</div>							
							@endif
							
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<strong>Whoops!</strong> There were some problems with your input.<br><br>
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							
							<pre>{{ var_dump(Input::all()) }}</pre>
							
							@if(isset($filename))
							<div class="jc-demo-box">
							  <img src="{{URL::to('/uploads')}}/{{$filename}}" id="target" alt="[Jcrop Example]" />

							  <div id="preview-pane" style="visibility:hidden;display:none;">
								<div class="preview-container">
								  <img src="{{URL::to('/uploads')}}/{{$filename}}" class="jcrop-preview" alt="Preview" />
								</div>
							  </div>

							  <div class="clearfix"></div>

							</div>
							
							<br />

							<!-- This is the form that our event handler fills -->
							<form id="coords" class="form-inline coords" method="post" action="<?=URL::to('/upload/crop')?>" onsubmit="return checkCoords();">
								<div class="inline-labels">
									<label>X1 <input readonly class="form-control input-lg" type="text" size="4" id="x1" name="x1" /></label>
									<label>Y1 <input readonly class="form-control input-lg" type="text" size="4" id="y1" name="y1" /></label>
									<label>X2 <input readonly class="form-control input-lg" type="text" size="4" id="x2" name="x2" /></label>
									<label>Y2 <input readonly class="form-control input-lg" type="text" size="4" id="y2" name="y2" /></label>
									<label>W <input readonly class="form-control input-lg" type="text" size="4" id="w" name="w" /></label>
									<label>H <input readonly class="form-control input-lg" type="text" size="4" id="h" name="h" /></label>
								</div>
								
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" name="filename" value="{{$filename}}">
								
								<br />
								
								<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-hdd"></span> Crop and Save</button>

							</form>
							@endif
									
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.Jcrop.js') }}"></script>
<script type="text/javascript">
  jQuery(function($){

    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();
    
    console.log('init',[xsize,ysize]);
    $('#target').Jcrop({
      onChange: showCoords,
      onSelect: showCoords,
      onRelease:  clearCoords,
      <?php if(Input::get('l')==1) {?>aspectRatio: xsize / ysize<?php } ?>
    },function(){
      // Use the API to get the real image size
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;
	  <?php if(Input::get('r')==1) {?>
	  //$type=='albums'
	  //preset 720x455 for gallery / albums
      /*jcrop_api.setOptions({
		  minSize: [ 720, 455 ],
		  maxSize: [ 720, 455 ]
      });*/

	  //as close as possable to above
      jcrop_api.setOptions({
		  minSize: [ 190, 120 ],
		  maxSize: [ 190, 120 ]
      });
	  <?php } ?>
      // Move the preview into the jcrop container for css positioning
      $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
	  showCoords(c);
    };

	  $('#coords').on('change','input',function(e){
		  var x1 = $('#x1').val(),
			  x2 = $('#x2').val(),
			  y1 = $('#y1').val(),
			  y2 = $('#y2').val();
		  jcrop_api.setSelect([x1,y1,x2,y2]);
	  });

	  // Simple event handler, called from onChange and onSelect
	  // event handlers, as per the Jcrop invocation above
	  function showCoords(c)
	  {
		<?php list($width, $height) = getimagesize($destinationPath.$filename); ?>
		var width = <?=$width?>;
		var height = <?=$height?>;
		var aspectRatio = width / height;

		var css_width = $('#target').css('width').replace(/[^-\d\.]/g, '');
		var css_height = $('#target').css('height').replace(/[^-\d\.]/g, '');

		var difWidth = width/css_width;
		var difHeight = height/css_height;

		/*console.log(
			'width:' + width
			+ '\n height:' + height
			+ '\n aspectRatio:' + aspectRatio
			+ '\n css_width:' + css_width
			+ '\n css_height:' + css_height
			+ '\n difWidth:' + difWidth
			+ '\n difHeight:' + difHeight
			);*/

		//console.log([720 - css_width,455 - css_height]);

		$('#x1').val(Math.round(c.x * difWidth));
		$('#y1').val(Math.round(c.y * difHeight));
		$('#x2').val(Math.round(c.x2 * difWidth));
		$('#y2').val(Math.round(c.y2 * difHeight));
		$('#w').val(Math.round(c.w * difWidth));
		$('#h').val(Math.round(c.h * difHeight));
	  };

	  function clearCoords()
	  {
		$('#coords input.form-control').val('');
	  };

	  function checkCoords()
	  {
		if (parseInt($('#w').val())) return true;
		alert('Please select a crop region then press submit.');
		return false;
	  };

  });
</script>
@stop