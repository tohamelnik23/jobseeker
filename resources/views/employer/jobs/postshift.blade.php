@extends('layouts.app')
@section('title', 'Post Shift')
@section('css')
<link href="{!! asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') !!}" rel="stylesheet">
<link href="{!! asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/selectize/selectize.bootstrap3.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/date_hour_minute/jquery.durationpicker.css') }}">
<style>
    .postjob li>a.headline {
        font-size: 14px;
        font-weight: 700;
    } 
    .postjob .active ~ li a.headline{
        opacity: 1;
        font-size: 14px;
        font-weight: 700;
    }  
    .postjob .active ~ li a.headline:hover, .wz-classic .active ~ li a.headline:focus{ 
        color: #26a69a;
        font-size: 15px;
    }
    .payment_type_item{
        border: 1px solid #5a5a5a;
        font-size: 20px;
        color: #5a5a5a;
        padding-top: 20px;
        padding-bottom: 20px;
        cursor:pointer;
    } 
    .payment_type_item.active{
        border: 1px solid #26a69a;
        color: #26a69a;
    } 
    .payment_type_item i{
        font-size: 30px;
    } 
	hr{
		margin-top: 40px;
		margin-bottom: 40px;  
		border-color: rgba(0,0,0,0.2) !important;
	}
    .inline-form-control{
        height: 35px;
        font-size: 14px;
        border-radius: 3px;
        box-shadow: none;
        border: 1px solid rgba(0,0,0,0.7);
        transition-duration: .5s;
        padding: 6px 12px;
        color: #555;
        font-weight: 600;
        background-color: #fff;
        background-image: none;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    /***************************************************/
    .duration-picker-container{
        display: inline-block;
        font-size: 14px;
    }
    .duration-picker-container select {
        font-size: 13px;
    }
    /******************************************************************************/
    #drop-zone, .drop-zone{
        width: 100%;
        min-height: 150px;
        border: 3px dashed rgba(0, 0, 0, .3);
        border-radius: 5px;
        font-family: Arial;
        text-align: center;
        position: relative;
        font-size: 15px;
        color: #222;
    }  
    #drop-zone input, .drop-zone input {
        position: absolute;
        cursor: pointer;
        left: 0px;
        top: 0px;         
        opacity: 0;
    }
    #drop-zone.mouse-over, .drop-zone.mouse-over  {
        border: 3px dashed rgba(0, 0, 0, .3);
        color: #7E7E7E;
    }
    #clickHere, .clickHere{
        display: inline-block;
        cursor: pointer;
        color: white;
        font-size: 17px;
        width: 150px;
        border-radius: 4px;
        background-color: #4679BD;
        padding: 10px;
    } 
    #clickHere:hover{
        background-color: #376199;    
    }
    #filename,  .filename {
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        line-height: 1.5em;
    }                             
    .file-preview {
        background: #ccc;
        border: 5px solid #fff;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        font-size: 14px;
        margin-top: 5px;
    } 
    .closeBtn:hover { 
        color: red;
        display:inline-block;
    } 
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-main text-dark pad-btm text-thin">  
                @if($jobpost_type == "edit")
                    Review A Shift
                @else
                    POST A Shift
                @endif
            </h2>
            <div class="panel">
                <div class="panel-body">
                    <form class="form-horizontal postjob mar-top">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> Write a headline for your shift post  </h4>
                            </div>
                        </div>  
                        <div class="form-group">
                            <div class="col-lg-12">
                                <input type="text" minlength = 3  maxlength = 256  data-content = "The length of headline should be at least 3 characters" class="form-control edit" name="headline" @if(isset($job)) value = "{!! $job->headline !!}"  @endif autocomplete="off" >
                                <span class="help-block">
                                    <strong></strong>  
                                </span>
                            </div>            
                        </div> 
                        <div class="form-group clearfix">
                            <div class="col-lg-12 mar-top">
                                <h4 class = "text-dark"> Shift Type </h4>
                            </div> 
                            <div class="col-lg-12">
                                <p class = "mar-top text-dark text-bold">  Let's categorize your shift, which helps us personalize your shift details and match your shift to relevant freelancers and agencies. Here are some suggestions based on your shift title:  </p>
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <div class="col-sm-6"> 
                                @php
                                    if(isset($job)){
                                        $my_industry = $job->getJobCategory();
                                    }
                                @endphp
                                <select class = "form-control edit" name = "industry">
                                    @foreach($industries as $industry)
                                        @if($industry->calculate_subcategories())
                                            <option  @if(isset($job) && isset($my_industry)  && ($my_industry->id == $industry->id))  selected @endif value = "{{ $industry->id }}" > {{ $industry->industry }} </option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="help-block">             
                                    <strong></strong>  
                                </span> 
                            </div>		
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-lg-12">
                                <h5 class = "text-dark"> Sub Category </h5>
                            </div>  
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-6"> 
                                <select class = "form-control edit" name = "subcategory">
                                    @foreach($industries as $industry)
                                        @if($industry->calculate_subcategories())
                                            @php
                                                $subcategories = $industry->subcategories; 
                                            @endphp
                                        @endif
                                        @foreach($subcategories as $subcategory)
                                            <option data-subcategory = "{!! $subcategory->category_id !!}"  @if(isset($job) && ($job->job_type == $subcategory->id)) selected @endif value = "{{ $subcategory->serial }}" > {{ $subcategory->name }} </option>
                                        @endforeach 
                                    @endforeach
                                </select>
                                <span class="help-block">             
                                    <strong></strong>  
                                </span>
                            </div>		
                        </div>
                        <hr class = "text-dark" />
                        <div class="form-group clearfix">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> Describe your gig </h4>
                                <p class = "mar-top text-dark pt-14"> This is how talent figures out what you need and why you are looking for in a work relationship, and anything unique abour your project, team or company.</p>
                            </div>
                        </div>
                        <div class = "form-group">
                            <div class="col-lg-12 clearfix">
                                <textarea name = "description" class = "form-control edit" maxlength = "65535"  minlength = 50  data-content = "The length of description should be at least 50 characters"   placeholder = "Already have a job description? Paste it here!" rows = 12 autocomplete="off">@if(isset($job)){!! $job->description !!} @endif</textarea>
                                <span class="help-block">             
                                    <strong></strong>  
                                </span>
                            </div>

                            <div class="col-lg-12 form-group  clearfix"> 
                                <label class = "col-md-12"> <strong class = "text-dark"> Attachment: </strong> </label>
                                <div class = "col-md-12">
                                    <div class="drop-zone">
                                        <p class = "mar-top">Drop files here...</p>
                                        <div class="clickHere">or click here.. <i class="fa fa-upload"></i>
                                            <input id = "1" type="file" name="attachment[]" class="active file" multiple />
                                        </div>
                                        <div class = 'filename text-left'>
                                            @if(isset($job))
                                                @php
                                                    $attachments = $job->get_attachments(); 
                                                @endphp
                                                @foreach($attachments as $attachment_index => $attachment)
                                                <div class="addedfile-multi" id = "file_{!! $attachment_index !!}">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-file fa-stack-1x "></i>
                                                        <strong class="fa-stack-1x" style="color:#FFF; font-size:12px; margin-top:2px;">{{ $attachment_index + 1 }}</strong>
                                                    </span>{{  $attachment->org_file_name }}&nbsp;&nbsp;
                                                    <span data-id = "{{ $attachment_index + 1 }}" data-modified = "1618585308173"  data-name = "{{  $attachment->org_file_name }}"  class="fa fa-times-circle fa-lg closeBtn orgfile"  title="remove"></span>
                                                    <input name = "org_attachments[]"  value = "{{ $attachment->serial }}" />
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(Session::has('file_size_error'))
                                <div class="col-md-12 has-error">
                                    <span class="help-block">
                                        <strong>You can send up to 25 MB in attachments. If you have more than one attachment, they can't add up to more than 25 MB. If your file is greater than 25 MB, Gmail automatically adds a Google Drive link in the email instead of including it as an attachment.</strong>
                                    </span>
                                </div>
                                @endif 
                            </div>

                        </div>
                        <hr class = "text-dark" />
                        <div class = "form-group">
                            <div class = "col-lg-12">
                                <h4 class = "text-dark"> Type </h4>
                                <p class = "mar-top-5 text-dark pt-14"> Describe your gig's work type </p>
                            </div>
                            <div class = "col-lg-12">
                                <div class="radio location_type_radio">  
                                    <input id  = "worktype-form-radio" class="magic-radio" type="radio"   name = "location_type" value = "local" @if(isset($job) && ($job->location_type == 'local')) checked @endif >
                                    <label for = "worktype-form-radio">Local Work</label>
                                    <input id  = "worktype-form-radio-2" class="magic-radio" type="radio" name = "location_type" value = "remote" @if(isset($job) && ($job->location_type == 'remote')) checked @endif>
                                    <label for = "worktype-form-radio-2">Remote Work</label> 
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span>
                                </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> Shift Location </h4> 
                            </div>
                        </div> 
                        @php
                            $use_my_address = 0; 
                            if(isset($job)){
                                if(($job->zip == Auth::user()->accounts->zip) && ($job->city == Auth::user()->accounts->city)  && ($job->state == Auth::user()->accounts->state) && ($job->address1 == Auth::user()->accounts->caddress) && ($job->address2 == Auth::user()->accounts->oaddress)){
                                    $use_my_address = 1; 
                                }
                            }
                        @endphp
                        <div class="form-group clearfix">
                            <div class="col-lg-12"> 
                                <div class="checkbox">
                                    <input id   =   "usemylocation-form-checkbox" class="magic-checkbox" type="checkbox" name = "use_my_location"  @if($use_my_address) checked @endif >
                                    <label for  =   "usemylocation-form-checkbox" class = "text-dark"> Use My Address ({{ Auth::user()->accounts->zip }}, {{ Auth::user()->accounts->caddress }}, {{ Auth::user()->accounts->city }}, {{ Auth::user()->accounts->state }}) </label>
                                </div>
                            </div>
                        </div>

                        <div class = "new_location_field hidden text-dark">
                            <div class="form-group">
                                <label class="col-lg-12 control-label  text-left">  <star>*</star> Address1</label>
                                <div class="col-lg-6">
                                    <input type="text" maxlength = 512 minlength = 5 data-content = "The length of address should be at least 5 characters"   placeholder="123 Moorhead Manor, Naples, FL, USA" name="address1"  class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->address1 !!}"  @endif autocomplete="off"> 
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label  text-left">  Address2 </label>
                                <div class="col-lg-6">
                                    <input type="text" maxlength = 512 name="address2" class="form-control" @if(isset($job)) value = "{!! $job->address2 !!}"  @endif autocomplete="off">
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label  text-left"> <star>*</star>City</label>
                                <div class="col-lg-6">
                                    <input type="text" maxlength = 512 minlength = 2 data-content = "The length of city should be at least 2 characters"  placeholder="Naples" name="city" class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->city !!}"  @endif autocomplete="off">
                                    <span class="help-block">
                                        <strong></strong>  
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label text-left" for = "state"><star>*</star> State</label>
                                <div class="col-sm-6">
                                    @php
                                        $states = DB::table("states")->get();
                                    @endphp
                                    <select class = "form-control edit" name="state">
                                        @foreach($states as $state)
                                            <option  @if(isset($job) && ($job->state == $state->abbreviation))  selected    @endif     value = "{{ $state->abbreviation }}" > {{ $state->state }} </option>
                                        @endforeach
                                    </select> 
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-lg-12 control-label  text-left"> <star>*</star> Zip</label>
                                <div class="col-lg-6">
                                    <input type="text" maxlength = 20 data-content = "Please input the zip code"  placeholder="34212" name="zip" class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->zip !!}"  @endif autocomplete="off">
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span> 
                                </div>
                            </div>
                        </div>

                        <hr class = "text-dark" />
                        <div class="form-group clearfix">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> Shift  Time </h4> 
                            </div>
                        </div> 
                        <div class = "shift-time-group">
                            @if(isset($job))
                                @include('employer.jobs.partial.shift_time_item', ['item_job' => $job, 'shift_time_type' => 1])
                            @else
                                @include('employer.jobs.partial.shift_time_item')
                            @endif
                        </div>
                        @if(!isset($job))
                            <div class="row">
                                <div class  =  "form-group  col-md-12 tex-center">
                                    <button class="btn btn-info btn-icon btn-rounded add_new_shift_time_button" type = "button">
                                        <span class = "h5  text-light"> Add New Shift Time </span>
                                    </button>
                                </div>
                            </div>
                        @endif 
                        <div class = "form-group clearfix">
                            <div class = "col-lg-12 correct_duration_error has-error hidden">
                                <span class="help-block mar-no">             
                                    <strong> Please choose the correct duration. </strong>  
                                </span>
                            </div>
                        </div> 
                        <hr class = "text-dark" />
                        <div class="form-group mar-top">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> Skills </h4> 
                            </div>
                        </div>  
                        @php
                            $myskills_arr  = array();  
                            if(isset($job)){
                                $myskills = $job->getSkills();
                                foreach($myskills as $myskill){
                                    $myskills_arr[] = $myskill->id;
                                }
                            } 
                        @endphp
                        <div class="form-group">
                            <div class="col-sm-12 "> 
                                <select id="skills" name="skills[]" multiple class="form-control"   placeholder="Enter skills here...">
                                    @foreach($skills as $skill)
                                        <option  @if( in_array( $skill->id, $myskills_arr ) ) selected @endif  value="{!! $skill->id !!}"> {{ $skill->skill }} </option> 
                                    @endforeach
                                </select> 
                                <span class="help-block">             
                                    <strong></strong>  
                                </span> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-lg-12">
                                <h4 class = "text-dark"> How do you want to pay?</h4> 
                            </div>
                        </div>   
                        <div class = "form-group clearfix">
                            <div class="col-xs-6">
                                <div class="pad-all">           
                                    <div   class = "text-center clearfix payment_type_item hourly @if(isset($job) && ( $job->payment_type == 'hourly')) active @endif">
                                        <p> <i class = "fa fa-clock-o"></i>  </p>
                                        <p> Hourly rate </p>
                                    </div>  
                                </div> 
                            </div> 
                            <div class="col-xs-6">
                                <div class="pad-all">           
                                    <div   class = "text-center clearfix payment_type_item fixed @if(isset($job) && ( $job->payment_type == 'fixed')) active @endif">
                                        <p> <i class = "fa fa-usd"></i>  </p>
                                        <p> Price for Shift</p>
                                    </div>  
                                </div> 
                            </div> 
                        </div>
                        <input type = "hidden" value = "" name = "payment_type"> 
                        <div class = "form-group payment_type_group hourly clearfix">
                            <div class = "form-group clearfix">
                                <!-- label class="col-sm-12 control-label text-left text-dark text-bold" for="estimted_budget"> Estimated Budget </label -->
                                <div class="col-sm-6">
                                @php
                                    $suggested_budgets = DB::table("suggested_budget")->where('type', 'hourly')->where('deleted', 0)->get();
                                @endphp
                                    <select class = "form-control" name = "estimted_budget">
                                        @foreach($suggested_budgets as $suggested_budget)
                                            <option  @if(isset($job) && ($job->estimted_budget == $suggested_budget->id)) selected @endif value = "{!! $suggested_budget->id  !!}"> {!! $suggested_budget->label !!} </option>
                                        @endforeach 
                                        <option value = "" @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "hourly")) selected @endif  >  Customized </option> 
                                    </select>
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span> 
                                </div>
                            </div> 
                            <div class = "form-group custom_part clearfix" style = "margin-top: 15px !important;">
                                <div class = "col-xs-6">
                                    <label class="col-sm-12 control-label text-left text-dark text-bold" for="custom_hourly_from"> From </label> 
                                    <div class="col-sm-6 input-group  "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input" id="custom_hourly_from" name="custom_hourly_from" @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "hourly"))  value="{!! $job->budget_start !!}" @endif>
                                        <span class="input-group-addon text-dark">/hour</span> 
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="help-block">             
                                            <strong></strong>  
                                        </span> 
                                    </div> 
                                </div>
                                <div class = "col-xs-6">
                                    <label class="col-sm-12 control-label text-left text-dark text-bold" for="custom_hourly_to"> To </label>
                                    <div class="col-sm-6 input-group  "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input" id="custom_hourly_to" name="custom_hourly_to"  @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "hourly"))  value="{!! $job->budget_end !!}" @endif >
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
                        <div class = "form-group payment_type_group fixed clearfix"> 
                            <div class = "form-group clearfix">
                                <!--label class="col-sm-12 control-label text-left text-dark text-bold" for="estimted_budget"> Estimated Budget </label -->
                                <div class="col-sm-6"> 
                                @php
                                    $suggested_budgets = DB::table("suggested_budget")->where('type', 'fixed')->where('deleted', 0)->get();
                                @endphp 
                                    <select class = "form-control" name="estimted_budget">
                                        @foreach($suggested_budgets as $suggested_budget)
                                            <option @if(isset($job) && $job->estimted_budget == $suggested_budget->id) selected @endif  value = "{!! $suggested_budget->id  !!}"> {!! $suggested_budget->label !!} </option>
                                        @endforeach
                                        <option value = "" @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "fixed")) selected @endif> Customized </option> 
                                    </select>  
                                    <span class="help-block">             
                                        <strong></strong>  
                                    </span>
                                </div>
                            </div> 
                            <div class = "form-group custom_part  clearfix" style = "margin-top: 15px !important;">
                                <div class = "col-xs-6">
                                    <label class="col-sm-12 control-label text-left text-dark text-bold" for="custom_fixed_from"> Minimum budget </label> 
                                    <div class="col-sm-6 input-group  "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input" id="custom_fixed_from" name="custom_fixed_from"  @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "fixed"))  value="{!! $job->budget_end !!}" @endif autocomplete="off">
                                            
                                        <em id="job_date-error" class="error help-block hide">
                                        </em>
                                    </div>
                                </div>
                                <div class = "col-xs-6">
                                    <label class="col-sm-12 control-label text-left text-dark text-bold" for="custom_fixed_to"> Maximum budget </label>
                                    <div class="col-sm-6 input-group  "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control decimal-input" id="custom_fixed_to" name="custom_fixed_to"  @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "fixed"))  value="{!! $job->budget_end !!}" @endif autocomplete="off"> 
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
                            <div class = "col-md-12 text-center demo-nifty-btn">
                                @if($jobpost_type == "edit")
                                    @if($job->status == 0)
                                        <button type="button" class="finish publish_jobs draft btn">
                                            <span class = "text-mint"> Save Draft </span>
                                        </button> 
                                        <button type="button" class="finish publish_jobs publish btn btn-mint"> Publish </button> 
                                    @endif
                                    @if($job->status == 1)
                                        <button type="button" class="finish publish_jobs publish btn btn-mint"> Edit </button> 
                                    @endif
                                @else
                                    <button type="button" class="finish publish_jobs draft btn" style="">
                                        <span class = "text-mint"> Save Draft </span>
                                    </button>
                                    <button type="button" class="finish publish_jobs publish btn btn-mint" style=""> Publish </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
    @if(!isset($job))
    <div class = "shift-time-template hidden">
        @include('employer.jobs.partial.shift_time_item')
    </div>
    @endif 
