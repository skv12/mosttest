@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <x-chat-list/>
        </div>
        <div class="col-md-7">
            <x-chat-messagger/>
        </div>
        <div class="col-md-2">
            <x-users-list/>
        </div>
    </div>   
</div>
<script type="module">
$(document).ready(function() {
    
    // Variables
    var activeChat;
    var prevChat = -1;
    var currentUser = $("meta[name=user-id").attr('content');
    var typing = false;
    var typingTimer;
    var messageBox = $('.messages-box');
    var userList = $('#userList');
    var chatList = $('#chatList');

    /* Functions */

    // Render messages
    function addMessage(message, messageUser){
        var messageMarginStartStyle = ' ms-auto ';
        var messageBgStyle = 'bg-primary ';
        var messageTextStyle = ' text-white ';
        var messageStatus = $('<span style="font-size: 18px;" id=status-' + message.id + '" message-count="' + message.is_seen.length + '" class="messageStatus text-muted position-absolute end-0">&diams;</span>');
        if(message.user_id != currentUser){
            messageMarginStartStyle = '';
            messageBgStyle = 'bg-light ';
            messageTextStyle = ' text-muted';
            messageStatus = $('<span><span>');
        }
        var el = $('<div id="' + (message.id) + '" class="media message w-50 ' + messageMarginStartStyle + 'mb-3"><div class="media-body">' + messageUser + '<div class="'+ messageBgStyle +' rounded py-2 px-3 mb-2"><p class="text-small mb-0' + messageTextStyle +'">' + message.message + '</p></div><div style="position: relative"><span class="small text-muted">' + window.format(new Date(message.created_at), 'dd.MM.yy | hh:mm') + '</span></div></div></div>'); 
        messageBox.append(el);
        el.children().children().last().append(messageStatus);
        var seen = false;
        message.is_seen.forEach((element, index) => {           
            if(element.user_id == currentUser){
                seen = true;
            }
        });
        if(!seen){
           messageSeen(message);
        }
        if(message.is_seen.length >= userList.children().length){
            el.children().children().children().last().removeClass('text-muted text-primary');
            el.children().children().children().last().addClass('text-success');
        }
        else if(message.is_seen.length < userList.children().length && message.is_seen.length > 1){
            el.children().children().children().last().removeClass('text-muted text-success');
            el.children().children().children().last().addClass('text-primary');
        }
        else{
            el.children().children().children().last().removeClass('text-success text-primary');
            el.children().children().children().last().addClass('text-muted');
            
        }

    }

    // Render user
    function addUserToUserList(user){
        var userOnlineStyle = '';
        if(user.is_online){
            userOnlineStyle = ' bg-success text-white';
        }
        userList.append('<div class="list-group-item list-group-item-action rounded-0 ' + userOnlineStyle +'" id="user-' + user.id +'"><h6 class="mb-0">' + user.name + '</h6></div>');
    }

    // Render chats on chat list
    function addChat(chat, first = false){
        var activeStyle = '';
        if(first){
            activeStyle = 'active';
        }
        chat.users.forEach(user => {
            if(user.id == currentUser){
                chatList.append('<a href="#' + chat.id +'"id="' + chat.id +'" class="openChat list-group-item list-group-item-action rounded-0 ' + activeStyle + '"><div class="media"><div class="media-body ml-4"><div class="d-flex align-items-center justify-content-between mb-1"><h6 class="mb-0">' + chat.name + '</h6></div><p class="font-italic text-muted mb-0 text-small" id="chatLastMessage"></p></div></div></a>');
            }
         });
        }

    // Scroll message box if open chat or add messages
    function updateScroll(element){
        element.animate({ scrollTop: element.prop("scrollHeight")}, 350);
    }

    function getChats(user){
        axios.get('/chats', 
        ).then(function (response){
            $('#chatName').html(response.data.name);
            response.data.forEach((chat, index) => {
                if(index == 0){
                    addChat(chat, true);
                    $('#chatID').val(chat.id);
                    activeChat = $('#chatID').val();
                    connectChat(activeChat);
                }    
                else addChat(chat);
            });
        }).catch(function (error){
            console.log(error);
        });
    }
    function getUsers(){
        axios.get('/users/', 
        ).then(function (response){
            
            response.data.forEach(e => {
                if(e.id == currentUser)
                    $('#selectUsers').append('<option value="' + e.id +'"selected hidden>' + e.name + '</option>');
                else $('#selectUsers').append('<option value="' + e.id +'">' + e.name + '</option>');
            });
        }).catch(function (error){
            console.log(error);
        });
    }
    function getMessages(chat){
        axios.get('/chats/' + chat, 
        ).then(function (response){
            messageBox.empty();
            userList.empty();
            $('#chatName').html(response.data.chat.name);
            response.data.chat.users.forEach(user => {
                addUserToUserList(user);
            });
            response.data.messages.forEach(message => {
                addMessage(message, message.username);
            });
            
            updateScroll(messageBox);
        }).catch(function (error){
            console.log(error);
        });
    }

    function messageSeen(message){
        axios.post('/message', {
                user_id: currentUser,
                message_id: message.id
        }).then(function (response){
        }).catch(function (error){
            console.log(error);
        });
    }

    /* Channels  */

    // Connect to chat
    function connectChat(chat_id){
        window.Echo.private('chat.' + chat_id)
        .listen('MessageSent', function (event){
            addMessage(event.message, event.user.name);
            updateScroll(messageBox);
        })
        .listenForWhisper('typing', function (event){
            var typingUser = event.username;
            var typing = event.typing;
            if(typing) {
                $('#chatUser').html(typingUser);
                $('#chatStatus').show();
            }
            setTimeout(function(){
                $('#chatStatus').hide();
                typing = false;
            }, 1000);
            });
        console.log('connected to chat ' + chat_id);
        getMessages(chat_id);
    }

    // Listen if chat is created
    window.Echo.private('user.chats')
        .listen('ChatCreated', function (event){
            addChat(event.chat);
        });

    window.Echo.private('message.seen')
        .listen('MessageSeen', function (event){
            console.log(event); 
        });

    // Listen if chat is created
    window.Echo.private('users.status')
        .listen('UserStatus', function (event){
            if(event.message == 'login')
                $('#user-'+ event.user.id).addClass('bg-success text-white');
            else if(event.message == 'logout')
            $('#user-'+ event.user.id).removeClass('bg-success text-white');
        });
    
    // If chat changed
    $('#chatID').on('change', function(){
        prevChat = activeChat;
        activeChat = $('#chatID').val();
        window.Echo.leaveChannel('chat.' + prevChat);
        console.log('leaved chat '+ prevChat);
        connectChat(activeChat);
    });

    // Send typing status
    $("#message").keydown(function(e) {
        window.Echo.private('chat.'+ activeChat)
            .whisper('typing', {
                user: currentUser,
                username:  "<?php echo Auth::user()->name;?>",
                typing: true
            });
        
    });

    // Open chat
    $(document).on('click', ".openChat", function(e){
        e.preventDefault();
        var _chat = $(this).attr('id');
        $('#chatID').val(_chat).trigger('change');
        $('.openChat.active').removeClass('active');
        $(this).addClass('active');
    }); 

    // Create chat
    $("#createChatSubmit").click(function(e){
        e.preventDefault();
        var usersSelected = [];
        $('#selectUsers option:selected').each(function(i, selected) {
            usersSelected[i] = $(selected).val();
        });
        axios.post('/chats', {
                chats: {
                    name: $("#name").val(),
                },
                users: usersSelected,
            
        }
        ).then(function (response){
            console.log('chat created');
        }).catch(function (error){
            console.log(error);
        });
    }); 

    // Send message
    $("#sendMessage").click(function(e){
        e.preventDefault();
        axios.post('/chats/' + activeChat + '/message', {
                message: $("#message").val(),
                chat_id: activeChat
        }
        ).then(function (response){
            $('#message').val('');
        }).catch(function (error){
            console.log(error);
        });
    }); 

    // Start
    getChats(currentUser);
    getUsers();
});
</script>
@endsection
