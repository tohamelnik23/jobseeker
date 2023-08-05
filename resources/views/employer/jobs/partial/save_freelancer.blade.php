@php 
    $profile_freelancer =   $user->getBestProfile([], 0);  
@endphp
<div  class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">X</button>	
	<strong></strong>
</div>
<form id = "SaveFreelancerForm"  method="post" enctype="multipart/form-data" class="form-horizontal" action="{{ route('employer.jobs.addsavefreelancer') }}">
	@csrf
    <div class="form-group clearfix">
        <div class="col-lg-12">
            <div class="cfe-ui-profile-identity">
                <div class="mr-10 mr-lg-30 position-relative">
                    <img class="img-circle img-md" src="{!! $user->getImage() !!}">      
                </div>
                <div class="identity-container"> 
                    <div class="mar-btm-5 mb-md-10">
                        <h5 class="d-inline vertical-align-middle m-0 text-mint"> {!! $user->accounts->name  !!} </h5> 
                        <p class="text-dark mar-top-5">
                            {!! $profile_freelancer->role_title !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type = "hidden" name = "user_id" value = "{!! $user->serial !!}" /> 
	<div class="form-group clearfix">
		<label class="col-sm-12 control-label text-left" for="notes">
			<strong>   Notes  </strong>
		</label>
		<div class="col-sm-12  @if ($errors->has('notes')) has-error @endif">
			<textarea name = "notes" placeholder = "Add an optional note so you can remember what was important about this freelancer" class = "form-control"  rows = "10">@if(isset($saved_freelancer)){!!  $saved_freelancer->notes !!}@else{!! old('notes') !!}@endif</textarea>
			<span class="help-block"> 
				<strong></strong>  
			</span>
		</div>
	</div>
	<div class="form-group clearfix">
		<div class="col-sm-12 mar-top action_btn_group" data-serial = "{!!  $user->serial !!}">
            @if(isset($saved_freelancer))
                <button type="button" class="btn btn-default  btn-card-button remove_savefreelancer text-mint"> Remove From Saved </button>
                <button type="button" class="btn btn-mint  add_savefreelancer" disabled> Update Note </button> 
            @else
			    <button type="button" class="btn btn-mint add_savefreelancer">Save </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            @endif			
		</div>
	</div>
</form>