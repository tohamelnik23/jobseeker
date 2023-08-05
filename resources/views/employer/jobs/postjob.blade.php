@extends('layouts.app')
@section('title', 'Post Gig')
@section('css')
    <link href="{!! asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/selectize/selectize.bootstrap3.css') }}">
    <style>
        .wz-classic li>a.headline {
            font-size: 14px;
            font-weight: 700;
        } 
        .wz-classic .active ~ li a.headline{
            opacity: 1;
            font-size: 14px;
            font-weight: 700;
        }  
        .wz-classic .active ~ li a.headline:hover, .wz-classic .active ~ li a.headline:focus{ 
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
                        Review
                    @else
                        POST A GIG
                    @endif
                </h2>
                <div class="panel">
                    <div id="mypost-cls-wz">  
                        <ul class="wz-nav-off wz-icon-inline">
                            <li class="col-xs-2 bg-mint headline">
                                <a   data-toggle="tab" href="#mypost-cls-tab1">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-information icon-lg"></i></span> 
                                    Headline
                                </a>
                            </li>
                            <li class="col-xs-2 bg-mint description">
                                <a   data-toggle="tab" href="#mypost-cls-tab2">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-information icon-lg"></i></span> 
                                    Description
                                </a>
                            </li> 
                            <li class="col-xs-2 bg-mint location">
                                <a   data-toggle="tab" href="#mypost-cls-tab3">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-male icon-lg"></i></span> 
                                    Locaton
                                </a>
                            </li>
                            <li class="col-xs-2 bg-mint date_skills">
                                <a  data-toggle="tab" href="#mypost-cls-tab4">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-home icon-lg"></i></span>
                                    Date & Skills
                                </a>
                            </li> 
                            <li class="col-xs-2 bg-mint question">
                                <a    data-toggle="tab" href="#mypost-cls-tab5">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-home icon-lg"></i></span>
                                    Question
                                </a>
                            </li> 
                            <li class="col-xs-2 bg-mint budget">
                                <a   data-toggle="tab" href="#mypost-cls-tab6">
                                    <span class="icon-wrap icon-wrap-xs"><i class="demo-pli-medal-2 icon-lg"></i></span>
                                    Budget
                                </a>
                            </li>
                        </ul> 
                        <!--Progress bar-->
                        <div class="progress progress-xs progress-striped active">
                            <div class="progress-bar progress-bar-dark"></div>
                        </div> 
                        <!--Form-->
                        <form class="form-horizontal mar-top postjob">
                            <div class="panel-body">
                                <div class="tab-content"> 
                                    <div id="mypost-cls-tab1" class="tab-pane headline">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <h4> Write a headline for your gig post  </h4>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input type="text" minlength = 3  maxlength = 256  data-content = "The length of headline should be at least 3 characters" class="form-control edit" name="headline" @if(isset($job)) value = "{!! $job->headline !!}"  @endif >
                                                <span class="help-block">
                                                    <strong></strong>  
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <h5 class = "mar-top"> Example titles </h5>
                                            </div>
                                            <div class = "col-lg-12">
                                                <ul>
                                                    <li class = "text-dark pt-14">Wash and wax my car</li>
                                                    <li class = "text-dark pt-14">Clean my 3 bedroom apartment</li>
                                                </ul> 
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-lg-12 mar-top">
                                                <h4> Gig Type </h4>
                                            </div> 
                                            <div class="col-lg-12">
                                                <p class = "mar-top text-dark text-bold">  Let's categorize your gig, which helps us personalize your gig details and match your gig to relevant freelancers and agencies. Here are some suggestions based on your gig title:  </p>
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
                                                            <option  @if(isset($job) && isset($my_industry)  && ($my_industry->id == $industry->id))  selected @endif     value = "{{ $industry->id }}" > {{ $industry->industry }} </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="help-block">             
                                                    <strong></strong>  
                                                </span> 
                                            </div>		
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-lg-12 mar-top">
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
                                    </div>
                                    <!--Description-->
                                    <div id="mypost-cls-tab2" class="tab-pane description">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <h4> Describe your gig </h4>
                                                <p class = "mar-top text-dark pt-14"> This is how talent figures out what you need and why you are looking for in a work relationship, and anything unique abour your project, team or company.</p>
                                            </div>
                                        </div>
                                        <div class = "form-group">
                                            <div class="col-lg-12">
                                                <textarea name = "description" class = "form-control edit" maxlength = "65535"  minlength = 50  data-content = "The length of description should be at least 50 characters"   placeholder = "Already have a job description? Paste it here!" rows = 12>@if(isset($job)){!! $job->description !!} @endif</textarea>
                                                <span class="help-block">             
                                                    <strong></strong>  
                                                </span>
                                            </div>
                                        </div>

                                        <div class = "form-group  clearfix"> 
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
                                    <!--Location-->
                                    <div id="mypost-cls-tab3" class="tab-pane location">
                                        <div class = "form-group">
                                            <div class = "col-lg-12">
                                                <h4> Type </h4>
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
                                                <h4> Gig Location </h4>
                                                <p class = "mar-top text-dark pt-14">  Describe your gig's location.</p>
                                            </div>
                                        </div>
                                        @if(Auth::user()->accounts->caddress && Auth::user()->accounts->zip &&  Auth::user()->accounts->city && Auth::user()->accounts->state)
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
                                                        <label for  =   "usemylocation-form-checkbox" class = "text-dark"> Use My Address </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class = "new_location_field hidden text-dark">        
                                            <div class="form-group">
                                                <label class="col-lg-12 control-label  text-left">  <star>*</star> Address1</label>
                                                <div class="col-lg-6">
                                                    <input type="text" maxlength = 512 minlength = 5 data-content = "The length of address should be at least 5 characters"   placeholder="123 Moorhead Manor, Naples, FL, USA" name="address1"  class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->address1 !!}"  @endif> 
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span> 
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-12 control-label  text-left">  Address2 </label>
                                                <div class="col-lg-6">
                                                    <input type="text" maxlength = 512 name="address2" class="form-control" @if(isset($job)) value = "{!! $job->address2 !!}"  @endif>
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-12 control-label  text-left"> <star>*</star>City</label>
                                                <div class="col-lg-6">
                                                    <input type="text" maxlength = 512 minlength = 2 data-content = "The length of city should be at least 2 characters"  placeholder="Naples" name="city" class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->city !!}"  @endif>
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span> 
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label text-left" for="state"><star>*</star> State</label>
                                                <div class="col-sm-6">
                                                    @php
                                                        $states = DB::table("states")->get();
                                                    @endphp
                                                    <select class = "form-control edit form-editable" name="state">
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
                                                    <input type="text" maxlength = 20 data-content = "Please input the zip code"  placeholder="34212" name="zip" class="form-control edit form-editable" @if(isset($job)) value = "{!! $job->zip !!}"  @endif>
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Skills & Date -->
                                    <div id="mypost-cls-tab4" class="tab-pane date_skills"> 
                                        <div class="form-group clearfix">
                                            <div class="col-lg-12">
                                                <h4> Project Date and Time </h4>
                                                <p class = "mar-top text-dark pt-14">  Please select the dates to finish.   </p>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="form-group col-lg-6">  
                                                <div class="col-sm-12 input-group  eb  ">
                                                    <input type="text" class="form-control edit" id="job_date" name="job_date" placeholder="Date..."   data-content = "The date is required"  @if(isset($job)) value = "{!! $job->getJobDate() !!}"  @endif   />
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> 
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span> 
                                                </div> 
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <div class="col-sm-12 input-group bootstrap-timepicker timepicker eb">
                                                    <input type="text" class="form-control edit" id="job_time" name="job_time" placeholder="Time..."  data-content = "The time is required" @if(isset($job)) value = "{!! $job->getJobTime() !!}"  @endif />
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span> 
                                                </div>
                                                <div class="col-sm-12">
                                                    <span class="help-block">             
                                                        <strong></strong>  
                                                    </span> 
                                                </div> 
                                            </div>
                                        </div> 
                                        <div class="form-group mar-top">
                                            <div class="col-lg-12">
                                                <h4> Skills </h4>
                                                <p class = "mar-top text-dark pt-14">   What skills are most important to you? </p>
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
                                    </div>  
                                    <!-- Question -->
                                    <div id="mypost-cls-tab5" class="tab-pane question">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <h4> Screening Questions (Optional) </h4>
                                                <p class = "mar-top text-dark pt-14"> Narrow down your candidates.  </p>
                                            </div>
                                        </div> 
                                        <div class = "form-group clearfix">
                                            <div class = "col-lg-12">
                                                <h5 class = "mar-top text-dark addupto_questions_label">  Add up to 5 more questions </h5> 
                                            </div> 
                                        </div> 
                                        @include('employer.jobs.custom_question', ['notype' => 1])  
                                        @php
                                            $suggested_question_arr = array(); 
                                            $custom_questions = array();
                                            if(isset($job)){
                                                $job_questions =  $job->getQuestions();
                                                foreach($job_questions as $job_question){
                                                    if($job_question->type == 1)
                                                        $suggested_question_arr[] = $job_question->serial;
                                                    else
                                                        $custom_questions[] = $job_question;
                                                }
                                            } 
                                        @endphp
                                        <div class = "custom_question_group clearfix"> 
                                            <div class = "form-group clearfix custom_question_group_label hidden">
                                                <div class = "col-lg-12">
                                                    <h5 class = "mar-top text-dark ">  Your Question </h5> 
                                                </div> 
                                            </div> 

                                            @foreach($custom_questions as $custom_question) 
                                                @include('employer.jobs.custom_question', ['custom_question' => $custom_question])  
                                            @endforeach

                                        </div>
                                        <div class = "form-group clearfix">
                                            <div class = "col-lg-12">
                                                <h5 class = "mar-top text-dark ">  Suggested </h5> 
                                            </div>
                                            @php
                                                $suggested_questions = DB::table("qeuestions")->where('type', 1)->where('deleted', 0)->get(); 
                                            @endphp 
                                            <div class="col-md-9"> 
                                                @foreach($suggested_questions as $suggested_question) 
                                                    <div class="checkbox">
                                                        <input  @if( in_array( $suggested_question->serial, $suggested_question_arr ) ) checked @endif     id="suggested-question-{!! $suggested_question->serial !!}" class="magic-checkbox suggested_question_checkbox" type="checkbox" name = 'suggested_question[]' value = "{!! $suggested_question->serial !!}">
                                                        <label for="suggested-question-{!! $suggested_question->serial !!}">{!! nl2br($suggested_question->question ) !!}</label>
                                                    </div>   
                                                @endforeach
                                            </div>
                                        </div> 
                                    </div> 
                                    <div id = "mypost-cls-tab6" class = "tab-pane budget">
                                        <div class="form-group clearfix">
                                            <div class="col-lg-12">
                                                <h4> How do you want to pay?</h4>
                                                <p class = "mar-top text-dark pt-14">  Please select payment type.  </p>
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
                                                        <p> Project Budget </p>
                                                    </div>  
                                                </div> 
                                            </div> 
                                        </div>  
                                        <input type = "hidden" value = "" name = "payment_type"> 
                                        <div class = "form-group payment_type_group hourly clearfix">
                                            <div class = "form-group clearfix">
                                                <label class="col-sm-12 control-label text-left text-dark text-bold" for="estimted_budget"> Estimated Budget </label>
                                                <div class="col-sm-6"> 
                                                @php
                                                    $suggested_budgets = DB::table("suggested_budget")->where('type', 'hourly')->where('deleted', 0)->get();
                                                @endphp
                                                    <select class = "form-control" name="estimted_budget">
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
                                                <label class="col-sm-12 control-label text-left text-dark text-bold" for="estimted_budget"> Estimated Budget </label>
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
                                                        <input type="text" class="form-control decimal-input" id="custom_fixed_from" name="custom_fixed_from"  @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "fixed"))  value="{!! $job->budget_end !!}" @endif >
                                                         
                                                        <em id="job_date-error" class="error help-block hide">
                                                        </em>
                                                    </div>
                                                </div> 
                                                <div class = "col-xs-6">
                                                    <label class="col-sm-12 control-label text-left text-dark text-bold" for="custom_fixed_to"> Maximum budget </label>
                                                    <div class="col-sm-6 input-group  "> 
                                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                        <input type="text" class="form-control decimal-input" id="custom_fixed_to" name="custom_fixed_to"  @if(isset($job) && ($job->estimted_budget == null) && ( $job->payment_type == "fixed"))  value="{!! $job->budget_end !!}" @endif>
                                                        
                                                    </div>  
                                                    <div class="col-sm-12">
                                                        <span class="help-block"> 
                                                            <strong></strong>  
                                                        </span> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="box-inline">
                                    <button type="button" class="previous btn btn-mint">Previous</button>
                                    <button type="button" class="next btn btn-mint">Next</button>
                                </div> 
                                <div class="box-inline pull-right"> 
                                    @if($jobpost_type == "edit")
                                        @if($job->status == 0)
                                            <button type="button" class="finish publish_jobs draft btn" disabled> Save Draft </button> 
                                            <button type="button" class="finish publish_jobs publish btn btn-mint" disabled> Publish </button> 
                                        @endif 
                                        @if($job->status == 1)
                                            <button type="button" class="finish publish_jobs publish btn btn-mint" disabled> Edit </button> 
                                        @endif 
                                    @else
                                    <button type="button" class="finish publish_jobs draft btn" disabled> Save Draft </button> 
                                    <button type="button" class="finish publish_jobs publish btn btn-mint" disabled> Publish </button> 
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
        </div>
    </div> 
    <div class = "postjob_template hidden">
        <div class = "custom_question_template">
            @include('employer.jobs.custom_question')
        </div>
    </div> 
