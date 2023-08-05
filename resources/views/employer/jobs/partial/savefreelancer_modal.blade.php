<!-- Profile Item Stuff --> 
<div class="modal fade" id="addSaveFreelancerModal" tabindex="-1" role="dialog" aria-labelledby="addSaveFreelancerModal" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="verifyprofilepicModalLabel">Save Freelancer</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"> 
			</div>
		</div>
  	</div>
</div>

@push('partialscripts')
<script>
    /***********************************  Save Freelancer Action  *************************************/
    $(document).on("click", ".save_freelancer_action", function(){  
        var user_id  =  $(this).closest(".action_btn_group").attr('data-serial');  
        $.ajax({
            url: "{{ route('employer.jobs.getsavefreelancer') }}", 
            type: 'POST',
            dataType: 'json',
            data: {user_id: user_id },     
            beforeSend: function () { 
            },                                        
            success: function(json) {
                if(json.status == 1){
                    $("#addSaveFreelancerModal .modal-body").html( json.html );
                    $("#addSaveFreelancerModal").modal("show");
                }
                else{
                    swal({
                        title: "Error Occured",   
                        text:   json.msg, 
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
    $(document).on("click", ".add_savefreelancer", function(){
        $.ajax({
            url:   "{!!  route('employer.jobs.addsavefreelancer')  !!}",
            type: 'POST',
            data:  $("#SaveFreelancerForm").serialize(),
            dataType: 'json',
            beforeSend: function (){
            },
            success: function(json){
                if(json.status){
                    location.reload();
                }
                else{
                    $.niftyNoty({
                        type: 		'danger',
                        icon : 		'fa fa-check',
                        message : 	 json.msg,
                        container : 'floating',
                        timer : 5000
                    });
                }
            },
            complete: function () {
            },
            error: function() {
            }
        });  
    }); 
    $(document).on("keyup", "#SaveFreelancerForm textarea[name = 'notes']", function(){
        $(".add_savefreelancer").prop("disabled", false);
    });
    $(document).on("click", ".remove_savefreelancer", function(){
        var user_id  =  $(this).closest(".action_btn_group").attr('data-serial');
        $.ajax({
            url: "{{ route('employer.jobs.removesavefreelancer') }}", 
            type: 'POST',
            dataType: 'json',
            data: {user_id: user_id },     
            beforeSend: function () { 
            },                                        
            success: function(json) {
                if(json.status == 1){
                    location.reload();
                }
                else{
                    swal({
                        title: "Error Occured",   
                        text:   json.msg, 
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