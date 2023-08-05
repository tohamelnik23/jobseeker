@push('partialscripts')
<script>
     /************************************** Invite action  ************************************************/ 
    $(document).on("click", ".btn-invite-action", function(){ 
        var user_id  =  $(this).closest(".action_btn_group").attr('data-serial'); 
        $.ajax({
            url: "{{ route('employer.jobs.inviteaction', $job->serial) }}", 
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