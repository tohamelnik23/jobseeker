<div class="modal fade" id = "changeDisctanceModal" tabindex="-1" role="dialog" aria-labelledby="changeDisctanceModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light bord-btm">
                <h4 class="modal-title text-dark pt-20"> Change Disired Distance </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>                                              
            <div class="modal-body">  
                <div class = "pad-all">  
                    <form id = "changeDisctanceForm"  method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class = "row">
                            <div class = "col-md-6">
                                <p class = "text-dark pt-15">
                                    What distance are you willing to travel (miles)? Knowing when you are available location helps DiscoverGigs find the right gigs for you. 
                                </p>
                            </div>  
                            <div class = "col-md-6">
                                <div class = "row">
                                    <div class = "col-md-12">
                                        <label class = "text-dark pt-15 text-bold mar-btm-5"> I wish </label>
                                    </div>
                                    <div class = "col-md-12 distance_group"> 
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
                                            <button type="button"  class = "save_disired_distance btn btn-mint btn-lg "> Save  </button>
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
    function showDistanceItem(){
        var data_type = $(".ditance-button.btn-mint").attr('data-type'); 
        if(data_type == "any_distance"){
            $(".localwork_miles").addClass("hidden");
            $("#changeDisctanceForm input[name = 'travel_distance']").prop('disabled', true);
        }
        else{
            $(".localwork_miles").removeClass("hidden");
            $("#changeDisctanceForm input[name = 'travel_distance']").prop('disabled', false);
        }

        var slider = document.getElementById("customRange");
		var output = document.getElementById("demo"); 
		if(slider){
			output.innerHTML = slider.value; 
			slider.oninput = function() {
			    output.innerHTML = this.value;
			}
		}
    }
    $(".disired_travel_distance-button").click(function(){  
        $.ajax({
            url: "{{ route('employee.get.traveldistance') }}", 
            type: 'POST',
            dataType: 'json', 
            beforeSend: function () {
            },                                        
            success: function(json) {
                if(json.status == 1){
                    $(".distance_group").html(json.html);
                    showDistanceItem();
                    $("#changeDisctanceModal").modal('show');
                }                   
            },
            complete: function () { 
            },
            error: function(){
            }
        }); 
    }); 
    $(document).on( "click", ".ditance-button.btn-default",  function(){
        $(".ditance-button").addClass("btn-default").removeClass("btn-mint");
        $(this).removeClass("btn-default").addClass("btn-mint");
        showDistanceItem();
    });

    $(document).on("click", ".save_disired_distance", function(){ 
        var dataS = $("#changeDisctanceForm").serialize(); 
        $.ajax({
            type: "POST",
            url:  "{!! route('employee.update.traveldistance') !!}",
            data: dataS,
            success: function(data) { 
                if(data.error == true){ 
                }else{  
                    location.reload(); 
                }
            } 
        });  
    });

</script>
@endpush