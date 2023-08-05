@php
    $close_job_messages =  Mainhelper::getJobCloseReasons(); 
@endphp 
<div class="modal fade" id = "CloseActionModal" tabindex="-1" role="dialog" aria-labelledby="CloseActionModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"> Close Job </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">  
                <div class = "pad-all">  
                    <form id = "closeActionForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class = "row">
                            <div class = "col-md-12">
                                <p class = "text-dark">
									Use this to close your job to new applicants and notify current applicants. Those you have hired will not be affected.
                                </p>
                            </div> 
                            <div class = "col-md-12">
                                <h5 class = "text-dark">
									Reason for closing
                                </h5>
                            </div>
                            <div class="col-md-12"> 
                                @foreach($close_job_messages as $close_job_message)
                                <div class="radio">
                                    <input id="close-job-form-radio-{!! $close_job_message->id  !!}" class="magic-radio close_form_radio" type="radio" name="reason" value = "{!! $close_job_message->id !!}">
                                    <label for="close-job-form-radio-{!! $close_job_message->id  !!}">{!! $close_job_message->reason !!}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group clearfix">
                                <div class="col-sm-12 mar-top action_btn_group"> 
                                    <button type="button" disabled class="btn btn-mint close_gig_form"> Yes. Close a Gig </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
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
	var close_job_id  = ""; 
	function closeformAction(){
        var flag = 0; 
        $(".close_form_radio").each(function(){
            if($(this).prop("checked")){
                flag = 1;
            }
        });
        if(flag)
            $(".close_gig_form").prop('disabled', false);
        else
            $(".close_gig_form").prop('disabled', true);
    }
	$(document).on("click", ".close_form_radio", function(){
        closeformAction();
    });
	$(".remove_gig_posting").click(function(){
		$('#closeActionForm')[0].reset();
		close_job_id = $(this).attr('data-gig-serial');
		closeformAction();
		$("#CloseActionModal").modal("show");
	}); 
	$(".close_gig_form").click(function(){
        var url = "{{route('employer.jobs.close',  ':request_type' ) }}";  
        url     =  url.replace(':request_type',  close_job_id); 
        $.ajax({  
            url:   url, 
            type: 'POST',
            data:  $("#closeActionForm").serialize(),
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