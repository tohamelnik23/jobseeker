<!-- Profile Item Stuff --> 
@php
    $decline_messages = DB::table('decline_reasons')->where('type', 'proposal_client')->get();
@endphp 
<div class="modal fade" id="DeclineActionModal" tabindex="-1" role="dialog" aria-labelledby="DeclineActionModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="verifyprofilepicModalLabel">Decline</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">  
                <div class = "pad-all">  
                    <form id = "declineForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class = "row">
                            <div class = "col-md-12">
                                <p class = "text-dark">
                                    Select Decline to remove the freelancer from consideration for this gig. Optionally, you can include a message to let the freelancer know why you're not interested.
                                </p>
                            </div> 
                            <div class = "col-md-12">
                                <h5 class = "text-dark">
                                    Reason
                                </h5>
                            </div>
                            <div class="col-md-12"> 
                                @foreach($decline_messages as $decline_message)
                                <div class="radio">
                                    <input id="decline-form-radio-{!! $decline_message->id  !!}" data-type = "{!! $decline_message->more_info   !!}" class="magic-radio decline_form_radio" type="radio" name="reason" value = "{!! $decline_message->id !!}">
                                    <label for="decline-form-radio-{!! $decline_message->id  !!}">{!! $decline_message->content !!}</label>
                                </div>
                                @endforeach
                            </div> 

                            <div class = "col-md-12 other_reason_div hidden mar-btm">
                                <input name = "other_reason" class="form-control" placeholder = "Enter a reason" maxlenght = 512 >
                            </div> 
                            <div class = "col-md-12">
                                <h5 class = "text-dark">
                                    Message
                                </h5>
                                <p class = "text-dark"> 
                                    Add an optional message to share with the client when we notify them that this invitation has been withdrawn.
                                </p>
                            </div>
                            <div class="col-sm-12">
                                <textarea name = "decline_notes" class="form-control" maxlenght = 5000 rows="6"></textarea>
                                <span class="help-block"> 
                                    <strong></strong>  
                                </span>
                            </div> 
                            <div class="form-group clearfix">
                                <div class="col-sm-12 mar-top action_btn_group"> 
                                    <button type="button" disabled class="btn btn-mint decline_invite_form"> Decline </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                                </div>
                            </div> 
                        </div> 
                    </form>
                </div>

			</div>
		</div>
  	</div>
</div>
@push('partialscripts')
<script> 
    var decline_type     = "";
    var decline_user_id  = "";
    function declineformAction(){
        var flag = 0;
        $(".other_reason_div").addClass("hidden");
        $("input[name = 'other_reason']").removeClass("edit");
        $(".decline_form_radio").each(function(){
            if($(this).prop("checked")){
                flag = 1; 
                if($(this).attr("data-type") == '1'){
                    $(".other_reason_div").removeClass("hidden");
                    $("input[name = 'other_reason']").addClass("edit"); 
                    if($.trim($("input[name = 'other_reason']").val()) == "")
                        flag = 0; 
                }
                else{
                    $(".other_reason_div").val("");
                }
            }
        }); 
        if(flag)
            $(".decline_invite_form").prop('disabled', false);
        else
            $(".decline_invite_form").prop('disabled', true);
    } 
    $("input[name = 'other_reason']").keyup(function(){
        declineformAction();
    }); 
    $(document).on("click", ".decline_form_radio", function(){
        declineformAction();
    }); 
    $(".decline-action").click(function(){
        $('#declineForm')[0].reset();
        declineformAction(); 
        decline_type = $(this).attr('data-type');
        decline_user_id  =  $(this).closest(".action_btn_group").attr('data-serial'); 

        $("#DeclineActionModal").modal("show");
    }); 
    $(".decline_invite_form").click(function(){ 
        var url = "{{route('employer.jobs.declineaction', [':request_type', $job->serial])}}";
        // decline_type
        url     =  url.replace(':request_type',  decline_type);   
        $.ajax({  
            url:   url, 
            type: 'POST',
            data:  $("#declineForm").serialize()  + '&user_id=' + decline_user_id,
            dataType: 'json',
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    location.href = json.url;
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
</script> 
@endpush