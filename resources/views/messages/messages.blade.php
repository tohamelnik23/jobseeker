@extends('layouts.app')
@section('title', 'Proposal Details') 
@section('css') 
@endsection
@section('content')
<div class="container full-way-container">
    <div class="page-fixedbar-container">
        <div class="page-fixedbar-content bord-top">
            <div class="nano">
                <div class="nano-content">
                    <div class="pad-all bord-btm">
                        <input type="text" placeholder="Search or start new chat" class="form-control">
                    </div> 
                    <div class="chat-user-list">
                        @include('messages.partial.chat_user_list') 
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <div id="page-content">
        <div class="panel mar-no">
            <div class="media-block chat-header pad-all bg-light bord-btm bord-top">
                <!--div class="pull-right">
                    <div class="btn-group dropdown">
                        <a href="#" class="dropdown-toggle btn btn-trans" data-toggle="dropdown" aria-expanded="false"><i class="pci-ver-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right" style="">
                            <li><a href="#"><i class="icon-lg icon-fw demo-psi-pen-5"></i> Edit</a></li>
                            <li><a href="#"><i class="icon-lg icon-fw demo-pli-recycling"></i> Remove</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="icon-lg icon-fw demo-pli-mail"></i> Send a Message</a></li>
                            <li><a href="#"><i class="icon-lg icon-fw demo-pli-calendar-4"></i> View Details</a></li>
                            <li><a href="#"><i class="icon-lg icon-fw demo-pli-lock-user"></i> Lock</a></li>
                        </ul>
                    </div>
                </div--> 
            </div> 
            <div class="nano nano-message-scroll" style="height: calc(100vh - 200px);">
                <div class="nano-content">
                    <div class="panel-body chat-body media-block" style = "min-height: 100%;">  
                        @forelse($message_lists as $message_list)
                            <div class = "chat-body-item {!! $message_list->serial !!} hidden" data-serial = "{!! $message_list->serial  !!}">
                                <div class="text-center text-bold"><a href = "#" class = "btn-link text-mint load_earlier_btn hidden"> Load earlier message ... </a></div>
                                <div class = "chat-body-item-content">
                                </div>
                            </div>
                        @empty
                        @endforelse
                        <div class = "chat-body-item  no-message">
                            <h3 class = "text-center" style = "margin-top: 40px;"> No Messages </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pad-all bg-light">
                <div class="input-group send-chart-group hidden">
                    <input type="text" placeholder="Type your message" class="form-control" id = "sendMessageBox">
                    <span class="input-group-btn">
                        <!--button class="btn btn-icon add-tooltip" data-original-title="Add file" type="button"><i class="demo-psi-paperclip icon-lg"></i></button>
                        <button class="btn btn-icon add-tooltip" data-original-title="Emoticons" type="button"><i class="demo-pli-smile icon-lg"></i></button -->
                        <button class="btn btn-mint add-tooltip sendmessage_button" data-original-title="Send" type="button">
                            <i class="demo-pli-paper-plane icon-lg"></i> Send
                        </button>
                    </span>
                </div>
            </div>
        </div> 
    </div> 
