@extends('layouts.app')
@section('title', 'Notifications')
@section('css')

@endsection
@section('content')
<div class="container"> 
    <div class="row justify-content-center">
        <div class = "col-md-12">
            @include('partial.alert')
        </div>

        <div class = "col-md-12 ">
            <h2 class="text-main  pad-btm mar-no  pad-lft text-semibold text-left text-dark">  
                Notifications
            </h2>
        </div> 
        <div class = "col-md-12">
            <div class = "card">
                <div class = "card-body  pad-no">
                    <div class = "clearfix pad-all bord-btm">
                        <h4 class="m-sm-bottom text-dark m-lg-top"> Today  </h4>
                    </div> 
                    <div class = "@if(count($today_notifications)) up-proposals-list__block @endif pad-all">
                        @forelse($today_notifications as $today_notification_index => $notification)  
                            <div class = "d-flex bord-btm up-card-section" data-aa = "item{!! $today_notification_index !!}">
                                <div class = "mr-20">
                                    @php
                                        $notification->setReadBy();
                                        $from_user = $notification->getFromUser();
                                    @endphp
                                    <a href="#">
                                        @if(isset($from_user))
                                            <img alt = "{!! $from_user->accounts->name !!}" class="img-xs img-circle" src="{!! $from_user->getImage()  !!}">
                                        @else
                                            <i class = "fa fa-tags pt-25"></i>
                                        @endif
                                    </a>
                                </div>
                                <div class = "flex-1  d-md-flex">
                                    <div class = "notification-text flex-5">
                                        <div class="pb-5 text-dark pt-13">  {!! $notification->notifications_value !!}  </div> 
                                        <small class="text-muted pt-12">{!! $notification->updated_at->diffForhumans()  !!}</small>
                                    </div>
                                    <div class = "flex-1 mx-md-20 my-10 my-md-0"> 
                                        <a href = "{!! $notification->generateURL() !!}"   class = "btn btn-mint proposal-title-button">{!! $notification->generateLabel() !!}</a>
                                    </div>
                                </div> 
                                <div class = "">  
                                    <a href = "{!! route('notification.delete', $notification->notifications_serial) !!}"   class = "text-mint btn-link main-card-button mar-top-5 pad-lft pull-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" aria-hidden="true" role="img"><polygon points="12.524 0 7 5.524 1.476 0 0 1.476 5.524 7 0 12.524 1.476 14 7 8.476 12.524 14 14 12.524 8.476 7 14 1.476"></polygon></svg> 
                                    </a>
                                </div>
                            </div> 
                        @empty
                        <div class = "row"> 
                            <div class = "col-md-12 pad-btm">
                                <div class="pb-5 text-dark up-card-section pt-14">  No new notifications  </span></div> 
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class = "card mar-top">
                <div class = "card-body  pad-no">
                    <div class = "clearfix pad-all bord-btm">
                        <h4 class="m-sm-bottom text-dark m-lg-top"> Earlier  </h4>
                    </div> 
                    <div class = "@if(count($earlier_notifications)) up-proposals-list__block  @endif pad-all">
                        @forelse($earlier_notifications as $earlier_notification => $notification)
                            <div class = "d-flex  bord-btm up-card-section" data-aa = "item{!! $earlier_notification !!}">
                                <div class = "mr-20">
                                    @php
                                        $notification->setReadBy();
                                        $from_user = $notification->getFromUser();
                                    @endphp

                                    <a href="#">
                                        @if(isset($from_user))
                                            <img alt = "{!! $from_user->accounts->name !!}" class="img-xs img-circle" src="{!! $from_user->getImage()  !!}">
                                        @else
                                            <i class = "fa fa-tags pt-25"></i>
                                        @endif
                                    </a> 
                                </div>
                                <div class = "flex-1  d-md-flex">
                                    <div class = "notification-text flex-5">
                                        <div class="pb-5 text-dark pt-13">  {!! $notification->notifications_value !!}  </div> 
                                        <small class="text-muted pt-12">{!! $notification->updated_at->diffForhumans()  !!}</small>
                                    </div> 
                                    <div class = "flex-1 mx-md-20 my-10 my-md-0"> 
                                        <a href = "{!! $notification->generateURL() !!}"   class = "btn btn-mint proposal-title-button">{!! $notification->generateLabel() !!}</a>
                                    </div> 
                                </div> 
                                <div class = ""> 
                                    <a href = "{!! route('notification.delete', $notification->notifications_serial) !!}"   class = "text-mint btn-link main-card-button mar-top-5 pad-lft pull-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" aria-hidden="true" role="img"><polygon points="12.524 0 7 5.524 1.476 0 0 1.476 5.524 7 0 12.524 1.476 14 7 8.476 12.524 14 14 12.524 8.476 7 14 1.476"></polygon></svg> 
                                    </a>
                                </div>
                            </div>
                        @empty
                        <div class = "row"> 
                            <div class = "col-md-12 pad-btm">
                                <div class="pb-5 text-dark up-card-section pt-14">  No old notifications  </span></div> 
                            </div> 
                        </div>
                        @endforelse
                    </div>  
                </div>
            </div> 
        </div> 
    </div>
</div>
@endsection
@section('javascript')


@stop