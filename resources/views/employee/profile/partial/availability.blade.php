<div class="btn-group btn-group-toggle">
    <button class="btn available-button  @if($user->availability == "active") btn-mint @else btn-default @endif" data-type = "active"  type="button"  > <span class = "pt-16"> Active </span></button>
    <button class="btn available-button   m-0-left @if($user->availability == "active") btn-default @else btn-mint @endif" data-type = "inactive" type="button"> <span class = "pt-16"> Inactive </span></button> 
</div>