<form id = "submit_work_detail"  method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class = "row">
        <div class = "col-md-12">
            <p class = "text-dark pt-14"> Use this form to request approval for the work you've completed. Your payment will be released upon approval. </p>
        </div> 
        <div class = "col-md-12">
            <h5 class = "text-dark">
                Milestone
            </h5>
            <p class = "text-dark"> 
                {!! $milestone->headline !!}
            </p>
        </div>  
        <div class = "col-md-12">
            <h5 class = "text-dark">
                Amount
            </h5> 
        </div>  
        <div class = "col-md-12">    
            <div class = "row">
                <div class="col-sm-4 input-group "> 
                    <span class="input-group-addon text-dark"><i class="glyphicon glyphicon-usd"></i></span>
                    <input type="text" class="form-control hgt-35 text-right decimal-input submit_work_control edit" name = "submit_amount" placeholder = "" value = "{!! $milestone->amount !!}">  
                </div>
            </div>
        </div>
        <div class = "col-md-12 clearfix mar-top">
            <h5 class = "text-dark">
                Message
            </h5>
            <p class = "text-dark"> 
                Add an optional message to share with the client when we notify them that this invitation has been withdrawn.
            </p>
        </div> 
        <div class="col-sm-12">
            <textarea name = "submit_message" class="form-control submit_work_control" placeholder = "Describe the work you've completed for this milestone"  maxlenght = 5000 rows="6"></textarea>
            <span class="help-block"> 
                <strong></strong>  
            </span>
        </div> 
        <input type = "hidden" name = "milestone_id" value = "{!! $milestone->serial !!}" /> 
        <div class="form-group clearfix">
            <div class="col-sm-12 mar-top action_btn_group"> 
                <button type="button" disabled class="btn btn-mint request_submit_work"> Submit  </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
            </div>
        </div> 
    </div> 
</form>