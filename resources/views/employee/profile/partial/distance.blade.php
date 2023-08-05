<div class="btn-group btn-group-toggle">
    <button class="btn ditance-button @if($user->accounts->travel_distance) btn-default @else btn-mint @endif" data-type = "any_distance"  type="button"  > <span class = "pt-16"> Any Distance  </span></button>
    <button class="btn ditance-button   m-0-left @if($user->accounts->travel_distance) btn-mint @else btn-default @endif " data-type = "local_work" type="button"> <span class = "pt-16"> Local Work  </span></button> 
</div>
<div class = "localwork_miles  hidden">
    <div class = "col-md-12 mar-top">
        <input name="travel_distance" type="range" class="form-control-range" id="customRange" value="{{($user->accounts->travel_distance!='NULL')?$user->accounts->travel_distance:'26'}}" min="0" max = "50" step="2">
    </div> 
    <div class = "col-md-12">
        <span class="float-left mr-2" style="font-weight: 700!important; font-size:18px;">0</span>
        <span  class="float-right" style="font-weight: 700!important; font-size:18px;">50</span>
    </div> 
    <div class = "col-md-12 text-center">
        <p class = "h5"><span id="demo"></span> Miles</p>
    </div> 
</div>