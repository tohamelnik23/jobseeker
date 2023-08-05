@extends('layouts.app')
@section('title', 'Start')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/selectize/selectize.bootstrap3.css') }}">
    <style> 
        .onboarding-header__title{
            font-size: 4rem;
            line-height: 1.2;
            font-weight: 200;
            color: rgba(0,0,0,.9);
            margin: 0 auto 40px;
        }
        .btn-continue{
            padding: 9px 12px;
            font-size: 1.8rem;
        }
        .socialsecuritynumber{
            font-size: 25px;
            height: 50px;
            text-align: center;
        }

        .bootstrap-tagsinput{
            padding: 6px 6px;
        }
        .bootstrap-tagsinput .label{
            padding: .4em .6em .3em;
            font-size: 100%;
        }

        .btn-default.btn-continue{
            border: 2px solid #2176bd !important;
        }
    </style>
@endsection 
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 mar-btm">
                <h2 class="pt6 text-center text-dark">
                    Your profile helps people discover you and the right opportunities you are most suited
                </h2> 
            </div> 
            <div class="col-md-6 col-md-offset-3">
                <form id="startform" method="post" class="form-horizontal" action="{{{ route('employee.profile.start') }}}">
                    @csrf
                    <input type = "hidden" name = "profile_start" value = "1" >
                    @if($user->accounts->start_stage == 0)
                        <!-----------------------------------------------------------    Address Details ---------------------------------------------------->
                        <div class = "row mar-top">
                            <div class = "col-md-12">
                                <h4 class = " text-mint"> You have 1 answer out of 4 questions </h4>
                            </div>
                        </div>

                        <div class="row mar-top">
                            <div class="form-group col-lg-6 clearfix">
                                <label class="control-label text-dark text-left" for="birthdate"><star>*</star> Birth date</label>
                                <div class="input-group date" data-provide="datepicker">
                                    <input type = "text" name = "birthdate" class ="form-control edit" id = "birthdate" value="{{old('birthdate',$user->accounts->birthdate)}}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    @if ($errors->has('birthdate'))
                                        <em id="birthdate-error" class="error help-block">
                                            {{ $errors->first('birthdate') }}
                                        </em>
                                    @endif
                                </div>
                            </div> 
                        </div>



                        <div class="row mar-top"> 
                            <div class="form-group col-lg-12 clearfix">
                                <label class="control-label text-dark"><star>*</star> Address</label>
                                <input id = "caddress" name="caddress" placeholder="Address" value="{{old('caddress',$user->accounts->caddress)}}"  type="text" class="form-control">
                            </div> 
                        </div> 
                        <div class="row mar-top">
                            <div class="form-group col-lg-12 clearfix mar-top5">
                                <div class="row"> 
                                    <label class="col-sm-12 control-label text-left text-dark" for="oaddress">Address 2</label>
                                    <div class="col-sm-12  @if ($errors->has('oaddress')) has-error @endif">
                                        <input type="text" class="form-control" id="oaddress" name="oaddress" placeholder="Address" value="{{old('oaddress',$user->accounts->oaddress)}}"/>
                                        <em id="oaddress-error" class="error help-block hide">
                                        </em>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row mar-top">
                            <div class="form-group col-lg-4 clearfix mar-top5">
                                <div class="row"> 
                                    <label class="col-sm-12 control-label text-left text-dark" for="city"><star>*</star> City</label>
                                </div>
                                <div class="row"> 
                                    <div class="col-sm-12  @if ($errors->has('city')) has-error @endif">
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{old('city',$user->accounts->city)}}"/>
                                        <em id="city-error" class="error help-block hide">
                                        </em>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group col-lg-4 clearfix mar-top5">
                                <div class="row"> 
                                    <label class="col-sm-12 control-label text-left text-dark" for="state"><star>*</star> State</label>
                                    <div class="col-sm-12  @if ($errors->has('state')) has-error @endif">
                                        @php
                                            $states = DB::table("states")->get();
                                        @endphp 
                                        <select id="state" class = "form-control edit" name="state">
                                            @foreach($states as $state)
                                                <option value = "{{ $state->abbreviation }}" > {{ $state->state }} </option>
                                            @endforeach
                                        </select> 
                                        <em id="state-error" class="error help-block hide">
                                        </em>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group col-lg-4 clearfix mar-top5">
                                <div class="row"> 
                                    <label class="col-sm-12 control-label text-left text-dark" for="zip"> <star>*</star> Zip Code</label>
                                    <div class="col-sm-12  @if ($errors->has('zip')) has-error @endif">
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip"  value="{{old('zip',$user->accounts->zip)}}"/>
                                        <em id="zip-error" class="error help-block hide">
                                        </em>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                        <div class = "row mar-top pad-top">
                            <div class = "form-group col-lg-12">
                                <button type = "submit" class="btn btn-primary btn-rounded btn-block btn-continue"> Continue </button>
                            </div>
                        </div>
                    @elseif($user->accounts->start_stage == 1)  
                        <div class="form-group">  
                            <div class = "col-md-12 mar-top">
                                <h4 class = "text-mint"> You have 2 answers out of 4 questions </h4>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label class="control-label col-lg-12 text-left text-dark h4 mar-btm" for="travel_distance">
                                What distance are you willing to travel (miles)?
                            </label>  
                            <div class="col-md-12 text-dark mar-top"> 
                                <div class="radio">
                                    <input id  = "distance-form-radio" class="magic-radio" type="radio" name = "distance_gig" value = "any_distance" >
                                    <label for = "distance-form-radio"> Any Distance </label>
                                </div>
                                <div class="radio">
                                    <input id="distance-form-radio-2" class="magic-radio" type="radio" name = "distance_gig" value = "local_work" checked="">
                                    <label for="distance-form-radio-2"> Local Work </label>
                                </div>
                            </div> 

                            <div class = "localwork_miles hidden">
                                <div class = "col-md-12 mar-top">
                                    <input name="travel_distance" type="range" class="form-control-range" id="customRange" value="{{($user->accounts->travel_distance!='NULL')?$user->accounts->travel_distance:'26'}}" min="0" max = "50" step="2">
                                </div> 
                                <div class = "col-md-12">
                                    <span class="float-left mr-2" style="font-weight: 700!important; font-size:18px;">0</span>
                                    <span  class="float-right" style="font-weight: 700!important; font-size:18px;">50</span>
                                </div> 
                                <div class = "col-md-12 text-center">
                                    <p class = "h5"><span id="demo"></span> Miles</p>
                                </div> 
                            </div>


                            <div class = "form-group col-md-12">
                                <button type = "button" class="btn btn-primary mar-top btn-rounded btn-block btn-continue"> Continue </button>
                            </div> 
                        </div>
                    @elseif($user->accounts->start_stage == 2)
                        <div class = "row mar-top">
                            <div class = "col-md-12">
                                <h4 class = "text-mint"> You have 3 answers out of 4 questions </h4>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label text-center h4" for="socialsecuritynumber">  Social Security Number</label>
                            <div class = "col-sm-12">
                                <i class = "text-dark pt-14"> Social Security number helps us to identify you and makes you are more trusted doer. </i>
                            </div>
                            <div class="col-sm-12  @if ($errors->has('socialsecuritynumber')) has-error @endif mar-top">
                                <input type="text" class="form-control socialsecuritynumber"   name="socialsecuritynumber" placeholder = "" value="{{old('socialsecuritynumber',$user->accounts->socialsecuritynumber)}}"/>
                                <em  class="error help-block hide">
                                </em>
                            </div>
                        </div>  
                        <div class = "form-group col-lg-12 mar-top pad-top">
                            <button type = "submit" class="btn btn-primary btn-rounded btn-block btn-continue"> Continue </button>
                        </div>
                    @elseif($user->accounts->start_stage == 3)
                        <div class="form-group mar-top clearfix">
                            <div class = "col-md-12">
                                <h4 class = "text-mint"> You have 4 answers out of 4 questions </h4>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-12 text-left text-dark h5 mar-btm">
                                Please define your role as someone who gets stuff done!
                            </label>   
                        </div>

                        <div class = "form-group clearfix">
                            <div class = "col-sm-12">
                                <i class = "text-dark pt-14"> Add detail and sell your services to potential hires.  Clients are looking for people who talk about their experience and the more detail the better. </i>
                            </div> 
                        </div>  

                        <div class = "form-group clearfix">
                            <label class="col-sm-12 control-label text-left text-dark" for="role_skills">
                                <strong>   Skill </strong>
                            </label> 
                            <div class="col-sm-12 "> 
                                <select id="skills" name="skills[]" multiple class="form-control skills"   placeholder="Enter skills here...">
                                    @foreach($skills as $skill)
                                        <option value="{!! $skill->id !!}"> {{ $skill->skill }} </option> 
                                    @endforeach
                                </select> 
                                <span class="help-block"> 
                                    <strong></strong>  
                                </span>
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left text-dark" for="role_title">
                                <strong>  <star>*</star> Title  </strong>
                            </label>
                            <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                <input type="text" class="form-control description edit" maxlength = 256  name = "role_title" placeholder="" value="{{old('role_title','')}}" />
                                <span class="help-block"> 
                                    <strong></strong>  
                                </span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left text-dark" for="role_description">
                                <strong>  <star>*</star> Description  </strong>
                            </label> 
                            <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                                <textarea name = "description" class = "form-control edit"  rows = "10">{!! old('description') !!}</textarea>
                                <span class="help-block"> 
                                    <strong></strong>  
                                </span>
                            </div>
                        </div>  
                        <div class = "row">
                            <div class = "col-md-6">
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left text-dark">
                                        <strong>  <star>*</star> Start Rate  </strong>
                                    </label> 
                                    <div class="col-sm-8 input-group "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control hourly_rate_from decimal-input edit"  name = "hourly_rate_from" placeholder=""  value="{{old('hourly_rate_from','')}}"  /> 
                                        <span class="input-group-addon text-dark">/hour</span>   
                                    </div>
                                </div>  
                            </div>
                            <div class = "col-md-6">
                                <div class="form-group clearfix">
                                    <label class="col-sm-12 control-label text-left text-dark">
                                        <strong>  <star>*</star> End Rate  </strong>
                                    </label> 
                                    <div class="col-sm-8 input-group "> 
                                        <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="text" class="form-control hourly_rate_end decimal-input edit"  name = "hourly_rate_end" placeholder=""  value="{{old('hourly_rate_end','')}}"  /> 
                                        <span class="input-group-addon text-dark">/hour</span>   
                                    </div>
                                </div>  
                            </div> 
                        </div>  

                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-left text-dark">
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
                            <label class="col-sm-12 control-label text-left text-dark">
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


                        <div class = "row mar-top pad-top">
                            <div class = "form-group clearfix col-lg-12 ">
                                <button type = "button" class="btn btn-primary btn-rounded btn-block btn-continue"> Continue </button>
                            </div>
                        </div> 
                    @elseif($user->accounts->start_stage == 1000)
                        <div class="form-group clearfix">
                            <label class="col-sm-12 control-label text-center h4"> Will you allow credit check?  </label>  
                        </div>  
                        <div class = "row mar-top pad-top">
                            <div class = "form-group clearfix col-lg-12 ">
                                <button type = "button" class="btn btn-default btn-rounded btn-block btn-continue" data-value = "1"> YES </button>
                            </div>
                        </div> 
                        <div class = "row  pad-top">
                            <div class = "form-group clearfix col-lg-12 ">
                                <button type = "button" class="btn btn-default btn-rounded btn-block btn-continue" data-value = "0"> NO </button>
                            </div>
                        </div>
                    @endif 
                </form>
            </div>
        </div>
    </div>
