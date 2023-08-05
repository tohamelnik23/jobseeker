<div class="panel-heading">
    <div class="panel-control pull-left">
        <ul class="nav nav-tabs"> 
            @php
                $posted_proposals = $job->countActions('proposals');
            @endphp
            <li class = "@if($subrequest == 'applicants') active @endif"><a  href="{!! route('employer.jobs.mainaction', [ $job->serial ,'applicants'])  !!}"> ALL PROPOSALS ({!! $posted_proposals !!}) </a></li>
            <li class = "@if($subrequest == 'shortlisted') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'shortlisted'])  !!}"> SHORT LISTED ({!! $job->countShortlisted() !!}) </a></li>
            <li class = "@if($subrequest == 'messaged') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'messaged'])  !!}"> MESSAGED ({!! $job->countActions('messaged') !!})  </a></li>
            @php
                $archived_proposals = $job->countActions('archived');
            @endphp
            <li class = "@if($subrequest == 'archived') active @endif"><a href="{!! route('employer.jobs.mainaction', [ $job->serial ,'archived'])  !!}"> ARCHIVED ({!! $archived_proposals !!}) </a></li>
        </ul>
    </div>
</div>