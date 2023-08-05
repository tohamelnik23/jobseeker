@extends('layouts.app')
@section('title', 'Search Experts')
@section('css') 
@endsection
@section('content')
<div class="container">
    <div class = "row">
        @if($tab_type == "search")
            <div class = "col-lg-2 hidden">
                <form    method = "GET"  action = "{{ route('search.employee') }}">  
                    <input id = "query_filter_zipcode"  type = "hidden"  name = "zip_code" /> 
                    <input id = "query_filter_category" type = "hidden"  name = "category" />   
                    <button  class="hidden searchbutton" type="submit"> Search </button> 
                </form>
            </div>
        @endif 
        <div class = "@if($tab_type == 'search') col-lg-12 @else  col-lg-12  @endif">
            <div class = "card">
                <div class = "card-body pad-no">
                    <header class = "pad-all bord-btm clearfix"> 
                        @if($show_zipcode)
                            <div class = "col-md-12 d-flex">
                                <div class="input-group mar-btm flex-1">
                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                    <input type="text" class="form-control search_input" name = "zipcode" placeholder="ZipCode" @if(isset($zip_code)) value = "{!! $zip_code !!}" @endif>
                                </div>
                                <div class="input-group mar-btm flex-2">
                                    <select class = "form-control search_input" name = "category">
                                        @foreach($categories as $category)
                                            @if($category->calculate_subcategories())
                                                <option value = "{!! $category->serial !!}"  @if($cur_category == $category->id) selected @endif >{!! $category->industry !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint search_button" type="button"><i class = "fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div> 
                        @else 
                            <div class = "col-md-12"> 
                                <div class="input-group mar-btm">
                                    <select class = "form-control search_input" name = "category">
                                        @foreach($categories as $category)
                                            @if($category->calculate_subcategories())
                                                <option value = "{!! $category->serial !!}"  @if($cur_category == $category->id) selected @endif >{!! $category->industry !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-mint search_button" type="button"><i class = "fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </header>
                    <div class = "job-result-page">
                        <div class = "row">
                            <div class = "col-md-12">
                                @forelse($users as $freelancer)
                                    @php  
                                        $profile_freelancer =  $freelancer->getRightProfile( $cur_category, 'category');  
                                    @endphp
                                    <section class = "freelancer_item_card">
                                        <div class = "freelancer_item_result row"> 
                                            <div class = "col-md-2 col-sm-3">
                                                <div class = "row">  
                                                    <div class="col-md-12"> 
                                                        <a target = "_blank" href="{!!   route('freelancers.detail', $freelancer->serial) !!}"> 
                                                            <img  class="img-circle img-md" src = "{{$freelancer->getImage()}}"> 
                                                        </a>   
                                                    </div> 
                                                </div>
                                            </div> 
                                            <div class = "col-md-10 col-sm-9">
                                                <div class = "row"> 
                                                    <div class="@if(Auth::check() && (Auth::user()->role == 2))  col-xs-12 col-md-6 col-lg-7 @else   col-xs-12 col-md-12 col-lg-12 @endif"> 
                                                        <h5 class="m-0-top-bottom display-inline-block"> 
                                                            <a target = "_blank" href="{!!  route('freelancers.detail', $freelancer->serial) !!}"   class="freelancer-tile-name" title="{!! $freelancer->name !!}"> 
                                                                <span class = " text-mint">{!! $freelancer->name !!}</span> 
                                                            </a>
                                                        </h5>
                                                        <h5 class="m-0 freelancer-tile-title text-dark pt-15">{!! $profile_freelancer->role_title !!}</h5> 
                                                        <p class = "text-dark"> {!! Mainhelper::getStateFromValue($freelancer->state)   !!} </p> 
                                                    </div>

                                                    @if(Auth::check() && (Auth::user()->role == 2))  
                                                        <div class="text-right col-md-6 col-lg-5">
                                                            <div class = "action_btn_group" data-serial = "{!! $freelancer->serial !!}">
                                                                <div class = "col-md-4 col-sm-6">
                                                                    @php
                                                                        $checkSaved = $freelancer->checkSaved( Auth::user()->id );
                                                                    @endphp
                                                                    <button class="btn   @if($checkSaved) remove_savefreelancer @else save_freelancer_action    @endif  btn-default btn-card-button btn-rounded" type = "button">
                                                                        <span class = "text-mint"> 
                                                                            @if($checkSaved)
                                                                                <i class = "fa fa-heart"></i>
                                                                            @else
                                                                                <i class = "fa fa-heart-o"></i>
                                                                            @endif
                                                                        </span>
                                                                    </button>
                                                                </div> 
                                                                <div class = "col-md-8 col-sm-6">
                                                                    <button class="btn btn-block  btn-mint  btn-card-button" type = "button">
                                                                        Invite to Gig  
                                                                    </button>
                                                                </div>  
                                                            </div>
                                                        </div> 
                                                    @endif

                                                </div>
                                            </div> 
                                        </div>
                                        <div class = "freelancer_item_result row">
                                            <div class = "col-xs-12">
                                                <div class  = "row">
                                                    <div class = "col-xs-12">
                                                        <h5 class = "text-dark">{!! $profile_freelancer->getHourlyDescription() !!} </h5> 
                                                    </div>
                                                </div>
                                                <div class = "row">
                                                    <div class = "col-xs-12"> 
                                                        <div class = "text-dark ellipsis-multiline mar-top-5"> 
                                                            <span class="pt-14 text-dark ">
                                                                {{ $profile_freelancer->description }}
                                                            </span>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div> 
                                            @php
                                                $role_skills = $profile_freelancer->getRoleSkills(); 
                                            @endphp 
                                            @if(count($role_skills))
                                                <div class = "col-xs-12 mar-top"> 
                                                    @foreach($role_skills as $role_skill_index => $role_skill)
                                                        @if($role_skill_index <= 3) 
                                                            <span class="badge pad-rgt">{!! $role_skill->skill !!}</span>
                                                        @endif
                                                    @endforeach 
                                                    @if( count($role_skills) > 3)
                                                        <a target = "_blank" href="{!!  route('freelancers.detail', $freelancer->serial) !!}" class="btn-link text-mint text-bold pad-rgt">{!! ( count($role_skills) - 3 ) !!} more</a>
                                                    @endif
                                                </div>
                                            @endif 
                                        </div>
                                    </section>
                                @empty
                                    <div class = "clearfix  text-center">
                                        <img src = "{!! asset('img/background/no-search-result.png') !!}" width = "20%" />
                                        <h3 class = "text-dark"> There are no results that match your search </h3>
                                        <p class = "pt-15 mar-btm pad-btm"> Please try adjusting your search keywords or filters </p>
                                    </div>
                                @endforelse
                            </div> 
                            @if(0)
                                @if($users->hasPages())
                                    <div class = "col-md-12 mar-top text-right pad-hor">
                                        {!! $users->links() !!}
                                    </div>
                                @endif 
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

@if(Auth::check() && (Auth::user()->role == 2))  
    @include('employer.jobs.partial.savefreelancer_modal') 
@endif

@endsection
@section('javascript')
<script>
    $(document).on("click", ".btn-description-action" , function(){
        var obj = $(this).closest(".freelancer_item_card");
        obj.find(".description-part").addClass("hidden"); 
        if($(this).hasClass("more")){
            obj.find(".description-detaillist").removeClass("hidden");
        }
        if($(this).hasClass("less")){
            obj.find(".description-shortlist").removeClass("hidden");
        } 
    });  
    $(document).on("click", ".filter_action", function(){
        $(".searchbutton").trigger("click");
    }); 

    function search_action_input_val(){
        var search_zipcode =  $.trim($("input[ name = 'zipcode']").val());
        $("#query_filter_zipcode").val(search_zipcode); 
        $("#query_filter_category").val( $("select[name = 'category'] option:selected").val() );
    }

    function search_action_input(){
        search_action_input_val();
        $(".searchbutton").trigger("click");
    } 
    $('.search_input').keydown(function (e){
        if(e.keyCode == 13){
            search_action_input();
        }
    });
    search_action_input_val(); 
    $(".search_button").click(function(){
        search_action_input();
    });
    /**************************************************/
    $("select[name = 'category']").change(function(){
        search_action_input();
    });
</script>
@stop