@endsection
 
@section('javascript')
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js?'.time())}}"></script>    
    <script src="{{  asset('plugins/selectize/selectize.min.js') }}"     type="text/javascript"></script>  
    <script type="text/javascript" src="{{ asset('js/bootstrap-tagsinput.js?'.time())}}"></script>
    <script>    
        $(document).ready( function (){
            @if($user->accounts->start_stage == 0)
                /*
                $("#caddress").trigger("geocode"); 
                $("#caddress").geocomplete({
                    details: "form",
                    types: ["geocode", "establishment"],
                }); 
                */
                $("#startform").validate({
                    rules: {
                        city: "required",
                        state: "required",
                        zip: "required",
                        birthdate: "required",
                        caddress: "required",
                    },
                    messages: {
                        city:       "Please enter your City",
                        state:      "Please enter your State",
                        zip:        "Please enter your Zip",
                        birthdate:  "Please enter the birth date",
                        caddress:   "Please enter your address"
                    },
                    errorElement: "em",
                    errorPlacement: function ( error, element ) { 
                        error.addClass( "help-block" ); 
                        if(element.prop( "type" ) === "checkbox" ) {
                            error.insertAfter( element.parent( "label" ) );
                        } 
                        else{
                            error.insertAfter( element );
                        }
                    },
                    highlight: function ( element, errorClass, validClass ) {
                        $(element).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
                    },submitHandler: function(form){
                        $(form).find("button[type=submit]").prop('disabled',true);
                        var dataS = $(form).serialize();
                        $.ajax({
                            type: $(form).attr('method'),
                            url:  "{!! route('employee.update.address') !!}",
                            data: dataS,
                            success: function(data) { 
                                if(data.error==true){
                                    $.each(data.errors, function(key, value){
                                        var input = '#addressForm input[name=' + key + ']';
                                        $(input).parent().addClass('has-error');
                                        $('#'+key+'-error').removeClass('hide').addClass('show').text(value);
                                        $('#responce').addClass('hide');
                                    });
                                }else{ 
                                    $(form).find("button[type=submit]").prop('disabled',false); 
                                    location.reload(); 
                                }
                            } 
                        });
                    },
                }); 
            @elseif($user->accounts->start_stage == 1)
                var slider = document.getElementById("customRange");
                var output = document.getElementById("demo"); 
                if(slider){
                    output.innerHTML = slider.value; 
                    slider.oninput = function() {
                    output.innerHTML = this.value;
                    }
                } 
                $(".btn-continue").click(function(){ 
                    var dataS = $("#startform").serialize();
                    $.ajax({
                        type: $("#startform").attr('method'),
                        url:  "{!! route('employee.update.traveldistance') !!}",
                        data: dataS,
                        success: function(data) { 
                            if(data.error==true){
                                
                            }else{  
                                location.reload(); 
                            }
                        } 
                    }); 
                }); 
                $("input[ name = 'distance_gig' ]").click(function(){
                    updateTravel(); 
                }); 
                function updateTravel(){
                    var distance_gig = $("input[ name = 'distance_gig' ]:checked").val(); 
                    if(distance_gig == "any_distance"){
                        $(".localwork_miles").addClass("hidden");
                        $("input[name = 'travel_distance']").prop('disabled', true);
                    }
                    else{
                        $(".localwork_miles").removeClass("hidden");
                        $("input[name = 'travel_distance']").prop('disabled', false);
                    }
                }
                updateTravel(); 

            @elseif($user->accounts->start_stage == 2) 
                /*************************************************************  Social Security *****************************************************/
                $("#startform").validate({
                    rules: {
                       // socialsecuritynumber: "required",
                    },
                    messages: {
                        //socialsecuritynumber: "Please enter your social security number",
                    },
                    errorElement: "em",
                    errorPlacement: function ( error, element ) {
                        // Add the `help-block` class to the error element
                        error.addClass( "help-block" ); 
                        if ( element.prop( "type" ) === "checkbox" ) {
                            error.insertAfter( element.parent( "label" ) );
                        } else {
                            error.insertAfter( element );
                        }
                    },
                    highlight: function ( element, errorClass, validClass ) {
                        $( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
                    },submitHandler: function(form) {
                        $(form).find("button[type=submit]").prop('disabled',true);
                        var dataS = $(form).serialize();
                        $.ajax({
                            type: $(form).attr('method'),
                            url:  "{!! route('employee.update.socialsecuritynumber') !!}",
                            data: dataS,
                            success: function(data) { 
                                if(data.error==true){
                                    $.each(data.errors, function(key, value){
                                        var input = '#socialsecuritynumberForm input[name=' + key + ']';
                                        $(input).parent().addClass('has-error');
                                        $('#'+key+'-error').removeClass('hide').addClass('show').text(value); 
                                    });
                                }else{
                                    $(form).find("button[type=submit]").prop('disabled',false);
                                    location.reload(); 
                                }
                            } 
                        });
                    },
                }); 
                $(".socialsecuritynumber").mask('999-99-9999'); 
            @elseif($user->accounts->start_stage == 3)
                /*************************************************************  Skills SET  *****************************************************/ 
                var $select = 	$('#skills').selectize({
                                    placeholder: 'Enter Skills Here',
                                    plugins: ['remove_button']
                                }); 
                function CheckRoleModal(obj, validdate_scope = "all"){
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
                $(document).on("click", ".btn-continue" , function(){
                    var flag        =  CheckRoleModal($("#startform")); 
                    if(flag){
                        $.ajax({ 
                            url:   "{!! route('employee.add.role') !!}",
                            type: 'POST',
                            data:  $("#startform").serialize(),
                            dataType: 'json',
                            beforeSend: function (){
                            },
                            success: function(json){
                                if(json.status){
                                    location.reload();
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
                            },
                            error: function() {
                            }
                        });  
                    } 
                });  
                function industry_change(){
                    var current_id =  $("#startform select[name = 'industry']").find("option:selected").val(); 
                    console.log( current_id ); 
                    var flag        = 0;
                    var first_value = "";
                    $("#startform select[name = 'subcategory']").find("option").each(function(){
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
                    if($("#startform select[name = 'subcategory'] option:selected").hasClass('hidden')){
                        $("#startform select[name = 'subcategory']").val(first_value);
                    }
                } 
                $(document).on("change", "#startform select[name = 'industry']", function(){
                    industry_change();
                });
                industry_change();


            @elseif($user->accounts->start_stage == 100)
                $(".btn-continue").click(function(){ 
                    $.ajax({
                        type: $("#startform").attr('method'),
                        url:  "{!! route('employee.update.allowcreditcheck') !!}",
                        data: { allowcreditcheck: $(this).attr('data-value'), profile_start : 1 },
                        success: function(data) { 
                            if(data.error==true){
                                
                            }else{  
                                location.reload(); 
                            }
                        } 
                    });
                });  
            @endif
        });
    </script>
@endsection