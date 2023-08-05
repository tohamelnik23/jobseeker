<div id="transportationresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">X</button>	
	<strong></strong>
</div>
<form id="addtransportationForm"  method="post" enctype="multipart/form-data" class="form-horizontal" action="{{{ route('employee.add.certification') }}}">
	@csrf 
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="description">
			<strong>  <star>*</star> Description  </strong>
		</label>
		<div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
			<input type="text" class="form-control description"  name = "description" placeholder="" value="{{old('description','')}}"/>
			<em   class="error help-block hide description-error"> </em>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="validationID">
			<strong> Picture of Proof </strong>
		</label>
		<div class = "col-sm-12 ">
			<input type = "file" class = "col-sm-12 form-control picture" name = "picture"   accept="image/*" /> 
		</div>
		<em   class="error help-block hide picture-error"> </em>
	</div>  
	<div class="form-group clearfix">
		<div class="col-sm-12 mar-top">
			<button type="submit" class="btn btn-primary">Save Changes</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	</div>
</form>