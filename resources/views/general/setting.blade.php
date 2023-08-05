@extends('layouts.app')
@section('title', 'Notification Settings')
@section('css') 
<link href="{!! asset('plugins/bootstrap-select/bootstrap-select.min.css') !!}" rel="stylesheet">
<link href="{!! asset('plugins/snackbar/snackbar.min.css') !!}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class = "col-md-3">
                @include('general.partial.settings_leftsidebar') 
            </div>
            <div class="col-md-9">
                <form class="panel-body form-horizontal form-padding"  method="post" action="{{{ route('settings.notifications') }}}">
                    @csrf
                    <div class="card mar-btm">
                        <div class="card-header bg-light"> 
                            <h4 class = "text-dark"> Messages </h4>
                        </div>
                        <div class="card-body pad-no"> 
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-all">
                                    <div class = "row">
                                        <div class = "col-md-12 mb-20 mb-lg-30">
                                            <h4 class = 'text-dark mar-no'>Email</h4>
                                            <p> (sending to {!! Auth::user()->email !!}) </p>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <label  class = "text-bold text-dark mar-btm">
                                                Send an email with unread activity for:
                                            </label>
                                        </div>
                                    </div> 
                                    <div class = "row mt-10">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_email_type");
                                        @endphp
                                        <select class="selectpicker setting_select_controller col-md-6 mb-10" data-key = "notification_message_email_type">
                                            <option @if($setting_value == "all_activity") selected @endif  value = "all_activity">All Activity</option> 
                                            <option @if($setting_value == "nothing") selected @endif       value = "nothing">Nothing</option> 
                                        </select> 
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_email_time");
                                        @endphp
                                        <select class="selectpicker setting_select_controller col-md-6" data-key = "notification_message_email_time">
                                            <option @if($setting_value == "immediate") selected @endif value = "immediate">Immediate</option>
                                            <option @if($setting_value == "every_15") selected @endif value = "every_15">Every 15 minutes</option>
                                            <option @if($setting_value == "every_hour") selected @endif value = "every_hour">Once an hour</option>
                                            <option @if($setting_value == "every_day") selected @endif value = "every_day">Once a day</option>
                                        </select> 
                                    </div>
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_email_sendtype");
                                        @endphp
                                            <div class="checkbox">
					                            <input id="email-notify-checkbox" class="magic-checkbox setting_controller" data-key = "notification_message_email_sendtype"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email-notify-checkbox"> Only send when offline </label>
					                        </div>
                                        </div>
                                    </div>
                                </div>
                            </section> 
                            <!--section class = "pad-all">
                                <div class = "pad-all">
                                    <div class = "row">
                                        <div class = "col-md-12 mb-20 mb-lg-30">
                                            <h4 class = 'text-dark mar-no'>SMS</h4>
                                            <p> (sending to {!! Mainhelper::formatNumber(Auth::user()->cphonenumber) !!}) </p>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <label  class = "text-bold text-dark mar-btm">
                                                Send an SMS with unread activity for:
                                            </label>
                                        </div>
                                    </div> 
                                    <div class = "row mt-10">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_sms_type");
                                        @endphp
                                        <select class="selectpicker col-md-6 mb-10">
                                            <option @if($setting_value == "all_activity") selected @endif value = "all_activity">All Activity</option> 
                                            <option @if($setting_value == "nothing") selected @endif      value = "nothing">Nothing</option> 
                                        </select>
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_sms_time");
                                        @endphp
                                        <select class="selectpicker col-md-6">
                                            <option @if($setting_value == "immediate") selected @endif  value = "immediate">Immediate</option>
                                            <option @if($setting_value == "every_15") selected @endif   value = "every_15">Every 15 minutes</option>
                                            <option @if($setting_value == "every_hour") selected @endif value = "every_hour">Once an hour</option>
                                            <option @if($setting_value == "every_day") selected @endif  value = "every_day">Once a day</option>
                                        </select> 
                                    </div>
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_message_sms_sendtype");
                                        @endphp
                                            <div class="checkbox">
					                            <input id ="sms-notify-checkbox" class="magic-checkbox" type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms-notify-checkbox"> Only send when offline </label>
					                        </div>
                                        </div>
                                    </div>
                                </div>
                            </section--> 
                        </div>
                    </div>
                    <div class="card mar-btm">
                        <div class="card-header bg-light"> 
                            <h4 class = "text-dark"> Other Email Updates </h4>
                        </div>
                        <div class="card-body pad-no"> 
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor">
                                    <p class = "text-dark">
                                        Send email notification to <strong>{!! Auth::user()->email !!}</strong> when...
                                    </p>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Recruiting</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_job_posted_modified");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_job_posted_modified-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_job_posted_modified"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_job_posted_modified-checkbox"> A gig is posted or modified </label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_proposal_received");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_proposal_received-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_proposal_received"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_proposal_received-checkbox"> A proposal is received </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_interview_accepted");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_interview_accepted-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_interview_accepted"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_interview_accepted-checkbox"> An interview is accepted or offer terms are modified </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_interview_declined");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_interview_declined-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_interview_declined"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_interview_declined-checkbox"> An interview or offer is declined or withdrawn </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_offer_accepted");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_offer_accepted-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_offer_accepted"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_offer_accepted-checkbox"> An offer is accepted </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_offer_expiring");
                                            @endphp       
                                            <div class="checkbox">
					                            <input id ="email_offer_expiring-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_offer_expiring"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_offer_expiring-checkbox"> An offer will expire soon. </label>
					                        </div>            
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_offer_expired");
                                            @endphp       
                                            <div class="checkbox">
					                            <input id  = "email_offer_expired-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_offer_expired"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "email_offer_expired-checkbox"> An offer expired. </label>
					                        </div>            
                                        </div>
                                    </div>

                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_job_expire_soon");
                                            @endphp
                                            <div class="checkbox">
					                            <input id = "email_job_expire_soon-checkbox_soon" class="magic-checkbox setting_controller" data-key = "notification_email_job_expire_soon"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "email_job_expire_soon-checkbox_soon"> A gig posting will expire soon </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_job_expired");
                                            @endphp
                                            <div class="checkbox">
					                            <input id  = "email_job_expired-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_job_expired"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "email_job_expired-checkbox"> A gig posting expired </label>
					                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </section>
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Freelancer and Agency Proposals</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_offer_received");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_offer_received-checkbox" class = "magic-checkbox setting_controller" data-key = "notification_email_offer_received"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_offer_received-checkbox"> An offer or interview invitation is received </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_offer_withdrawn");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_offer_withdrawn-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_offer_withdrawn"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_offer_withdrawn-checkbox"> An offer or interview invitation is withdrawn </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_proposal_rejected");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_proposal_rejected-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_proposal_rejected"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_proposal_rejected-checkbox"> A Proposal is rejected </label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_job_closed");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_job_closed-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_job_closed"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_job_closed-checkbox"> A gig I applied to has been cancelled or closed  </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_proposal_withdrawn");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_proposal_withdrawn-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_proposal_withdrawn"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_proposal_withdrawn-checkbox"> A proposal is withdrawn </label>
					                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </section>
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Contracts</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_contract_begins");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_contract_begins-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_contract_begins"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_contract_begins-checkbox"> A hire is made or a contract begins </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_contract_modified");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_contract_modified-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_contract_modified"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_contract_modified-checkbox"> Contract terms are modified </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_email_contract_ends");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="email_contract_ends-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_contract_ends"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email_contract_ends-checkbox">A contract ends</label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12"> 
                                            <div class="checkbox">
					                            <input id ="email_payment_receipts-checkbox" disabled class="magic-checkbox" type="checkbox"  checked = "" >
					                            <label for="email_payment_receipts-checkbox"> Payment receipts and other financial related emails  </label>
					                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </section>
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Communications from DiscoverGigs</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_email_discovergigs_notification");
                                        @endphp
                                            <div class="checkbox">
					                            <input id ="email-discovergigs-checkbox" class="magic-checkbox setting_controller" data-key = "notification_email_discovergigs_notification"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="email-discovergigs-checkbox"> Send me useful emails every now and then help me get the most of Discovergigs </label>
					                        </div>
                                        </div>
                                    </div>  
                                </div>
                            </section>  
                        </div>
                    </div>
                    <div class="card mar-btm">
                        <div class="card-header bg-light"> 
                            <h4 class = "text-dark"> Other SMS Updates </h4>
                        </div>
                        <div class="card-body pad-no"> 
                            <section class = "pad-all bord-btm text-dark">
                                <div class = "pad-hor">
                                    <p class = "text-dark">
                                        Send email notification to <strong>{!! Mainhelper::formatNumber(Auth::user()->cphonenumber) !!}</strong> when... 
                                    </p>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Recruiting</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_job_posted_modified");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_job_posted_modified-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_job_posted_modified"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_job_posted_modified-checkbox"> A gig is posted or modified </label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_proposal_received");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_proposal_received-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_proposal_received"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_proposal_received-checkbox"> A proposal is received </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_interview_accepted");
                                            @endphp
                                            <div class="checkbox">
					                            <input id = "sms_interview_accepted-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_interview_accepted"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_interview_accepted-checkbox"> An interview is accepted or offer terms are modified </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_interview_declined");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_interview_declined-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_interview_declined"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_interview_declined-checkbox"> An interview or offer is declined or withdrawn </label>
					                        </div>
                                        </div>
                                    </div>                
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_offer_accepted");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_offer_accepted-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_offer_accepted"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_offer_accepted-checkbox"> An offer is accepted </label>
					                        </div>
                                        </div>
                                    </div>                
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_job_expire_soon");
                                            @endphp
                                            <div class="checkbox">
					                            <input id = "sms_job_expire_soon-checkbox_soon" class="magic-checkbox setting_controller" data-key = "notification_sms_job_expire_soon"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_job_expire_soon-checkbox_soon"> A gig posting will expire soon </label>
					                        </div>
                                        </div>
                                    </div>          
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_job_expired");
                                            @endphp
                                            <div class="checkbox">
					                            <input id  = "sms_job_expired-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_job_expired"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_job_expired-checkbox"> A gig posting expired </label>
					                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </section>
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Freelancer and Agency Proposals</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_offer_received");
                                            @endphp
                                            <div class="checkbox">
					                            <input id  = "sms_offer_received-checkbox" class = "magic-checkbox setting_controller" data-key = "notification_sms_offer_received"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_offer_received-checkbox"> An offer or interview invitation is received </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_offer_withdrawn");
                                            @endphp
                                            <div class="checkbox">
					                            <input id  = "sms_offer_withdrawn-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_offer_withdrawn"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_offer_withdrawn-checkbox"> An offer or interview invitation is withdrawn </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_proposal_rejected");
                                            @endphp
                                            <div class="checkbox">
					                            <input id = "sms_proposal_rejected-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_proposal_rejected"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for= "sms_proposal_rejected-checkbox"> A Proposal is rejected </label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_job_closed");
                                            @endphp
                                            <div class="checkbox">
					                            <input id  = "sms_job_closed-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_job_closed"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for = "sms_job_closed-checkbox"> A gig I applied to has been cancelled or closed  </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_proposal_withdrawn");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_proposal_withdrawn-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_proposal_withdrawn"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_proposal_withdrawn-checkbox"> A proposal is withdrawn </label>
					                        </div>
                                        </div>
                                    </div>   
                                </div>
                            </section>
                            <section class = "pad-all bord-btm  text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Contracts</h4>
                                        </div> 
                                    </div> 
                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_contract_begins");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_contract_begins-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_contract_begins"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_contract_begins-checkbox"> A hire is made or a contract begins </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_contract_modified");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_contract_modified-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_contract_modified"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_contract_modified-checkbox"> Contract terms are modified </label>
					                        </div>
                                        </div>
                                    </div>
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            @php
                                                $setting_value =  Auth::user()->getValue("notification_sms_contract_ends");
                                            @endphp
                                            <div class="checkbox">
					                            <input id ="sms_contract_ends-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_contract_ends"  type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms_contract_ends-checkbox">A contract ends</label>
					                        </div>
                                        </div>
                                    </div> 
                                    <div class = "row">
                                        <div class = "col-md-12"> 
                                            <div class="checkbox">
					                            <input id ="sms_payment_receipts-checkbox" disabled class="magic-checkbox" type="checkbox"  checked="">
					                            <label for="sms_payment_receipts-checkbox"> Payment receipts and other financial related emails  </label>
					                        </div>
                                        </div>
                                    </div> 
                                </div>
                            </section>
                            <section class = "pad-all bord-btm text-dark">
                                <div class = "pad-hor"> 
                                    <div class = "row">
                                        <div class = "col-md-12">
                                            <h4 class = "text-dark">Communications from DiscoverGigs</h4>
                                        </div> 
                                    </div>

                                    <div class = "row mt-10">
                                        <div class = "col-md-12">
                                        @php
                                            $setting_value =  Auth::user()->getValue("notification_sms_discovergigs_notification");
                                        @endphp
                                            <div class="checkbox">
					                            <input id ="sms-discovergigs-checkbox" class="magic-checkbox setting_controller" data-key = "notification_sms_discovergigs_notification"   type="checkbox" @if($setting_value == "yes") checked="" @endif>
					                            <label for="sms-discovergigs-checkbox"> Send me useful emails every now and then help me get the most of Discovergigs  </label>
					                        </div>
                                        </div>
                                    </div>  
                                </div>
                            </section>  
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript') 
<script src="{!! asset('plugins/bootstrap-select/bootstrap-select.min.js') !!}"></script>
<script src="{!! asset('plugins/snackbar/snackbar.min.js') !!}"></script>
<script>
    function set_setting_value(setting_key, setting_value){
        $.ajax({
            url:   "{!! route('settings.notifications') !!}",
            type: 'POST',
            data: { setting_key: setting_key,   setting_value: setting_value},
            dataType: 'json',
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    Snackbar.show({text: 'Changes Saved',  pos: 'top-center',  actionText: '<i class = "fa fa-times"></i>'}); 
                }
            },
            complete: function () {
            },
            error: function() {
            }
        });
    }
    $(".setting_select_controller").change(function(){
        set_setting_value($(this).attr('data-key'), $(this).find("option:selected").val());
    });
    $(".setting_controller").change(function(){
        var value = "";
        if($(this).prop("checked")){
            value = "yes";
        }
        else{
            value = "no";
        }
        set_setting_value($(this).attr('data-key'), value);
    });

    var hash_url = window.location.hash;
    if(hash_url != ""){  
        if($(hash_url).length){  
            if($(hash_url).prop("checked")){
                $(hash_url).trigger("click");
            }  
        }
    } 
</script>
@stop