@extends('layouts.app')
@section('title', 'Submit a Proposal') 
@section('css') 
@endsection
@section('content')
    <div class="container"> 
        <form id = "proposalForm"  method="post" enctype="multipart/form-data" class="form-horizontal" action="{{{ route('jobs_proposals', $job->serial) }}}">
            @csrf
            <div class="row justify-content-center"> 
                <div class = "col-md-12">
                    @include('partial.alert')
                </div> 
                <div class = "col-md-12">
                    <h2 class="text-main pad-btm mar-no pad-no  text-semibold text-left text-dark">  
                        Submit a proposal
                    </h2>
                </div>
                <div class = "col-md-12 mar-top"> 
                    <div class="panel">
                        <div class="panel-heading bord-btm" style = "height: inherit;">
                            <div class = "pad-hor">
                                <div class = "row ">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark mar-btm">Propsal Settings</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class = "row">
                                <div class = "col-md-12">
                                    <h5 class = "text-dark">
                                        Propose with a Specialized profile
                                    </h5>
                                </div>
                                <div class = "col-md-12">
                                    <div class = "form-group clearfix"> 
                                        <div class="col-sm-6">
                                            @php
                                                $user_roles = $user->getRoles();
                                            @endphp
                                            <select class = "form-control" name="specialized_role" >
                                                @foreach($user_roles as $user_role)
                                                    <option value = "{!! $user_role->serial !!}" data-description = "{!! $user_role->getHourlyDescription()  !!}" @if($job->payment_type == 'hourly') data-suggestvalue = "{!! $user_role->getSuggestBudget() !!}" @else data-suggestvalue = "{!! $job->getSuggestBudget() !!}" @endif> {!! $user_role->role_title !!} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div>  
                </div> 

                <div class = "col-md-12 mar-top"> 
                    <div class="panel">
                        <div class="panel-heading bord-btm" style = "height: inherit;">
                            <div class = "pad-hor">
                                <div class = "row ">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark mar-btm"> <span class = "text-uppercase">{{ $job->type }}</span> Details </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class = "row"> 
                                <div class = "col-sm-8 freelanceritem_card bord-rgt">
                                    <h4 class = "text-dark"> {!! $job->headline !!}  </h4>
                                    <small class="text-muted display-inline-block pt-14">  
                                        <span class="ng-scope">Posted </span> 
                                        <time class="">  {!! date('F d, Y', strtotime($job->updated_at)) !!}</time>  
                                    </small> 
                                    <div class = "m-sm-bottom ng-isolate-scope mar-top">
                                        @if(strlen($job->description) > 340 )
                                        <div class = "description-shortlist description-part"> 
                                            <span class="pt-14 text-dark ">
                                                {!! substr($job->description, 0, 340) !!} ...
                                            </span>
                                            <a class="btn-link text-mint btn-description-action more" href="javascript:void(0)"> more </a> 
                                        </div> 
                                        <div class = "description-detaillist hidden description-part"><span class="pt-14 text-dark">{!! $job->description !!}</span><a class="btn-link btn-description-action text-mint less" href="javascript:void(0)"> less </a> 
                                        </div>
                                        @else
                                        <div class = "description-shortlist description-part"> 
                                            <span class="pt-14 text-dark ">
                                                {{ $job->description }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>  
                                </div>
                                <div class = "col-sm-4">
                                    <div class = "row">
                                        <div class="col-xs-12">
                                            <h5 class="text-dark" style="margin-bottom: 4px;">
                                                @if($job->payment_type == "hourly")
                                                    <i class = "fa fa-clock-o pt-15 pad-rgt"></i> 
                                                @else
                                                    <i class="glyphicon glyphicon-usd"></i>
                                                @endif
                                                {!! $job->getBudget() !!}
                                            </h5>
                                            <p class="pad-lft mar-lft text-capitalize" style="margin-top: 0px;">{!! $job->payment_type !!}</p> 
                                        </div>  
                                        <div class="col-xs-12">
                                            <div class = "text-dark h5" style = "margin-bottom: 4px;">
                                                <i class = "fa fa-calendar-check-o pt-15 pad-rgt"></i>   
                                                {!! $job->getDisplayDate() !!}   {!! $job->getJobTime() !!}
                                                @if($job->shift_end_date_time)
                                                    ~ {!! date('H:i A' , strtotime($job->shift_end_date_time))  !!}
                                                @endif 
                                            </div>
                                            <p class="pad-lft mar-lft text-capitalize" style="margin-top: 0px;">Project Date</p> 
                                        </div>  
                                    </div> 
                                </div>  
                            </div>
                            <div class = "row"> 
                                <div class = "col-sm-12"> 
                                    <hr class = "mar-btm" />     
                                    @php
                                        $skills = $job->getSkills();
                                    @endphp
                                    <section class = "air-card-divider-sm clearfix"> 
                                        <h5 class="m-sm-bottom text-dark  ">Skills and expertise </h5>
                                        <p>
                                            @foreach($skills as $skill)
                                                <span class="badge pad-rgt">{!! $skill->skill !!}</span> 
                                            @endforeach
                                        </p> 
                                    </section> 
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>  
                <div class = "col-md-12 mar-top"> 
                    <div class="panel">
                        <div class="panel-heading bord-btm" style = "height: inherit;">
                            <div class = "pad-hor">
                                <div class = "row ">
                                    <div class = "col-md-4 ">
                                        <h3 class = "text-dark mar-btm"> Terms </h3>
                                    </div> 
                                    <div class = "col-md-8 mar-top">
                                        <h4 class = "text-right"> @if($job->payment_type == 'fixed') Client's budget:  {!! $job->getBudget() !!}  @endif </h4>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body"> 
                            @if($job->payment_type == 'fixed')
                                <div class = "row">  
                                    <div class = "col-xs-12">  
                                        <h4 class = "text-dark"> What is the full amount you'd like to bid for this gig? </h4> 
                                    </div> 
                                </div> 
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-no">  Bid </p>
                                        <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                    </div>
                                    <div class = "col-xs-5 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_budget decimal-input edit proposal_amount" name="proposal_amount" placeholder="" value = "12.00">  
                                            </div>    
                                        </div> 
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-xs-11">
                                        <hr />
                                    </div>
                                </div>
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-top"> {!! $job_taker_fee !!}% Service Fee </p> 
                                    </div>
                                    <div class = "col-xs-5 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_fee decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00">  
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-xs-12">
                                        <hr />
                                    </div>
                                </div>
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                        <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                    </div>
                                    <div class = "col-xs-5 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_budget proposal_income decimal-input edit" name="proposal_income" placeholder="" value = "12.00">  
                                            </div>  
                                        </div> 
                                    </div>
                                </div> 
                            @endif

                            @if($job->payment_type == 'hourly')
                                <div class = "row">  
                                    <div class = "col-xs-12">  
                                        <h4 class = "text-dark"> What is the rate you'd like to bid for this gig? </h4> 
                                    </div> 
                                </div>
                                <div class = "row">  
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4"> Your profile rate: <strong class = "my_profile_rate"> $22.00/hr </strong> </p>
                                    </div>
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4"> Client's budget: {!! $job->getBudget() !!} </p>
                                    </div>  
                                </div>
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-no">  Hourly Rate </p>
                                        <p class = "text-dark pt-14"> Total amount the client will see on your proposal </p>
                                    </div>
                                    <div class = "col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_amount proposal_budget decimal-input edit" name="proposal_amount" placeholder="" value = "12.00"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>    
                                        </div>

                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-xs-12">
                                        <hr />
                                    </div>
                                </div>
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-top">  {!! $job_taker_fee !!}% Service Fee </p> 
                                    </div>
                                    <div class = "col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_fee proposal_budget decimal-input edit" disabled name="proposal_fee" placeholder="" value = "2.00"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>    
                                        </div> 
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-xs-12">
                                        <hr />
                                    </div>
                                </div>
                                <div class = "row mar-top">
                                    <div class = "col-xs-6">
                                        <p class = "pt-14 h4 text-dark mar-no">  You'll Receive </p>
                                        <p class = "text-dark pt-14"> The estimated amount you'll receive after service fees </p>
                                    </div>
                                    <div class = "col-xs-6 mar-no ">
                                        <div class="form-group clearfix"> 
                                            <div class="col-sm-8 input-group "> 
                                                <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                                <input type="text" class="form-control hgt-35 text-right proposal_income proposal_budget decimal-input edit" name="proposal_income" placeholder="" value = "12.00"> 
                                                <span class="input-group-addon text-dark">/hr</span> 
                                            </div>    
                                        </div> 
                                    </div>
                                </div>  
                            @endif 
                        </div>
                    </div>  
                </div> 
                <div class = "col-md-12 mar-top"> 
                    <div class="panel">
                        <div class="panel-heading bord-btm" style = "height: inherit;">
                            <div class = "pad-hor">
                                <div class = "row ">
                                    <div class = "col-md-12">
                                        <h3 class = "text-dark mar-btm"> Additional details </h3>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">   
                            <div class="form-group clearfix">
                                <label class="col-sm-12 control-label text-left h4 text-dark" for="role_description">
                                    <strong>Message to Gig Owner</strong>
                                </label>
                                <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                    <textarea name = "coverletter" class = "form-control edit"  rows = "10"></textarea>
                                    <span class="help-block"> 
                                        <strong></strong>  
                                    </span>
                                </div>
                            </div>   
                            @php
                                $questions = $job->getQuestions();
                            @endphp 
                            @foreach($questions as $question)
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left h5 text-dark" for="role_description"> {!! $question->question !!} </label>
                                    <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                        <textarea name = "answer[{!! $question->serial !!}]" class = "form-control edit"  rows = "5"></textarea>
                                        <span class="help-block">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group clearfix">
                                <div class = "col-xs-12">
                                    <hr />
                                </div>
                            </div> 
                            <div class="form-group clearfix">
                                <div class = "col-xs-12">
                                    <button type = "button" class = "btn btn-mint submit_proposal"> Submit a Proposal </button>
                                    <a  href = "{!! route('jobs_details', $job->serial) !!}"  class = "btn btn-mint-border text-mint">  Cancel </a>
                                </div>
                            </div>
                        </div> 
                    </div>  
                </div> 
            </div>
        </form>
    </div>
@endsection
@section('javascript')
    <script>
        var service_fee = <?= $job_taker_fee ?>;
        var benefit_fee =  100 - service_fee;

        $(document).on("click", ".btn-description-action" , function(){
            var obj = $(this).closest(".freelanceritem_card");
            obj.find(".description-part").addClass("hidden"); 
            if($(this).hasClass("more")){
                obj.find(".description-detaillist").removeClass("hidden");
            }
            if($(this).hasClass("less")){
                obj.find(".description-shortlist").removeClass("hidden");
            } 
        }); 
        function proposal_budget(obj = ""){
            if(obj == ""){
                var proposal_amount = $(".proposal_amount").val();
                var proposal_fee    = proposal_amount * service_fee / 100;
                var proposal_income = proposal_amount - proposal_fee; 
                $(".proposal_fee").val(proposal_fee.toFixed(2));
                $(".proposal_income").val(proposal_income.toFixed(2));
            }
            else{
                if(obj.hasClass('proposal_amount')){
                    var proposal_amount = $(".proposal_amount").val();
                    var proposal_fee    = proposal_amount * service_fee / 100;
                    var proposal_income = proposal_amount - proposal_fee; 
                    $(".proposal_fee").val(proposal_fee.toFixed(2));
                    $(".proposal_income").val(proposal_income.toFixed(2));
                } 
                if(obj.hasClass('proposal_income')){
                    var proposal_income =  $(".proposal_income").val();
                    var proposal_amount =  proposal_income / benefit_fee * 100; 
                    var proposal_fee    =  proposal_amount * service_fee / 100; 
                    $(".proposal_fee").val(proposal_fee.toFixed(2));
                    $(".proposal_amount").val(proposal_amount.toFixed(2));
                }
            }
        } 
        $(document).on("keyup", ".proposal_budget", function(){
            proposal_budget($(this));
        });
        $("select[name = 'specialized_role']").change(function(){
            var active_option       = $(this).find("option:selected").val();
            var data_description    = $(this).find("option:selected").attr("data-description");
            var data_suggestvalue   = $(this).find("option:selected").attr("data-suggestvalue"); 
            $(".proposal_amount").val(data_suggestvalue);
            $(".my_profile_rate").html(data_description);
            proposal_budget(); 
        });
        $("select[name = 'specialized_role']").trigger("change"); 
        function CheckProposalForm(obj, validdate_scope = "all"){
            var flag = 1; 
            var validate_string = ""; 
            validate_string 	= ".form-control.edit";  
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

        $(".submit_proposal").click(function(){
            var flag        =  CheckProposalForm($("#proposalForm")); 
            if(flag){
                $.ajax({ 
                    url:   "{!! route('jobs_proposals', $job->serial) !!}",
                    type: 'POST',
                    data:  $("#proposalForm").serialize(),
                    dataType: 'json',
                    beforeSend: function (){
                        $("#cover-spin").show();
                    },
                    success: function(json){
                        if(json.status){
                           location.href = json.url;
                        }
                        else{
                            swal({
								title: "Error Occured",   
								text:  json.msg,
								type: "error",   
								confirmButtonText: "Close" 
								}).then(function(isConfirm) {
								if(isConfirm){ 
								}
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
    </script>
@stop