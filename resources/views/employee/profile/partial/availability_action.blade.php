<div class="modal fade" id = "changeAvailabilityModal" tabindex="-1" role="dialog" aria-labelledby="CloseActionModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light bord-btm">
                <h4 class="modal-title text-dark pt-20"> Change Availability </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>                                              
            <div class="modal-body">  
                <div class = "pad-all">  
                    <form id = "changeAvailabilityForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class = "row">
                            <div class = "col-md-6">
                                <p class = "text-dark pt-15">
                                    Are you available to  work? Knowing when you are available helps DiscoverGigs find the right gigs for you. 
                                </p>
                            </div>  
                            <div class = "col-md-6">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <label class = "text-dark pt-15 text-bold mar-btm-5"> I am curently  </label>
                                    </div>
                                    <div class = "col-md-12 availability_group">
                                        
                                    </div>
                                </div>                                
                            </div>   
                        </div> 
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="form-group clearfix text-right">
                                    <div class="col-sm-12 mar-top">  
                                        <div class = "btn-group btn-group-lg">
                                            <button type="button" class="btn btn-default btn-lg  btn-secondary" data-dismiss="modal">Cancel</button> 
                                            <button type="button"  class = "save_availability btn btn-mint btn-lg "> Save  </button>
                                        </div>
                                    </div>
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
        $(".availability-button").click(function(){
            $.ajax({
                url: "{{ route('employee.get.availability') }}", 
                type: 'POST',
                dataType: 'json', 
                beforeSend: function () {
                },                                        
                success: function(json) {
                    if(json.status == 1){
                        $(".availability_group").html(json.html);
                        $("#changeAvailabilityModal").modal('show');
                    }                   
                },
                complete: function () { 
                },
                error: function(){
                }
            });           
        });

        $(document).on( "click", ".available-button.btn-default",  function(){
            $(".available-button").addClass("btn-default").removeClass("btn-mint");
            $(this).removeClass("btn-default").addClass("btn-mint"); 
        });

        $(document).on("click", ".save_availability", function(){
            var data_type = $(".available-button.btn-mint").attr('data-type');
            $.ajax({
                url: "{{ route('employee.update.availability') }}", 
                type: 'POST',
                data: {availiability : data_type },
                dataType: 'json',
                beforeSend: function () {
                },
                success: function(json) {       
                    if(json.status == 1){
                        location.reload();
                    }
                },
                complete: function () { 
                },
                error: function(){
                }
            });
        });
        
    </script>
@endpush