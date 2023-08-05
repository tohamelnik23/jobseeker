<div class = "form-group  custom_question_item  mar-no clearfix @if(isset($notype)) add  @else edit @endif" style = "margin: 0px !important;">
    <div class = "label_part clearfix">
        @if(isset($notype))
            <div class="col-md-12 mar-top-5">
                <a href="javascript:void(0)" class="text-mint text-bold pt-12 custome_question_button add">
                    <i class="fa fa-plus"> </i> 
                    WRITE YOUR OWN QUESTION
                </a> 
            </div>
        @else
            <div class="col-md-12 mar-top-5">
                <span class = "question_content_label text-dark">@if(isset($custom_question)){!! nl2br($custom_question->question) !!}@endif</span>
                <a href="javascript:void(0)" class="text-mint pad-lft btn btn-default btn-rounded pt-15 custome_question_button edit">
                    <i class="fa fa-pencil"> </i>  
                </a> 
            </div>
        @endif
    </div>

    <div class = "edit_part  hidden clearfix mar-top-5">
        <div class = "col-xs-8">
            <textarea class = "form-control question_content" rows = 5>@if(isset($custom_question)){!!  $custom_question->question !!}@endif</textarea> 
            <span class="help-block">             
                <strong></strong>  
            </span> 
        </div>
        <div class = "col-xs-4">
            <a href = "javascript:void(0)" class = "text-danger icon-2x remove_question @if(isset($notype)) add  @else edit @endif "> <i class = "fa fa-trash"></i></a>
        </div>
        <div class  = "col-xs-12 mar-top">
            <button class = "btn btn-mint  @if(isset($notype)) add  @else edit @endif save_question" type = "button"> Save Question </button>
        </div>
    </div>
</div>