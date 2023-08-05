<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title') </title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> 
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-timepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet">               
    <link href="{{ asset('css/nifty.min.css') }}" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/demo/nifty-demo.min.css') }}"/> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/demo/nifty-demo-icons.min.css') }}"/>
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('plugins/sweet/sweetalert2.min.css') }}" rel="stylesheet">  
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/pace/pace.min.css') }}">
    <script src="{{ asset('plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/xiaomin.css') }}"> 
    <style>
        .text-2x{
            font-size: 2em !important;
        }
        .modal .modal-header{
            background-color: #f1f1f1;
        }
        .mar-top-5{
            margin-top: 5px;
        }
        .navbar-brand{
            float: left;
            height: 50px;
            padding: 15px 15px;
            font-size: 18px;
            line-height: 20px;
        }
        .card-header{
            color: #444;
            font-weight: 550;
            padding: 15px 20px;
            font-size: 15px;
        }                                                           
        .dropdown-item{
            white-space: inherit;
            padding-top: 10px;
            font-size: 12px;
        }
        .dropdown-item p{
            margin-bottom: 1px;
        }
       
        .dropdown-item .dropdown-item-date{
            color: #545454;
            font-size: 11px;
        }

        fieldset.scheduler-border {
            border: 1px groove  #ddd !important; 
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        } 
        legend.scheduler-border {
            font-size: 1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        } 
        .checkbox-img{
            height: 16px;
            width: 16px;
        }
        /**********************************************************************/

        #cover-spin {
            position:fixed;
            width:100%;
            left:0;right:0;top:0;bottom:0;
            background-color: rgba(255,255,255,0.7);
            z-index:9999;
            display:none;
        } 
        @-webkit-keyframes spin {
            from {-webkit-transform:rotate(0deg);}
            to {-webkit-transform:rotate(360deg);}
        } 
        @keyframes spin {
            from {transform:rotate(0deg);}
            to {transform:rotate(360deg);}
        } 
        #cover-spin::after {
            content:'';
            display:block;
            position:absolute;
            left:48%;top:40%;
            width:40px;height:40px;
            border-style:solid;
            border-color:black;
            border-top-color:transparent;
            border-width: 4px;
            border-radius:50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }
    </style> 
	@yield('css')
    @stack('partialcss')
