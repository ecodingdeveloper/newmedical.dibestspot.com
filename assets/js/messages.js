function clock() {
                var time = new Date();
                time = time.toLocaleString('en-US', { hour: 'numeric',minute:'numeric', hour12: true });
                $('#time').val(time);
                setTimeout('clock()',1000);
            }
            clock();

   search_user();
  
            function search_user(){

    var keywords=$('#search_users').val();
    var user_role=$('#user_role').val();
    if(user_role==7){
      var baseurl=base_url+'chat/search_users';
    } else {
      var baseurl=base_url+'messages/search_users';
    }
    $.post(baseurl,{keywords:keywords},function(data){
    
         
          var obj = JSON.parse(data);
          if(obj.status==200)
          { 
            
            var html='';
             $(obj.users_list).each(function(){ 
             

                if(this.role==1)
                {
                  if(this.admin_role==0)
                    var name = lg_dr+' '+this.first_name+' '+this.last_name;
                  else
                    var name = this.first_name+' '+this.last_name;
                }
                else{
                  var name = this.first_name+' '+this.last_name;
                }
                var last_msg="";
                if(this.type =='file'){
                  last_msg="File";
                }else{
                  last_msg=this.last_msg;
                }

                html +='<a href="javascript:void(0);" onclick="get_chat_img(\''+this.userid+'\',\''+this.username+'\',\''+this.first_name+' '+this.last_name+'\',\''+this.admin_role+'\')" class="media chat-data state'+this.userid+'">'+
                            '<div class="media-img-wrap">'+
                              '<div class="avatar '+this.online_status+'">'+
                                '<img src="'+this.profileimage+'" alt="User Image" class="avatar-img rounded-circle">'+
                              '</div>'+
                            '</div>'+
                        '<div class="media-body">'+
                          '<div>'+
                            '<div class="user-name">'+name+'</div>'+
                           // '<div class="user-last-chat">'+last_msg+'</div>'+
                          '</div>'+

                          '<div>'+
                            '<div class="last-chat-time block">'+this.chatdate+'</div>';
                            if(this.unread_count > 0){
                            html +='<div class="badge badge-success badge-pill unread'+this.userid+'">'+this.unread_count+'</div>';
                          }
                          html +='</div> '+
                         '</div>'+
                      '</a>';
             });

            
          }
          else{

                var html=lg_no_users_found;
          }

          $('.chat_users').html(html);

    });

     

  }
  
             

function get_chat_img(selected_user_id,selected_user_name,selected_name,admin_role=0)
{

  var user_role=$('#user_role').val();
  if(user_role==7){
    var baseurl=base_url+'chat/get_chat_img';
    baseurl1=base_url+'chat/get_messages';
  } else {
    var baseurl=base_url+'messages/get_chat_img';
    baseurl1=base_url+'messages/get_messages';
  }
  $.post(baseurl,{user_id:selected_user_id,admin_role:admin_role},function(data){
    var json = $.parseJSON(data);
    $('.chat-img').html('<div class="avatar '+json.online_status+'  "><img src="'+json.profileimage+'" alt="User Image" class="avatar-img rounded-circle"></div>');
    $('.user-status').html(json.status);
  });

  var status = $(this).attr('data-status'); // status
  $('#user_selected_id').val(selected_user_id); 

  $('#'+selected_user_name).text('');
  $(".chatclick").removeClass("selected");
  $(this).addClass('selected');
  $(".chat-data").removeClass('active');
  $(".state"+selected_user_id).addClass('active');
  $(".unread"+selected_user_id).remove();
  $('.openchat').html('<a href="#">'+selected_name+'<span class="status '+status+'"></span></a>');
  $('#recipients').val(selected_user_name);
  $('#receiver_id').val(selected_user_id);
  $('#to_user_id').val(selected_user_id);
  $('#admin_role').val(admin_role);
  $('#chat_box').removeClass('hidden');
  $('.chats').html('<img src="'+base_url+'assets/img/loading.gif" class="loading">')

  $.post(baseurl1,{selected_user_id:selected_user_id,admin_role:admin_role},function(response){
   
    $('.chats').html(response);

 //   $('#hidden_id').focus().addClass('hidden');

 $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight')); 
 setTimeout(function() {
   $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
 }, 3000);
 setTimeout(function() {
   $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
 }, 1000);

 $('.load-more-btn').click(function(){
   $('.load-more-btn').html('<button class="btn btn-sm">'+lg_please_wait__+' </button>');
   var total = $(this).attr('total');
   if(total>0 || total == 0 ){
     load_more(total);
     var total = total - 1;
     $(this).attr('total',total);
   }else{
     $('.load-more-btn').html('<button class="btn btn-sm">'+lg_thats_all+'</button>');
   }

 });
});


}

function get_chat_user(){

  var user_role=$('#user_role').val();
    if(user_role==7){
      var baseurl=base_url+'chat/get_chat_user';
    } else {
      var baseurl=base_url+'messages/get_chat_user';
    }

$.get(baseurl,function(data){
    var obj = jQuery.parseJSON(data);
    if(obj.status===200)
      {
        var obj = jQuery.parseJSON(data);
        get_chat_img(obj.user_id,obj.username,obj.name,obj.admin_role);
      }

  });

}
get_chat_user();