@endsection
@section('javascript')
    <script src="{!! asset('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') !!}"></script>
    <script src="{!! asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') !!}"></script>
    <script src="{!! asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script> 
    <script src="{{  asset('plugins/selectize/selectize.min.js') }}"     type="text/javascript"></script>
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
            validate_string = ".form-control.edit";  
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
            return flag;
        } 
        $(document).on('keyup', ".form-control.edit", function(){ 
            checkValidateOrder( $(this).closest("div") );
        });

        $('#mypost-cls-wz').bootstrapWizard({
            tabClass		: 'wz-classic',
            nextSelector	: '.next',
            previousSelector	: '.previous',
            onTabClick: function(tab, navigation, index) {
                return false;
            },
            onInit : function(){
                $('#mypost-cls-wz').find('.finish').hide().prop('disabled', true); 
            },
            onNext: function(tab, navigation, index){
                var flag = true; 
                if($(tab).hasClass("headline")){
                    flag  =  checkValidateOrder($("#mypost-cls-wz").find(".headline")); 
                } 
                if($(tab).hasClass("description")){
                    flag  =  checkValidateOrder($("#mypost-cls-wz").find(".description")); 
                }  
                if($(tab).hasClass("location")){
                    flag  =  checkValidateOrder($("#mypost-cls-wz").find(".location")); 

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

                if($(tab).hasClass("date_skills")){
                    flag  =  checkValidateOrder($("#mypost-cls-wz").find(".date_skills")); 
                }

                if(flag)
                    return true;
                else
                    return false; 
            },
            onTabShow: function(tab, navigation, index){
                var $total      = navigation.find('li').length;
                var $current    = index+1;
                var $percent    = ($current/$total) * 100;
                var wdt         = 100/$total;
                var lft         = wdt*index;
                $('#mypost-cls-wz').find('.progress-bar').css({width:$percent+'%'});
                if($current >= $total) {
                    $('#mypost-cls-wz').find('.next').hide();
                    $('#mypost-cls-wz').find('.finish').show();
                    $('#mypost-cls-wz').find('.finish').prop('disabled', false);
                } else {
                    $('#mypost-cls-wz').find('.next').show();
                    $('#mypost-cls-wz').find('.finish').hide().prop('disabled', true);
                }
            }
        }); 
        $('#job_date').datepicker({
			startDate: new Date()
		}); 
        $('#job_time').timepicker({
            minuteStep: 5,
            showInputs: false,
            disableFocus: true
        }); 
        $('#skills').selectize({
            placeholder: 'Enter Skills Here',
			plugins: ['remove_button']
		});
        /****************************************************************** Custom Question Stuff ********************************************************/
        // Custom question stuff
        function updateCustomQuestionStatus(){ 
            // show your question
            if( $(".custom_question_group .custom_question_item").length ){
                $(".custom_question_group_label").removeClass("hidden");
            }
            else{
                $(".custom_question_group_label").addClass("hidden");
            }  

            $(".custom_question_group .question_content").attr('name', 'custom_question[]'); 
            
            // calculate total number of questions
            var total_numberof_questions = $(".custom_question_group .custom_question_item").length + $('input[name="suggested_question[]"]:checked').length; 
            if(total_numberof_questions >= 5){
                // disable all unchecked checkbox 
                $(".addupto_questions_label").addClass("hidden"); 
                $('input[name="suggested_question[]"]').each(function(){ 
                    if(!$(this).prop("checked")){
                        $(this).prop("disabled", true);
                    }
                });  
                $(".custom_question_item.add").addClass("hidden");
            }
            else{
                var rest_questions = 5 - total_numberof_questions; 
                if( rest_questions == 1 )
                    $(".addupto_questions_label").html("Add one more question"); 
                else
                    $(".addupto_questions_label").html("Add up to " + rest_questions  + " more questions");
                
                $(".addupto_questions_label").removeClass("hidden");
                $(".custom_question_item.add").removeClass("hidden");
                $(".custom_question_item.add .edit_part").addClass("hidden");

                $('input[name="suggested_question[]"]').each(function(){ 
                    if(!$(this).prop("checked")){
                        $(this).prop("disabled", false);
                    }
                }); 
            } 
        } 
        function nl2br (str, is_xhtml) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        } 
        $(document).on("click", ".suggested_question_checkbox", function(){
            updateCustomQuestionStatus();
        }); 
        $(document).on("click", ".custome_question_button" ,function(){
            var obj = $(this).closest('.custom_question_item'); 
            if($(this).hasClass("edit")){ 
                obj.find(".label_part").addClass("hidden");
            } 
            if($(this).hasClass("add")){
                obj.find(".question_content").val("");
            } 
            obj.find(".edit_part").removeClass("hidden"); 
            addErrorItem(obj.find(".question_content")); 
        }); 
        $(document).on("click", ".remove_question", function(){ 
            var obj = $(this).closest('.custom_question_item');
            if($(this).hasClass("add")){ 
                obj.find(".edit_part").addClass("hidden");
            } 
            if($(this).hasClass("edit")){ 
                swal({
                    title: "Question",   
                    text: "Do you want to remove this question?",   
                    icon: "warning",
                    type: "warning", 
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger', 
                    }).then(function(isConfirm) {
                        if(isConfirm){
                            obj.remove();
                            updateCustomQuestionStatus();
                        }
                });
            }
        }); 
        $(document).on("click", ".save_question", function(){
            var obj = $(this).closest('.custom_question_item'); 
            var question_content = obj.find(".question_content").val(); 
            if( $.trim( question_content ).length >= 3 ){
                $(this).closest(".custom_question_item").find(".edit_part").addClass("hidden");  
                if($(this).hasClass("add")){
                    var custom_question_template = $(".postjob_template .custom_question_template").html(); 
                    $(".custom_question_group").append( custom_question_template ); 
                    // custom_question_template
                    var last_item = $(".custom_question_group .custom_question_item:last"); 
                    last_item.find(".question_content_label").html( nl2br(question_content) );
                    last_item.find(".question_content").val( question_content );
                }
                if($(this).hasClass("edit")){ 
                    obj.find(".question_content_label").html( nl2br(question_content) );
                    obj.find(".label_part").removeClass("hidden"); 
                } 
                updateCustomQuestionStatus();
            }
            else{ 
                addErrorItem( obj.find(".question_content"), "Please input the questions that has at least 3 characters");
            } 
        }); 
        $(document).on("keyup", ".question_content", function(){
            var question_content = $(this).val();
            if( $.trim( question_content ).length >= 3 ){
                addErrorItem($(this));
            }
            else{
                addErrorItem( $(this), "Please input the questions that has at least 3 characters");
            } 
        }); 
        /************************************************************** PreFill Data Stuff **************************************************************/
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
        /******************************************************************** Payment Stuff **************************************************************/
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
        /******************************************************************** Save and Post  **************************************************************/
        $(".publish_jobs").click(function(){ 
            var flag        =  checkValidateOrder($("#mypost-cls-wz").find(".tab-content"));  
            var post_status =  0;
            if($(this).hasClass("publish")){
                post_status = 1;
            } 
            if(flag){
                var form =  $(".postjob")[0];
                var data =  new FormData(form);
                data.append('post_status', post_status);
                data.append('job_type', 'gig');
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
                    cache:       false,
                    data :       data,
                    dataType:   'json',
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
    /**********************************************************************************************/
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
    /***********************************************************************************************/
    </script>
@stop