<div class="card">
	<div class="card-body">
	<div class="col-sm-12">
	@if(!empty($user->avtarMedia->name)) 
		<img src="{{$user->avtarMedia->path}}" class="avtar-img img-rounded img-responsive" style="height:150px;">
	@else
		<img src="{{ asset('avatar/placeholderAvatar.jpg') }}" class="avtar-img img-rounded img-responsive" style="height:150px;">
	@endif
	</div>
	</div>
</div>
<br>
<p><strong>Upload an official government ID to verify your Profile picture.</strong><br>
<small>image/jpeg, image/jpg, image/png,.pdf files are accepted only.</small></p>
<div class="needsclick dropzone" id="profilepic-dropzone">

									</div>
          <span class="image-upload-error-profilepic error invalid-feedback">
							  </span>
	<br>
<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
<button type="submit" id="profilepic-submit" class="btn btn-primary">Upload</button>	

@if(count($picrverification) > 0)
	<div class="row">
	<h3 class="col-md-12">Rejected documents</h3>
	@foreach($picrverification as $document)
	<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;">
	   @if($document->extension == 'pdf')
			<img src="{{asset('address/pdf.png')}}" class="card-img-top" style="max-height:150px; min-height:150px;">
		@else
			<img src="{{$document->path}}" class="card-img-top" style="max-height:150px; min-height:150px;">
		@endif
	  
	  <div class="card-body">
	  <p>{{$document->note}}</p>
		<a href="{{$document->path}}" class="btn btn-primary" target="_blank">View</a>
		
	  </div>
	</div>
	@endforeach
	</div>
@endif

@if(count($picaverification) > 0)
	<div class="row">
	<h3 class="col-md-12">Approved documents</h3>
	@foreach($picaverification as $document)
	<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;">
	   @if($document->extension == 'pdf')
			<img src="{{asset('address/pdf.png')}}" class="card-img-top" style="max-height:150px; min-height:150px;">
		@else
			<img src="{{$document->path}}" class="card-img-top" style="max-height:150px; min-height:150px;">
		@endif
	  
	  <div class="card-body">
	  <p>{{$document->note}}</p>
		<a href="{{$document->path}}" class="btn btn-primary" target="_blank">View</a>
		
	  </div>
	</div>
	@endforeach
	</div>
@endif	
						  