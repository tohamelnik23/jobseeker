@php 
    $sent_offers  = $job->countActions('offers'); 
    $hired_offers = $job->countActions('hired'); 
@endphp 
<div class="panel-heading">
    <div class="panel-control pull-left">
        <ul class="nav nav-tabs">
            <li class = "@if($subrequest == 'offers') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'offers'])  !!}"> OFFERS ({!! $sent_offers !!}) </a></li> 
            <li class = "@if($subrequest == 'hired') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'hired'])!!}" class = ""> HIRED ({!! $hired_offers !!}) </a></li>
        </ul>
    </div> 
</div> 