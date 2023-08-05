<form id = "editsubcategoryForm" method="post" class="form-horizontal" action="{{{ route('master.industries.subcategories.update', $industry->serial) }}}">
	@csrf
	<input type="hidden" name="id" value="{{$subcategory->serial}}">
	<div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="subcategory">Sub Category</label>
			<div class="col-sm-12  @if ($errors->has('subcategory')) has-error @endif">
				<input type="text" class="form-control" id="subcategory" name = "subcategory" placeholder="subcategory" value="{{old('subcategory',$subcategory->name)}}"/>
				<em id="subcategory-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>
	<div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary submitButton" name="save" value="Save Changes">Save</button>
				<a href="javascript:void(0)" class="btn btn-danger deleteaction"   data-string = "Do you want to remove this item?"   data-url = "{!! route('master.industries.subcategories.delete',  $subcategory->serial) !!}"   data-title="Delete Subcategory">
					Delete
				</a>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>	
</form>