@push('partialcss')
<style>
   .nav-stacked > li {
        float: none !important;
    }
    .nav-pills {
        display: block;
    }
    .nav-pills i{
        width: 17px;
    }
    .nav-pills .active i{
        width: 12px;
    }
    .nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
        background-color: transparent; 
        border-left-width: 0 !important; 
        color: #25476a; 
        padding: 9px 20px; 
        text-decoration: none; 
        font-weight: 700;
    }
    .nav-pills>li.active>a:after, .nav-pills>li.active a:hover:after, .nav-pills>li.active a:active:after, .nav-pills>li.active a:focus:after {
        content: " ";
        position: absolute;
        border-left: 3px solid #26a69a;
        left: 0;
        right: auto;
        bottom: 0;
        top: 0;
    }
    .fixed-content-part .panel{
        -webkit-box-shadow: 0 1px 6px rgba(57,73,76,0.35) !important;
        box-shadow: 0 1px 6px rgba(57,73,76,0.35) !important;
    }
    /*******************************************************************************************/ 
    .panel-edit-content{
        font-size: 17px !important;
    } 
    .edit-part{
        display:none;
    } 
    .editing .edit-part{
        display:block !important;
    } 
    .editing .show-part{
        display:none;
    } 
    .editing .panel-edit-content{
        display:none;
    } 
    .queue-edit-part{
        display:none;
    }
    .queue-edit-part.active{
        display:block !important;
    } 
</style>
@endpush 
@if(Auth::user()->role == '1')
    <ul class="nav nav-pills nav-stacked m-sm-bottom  pad-all left-queue-menu">
        <li class="@if($setting_tab == 'myprofile')  active @endif">
            <a href="{{ route('employee.profile') }}" class = " text-dark"> 
                <i class = "fa fa-user mar-rgt pt-17"></i>  My Profile                             
            </a>
        </li>
        <li class="@if($setting_tab == 'bank_information')  active @endif"> 
            <a href="{!! route('settings.bankcards') !!}" class = " text-dark">
                <i class = "fa fa-bank mar-rgt pt-17"></i>
                Bank Information 
            </a>
        </li>
        <li class="@if($setting_tab == 'notifications')  active @endif">
            <a href="{{ route('settings.notifications') }}" class = "text-dark"> 
                <i class = "fa fa-envelope  mar-rgt pt-17"></i>
                Notification Settings 
            </a>
        </li>
    </ul>
@endif 

@if(Auth::user()->role == '2')
    <ul class="nav nav-pills nav-stacked m-sm-bottom  pad-all left-queue-menu">
        <li class="@if($setting_tab == 'profile')  active @endif">
            <a href="{{ route('employer.profile') }}" class = " text-dark"> 
                <i class = "fa fa-user mar-rgt pt-17"></i>  My Info                             
            </a>
        </li>
        <li class="@if($setting_tab == 'deposit_methods')  active @endif"> 
            <a href="{!! route('employer.profile.settings.deposit_methods') !!}" class = " text-dark">
                <i class = "fa fa-id-card mar-rgt pt-17"></i>
                Billing Methods
            </a>
        </li>
        <li class="@if($setting_tab == 'notifications')  active @endif">
            <a href="{{ route('settings.notifications') }}" class = "text-dark"> 
                <i class = "fa fa-envelope  mar-rgt pt-17"></i>
                Notification Settings 
            </a>
        </li>
    </ul>
@endif