</div>      
@endsection
@section('javascript')
<script>
    $(document).ready(function(){ 
        var timeoutID; 
        function startMessageTimer(){
            timeoutID = window.setInterval(goMessageInactive, 1000);
        } 
        startMessageTimer();

        function resetMessageTimer(){
            window.clearInterval(timeoutID);    
            goMessageActive();
        }
        function goMessageActive(){  
            startMessageTimer(); 													
        }
        function goMessageInactive(){
            var message_list   =   $('.chat-item-user.init-active').attr('data-list');
            var obj            =   $(".chat-body-item." + message_list); 
            var last_message   =   obj.find(".chart-message-item").last();
            showMessage(message_list, 'refresh', last_message.attr('data-serial'));   
        }              
        function startMessageTimer() { 
            timeoutID = window.setInterval(goMessageInactive, 2000);
        }
        ////////////////////////////////////////////// MessageList //////////////////
        var timeoutUserListID = window.setInterval(goUserListInactive, 11000); 
        function goUserListInactive(){
            $.ajax({
                url: "{{ route('getuserlist') }}", 
                type: 'POST',
                dataType: 'json', 
                beforeSend: function () { 
                },                                        
                success: function(json) {
                    if(json.status == 1){
                        $(".u-message-alert").html(json.total_new_messages);
                        if(json.total_new_messages){ 
                            $(".u-message-alert").addClass("active");
                        }
                        else{ 
                            $(".u-message-alert").removeClass("active");
                        }

                        $(".chat-user-list").html( json.message_html );
                    }
                },
                complete: function () { 
                },
                error: function(){
                }
            });
        }
        //////////////////////////////////////////////////////////////////////////////
        function initMessage(){
            $("#sendMessageBox").val("");
            $(".first_be_click").trigger("click");
            $(".first_be_click").removeClass("first_be_click"); 
            initMessageBox();
        } 
        function initMessageBox(){
            if( $.trim($("#sendMessageBox").val()) !== "")
                $(".sendmessage_button").prop("disabled", false);
            else
                $(".sendmessage_button").prop("disabled", true); 
        }        
        $("#sendMessageBox").keyup(function(e){ 
            var code = e.key;
            if(code==="Enter"){
                $(".sendmessage_button").trigger("click");
            }
            initMessageBox();
        });
        function showMessage(message_list, message_type, offset = 0, content  = null){
            var obj             =  $(".chat-item-user." + message_list);
            var chat_body_item  =  $(".chat-body-item." + message_list); 

            if(message_type != "refresh"){
                resetMessageTimer();
            }

            $.ajax({
                url: "{{ route('get_message_content') }}", 
                type: 'POST',
                dataType: 'json',
                data: {message_list: message_list, request_type: message_type,  request_offset: offset, content: content},     
                beforeSend: function () { 
                },                                        
                success: function(json) {
                    if(json.status == 1){
                        $(".chat-body-item.no-message").addClass("hidden");
                        $(".send-chart-group ").removeClass("hidden");
                        
                        if(json.result.length){ 
                            for(var i = 0; i <json.result.length; i++){ 
                                if(!chat_body_item.find(".chat-meta-day." + json.result[i].key).length){ 
                                    if((message_type == "init") || (message_type == "old")){
                                        chat_body_item.find(".chat-body-item-content").prepend(json.result[i].date); 
                                    }else{
                                        chat_body_item.find(".chat-body-item-content").append(json.result[i].date);
                                    } 
                                    chat_body_item.find(".chat-meta-day." + json.result[i].key).after(json.result[i].value);
                                }
                                else{
                                    if((message_type == "init") || (message_type == "old"))
                                        chat_body_item.find(".chart-message-item." +  json.result[i].key).first().before(json.result[i].value);
                                    else
                                        chat_body_item.find(".chart-message-item." +  json.result[i].key).last().after(json.result[i].value);
                                }
                            } 
                        }
                        obj.find(".message_list_total_messages").html(json.new_messages);

                        if(message_type == "init"){
                            $(".chat-header").html(json.header);
                        }

                        if(json.new_message){
                            obj.addClass("chat-unread");
                            obj.find(".message_list_new_stuff").addClass("active");
                        }
                        else{
                            obj.removeClass("chat-unread");
                            obj.find(".message_list_new_stuff").removeClass("active");
                        }
                         
                        if((message_type == "init") || (message_type == "old")){
                            if(json.old_message_flag)
                                $(".load_earlier_btn").removeClass("hidden");
                            else
                                $(".load_earlier_btn").addClass("hidden");
                        }  
                        if(message_type == "add")
                            $("#sendMessageBox").val(""); 
                        obj.addClass("init-active"); 
                        //scroll bar
                        if((message_type == "init") || (message_type == "add")|| (message_type == "refresh")){
                            $(".nano-message-scroll").nanoScroller();
                            $(".nano-message-scroll").nanoScroller({ scroll: 'bottom' });
                        } 
                        if((message_type == "refresh") || (message_type == "add")){
                            $(".chat-item-user.active").html( json.message_user_list );
                        }
                        

                        $(".u-message-alert").html(json.total_new_messages);
                        if(json.total_new_messages){ 
                            $(".u-message-alert").addClass("active");
                        }
                        else{ 
                            $(".u-message-alert").removeClass("active");
                        }

                        if(message_type != "refresh"){
                            resetMessageTimer();
                        } 
                    }
                },
                complete: function () { 
                },
                error: function() { 
                }
            });
        }
        $(document).on("click", ".chat-item-user", function(event){
            event.preventDefault(); 
            var message_list    =   $(this).attr('data-list');
            var chat_body_item  =   $(".chat-body-item." + message_list);
            $(".chat-body-item").addClass("hidden");
            chat_body_item.removeClass("hidden");

            $(".chat-item-user").removeClass("active");
            $(this).addClass("active");

            if(!$(this).hasClass('init-active'))
                showMessage(message_list, 'init');  

            window.history.pushState("", "", '{!! route("messages") !!}' + '?room=' + message_list); 
        });
        $(document).on("click", ".load_earlier_btn", function(){
            var obj             =   $(this).closest(".chat-body-item");
            var message_list    =   obj.attr('data-serial');
            var first_message   =   obj.find(".chart-message-item").first(); 
            showMessage(message_list, 'old', first_message.attr('data-serial'));  
        });
        $(".sendmessage_button").click(function(){
            var content =  $("#sendMessageBox").val();
            if($.trim(content) !== ""){
                if($('.chat-item-user.init-active').length){
                    var message_list   =   $('.chat-item-user.init-active').attr('data-list'); 
                    var obj            =   $(".chat-body-item." + message_list); 
                    var last_message   =   obj.find(".chart-message-item").last();
                    showMessage(message_list, 'add', last_message.attr('data-serial'), $.trim(content));   
                }
            }
        }); 
        initMessage();
    });
</script>
@stop
