	<div class="card">
		<div class="card-body">
			<div class="col-sm-12"> 
				<img src="{{$user->getImage()}}" class="avtar-img img-rounded img-responsive" style="height:150px;"> 
			</div>
		</div>
	</div>
	<br>
	<p>
		<strong>Upload an official government ID to verify your Profile picture.</strong><br>
		<small>image/jpeg, image/jpg, image/png,.pdf files are accepted only.</small>
	</p> 
	<div class="needsclick dropzone" id="profilepic-dropzone"> </div>
    <span class="image-upload-error-profilepic error invalid-feedback">  </span>
	<br> 
	<div class = "row">
		<div class = "col-md-12 text-center">
			<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			<button type="submit" id="profilepic-submit" class="btn btn-primary">Upload</button>	
		</div>
	</div>
@if(0)
	@if(count($picrverification) > 0)
		<div class="row">
			<h5 class="col-md-12">Rejected documents</h5>
			@foreach($picrverification as $document)
				<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
					<img src="{{$document->getPath()}}" class="card-img-top" style="max-height:150px; min-height:150px;"> 
					<div class="card-body">
						<p>{!! nl2br($document->note) !!}</p>
						<a href="{{ $document->getPath() }}" class="btn btn-primary" target="_blank">View</a> 
					</div>
				</div>
			@endforeach
		</div>
	@endif
	
	@if(count($picaverification) > 0)
		<div class="row">
			<h5 class="col-md-12">Approved documents</h5>
			@foreach($picaverification as $document)
				<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
					<img src="{{ $document->getPath() }}" class="card-img-top" style="max-height:150px; min-height:150px;">  
					<div class="card-body">
						<p>{!! nl2br($document->note) !!}</p>
						<a href="{{$document->path}}" class="btn btn-primary" target="_blank">View</a> 
					</div>
				</div>
			@endforeach
		</div>
	@endif

@endif