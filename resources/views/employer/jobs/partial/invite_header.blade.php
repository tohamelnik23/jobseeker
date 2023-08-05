<div class="panel-heading">
    <div class="panel-control pull-left">
        <ul class="nav nav-tabs">
            <li class = "@if($subrequest == 'suggested') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'suggested'])  !!}"> SEARCH </a></li>            
            @php
                $total_invited = $job->countActions('invited');
            @endphp
            <li class = "@if($subrequest == 'pending') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'pending'])!!}" class = ""> INVITED FREELANCERS ({!! $total_invited !!}) </a></li> 
            <li class = "@if($subrequest == 'hires') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'hires'])!!}" class = ""> MY HIRES ({!! $job->countActions('hires') !!}) </a></li>
            <li class = "@if($subrequest == 'saved') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'saved'])!!}" class = ""> SAVED ({!! $job->countSaved() !!}) </a></li>
        </ul>
    </div> 
</div> 