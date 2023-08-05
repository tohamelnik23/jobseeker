@extends('layouts.app')
@section('title', 'Contract Details') 
@section('css') 
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/simplerating/star-rating-svg.css') }}"/>  
@endsection
@section('content') 
@php
    $job            =   $offer->getJob();
    $client         =   $job->getClient(); 
    $message_list   =   $job->getMessageList(Auth::user()->id); 

    if($offer->status == 10){
        $client_feedback        =  $offer->getFeedback( $client->id);
        $freelancer_feedback    =  $offer->getFeedback(  Auth::user()->id );
    }
@endphp 
<div class="container">
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div> 

        @if($offer->status == 10)
        <div class = "col-md-12 mar-top text-right">  
            <button type = "button" class = "btn btn-mint btn-card-button dis-title-button">Propose New Contract</button>        
        </div>
        @endif

        <div class = "col-md-12 mar-top">  
            <div class = "card freelancer_card">
                <div class = "card-body pad-no">
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class = "clearfix pad-all">
                                <div class = "row pad-hor">
                                    <div class = "col-md-7 col-sm-6">
                                        <h3 class = "text-dark one-line-limit-noblock">{!! $job->headline !!}</h3> 
                                        @if($offer->status == 1)
                                            <p> Active since {!! date('M d, Y', strtotime($offer->start_time))  !!} </p>
                                        @endif 
                                    </div>
                                    <div class = "col-md-5 col-sm-6 d-flex"> 
                                        <div class = "pad-top">
                                            <img class="img-circle img-md" src="{!! $client->getImage() !!}"> 
                                        </div>
                                        <div class = "pad-top">
                                            <h4 class = "text-dark"> {!! $client->accounts->name !!} </h4>
                                            <p class = "pt-15"> 3:06 PM Wed</p>
                                        </div>
                                    </div>  
                                    @if($offer->status == 10 )
                                    <div class = "col-md-12">
                                       <p class = "text-dark mar-no"> 
                                           <img src = "{!! asset('img/checkbox.png') !!}" height = 15 /> Completed  {!! date('M d',  strtotime($offer->end_time)  ) !!} - 
                                            @if(!isset($client_feedback))
                                                <a  href = "{!! route('employee.contract.leavefeedback', $offer->serial)  !!}">   Give Feedback </a>
                                                <span class = "mar-lft total-rating" data-star = '0'></span>
                                            @else
                                                <span class = "mar-lft total-rating" data-star = '{!! $client_feedback->rate_total !!}'></span>
                                            @endif
                                       </p>
                                    </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-12">
                            <div class="panel freelancer_card_panel">
                                <div class="panel-heading bord-btm">
                                    <div class="panel-control pull-left">
                                        <ul class="nav nav-tabs">
                                            <li class = "active "><a href = "#contract-details-milestone" data-toggle="tab" class = "text-uppercase"> Milestones & Earnings </a></li>  
                                            <li class = ""><a  href = "{!! route('messages') !!}?room={!! $message_list->serial !!}"    class = "text-uppercase"> Messages </a></li>
                                            <li class = ""><a href = "#contract-details-terms_settings" data-toggle="tab" class = "text-uppercase"> Terms & Settings </a></li>
                                            <li class = ""><a href = "#contract-details-feedback" data-toggle="tab" class = "text-uppercase"> Feedback </a></li>
                                        </ul>
                                    </div>
                                </div>  
                                <div class="panel-body pad-no">
                                    <div class="tab-content"> 
                                        <div class="tab-pane fade   active in " id="contract-details-milestone">
                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = "d-flex pad-hor">
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">Budget</p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->amount, 2) !!}</p>
                                                    </div>
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">In Escrow </p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->getTotalEscrow(), 2) !!}</p>
                                                     </div>
                                                    <div class = "flex-1 ">
                                                        <p class = "pt-15 text-bold text-dark">Milestones Paid</p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->getMilestonePaid(), 2) !!}</p>
                                                    </div>
                                                    <div class = "flex-1 bord-rgt">
                                                        <p class = "pt-15 text-bold text-dark">Remaining</p>
                                                        <p class = "pt-20 text-dark">${!! number_format($offer->getTotalRemaing(), 2) !!}</p>
                                                    </div>            
                                                    <div class = "flex-1 pad-hor">
                                                        <p class = "pt-15 text-bold text-dark">Total Earnings</p>
                                                        <p class = "pt-20 text-dark">${!! number_format( $offer->getTotalPaid(), 2) !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(($offer->status == 1))

                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor">
                                                    <div class = "row">
                                                        <div class = "col-xs-6">
                                                            <h4 class = "text-dark">
                                                                Remaining milestones 
                                                            </h4>
                                                        </div>
                                                        <div class = "col-xs-6 text-right">
                                                            <button type = "button" class = "btn  btn-card-button  btn-default">
                                                                <span class = "text-bold text-mint"> Add or Edit Milestones </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor"> 
                                                    @php
                                                        $remaining_milestones = $offer->getRemaininMilestones(); 
                                                    @endphp 
                                                    @forelse($remaining_milestones as $remaining_milestone)
                                                        <div class = "row">
                                                            <div class = "col-xs-6">
                                                                <h5 class = "text-dark">
                                                                    {!! $remaining_milestone->milestone_sort + 1 !!}. {!! $remaining_milestone->headline  !!}
                                                                    @if($remaining_milestone->status == "active")
                                                                    <span class="badge badge-relationship text-center m-0-top-bottom  mar-lft btn-sm"> 
                                                                        Active                         
                                                                    </span>
                                                                    @endif
                                                                </h5>
                                                                <p class = "text-dark mar-lft "> ${!! $remaining_milestone->amount !!} @if($remaining_milestone->deposit_status == 1) (Funded) @endif </p>
                                                            </div>
                                                            <div class = "col-xs-6 text-right">
                                                                @php
                                                                    $submit_work =  $remaining_milestone->checkSubmittionRequest();
                                                                @endphp 
                                                                @if(isset($submit_work))
                                                                    <p class = "text-dark text-bold "> <i> You have sent the request {!! $submit_work->created_at->diffForHumans() !!}. </i> </p>
                                                                    <button data-id = "{!! $remaining_milestone->serial !!}" type = "button" class = "btn submit-work-payment  btn-card-button  btn-mint">   Re-Submit Work for Payment    </button>
                                                                @else
                                                                    <button data-id = "{!! $remaining_milestone->serial !!}" type = "button" class = "btn submit-work-payment  btn-card-button  btn-mint">   Submit Work for Payment    </button>
                                                                @endif
                                                            </div>  
                                                        </div>
                                                    @empty
                                                        <div class = "row"> 
                                                            <div class = "col-xs-12">
                                                                <p class = "text-dark"> There are no remaining milestones. </p>
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div> 
                                            </div> 
                                            @endif
                                            <div class = "pad-all clearfix bord-btm">
                                                <div class = " pad-hor">
                                                    @php
                                                        $completed_milestones = $offer->getCompletedMilestones();
                                                    @endphp

                                                    <div class="category_stuff clearfix">
                                                        <div class="col-md-12">
                                                            <a href="javascript:void(0)" class="category-anchor">
                                                                <h4 class="text-dark mar-ver"> 
                                                                    Completed Milestones({!! count($completed_milestones) !!})
                                                                    <span class="pull-right text-bold text-dark anchor-down">  
                                                                        <i class="fa fa-chevron-down"></i>
                                                                    </span> 
                                                                    <span class="pull-right text-bold text-dark anchor-up"> 
                                                                        <i class="fa fa-chevron-up"></i>
                                                                    </span>  
                                                                </h4>
                                                            </a> 
                                                        </div> 
                                                        <div class="col-md-12 category-items">
                                                            @forelse($completed_milestones as $remaining_milestone)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h5 class = "text-dark"> 
                                                                        {!! $remaining_milestone->milestone_sort + 1 !!}. {!! $remaining_milestone->headline  !!}
                                                                    </h5>
                                                                    <p class = "text-dark mar-lft"> ${!! $remaining_milestone->amount !!} (Paid) </p> 
                                                                </div> 
                                                            </div>
                                                            @empty
                                                                <div class = "row"> 
                                                                    <div class = "col-xs-12">
                                                                        <p class = "text-dark"> There are no completed milestones. </p>
                                                                    </div>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div> 
                                                </div> 
                                            </div>
                                            @php
                                                $getAdditionalEarnings  = $offer->getAdditionalEarnings();
                                            @endphp
                                            <div class = "pad-all clearfix mar-btm">   
                                                <div class = " pad-hor"> 
                                                    <h4 class = "text-dark"> Additional earnings </h4>  
                                                    <div class="table-responsive mar-top">
                                                        <table class="table ">
                                                            <thead>
                                                                <tr>
                                                                    <th class = "text-dark text-bold">Date</th>
                                                                    <th class = "text-dark text-bold">Description</th>
                                                                    <th class = "text-dark text-bold">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($getAdditionalEarnings as $getAdditionalEarning)
                                                                <tr>
                                                                    <td class = "text-dark">{!! date('M d, Y', strtotime($getAdditionalEarning->created_at)) !!}  </td>
                                                                    <td  class = "text-dark">{!! $getAdditionalEarning->type !!}</td>
                                                                    <td class = "text-dark">${!! $getAdditionalEarning->amount  !!}</td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td class = "text-center" colspan = "3">
                                                                        There are no additional earnings.
                                                                    </td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="tab-pane fade" id="contract-details-terms_settings">
                                            <div class = "row">
                                                <div class = "col-md-6 col-sm-6 bord-rgt">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Contract Info </h4>
                                                        </div>
                                                        <div class = "pad-all">
                                                            <div class = "col-xs-6">
                                                                <h5 class = "text-dark"> Start Date </h5>
                                                            </div>
                                                            <div class = "col-xs-6">
                                                                <p class = "text-dark">  {!!  date('F d, Y', strtotime($offer->start_time))   !!}  </p>
                                                            </div> 
                                                        </div> 
                                                    </div> 
                                                    <div class = "pad-all clearfix"> 
                                                        <div class = "pad-all">
                                                            <div class = "col-xs-6">
                                                                <h5 class = "text-dark">  Contract ID </h5>
                                                            </div>
                                                            <div class = "col-xs-6">
                                                                <p class = "text-dark mar-top-5"> {!! $offer->serial !!} </p>
                                                            </div> 
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class = "col-md-6 col-sm-6">
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <div class = "pad-hor">
                                                            <h4 class = "text-dark"> Description of work </h4>
                                                        </div>
                                                        <div class = "pad-hor description_item_card">  
                                                            @if(strlen($job->description) > 170 )
                                                            <div class = "description-shortlist description-part"> 
                                                                <span class="pt-14 text-dark ">
                                                                    {!! substr($job->description, 0, 170) !!} ...
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
                                                    <div class = "pad-all clearfix bord-btm">
                                                        <ul class="ats-actions list-unstyled pad-hor">
                                                            <li class="m-sm-bottom">
                                                                <a  href = "{!! route('jobs_offer_details', $offer->serial) !!}" class="btn-link text-mint">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View Offer
                                                                </a>
                                                            </li> 
                                                            @php
                                                                $proposal = $offer->getProposal();
                                                            @endphp 
                                                            @if(isset($proposal))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('jobs_proposal_details', $proposal->serial) !!}" class="btn-link text-mint text-capitalize">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>View original proposal
                                                                </a>
                                                            </li>
                                                            @endif 
                                                            @if(isset($job))
                                                            <li class="m-sm-bottom">
                                                                <a target = "_blank" href = "{!! route('jobs_details', $job->serial) !!}" class="btn-link text-mint text-capitalize">
                                                                    <i class="fa fa-share-square-o vertical-align-middle m-0-left  mar-rgt pt-15"></i>
                                                                        View original {{ $job->type }} posting
                                                                </a> 
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="contract-details-feedback">                                             
                                            @if($offer->status == 10)
                                                <div class = "row mar-all pad-all">
                                                    <div class = "col-md-6 col-sm-6">
                                                        <h4 class = "text-dark">Clients Feedback to You</h4>
                                                        @if(isset($freelancer_feedback))
                                                            @if(isset($client_feedback))
                                                                <p  class = "total-rating" data-star = '{!! $freelancer_feedback->rate_total !!}'></p>
                                                                <p class = "text-dark">
                                                                    {{ $freelancer_feedback->message  }}
                                                                </p> 
                                                            @else
                                                                <p class = "text-dark">
                                                                    Employee's feedback is hidden until you provide feedback.
                                                                </p>
                                                            @endif
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                        @endif  
                                                    </div>
                                                    <div class = "col-md-6 col-sm-6">
                                                        <h4 class = "text-dark">Your  Feedback to Client</h4>
                                                        @if(isset($client_feedback))
                                                            <p  class = "total-rating" data-star = '{!! $client_feedback->rate_total !!}'></p>
                                                            <p class = "text-dark">
                                                                {{ $client_feedback->message  }}
                                                            </p>
                                                        @else
                                                            <p class = "text-dark">
                                                                No Feedback Given
                                                            </p>
                                                            <a  href = "{!! route('employee.contract.leavefeedback', $offer->serial)  !!}" class="btn  btn-card-button  btn-mint">
                                                                <span class="text-bold   pt-15"> Give Feedback </span>
                                                            </a>
                                                        @endif 
                                                    </div> 
                                                </div>
                                            @else
                                                <div class = "row mar-all pad-all">
                                                    <div class="col-md-12 text-center pt-70">
                                                        <i class = "fa fa-file pad-rgt"> </i> 
                                                    </div> 
                                                    <div class="col-md-12 text-center">
                                                        <h4 class = "text-dark"> This contract is not yet eligible for feedback.</h4> 
                                                    </div> 
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="submit_work_modal" tabindex="-1" role="dialog" aria-labelledby="submit_work_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyprofilepicModalLabel">Submit work or request payment for a milestone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class = "pad-all submit_work_modal_body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{asset('plugins/simplerating/jquery.star-rating-svg.js')}}"></script>
<script>
    @if($offer->status == 1)
        $(".submit-work-payment").click(function(){
            var milestone_id    =  $(this).attr('data-id');
            $.ajax({ 
                url:  "{{route('jobs_milestone_details')}}",
                type: 'POST',
                dataType: 'json',
                data: { milestone_id : milestone_id },
                beforeSend: function (){
                },
                success: function(json){
                    if(json.status){
                        $("#submit_work_modal .submit_work_modal_body").html( json.html );
                        $("#submit_work_modal").modal("show");
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
                },
                error: function() {
                }
            });        
        });
        function Submit_Work(){
            var flag = 1;
            $(".submit_work_control").each(function(){
                var current_value = $.trim($(this).val());
                if( current_value == "")
                    flag  = 0;
            }); 
            if(flag) $(".request_submit_work").prop('disabled', false);
            else     $(".request_submit_work").prop('disabled', true);
        } 
        $(document).on("keyup", ".submit_work_control", function(){
            Submit_Work();
        });
        $(document).on("click", ".request_submit_work", function(){
            $.ajax({
                url:  "{{route('employee.submitwork')}}",
                type: 'POST',
                dataType: 'json',
                data:  $("#submit_work_detail").serialize(),
                beforeSend: function (){
                },
                success: function(json){
                    if(json.status){
                        location.reload();
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
                },
                error: function() {
                }
            });
        });
    @endif

    @if($offer->status == 10)
        $(".total-rating").each(function(){
            $(this).starRating({
                initialRating: $(this).attr('data-star'),
                starSize:   14,
                totalStars: 5,  
                disableAfterRate: false,
                readOnly: true
            });
        }); 
    @endif

</script>
@stop