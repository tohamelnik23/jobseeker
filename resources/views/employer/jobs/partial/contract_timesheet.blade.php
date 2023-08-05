@php
    $minutes    = 0;
    $total_paid = 0;
    $now        = Carbon\Carbon::now()->toDateString();
@endphp
<thead>
    <tr>
        @foreach($timesheets as $timesheet)
            <th class = "text-center vertical-align-middle">
                <p class = "mar-no"> {!! $timesheet['week'] !!} </p>
                <p> {!! $timesheet['date'] !!} </p>
            </th>
        @endforeach 
        <th class = "text-center vertical-align-middle">
            <p class = "mar-no"> HOURS </p>
        </th>
        <th class = "text-center vertical-align-middle">
            <p class = "mar-no"> RATE </p> 
        </th>
        <th class = "text-center vertical-align-middle">
            <p class = "mar-no"> AMOUNT </p> 
        </th>
    </tr>
</thead>
<tbody>
    <tr>
        @foreach($timesheets as $timesheet)
            @php
                if(isset($timesheet['time_sheet'])){
                    list($hour, $minute)    =   explode(':', $timesheet['time_sheet']->timesheets_time );
                    $minutes                +=  $hour * 60;
                    $minutes                +=  $minute;    
                }
            @endphp
            <td  class = "text-center vertical-align-middle">
                @if( ($now >= $timesheet['real_date'])    &&  ( date('Y-m-d', strtotime($offer->start_time)) <= $timesheet['real_date'] ) )
                    @if(isset($timesheet['time_sheet']))
                        <p class = "mar-no">
                            @if( $timesheet['time_sheet']->timesheets_time == "00:00:00")
                                -
                            @else
                               {!! date('H:i', strtotime( $timesheet['time_sheet']->timesheets_time))  !!}
                            @endif
                        </p> 
                    @else
                        <p class = "mar-no"> - </p> 
                    @endif 
                @endif
            </td>
        @endforeach            
            @php
                $hours       =  floor($minutes / 60);
                $hours_val   =  $minutes / 60;
                $hours_val   =  round($hours_val, 2);
                $total_paid  =  $hours_val * $offer->amount;
                $minutes    -=  $hours * 60;
            @endphp
            <td  class = "text-center vertical-align-middle" > 
                <p class = "hours_total_timesheet mar-no">{!! sprintf('%02d:%02d', $hours, $minutes) !!}</p>
            </td>
            <td  class = "text-center vertical-align-middle hourly_rate_amount" data-amount = "{!! $offer->amount !!}">
                <p class = "mar-no">  ${!! $offer->amount !!}/hr </p>
            </td>
            <td  class = "text-center vertical-align-middle  text-dark text-bold" >
                <p class = "hourly_total_paid mar-no">${!! number_format($total_paid, 2) !!}</p> 
            </td>
    </tr>
</tbody>