<div  class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">X</button>	
	<strong></strong>
</div>
<form id="addRoleForm"  method="post" enctype="multipart/form-data" class="form-horizontal addRoleForm" action="{{{ route('employee.add.role') }}}">
	@csrf 
	@if(isset($role))
	<input type = "hidden" name = "role"   value="{{ $role->serial }}" /> 
	@endif 
	@php  
		$myskills_arr  = array();  
		if(isset($role)){
			$myskills = $role->getRoleSkills();
			foreach($myskills as $myskill){
				$myskills_arr[] = $myskill->id;
			}
		} 
	@endphp 
	<div class = "form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="role_skills">
			<strong>   Skill </strong>
		</label> 
		<div class="col-sm-12 "> 
			<select id="skills" name="skills[]" multiple class="form-control skills"   placeholder="Enter skills here...">
				@foreach($skills as $skill)
					<option  @if( in_array( $skill->id, $myskills_arr ) ) selected @endif  value="{!! $skill->id !!}"> {{ $skill->skill }} </option> 
				@endforeach
			</select> 
			<span class="help-block"> 
				<strong></strong>  
			</span>
		</div>
	</div>
	<div class = "row">
		<div class = "col-md-6">
			<div class="form-group clearfix">
				<label class="col-sm-12 control-label text-left">
					<strong>  <star>*</star> Start Rate  </strong>
				</label>
				<div class="col-sm-8 input-group   @if ($errors->has('hourly_rate_from')) has-error @endif"> 
					<span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
					<input type="text" class="form-control hourly_rate_from decimal-input edit"  name = "hourly_rate_from" placeholder="" @if(isset($role)) value="{{old('hourly_rate_from', $role->hourly_rate_from)}}"  @else value="{{old('hourly_rate_from','')}}" @endif/> 
					<span class="input-group-addon text-dark">/hour</span> 
				</div>    
				<div class="col-sm-12">
					<span class="help-block"> 
						<strong></strong>  
					</span>  
				</div>
			</div> 
		</div> 
		<div class = "col-md-6">
			<div class="form-group clearfix">
				<label class="col-sm-12 control-label text-left">
					<strong>  <star>*</star> End Rate  </strong>
				</label> 
				<div class="col-sm-8 input-group   @if ($errors->has('hourly_rate_end')) has-error @endif"> 
						<span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
						<input type="text" class="form-control hourly_rate_end decimal-input edit"  name = "hourly_rate_end" placeholder="" @if(isset($role))   value="{{old('hourly_rate_end', $role->hourly_rate_to)}}" @else value="{{old('hourly_rate_end','')}}"    @endif />
						<span class="input-group-addon text-dark">/hour</span> 
				</div>
				<div class="col-sm-12">
					<span class="help-block"> 
						<strong></strong>  
					</span>
				</div> 
			</div>
		</div> 
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="role_title">
			<strong>  <star>*</star> Title  </strong>
		</label>
		<div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
			<input type="text" class="form-control description edit"  name = "role_title" placeholder="" @if(isset($role))  value="{{old('role_title', $role->role_title)}}"   @else  value="{{old('role_title','')}}" @endif/>
			<span class="help-block"> 
				<strong></strong>  
			</span>
		</div>
	</div> 
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="role_description">
			<strong>  <star>*</star> Description  </strong>
		</label>
		<div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
			<textarea name = "description" class = "form-control edit"  rows = "10">@if(isset($role)){!!  $role->description !!}@else{!! old('description') !!}@endif</textarea>
			<span class="help-block"> 
				<strong></strong>  
			</span>
		</div>
	</div> 
	@php
		if(isset($role)){
			$subcategory 	= 	$role->subcategory();
			if(isset($subcategory))
				$category 	  	=	$subcategory->getCategory();
		}
	@endphp
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left">
			<strong>  <star>*</star> Service Type </strong>
		</label>
		<div class="col-sm-6  @if ($errors->has('industry')) has-error @endif">
			<select class = "form-control edit" name = "industry">
				@foreach($industries as $industry)
					@if($industry->calculate_subcategories())
						<option   value = "{{ $industry->id }}" @if(isset($category) && $category->id == $industry->id) selected @endif > {{ $industry->industry }} </option>
					@endif
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left">
			<strong>  <star>*</star> Sub Category   </strong>
		</label>
		<div class="col-sm-6  @if ($errors->has('subcategory')) has-error @endif"> 
			<select class = "form-control edit" name = "subcategory">
				@foreach($industries as $industry)
					@if($industry->calculate_subcategories())
						@php
							$subcategories = $industry->subcategories; 
						@endphp
					@endif
					@foreach($subcategories as $subcategory)
						<option data-subcategory = "{!! $subcategory->category_id !!}" value = "{{ $subcategory->serial }}"   @if(isset($role)  &&  ($role->subcategory == $subcategory->id)) selected @endif   > {{ $subcategory->name }} </option>
					@endforeach
				@endforeach				
			</select>
		</div>
	</div>
	<div class="form-group clearfix">
		<div class="col-sm-12 mar-top">
			<button type="button" class="btn btn-primary add_profile">Save Changes</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	</div>
</form>	