<div class="card mar-btm">
	<div class="card-body">
		@if($user->accounts->caddress != NULL)<p><b class="col-lg-3">Address:</b>{{$user->accounts->caddress}}</p>@endif
		@if($user->accounts->city != NULL)<p><b class="col-lg-3">City:</b>{{$user->accounts->city}}</p>@endif
		@if($user->accounts->state != NULL)<p><b class="col-lg-3">State:</b>{{$user->accounts->state}}</p>@endif
		@if($user->accounts->zip != NULL)<p><b class="col-lg-3">Zip:</b>{{$user->accounts->zip}}</p>@endif
		@if($user->accounts->oaddress != NULL)<p><b class="col-lg-3">Address 2:</b>{{$user->accounts->oaddress}}</p>@endif
	</div>
</div> 

<p>
	<strong>Upload an official government ID to verify your address details.</strong><br>
	<small>image/jpeg, image/jpg, image/png,.pdf files are accepted only.</small>
</p> 
<div class="needsclick dropzone" id="address-dropzone">
</div> 
<span class="image-upload-error-address error invalid-feedback"> </span>
<br> 
<div class = "row">
	<div class = "col-md-12 text-center">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" id="address-submit" class="btn btn-primary">Upload</button>	
	</div>
</div> 
	@if(count($rverification) > 0)
		<div class="row">
			<h5 class="col-md-12">Rejected documents</h5>
			@foreach($rverification as $document)
				<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;"> 
					<img src="{{   $document->getPath()  }}" class="card-img-top" style="max-height:150px; min-height:150px;"> 
					<div class="card-body">
						<p>{!! nl2br($document->note) !!}</p>
						<a href="{{  $document->getPath()  }}" class="btn btn-primary" target="_blank">View</a> 
					</div>
				</div>
			@endforeach
		</div>
	@endif 
	@if(count($averification) > 0)
		<div class="row">
			<h5 class="col-md-12">Approved documents</h5>
			@foreach($averification as $document)
			<div class="card col-md-3" style="width: 18rem; margin:10px; padding:0px;">
				<img src="{{   $document->getPath()  }}" class="card-img-top" style="max-height:150px; min-height:150px;">
				<div class="card-body">
					<p>{!! nl2br($document->note) !!}</p>
					<a href="{{$document->path}}" class="btn btn-primary" target="_blank">View</a> 
				</div>
			</div>
			@endforeach
		</div>
	@endif