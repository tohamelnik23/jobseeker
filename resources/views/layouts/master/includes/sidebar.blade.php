<div id="mainnav-shortcut" class="hidden">
	<ul class="list-unstyled shortcut-wrap">
		<li class="col-xs-3" data-content="My Profile">
			<a class="shortcut-grid" href="#">
				<div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
					<i class="demo-pli-male"></i>
				</div>
			</a> 
		</li>     
		<li class="col-xs-3" data-content="Messages">
			<a class="shortcut-grid" href="#">
				<div class="icon-wrap icon-wrap-sm icon-circle bg-warning">           
					<i class="demo-pli-speech-bubble-3"></i>
				</div>
			</a>    
		</li>                   
		<li class="col-xs-3" data-content="Activity">
			<a class="shortcut-grid" href="#">
				<div class="icon-wrap icon-wrap-sm icon-circle bg-success">
					<i class="demo-pli-thunder"></i>
				</div>
			</a>                                                        
		</li>
		<li class="col-xs-3" data-content="Lock Screen">
			<a class="shortcut-grid" href="#">
				<div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
					<i class="demo-pli-lock-2"></i>
				</div>                                                
			</a>                            
		</li>      
	</ul> 
</div>
<ul id="mainnav-menu" class="list-group">  
	<li @if(Request::is('master/dashboard')||  Request::is('master/dashboard/*')) class = "active-sub active" @endif>
		<a href="{{ route('master.dashboard') }}">
			<i class="demo-pli-home"></i> 
			<span class="menu-title">
				Dashboard
			</span>
		</a>
	</li>

	<li @if(Request::is('master/notifications')||  Request::is('master/notifications/*')) class = "active-sub active" @endif>
		<a href="{!! route('master.notifications.index') !!}">
			<i class="demo-pli-bell"></i> 
			<span class="menu-title">
				Notification 
				@if(Auth::user()->getNewNotification())
					<span class="pull-right badge badge-danger">{!! Auth::user()->getNewNotification() !!}</span>
				@endif
			</span> 
		</a>
	</li>
	<li  @if(Request::is('master/settings')||  Request::is('master/settings/*')) class = "active-sub active" @endif>
		<a href="#">
			<i class="demo-pli-gear"></i>
			<span class="menu-title">Settings</span>
			<i class="arrow"></i>
		</a>
		<ul class="collapse">
			<li  @if(Request::is('master/settings/index')) class = "active-sub active" @endif>
				<a href="{!! route('master.settings.index') !!}">
					Settings
				</a>
			</li> 
			<li  @if(Request::is('master/settings/industries') ||  Request::is('master/settings/industries/*') ) class = "active-sub active" @endif>
				<a href="{!! route('master.industries') !!}">
					Gig Type
				</a>  
			</li>
			<li  @if(Request::is('master/settings/skills')) class = "active-sub active" @endif>
				<a href="{!! route('master.skills') !!}">
					Skills
				</a>
			</li>
			<li  @if(Request::is('master/settings/questions')) class = "active-sub active" @endif>
				<a href="{!! route('master.questions') !!}"> 
					Questions
				</a>
			</li> 
		</ul>
	</li> 
	<li  @if(Request::is('master/members')||  Request::is('master/members/*')) class = "active-sub active" @endif>
		<a href="#">
			<i class="fa fa-book"></i>
			<span class="menu-title">Members</span>
			<i class="arrow"></i>
		</a>
		<ul class="collapse">
			<li  @if(Request::is('master/members/employees')) class = "active-sub active" @endif>
				<a href="{{route('master.members.employees')}}">
					Gig  Doers 
				</a>
			</li> 
			<li  @if(Request::is('master/members/employers')) class = "active-sub active" @endif>
				<a href="{{route('master.members.employers')}}">
					Gig Owners
				</a>
			</li> 
		</ul>
	</li>

 	<li class="list-divider"></li>   
</ul> 