@endsection
@section('javascript')
<script src="{!! asset('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') !!}"></script>
<script src="{!! asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') !!}"></script>
<script src="{!! asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script> 
<script src="{{  asset('plugins/selectize/selectize.min.js') }}"     type="text/javascript"></script>
<script src="{{  asset('plugins/date_hour_minute/jquery.durationpicker.js') }}"     type="text/javascript"></script>
<script>
    function industry_change(){
        var current_id =  $("select[name = 'industry']").find("option:selected").val();
        var flag        = 0;
        var first_value = "";
        $("select[name = 'subcategory']").find("option").each(function(){
            if( $(this).attr('data-subcategory') == current_id){
                $(this).removeClass("hidden"); 
                if(first_value == ""){
                    first_value =  $(this).val();
                }
            }
            else{
                $(this).addClass("hidden");
            } 
        });  
        if($("select[name = 'subcategory'] option:selected").hasClass('hidden')){
            $("select[name = 'subcategory']").val(first_value);
        } 
    }
    $("select[name = 'industry']").change(function(){
        industry_change();
    });
    industry_change(); 
    // CLASSIC STYLE 
    function checkValidateOrder(obj, validdate_scope = "all"){
        var flag = 1; 
        var validate_string = ""; 
        validate_string     = ".form-control.edit"; 
        obj.find(  validate_string  ).each(function(){ 
            if($(this).prop('disabled') == false){
                var attr_name   = $(this).attr('name');
                var str_content = $.trim($(this).val()); 
                var data_error_srting = "";
                var minlength = $(this).attr('minlength'); 
                if (typeof minlength !== typeof undefined && minlength !== false) { 
                }
                else{
                    minlength = 0;
                }
                var error_string = $(this).attr('data-content');
                if (typeof error_string !== typeof undefined && error_string !== false) { 
                }
                else{
                    error_string =  "This field is required";
                }
                if( (str_content == "") || ( str_content.length < minlength)){                 
                    flag = 0;
                    addErrorItem($(this), error_string);
                }
                else
                    addErrorItem($(this));
            }
            else
                addErrorItem($(this));            
        }); 
        //validate
        if( flag && (validdate_scope == "all")){
            flag    =   ShowDateValue(true);
            /*
                if(flag){
                    if($("input[name = 'duration']").val() == "-1"){
                        flag = 0;
                    }
                }
            */
            var location_flag = 0;
            location_flag = $("input[name = 'location_type']:checked").length; 
            if(location_flag){
                addErrorItem($(".location_type_radio")); 
            }
            else{
                addErrorItem($(".location_type_radio"), "Please select the location type");
            } 
            flag = location_flag && flag;  
        }
        return flag;
    }

    $(document).on('keyup', ".form-control.edit", function(){ 
        checkValidateOrder( $(this).closest("div"), 'small' );
    });

    function ShowDateValue( show_error = false){       
        var flag        = 0;
        var total_flag  = 1;
        $(".shift-time-group .shift-time-item").each(function(e){
            var obj     =   $(this);
            obj.find(".duration").val("-1");
            flag        =   0;
            if(obj.find(".job_start_date").val() !== "" ){
                obj.find(".duration").val("0");
                var start_date_time =   obj.find(".job_start_date").val()     + " " + obj.find(".job_time").val();
                var end_date_time   =   obj.find(".job_start_date").val()     + " " + obj.find(".job_end_time").val();

                let start_date      =   new Date(start_date_time);
                let end_date        =   new Date(end_date_time);
                var seconds         =   (end_date.getTime() - start_date.getTime()) / 1000;
                if(seconds > 0){
                    obj.find('.duration_picker').durationpicker('destroy'); 
                    var days_display    = true;
                    var hours_display   = true; 
                    if(seconds > 86400)
                        days_display = true;
                    else
                        days_display = false; 
                    if(seconds > 3600)
                        hours_display = true;
                    else
                        hours_display = false;          
                    obj.find('.duration_picker').durationpicker({
                        showDays:  days_display,
                        showHours: hours_display,
                        showMins:  true
                    })
                    .on("change", function(){
                        if(seconds > $(this).val()){
                            obj.find(".duration").val( $(this).val());                 
                        }
                        else{
                            obj.find(".duration").val("-1");  
                        }
                    });
                    obj.find('.duration_picker').trigger("change"); 
                    obj.find(".expire_date_group").removeClass("hidden"); 
                    flag = 1;
                }
            } 
            if(flag == 0){
                total_flag = 0;
                obj.find(".expire_date_group").addClass("hidden");
            }  
            if(flag){
                obj.find(".after_end_start_error").addClass("hidden");
            }
            else{
                if(show_error){
                    obj.find(".after_end_start_error").removeClass("hidden");
                } 
            } 
        }); 
        if( !$(".shift-time-group .shift-time-item").length )
            total_flag = 0;
        if(total_flag ){
            $(".correct_duration_error").addClass("hidden");
        }
        else{ 
            if(show_error){
                $(".correct_duration_error").removeClass("hidden");
            }
        }
        return total_flag;
    }  
    ShowDateValue();
    $(document).on("changeDate", ".job_date", function(e) {
       ShowDateValue();
    });
    function init_shift_date_time(){
        $('.job_date').datepicker({
            startDate: new Date(),
            todayBtn: "linked",
            todayHighlight: true,
            format: "yyyy-mm-dd"
        });  
        $('.job_time').timepicker({
            minuteStep: 5,
            showInputs: false,
            disableFocus: true
        });
    }

    init_shift_date_time();
    $(document).on('change', '.job_time',  function(){
        ShowDateValue();
    });

    @if(!isset($job))
        $(".add_new_shift_time_button").click(function(e){
            $(".shift-time-group").append($(".shift-time-template").html());
            init_shift_date_time();
        });
        $(document).on("click", ".remove-shift-time-button", function(){ 
            $(this).closest(".shift-time-item").remove();
        });
    @endif 
    /***************************************************************************************************************************************/
    $('#skills').selectize({
        placeholder: 'Enter Skills Here',
        plugins: ['remove_button']
    });
    /********************************************************** PreFill Data Stuff **********************************************************/
    function PreFillAddress(){
        if($("#usemylocation-form-checkbox").prop("checked")){
            $(".new_location_field").addClass("hidden");
            $(".form-editable").removeClass("edit");
        }
        else{
            $(".new_location_field").removeClass("hidden");
            $(".form-editable").addClass("edit");
        }
    }
    if($("#usemylocation-form-checkbox").length){
        PreFillAddress();
    }
    $("#usemylocation-form-checkbox").click(function(){
        PreFillAddress();
    });
    /*************************************************************** Payment Stuff **********************************************************/
    function paymentTypeAction(){
        $(".payment_type_group").addClass("hidden"); 
        if($(".payment_type_item.active").length){
            if($(".payment_type_item.active").hasClass("fixed")){
                $(".payment_type_group.fixed").removeClass("hidden"); 
                $(".payment_type_group.hourly .form-control").prop("disabled", true); 
                $(".payment_type_group.fixed .form-control").prop("disabled", false); 
                $("input[name = 'payment_type']").val("fixed");
            }
            if($(".payment_type_item.active").hasClass("hourly")){
                $(".payment_type_group.hourly").removeClass("hidden");
                $(".payment_type_group.fixed .form-control").prop("disabled", true);
                $(".payment_type_group.hourly .form-control").prop("disabled", false);
                $("input[name = 'payment_type']").val("hourly");
            }
        }
        else{
            $("input[name = 'payment_type']").val("");
        }
        // estimted_budget 
        $("select[name = 'estimted_budget']").each(function(){
            var obj = $(this).closest(".payment_type_group"); 
            if($(this).find("option:selected").val() == ""){
                obj.find(".custom_part").removeClass("hidden");  
                obj.find(".custom_part .form-control").addClass("edit");
            }
            else{
                obj.find(".custom_part").addClass("hidden"); 
                obj.find(".custom_part .form-control").removeClass("edit");
            }
        });
    } 
    $(document).on("change", "select[name = 'estimted_budget']", function(){
        paymentTypeAction();
    }); 
    $(document).on("click", ".payment_type_item", function(){
        $(".payment_type_item").removeClass("active");
        $(this).addClass('active'); 
        paymentTypeAction();
    }); 
    paymentTypeAction();
    /***************************************************************** Publish Jobs *********************************************************/
    $(".publish_jobs").click(function(){ 
        var flag        =  checkValidateOrder($(".postjob"));  
        var post_status =  0; 
        if($(this).hasClass("publish")){
            post_status = 1;
        } 
        if(flag){
            var form =  $(".postjob")[0];
            var data =  new FormData(form);
            data.append('post_status', post_status);
            data.append('job_type', 'shift'); 
            $.ajax({
                @if($jobpost_type == "edit")
                    url:   "{!! route('employer.jobs.edit', $job->serial) !!}",
                @else
                    url:   "{!! route('employer.job.addjob') !!}",
                @endif
                type: 'POST',
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache:      false,
                data :      data, 
                dataType: 'json',
                beforeSend: function (){
                    $("#cover-spin").show();
                },
                success: function(json){
                    if(json.status){
                        location.href = json.url;
                    }
                    else{
                        $.niftyNoty({
                            type: 		'danger',
                            icon : 		'fa fa-check',
                            message : 	 json.msg,
                            container : 'floating',
                            timer : 5000
                        });
                    }
                },
                complete: function () {
                    $("#cover-spin").hide();
                },
                error: function() {
                    $("#cover-spin").hide();
                }
            });
        }
    }); 
    /********************************************/
    $(document).on("mousemove", ".clickHere", function(e){
        var x = e.pageX;
        var y = e.pageY;
        var ooleft  = $(this).closest(".drop-zone").offset().left;
        var ooright = $(this).closest(".drop-zone").outerWidth() + ooleft;
        var clickZone = $(this).closest(".drop-zone").find(".clickHere"); 
        var oleft 		= clickZone.offset().left;              
        var oright 		= clickZone.outerWidth() + oleft;
        var otop 		= clickZone.offset().top;
        var obottom 	= clickZone.outerHeight() + otop;
        var inputFile 	= $(this).closest(".drop-zone").find(".active.file"); 
        $(this).closest(".drop-zone").find(".file").offset({
            top:  0,
            left: 0   
        });
        if (!(x < oleft || x > oright || y < otop || y > obottom)){
            inputFile.offset({
                top: y - 15,
                left: x - 160   
            });
        } 
        else{                                                                                                                         
            inputFile.offset({
                top: -400,
                left: -400
            });                           
        }
    });
    $(document).on("change", ".active.file", function(e){ 
        var fileNum = this.files.length,
            initial = 0,
            counter = 0;
            idxdata = $(this).closest(".drop-zone").find(".addedfile-multi").length;
        for (initial; initial < fileNum; initial++){                                                                                                        
            counter = counter + 1;  
            $(this).closest(".drop-zone").find('.filename').append('<div class="addedfile-multi" id="file_'+ (idxdata  + initial) +'"><span class="fa-stack fa-lg"><i class="fa fa-file fa-stack-1x "></i><strong class="fa-stack-1x" style="color:#FFF; font-size:12px; margin-top:2px;">' + (counter + idxdata) + '</strong></span> ' + this.files[initial].name + '&nbsp;&nbsp;<span data-id = "'+ $(this).attr('id') +'" data-modified = "'+ this.files[initial].lastModified +'"  data-name = "'+ this.files[initial].name +'"  class="fa fa-times-circle fa-lg closeBtn newfile"  title="remove"></span></div>');
        } 
        $(this).removeClass('active'); 
        $(this).closest(".drop-zone").find(".clickHere").append('<input id = '+ Date.now() +' class="file active" type="file" name="attachment[]"   multiple="">')
    }); 
    $(document).on("click", ".closeBtn", function(e){
        if($(this).hasClass("orgfile")){
            var jqObj = $(this); 
            var container = jqObj.closest('div');
            container.remove(); 
        }
        else{
            const dt        =   new DataTransfer();  
            var inputfiles  =   document.getElementById($(this).attr('data-id'));
            //console.log(inputfiles.files); 
            var count_file = 0;
            for (let file of inputfiles.files){ 
                if((file.name == $(this).attr('data-name')) && (file.lastModified == $(this).attr('data-modified'))){
                    continue;
                }
                dt.items.add(file);
                count_file++;
            }
            inputfiles.onchange = null;
            inputfiles.files = dt.files;   
            var jqObj = $(this); 
            var container = jqObj.closest('div');
            container.remove(); 
            // remove id if it is null
            if(!count_file)
                $("#" + $(this).attr('data-id')).remove();     
        }
    }); 
    $(document).on("dragover", ".drop-zone", function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('mouse-over');   
        var x = e.pageX; 
        var y = e.pageY; 
        var ooleft  = $(this).offset().left;
        var ooright = $(this).outerWidth() + ooleft; 
        var ootop 		= $(this).offset().top;
        var oobottom 	= $(this).outerHeight() + ootop; 
        var inputFile 	= $(this).closest(".drop-zone").find(".active.file");                                                                                                                    
        if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
            inputFile.offset({
                top: y - 15,
                left: x - 100
            }); 
        }
        else{
            inputFile.offset({        
                top: -400,                     
                left: -400
            });
        }  
    }); 
    $(document).on("drop", ".drop-zone", function(e){
        $(this).removeClass('mouse-over');   
    });
    /*******************************************/
</script>
@stop