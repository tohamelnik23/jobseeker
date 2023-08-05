<div  class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">X</button>	
	<strong></strong>
</div>    
<form id="noteForm"  method="post" enctype="multipart/form-data" class="form-horizontal" action="{{{ route('document.add.note') }}}">
    @csrf 
    <input type="hidden" name="id" value="{{$document->id}}">
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="description">
			<strong>    Note  </strong>
		</label>
		<div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
            <textarea rows="8" id="note" name="note" class="form-control" placeholder="note">{{$document->note}}</textarea>
            <em id="note-error" class="error help-block"></em>
		</div>
	</div>   
	<div class="form-group clearfix">
		<div class="col-sm-12 mar-top">
			<button type="submit" class="btn btn-primary">Save Changes</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	</div>
</form>	