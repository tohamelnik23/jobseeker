@push('partialscripts')

	<script>
	@if(isset($job))
		// short list stuff
		$(document).on("click", ".shortlistaction", function(){
			var user_id  =  $(this).closest(".action_btn_group").attr('data-serial');
			var request_type = "add"; 
			if($(this).hasClass("actives"))
				request_type = "remove"; 
			$.ajax({
				url: "{{ route('employer.jobs.shortlistaction', $job->serial) }}", 
				type: 'POST',
				dataType: 'json',
				data: {user_id: user_id, request_type: request_type },
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

		//archive action
		$(document).on("click", ".archiveaction", function(){
			var user_id  =  $(this).closest(".action_btn_group").attr('data-serial');
			var request_type = "add"; 
			if($(this).hasClass("actives"))
				request_type = "remove";   
			$.ajax({
				url: "{{ route('employer.jobs.archiveaction', $job->serial) }}", 
				type: 'POST',
				dataType: 'json',
				data: {user_id: user_id, request_type: request_type },
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
	@endif
	</script>

@endpush