$('.chat-send-btn').click(function(){
      $('.no_message').html('');
      var time = $('#time').val();
      var img = $('#img').val();
      var input_message = $.trim($('#input_message').val());
      if(input_message!=''){

         var content ='<li class="media sent">'+
        '<div class="avatar">'+
        '<img  src="'+img+'" class="avatar-img rounded-circle">'+
        '</div>'+
        '<div class="media-body">'+
        '<div class="msg-box">'+
        '<div>'+
        '<p>'+input_message+
        '</p>'+
    '<ul class="chat-msg-info">'+
      '<li>'+
        '<div class="chat-time">'+
          '<span>'+time+'</span>'+
        '</div>'+
      '</li>'+
    '</ul>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</li>';

        $('#ajax').append(content);
        $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
        message();
        $('#chat_form')[0].reset();
      }
      return false;
    });

function message()
    {
      var user_role=$('#user_role').val();
      if(user_role==7){
        var baseurl=base_url+'chat/insert_chat';
      } else {
        var baseurl=base_url+'messages/insert_chat';
      }
      var msg = $.trim($('#input_message').val());
      var to_username = $('#recipients').val();
      var admin_role = $('#admin_role').val();
     $.post(baseurl,{to_username:to_username,input_message:msg,admin_role:admin_role},function(response){
      //search_user();

     });

   }

    $('#chat_form').submit(function(){
      $('.no_message').html('');

      var date = new Date().toISOString().replace(/T.*/,'').split('-').reverse().join('-');
      var time = $('#time').val();
      var img = $('#img').val();
      var input_message = $.trim($('#input_message').val());
      if(input_message!=''){
        var content ='<li class="media sent">'+
        /*'<div class="avatar">'+
        '<img  src="'+img+'" class="avatar-img rounded-circle">'+
        '</div>'+*/
        '<div class="media-body">'+
        '<div class="msg-box">'+
        '<div>'+
        '<p>'+input_message+
        '</p>'+
    '<ul class="chat-msg-info">'+
      '<li>'+
        '<div class="chat-time">'+
          '<span>'+date+' '+time+'</span>'+
        '</div>'+
      '</li>'+
    '</ul>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</li>';
        $('#ajax').append(content);
        $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
        message();
        $('#chat_form')[0].reset();

      }
      return false;
    });



    $('#user_file').change(function(e) {
       e.preventDefault();
       var user_role=$('#user_role').val();
      if(user_role==7){
        var baseurl=base_url+'chat/upload_files';
      } else {
        var baseurl=base_url+'messages/upload_files';
      }
   var oFile = document.getElementById("user_file").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
            if (oFile.size > 25097152) // 25 mb for bytes.
            {

              toastr.warning(lg_file_size_must_);
                return false;
            }

            var formData = new FormData($('#chat_form')[0]);
            $.ajax({
              url: baseurl,
              type: 'POST',
              data: formData,
              beforeSend :function(){
                $('.progress').removeClass('d-none');
                $('.progress').css('display','block');
              },
              success: function(res) {
                $('.progress').addClass('d-none');
                var obj = jQuery.parseJSON(res);
                if(obj.error){
                  toastr.warning(obj.error);
                  
                  $('#user_file').val('');
                  return false;
                }

          // $("#progress-bar").width('0%');
          var to_username = $('#recipients').val();
          var img = $('#img').val();
          var time = $('#time').val();
          if(obj.type == 'image'){
            var image_src = obj.img;
			var path='<img alt="" src="'+base_url+'/'+image_src+'" class="img-fluid">';
          }
		  else if(obj.type != 'image'){
			 var file_src= obj.img; 
			 var path=base_url+'/'+file_src;
		  }
		  else{
            var image_src ='assets/img/download.png';

          }
          var up_file_name =obj.file_name;

            var content ='<li class="media sent">'+
          '<div class="avatar">'+
          '<img alt="" src="'+img+'" class="avatar-img rounded-circle">'+
          '</div>'+
          '<div class="media-body">'+
          '<div class="msg-box">'+
          '<div>'+
          '<p>'+path+'</p>'+
          '<p>'+up_file_name+'</p>'+
          '<a href="'+base_url+'/'+obj.img+'" target="_blank" download>'+lg_download+'</a>'+
           '<ul class="chat-msg-info">'+
        '<li>'+
          '<div class="chat-time">'+
            '<span>'+time+'</span>'+
          '</div>'+
        '</li>'+
      '</ul>'+
          '</div>'+
          '</div>'+
          '</div>'+
          '</li>';
          $('#ajax').append(content);
          $('#user_file').val('');

          $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));

          setTimeout(function() {
            $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
          }, 3000);

        },
        error: function(data){

         alert('error');

       },
       cache: false,
       contentType: false,
       processData: false

     });
            return false;

          });



    function load_more(total){

      var user_role=$('#user_role').val();
      if(user_role==7){
        var baseurl=base_url+'chat/get_old_messages';
      } else {
        var baseurl=base_url+'messages/get_old_messages';
      }

      var selected_user_id = $('#receiver_id').val();

      $.post(baseurl,{selected_user_id:selected_user_id,total:total},function(res){
        if(res){
          $('.load-more-btn').html('<button class="btn btn-sm" data-page="2"><i class="fa fa-refresh"></i> '+lg_load_more+'</button>');
          $('#ajax_old').prepend(res);
        }else{
          $('.load-more-btn').html('<button class="btn btn-sm">'+lg_thats_all+'</button>');
        }
      });
    }

    setInterval(function(){
       
      var user_role=$('#user_role').val();
      if(user_role==7){
        var baseurl=base_url+'chat/get_message';
      } else {
        var baseurl=base_url+'messages/get_message';
      }
                // console.log('log-applicant.j=>1321');
                var user_selected_id = $('#user_selected_id').val();
                //if(user_selected_id > 0 && user_selected_id !== undefined) {
                $.post(baseurl,{user_selected_id:user_selected_id},function(response) {

                    var json = jQuery.parseJSON(response);
                    if(json.message){
                        append_new_message(json.message);
                    }

                    if(json.other_message){
                      var message_count = jQuery.parseJSON(json.other_message); 
                      var total_count = 0;                       
                      $(message_count).each(function(){
                        $('#'+this.username).text(this.count);
                        total_count +=(+this.count);
                    });
                       $('.unread_msg_count').html(total_count);
                  }

           });
         // }
            }, 5000);
    
    function append_new_message(message){

                                   $('.no_message').html('');
                                   var message = jQuery.parseJSON(message);
                                   $(message).each(function(){
                                        
                                        if(this.message == 'file' && this.type == 'image'){


                                         var content ='<li class="media received">'+
                                         '<div class="avatar">'+
                                         '<img alt="" src="'+this.image+'" class="avatar-img rounded-circle">'+
                                         '</div>'+
                                         '<div class="media-body">'+
                                         '<div class="msg-box">'+
                                         '<div>'+
                                         '<p><img alt="" src="'+this.file_path+'/'+this.file_name+'" class="img-fluid"></p>'+ 
                                         '<p>'+this.file_name+'</p>'+                
                                         '<a href="'+this.file_path+'/'+this.file_name+'" target="_blank" download>'+lg_download+'</a>'+
                    '<ul class="chat-msg-info">'+
                      '<li>'+
                        '<div class="chat-time">'+
                          '<span>'+this.time+'</span>'+
                        '</div>'+
                      '</li>'+
                    '</ul>'+
                                         '</div>'+
                                         '</div>'+
                                         '</div>'+
                                         '</li>';
                                         $('#ajax').append(content); 

                                         $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));                    
                                     }else if(this.message == 'file' && this.type == 'others'){

                                         var content ='<li class="media received">'+
                                         '<div class="avatar">'+
                                         '<img alt="" src="'+this.image+'" class="avatar-img rounded-circle">'+
                                         '</div>'+
                                         '<div class="media-body">'+
                                         '<div class="msg-box">'+
                                         '<div>'+
                                         '<p><img alt="" src="'+base_url+'assets/img/download.png" class="img-fluid"></p>'+
                                         '<p>'+this.file_name+'</p>'+
                                         '<a href="'+this.file_name+'" target="_blank" download>'+lg_download+'</a>'+
                    '<ul class="chat-msg-info">'+
                      '<li>'+
                        '<div class="chat-time">'+
                          '<span>'+this.time+'</span>'+
                        '</div>'+
                      '</li>'+
                    '</ul>'+
                                         '</div>'+
                                         '</div>'+
                                         '</div>'+
                                         '</li>';
                                         $('#ajax').append(content); 

                                         setTimeout(function() {
                                          $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
                                      }, 3000);
                                         setTimeout(function() {
                                          $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
                                      }, 1000);                  
                                     }


                                     else{


                                        var content ='<li class="media received">'+
                                        '<div class="avatar">'+
                                        '<img alt="" src="'+this.image+'" class="avatar-img rounded-circle">'+
                                        '</div>'+
                                        '<div class="media-body">'+
                                        '<div class="msg-box">'+
                                        '<div>'+
                                        '<p>'+this.message+'</p>'+
                    '<ul class="chat-msg-info">'+
                      '<li>'+
                        '<div class="chat-time">'+
                          '<span>'+this.time+'</span>'+
                        '</div>'+
                      '</li>'+
                    '</ul>'+
                                        '</div>'+
                                        '</div>'+
                                        '</div>'+
                                        '</li>';

                                        $('#ajax').append(content); 

                                        setTimeout(function() {
                                          $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
                                      }, 3000);
                                        setTimeout(function() {
                                          $(".slimscrollleft").scrollTop($(".slimscrollleft").prop('scrollHeight'));
                                      }, 1000); 

                                    }
                                });

}