</head>
<body>
    <div id="cover-spin"></div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    DiscoverGigs.com
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button> 
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"> 
                    </ul> 
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-right ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
							<li class="nav-item">
                                <a class="nav-link" href="{{ route('employers.register') }}">Get Stuff Done</a>
                            </li>
							<li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.register') }}">Do Stuff</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown_search" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Search
                                </a> 
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown_search" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                    <a class="dropdown-item" href="{{ route('search') }}">
                                        <i class = "fa fa-search pad-rgt"> </i>  Find Gigs
                                    </a>
                                    <a class="dropdown-item" href="{{ route('search.employee') }}">
                                        <i class = "fa fa-users pad-rgt"> </i>  Find Experts
                                    </a>
                                </div>
                            </li>
                            @if (Route::has('register')) 
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else 
                            <li class="nav-item">
                                <a  class="nav-link" href="{!! route('notifications') !!}" role="button">
                                    <i class="demo-pli-bell pt-20"></i>
                                    @php
                                        $new_notifications  = Auth::user()->getNewNotification('employee');
                                    @endphp 
                                    @if($new_notifications)
                                        <span class="badge badge-header badge-danger"></span>
                                    @endif
                                </a>
                            </li> 
                            @php
                                $unread_messages = Auth::user()->getUnreadMessages();
                            @endphp
                            <li class="nav-item">
                                <a  class="nav-link" href="{!! route('messages') !!}" role="button">
                                    Messages
                                    <span class="badge u-message-alert  badge-success  @if($unread_messages) active @endif">{!! $unread_messages  !!}</span>
                                </a>
                            </li>
							@if(Auth::user()->role == 1) 
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        My Gigs
                                    </a> 
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{route('employee.jobs')}}">
                                            <i class = "fa fa-search pad-rgt"> </i>  My Gigs
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employee.contracts') }}">
                                            <i class = "fa fa-users pad-rgt"> </i>   All Contracts
                                        </a> 
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Reports
                                    </a> 
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{ route('employee.reports.main_action', 'in-progress') }}">
                                            Over View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employee.reports.earnings_history') }}">
                                            Transaction History
                                        </a> 
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Find Work
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{ route('search') }}">
                                            <i class = "fa fa-search pad-rgt"> </i>  Find Work
                                        </a>
                                        <a class="dropdown-item" href="{{ route('search.employee') }}">
                                            <i class = "fa fa-users pad-rgt"> </i>  Find Expert
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employee.saved') }}">
                                            <i class = "fa fa-heart-o pad-rgt"> </i> Saved Gigs
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employee.proposals') }}">
                                            <i class = "fa fa-tags pad-rgt"> </i> Proposals
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employee.profile') }}">
                                            <i class = "fa fa-user pad-rgt"> </i>  Profile
                                        </a>
                                    </div>
                                </li>
                                @if(Auth::user()->accounts->loan_enable)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                              Advance Power
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        
                                            <a class="dropdown-item" href="{{ route('loans.settings') }}">
                                                <i class = "fa fa-info-circle pad-rgt"> </i> Advance Details
                                            </a> 
                                            <a class="dropdown-item" href="{{ route('loans.request') }}">
                                                <i class = "fa fa-phone pad-rgt"> </i>  Advance Power
                                            </a>
                                            <a class="dropdown-item" href="{{ route('loans.history') }}">
                                                <i class = "fa fa-clock-o pad-rgt"> </i> Advance History
                                            </a>
                                        </div>
                                    </li>
                                @endif
                            @else 
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown_search" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Search
                                    </a> 
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown_search" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{ route('search') }}">
                                            <i class = "fa fa-search pad-rgt"> </i>  Find Gigs
                                        </a>
                                        <a class="dropdown-item" href="{{ route('search.employee') }}">
                                            <i class = "fa fa-users pad-rgt"> </i>  Find Experts
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Gigs
                                    </a> 
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{ route('employer.jobs') }}">
                                            My Gigs
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employer.mypostings') }}">
                                            All Gig Posts
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employer.contracts') }}">
                                            All Contracts
                                        </a>
                                        <a class="dropdown-item" href="{{ route('employer.job.add') }}">
                                            Post Job
                                        </a> 
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Reports
                                    </a> 
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                        <a class="dropdown-item" href="{{ route('employer.reports.billing_history') }}">
                                            Transactions
                                        </a>
                                    </div>
                                </li>
							@endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   <i class =  "fa fa-user-circle-o pt-20"></i>
                                </a> 
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style = "border: 1px solid rgba(0,0,0,.15) !important;padding: 5px 0; box-shadow: 0 6px 12px rgba(0,0,0,.175); margin: 2px 0px 0px 0px; font-size: 14px;">
                                    @if(Auth::user()->role == 1)
                                        <a class="dropdown-item" href="{{ route('employee.profile') }}">
                                           <i class = "fa fa-user pad-rgt"> </i>  {{ Auth::user()->accounts->name }}
                                        </a>
									@else
                                        <a class="dropdown-item" href="{{ route('employer.profile') }}">
                                            <i class = "fa fa-user pad-rgt"> </i> {{ Auth::user()->accounts->name }}
                                        </a> 
                                    @endif 
                                    <a class="dropdown-item" href="{{ route('settings.notifications') }}">
                                        <i class = "fa fa-gear   pad-rgt"> </i>  Settings
                                    </a>  
									<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class = "fa fa-unlock pad-rgt"></i>  {{ __('Logout') }}
                                    </a> 
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> 
        <main class="">
            @yield('content') 
        </main>
    </div>
	<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap.min.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js?'.time())}}"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap-timepicker.js?'.time())}}"></script>
    <script type="text/javascript" src="{{ asset('plugins/sweet/sweetalert2.min.js') }}"></script>
    <script src="{!! asset('plugins/masked-input/jquery.maskedinput.min.js') !!}"></script> 
    <script src="{{  asset('js/nifty.js') }}"         type="text/javascript"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });  
        var deleteconfirm_string = "Do you want to remove this item?";  
        $(document).on("click", ".deleteaction", function(){ 
            var delete_title   		= "Notification";
            var delete_strting 		= deleteconfirm_string; 
            if (typeof $(this).attr('data-title') !== 'undefined') {
                delete_title  		= $(this).attr('data-title');
            } 
            if (typeof $(this).attr('data-string') !== 'undefined') {
                delete_strting  	= $(this).attr('data-string');
            }  
            var url = $(this).data('url'); 
            swal({
                    title: delete_title,
                    text:  delete_strting,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes",
                    cancelButtonText: "No"
                }).then((result) => {
                    if (result.value){ 
                        window.location.href = url;
                    }
            });
        });  
        $(document).on("keydown", ".decimal-input", function(event){
            if (event.shiftKey == true) {
                event.preventDefault();
            } 
            if ((event.keyCode >= 48 && event.keyCode <= 57) ||
                (event.keyCode >= 96 && event.keyCode <= 105) ||
                event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
                event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {
            } else {
                event.preventDefault();
            }
            if($(this).val().indexOf('.') !== -1 && (event.keyCode == 190  || event.keyCode == 110))
                event.preventDefault();
        });
        $(document).on("keydown", ".integer-input", function(event){
            if (event.shiftKey == true) {
                event.preventDefault();
            }
            if ((event.keyCode >= 48 && event.keyCode <= 57) ||
                (event.keyCode >= 96 && event.keyCode <= 105) ||
                event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
                event.keyCode == 39 || event.keyCode == 46) { 
            } else {
                event.preventDefault();
            }
        }); 
        $(document).on("keyup", ".integer-input", function(event){
            if($(this).val() == "")
                $(this).val("1"); 
            if($(this).val() == "0")
                $(this).val("1");
        });
        $(document).on("change", ".integer-input", function(event){
            if($(this).val() == "")
                $(this).val("1"); 
            if($(this).val() == "0")
                $(this).val("1");
        });
        
        function addErrorForm(obj, string = ""){
            obj.closest(".form-group").find(".help-block strong").html(string);
            if(string === ""){
                obj.closest(".form-group").removeClass("has-error");
            }
            else{ 
                obj.closest(".form-group").addClass("has-error");
            }
        } 
        function addErrorItem(obj, string = ""){
            obj.closest("div").find(".help-block strong").html(string);
            if(string === ""){
                obj.closest("div").removeClass("has-error");
            }
            else{
                obj.closest("div").addClass("has-error");
            }
        } 
        $(document).on("click", ".category-anchor", function(){
            var obj = $(this).closest(".category_stuff");
            if(obj.hasClass("closed"))
                obj.removeClass("closed");
            else
                obj.addClass("closed"); 
        });
        $(document).on("click", ".btn-description-action" , function(){
            var obj = $(this).closest(".description_item_card");
            obj.find(".description-part").addClass("hidden"); 
            if($(this).hasClass("more")){
                obj.find(".description-detaillist").removeClass("hidden");
            }
            if($(this).hasClass("less")){
                obj.find(".description-shortlist").removeClass("hidden");
            }
        });
    </script>
	@yield('javascript')
    @stack('partialscripts')
</body> 
</html>
