$(document).ready(function(){

    $("#datepicker").datepicker({dateFormat: "yy-mm-dd"});
    $('.select2').select2();


    var page = 1; // Initial page number
    if(page >= $('#showMoreButton').attr('data-total-pages')){
        $('#showMoreButton').hide(); 
    }
    $('#showMoreButton').click(function() {
        page++; // Increment the page number
        $.ajax({
            url: BASE_URL + 'message/index',
            type: 'GET',
            data: { page: page },
            success: function(data) {
                var newMessages = $(data).find('.chat-body li'); // Extract new <li> elements
                $('#messageContainer').append(newMessages); // Append new <li> elements to the container

                if(page == $('#showMoreButton').attr('data-total-pages')){
                    $('#showMoreButton').hide(); 
                }
            }
        });
    });



    $('#profile-image').on('change',function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    })
    

    updateProfileInfo();
    chat();
    sendMessage();
    deleteChat();
})

function updateProfileInfo(){
    $('#update-form').on('submit', function(e){
        e.preventDefault();

        var name = $('.form-control[name="name"]').val().trim();
        if(name.length < 5 || name.length > 20){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Name must be 5 to 20 characters',
                    showConfirmButton: false,
                    timer: 1500
                })
        }else{
            var form = new FormData(this);
            var url = BASE_URL + 'profile/updateprofileData'
            var res = SERVER(url, form);
            if(res){
                Swal.fire({
                    position: 'top-end',
                    icon: res.status ? 'success' : 'error',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 1500
                  })
                  if(res.status){
                    setTimeout(function() {
                        $('body').fadeOut('slow', function() {
                            window.location.href = BASE_URL + 'profile';
                        });
                    }, 1500); 
                  }
            }
        }
    });
}



function chat(){
    $(document).on('click', '.openMessage', function() {
        var id = $(this).attr('data-id');
        var form = new FormData()
         form.append('id', id)
         res = SERVER(BASE_URL + `message/view`, form)
         if (res) {

            var str = `
            <div class="user-conversation ml-2">
                <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-9">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="${res.Profile.profile_picture !== '' ? `${BASE_URL}/app/webroot/img/uploads/${res.Profile.profile_picture}` : `${BASE_URL}/app/webroot/img/default.jpg`}" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">${res.Profile.name}</h6>
                                    <small class="account-status">${res.Profile.is_online == 1 ? `<i class="fa fa-circle online account-status-icon"></i> online` : `Last online: ` + res.Profile.last_login_time}</small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="chat-about">
                                    <h6 class="m-b-0"><a href="">View All Messages</a></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="reply-message-form" data-id="${res.Profile.fk_userid}">
                        <div class="chat-message clearfix">
                            <div class="input-group mb-0">
                                <div class="input-group-prepend" id="submitReplyBtn">
                                    <span class="input-group-text"><i class="fa fa-paper-plane"></i></span>
                                </div>
                                <textarea class="form-control" placeholder="Enter reply here..." rows="3" name="reply"></textarea>                    
                            </div>
                        </div>
                    </form>
                    <div class="chat-history-container">
                        <div class="chat-history" data-contact-id="${id}">
                            <ul class="m-b-0 chats" id="chats">
                                
                            </ul>
                        </div>
                    </div>
                    <div class="chat-message clearfix">
                        <div class="input-group mb-0">
                            <button id="showMoreBtn" class="btn btn-primary">Show More</button>
                        </div>
                    </div>
                </div>
            `;
            $('#chat-content').html(str);
           
        // Merge and sort messages from both arrays
        var allMessages = res.MessagesFromMe.concat(res.MessagesToMe);
        allMessages.sort(function (a, b) {
            return parseInt(a.id) - parseInt(b.id);
        });
        var currentPage = 1;
        var messagesPerPage = 10;
        var startIndex = 0;
        var endIndex = messagesPerPage;

        function displayMessages(page, appendMode) {
            var chatContent = '';
        
            allMessages.length <= endIndex ? $('#showMoreBtn').hide() : $('#showMoreBtn').show();
            
            // Append messages instead of replacing if appendMode is true
            if (appendMode) {
                startIndex = (page - 1) * messagesPerPage;
                endIndex = startIndex + messagesPerPage;
            }
        
            for (var i = startIndex; i < endIndex; i++) {
                var message = allMessages[i];
        
                if (message) {
                    var chat_id = $('.chat-history').attr('data-contact-id');
                    var isFromMe = message.from_id === chat_id ? false : true; 
                    var messageStyle = isFromMe ? 'other-message float-right' : 'my-message';
                    var iconStyle = isFromMe ? 'float-right icon-right' : '';
                    var innerStr = `
                        <li class="clearfix">
                            <div class="message-data ${isFromMe ? 'text-right' : ''}">
                                <span class="message-data-time">${message.sent_date}</span>
                            </div>
                            <div class="message ${messageStyle}">${message.content}</div>
                            <button type="button" class="btn waves-effect waves-light btn-danger ${iconStyle} deleteMessage" data-id="${message.id}"><i class="fa fa-trash"></i></button>
                        </li>
                    `;
                    chatContent += innerStr;
                    isFromMe = !isFromMe; 
                }
            }
        
            if (appendMode) {
                $('#chats').append(chatContent);
            } else {
                $('#chats').html(chatContent);
            }
        }

        displayMessages(currentPage);

        $('#showMoreBtn').on('click', function () {
            currentPage++;
            displayMessages(currentPage, true);
        
            if (endIndex >= allMessages.length) {
                $('#showMoreBtn').hide();
            }
        });
      }
    })
}

function sendMessage(){
    $('#send-message-form').on('submit', function(e){
            e.preventDefault();

            var form = new FormData(this);
            var url = BASE_URL + 'message/sendMessage'
            var res = SERVER(url, form);
            if(res){
                Swal.fire({
                position: 'top-end',
                icon: res.status ? 'success' : 'error',
                title: res.message,
                showConfirmButton: false,
                timer: 1500
              })
              
              if(res.status){
                setTimeout(function() {
                    $('body').fadeOut('slow', function() {
                        window.location.href = BASE_URL + 'message';
                    });
                }, 1500); 
              }
            }
    })
}


function deleteChat(){
    $(document).on('click', '.deleteMessage', function() {

        const id = $(this).attr('data-id')
        const form = new FormData()
        form.append('id', id)
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                res = SERVER(BASE_URL + `message/delete`, form)
                if(res){
                    Swal.fire({
                        icon: res.status ? 'success' : 'error',
                        title: res.status ? 'DELETED' : 'ERROR',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                   });

                   var chat_id = $('.chat-history').attr('data-contact-id');
                   if(typeof chat_id !== 'undefined'){
                        setTimeout(function() {
                            $(".openMessage[data-id='" + chat_id + "']").trigger("click");
                        }, 1500);
                   }else{
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                   }
                }
            }
          })
    });
}

$(document).on('submit','#reply-message-form', function(e){
    e.preventDefault();

    var id = $(this).attr('data-id');
    var form = new FormData(this);
    form.append('id', id)
    var url = BASE_URL + 'message/replyMessage'
    var res = SERVER(url, form);

    if(res){
        Swal.fire({
            position: 'top-end',
            icon: res.status ? 'success' : 'error',
            title: res.message,
            showConfirmButton: false,
            timer: 1500
          })

          var chat_id = $('.chat-history').attr('data-contact-id');
          $(".openMessage[data-id='" + chat_id + "']").trigger("click");
    }
})

$(document).on('click','#submitReplyBtn', function(){
    $('#reply-message-form').submit();
})
