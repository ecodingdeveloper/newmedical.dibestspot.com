if(modules=='email_template'){
    var email_template_table; 
    email_template_table= $('#emailtemplate_table').DataTable({
        'ordering':false,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/email_template/email_template_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


}var chatAppTarget = $('.chat-window');
(function() {
    if ($(window).width() > 991)
        chatAppTarget.removeClass('chat-slide');
    
    $(document).on("click",".chat-window .chat-users-list a.media",function () {
        if ($(window).width() <= 991) {
            chatAppTarget.addClass('chat-slide');
        }
        return false;
    });
    $(document).on("click","#back_user_list",function () {
        if ($(window).width() <= 991) {
            chatAppTarget.removeClass('chat-slide');
        }	
        return false;
    });
})();

$(document).ready(function() {

    var tz = jstz.determine();
    var timezone = tz.name();
    $.post(base_url+'ajax/set_timezone',{timezone:timezone},function(res){

})

     $.post(base_url+'ajax/currency_rate',function(res){
        //console.log(res);
      })

         $("#search_button").click(function(){

var search_keywords =  $.trim($('#search_keywords').val());
 if(search_keywords!=''){

  window.location.href=base_url+'admin/dashboard/search?keywords='+search_keywords;

  }else{
    toastr.warning("Please enter keyword");
  }


    });
});

//Update notfication status to view mode
    function clear_all(){

      $.ajax({
                type: "POST",
                url: base_url+'admin/dashboard/notification_update',
                dataType: "json",
                success: function(response)
                {
                // return false;
                 if(response.status==200){
                 toastr.success("Notifications clear");
                 setTimeout(function(){ location.reload(true); }, 1000);
                 location.reload(true);
                 }else{
                  toastr.error(response.msg);
                 }

            
              }
          });



    }

if(modules=='appointments' || modules == 'dashboard'){

if(modules == 'dashboard' && pages == 'index' ){


     


  //Revenue Chart 
  var rev_data=[];

    $.ajax({
                type: "POST",
                url: base_url+'admin/dashboard/revenue_graph',
                success: function(data)
                {
                                              // return false;
                  if(data.length){
                    var obj = jQuery.parseJSON(data);
                    var html = ''
                    
                     $(obj.data).each(function(){ 
                     

                      location_items={};
                      location_items["y"]=this.month;
                      location_items["a"]=this.revenue;
                      
                     
                      rev_data.push(location_items);

                     });
                     

                  }

                  window.mA = Morris.Area({
                        element: 'morrisArea',
                        data: rev_data,
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Revenue'],
                        lineColors: ['#1b5a90'],
                        lineWidth: 2,
                         parseTime: false,
                        fillOpacity: 0.5,
                        gridTextSize: 10,
                        hideHover: 'auto',
                        resize: true,
                      redraw: true
                    });

              }
          });


//Status Chart
  var status_data=[];

    $.ajax({
                type: "POST",
                url: base_url+'admin/dashboard/status_graph',
                success: function(data)
                {
                                              // return false;
                  if(data.length){
                    var obj = jQuery.parseJSON(data);
                    var html = ''
                    
                     $(obj.data).each(function(){ 
                     

                      location_items={};
                      location_items["y"]=this.month;
                      location_items["a"]=this.doctor;
                      location_items["b"]=this.patient;
                      location_items["c"]=this.clinic;
                      
                     
                      status_data.push(location_items);

                     });
                     

                  }

                  window.mL = Morris.Line({
                    element: 'morrisLine',
                    data:status_data,
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c'],
                    labels: ['Doctors', 'Patients', 'Clinic'],
                    lineColors: ['#1b5a90','#ff9d00','#db09aa'],
                    lineWidth: 1,
                    parseTime: false,
                    gridTextSize: 10,
                    hideHover: 'auto',
                    resize: true,
                  redraw: true
                });
              }
          });

    
}

if(pages =='notification'){
    

    status_update();

    //Update notfication status to view mode
    function status_update(){

      $.ajax({
                type: "POST",
                url: base_url+'admin/dashboard/notification_update',
                success: function(response)
                {
                    
            
              }
          });



    }

    function delete_notification(id){


       $.ajax({
                type: "POST",
                url: base_url+'admin/dashboard/delete_notification',
                data:{id:id},
                dataType: "json",
                success: function(response)
                {
                                         // return false;
                 if(response.status=='200'){
                 toastr.success(response.msg);
                 
                  setTimeout(function(){ location.reload(true); }, 1000);
                 }else{
                  toastr.error(response.msg);
                 }
                 

            
              }
          });


    }

    notification(0);

   
    

      function notification(load_more){

        if(load_more == 0){
         $('#page_no_hidden').val(1);
       }

       
       var page = $('#page_no_hidden').val();        
       var order_by = 'DESC';
       var keywords = $("#keywords"). val();

       

     //$('#search-error').html('');

     $.ajax({
       url:  base_url +'admin/dashboard/search_notification',
       type: 'POST',
       data: {
        page : page,
        order_by : order_by,
        keywords : keywords,
      },
     beforeSend: function(){
     $("#loading").show();
   },
   complete: function(){
     $("#loading").hide();
   },
      success: function(response){
        //$('#doctor-list').html(''); 
        if(response){

          var obj = $.parseJSON(response);
          if(obj.data.length >=1) {
          var html = '';

          $(obj.data).each(function(){ 
           
   
       html +='<div class="noti-list">'+
                      '<div class="noti-avatar">'+
                        '<img alt="avatar" src="'+this.profile_image+'">'+
                      '</div>'+
                      '<div class="noti-content">'+
                        '<span class="truncate head-notifications">'+this.from_name+'</span>'+
                        '<span class="notifications-time">'+this.notification_date+'</span>'+
                        '<div class="clearfix"></div>'+
                        '<p class="truncate">'+this.text+'</p>'+
                         '<p class="truncate">'+this.to_name+'</p>'+
                      '</div>'+
                      '<div class="noti-delete">'+
                        '<button class="text-danger" type="button"><i class="fa fa-trash" onclick="delete_notification('+this.id+')"></i></button>'+
                      '</div>'+
                    '</div>';

            
          

        
       
        });

          if(obj.current_page_no == 1){    
            $("#notification-list").html(html);    
          }else{

            $("#notification-list").append(html);    
          }  

        }
        else
        {
           var html='<div class="col-md-12">'+
                '<div class="card">'+
                '<div class="card-body">'+
                  '<p class="mb-0">No Notifications found</p>'+
                  '</div>'+
                  '</div>';
                  '</div>';

                  $("#notification-list").html(html);    
        }

                 


          var minimized_elements = $('h4.minimize');
          minimized_elements.each(function() {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) + '<span>... </span><a href="#" class="more">Load More</a>' +
              '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">Load less</a></span>'
              );
          });

          // $('a.more', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).hide().prev().hide();
          //   $(this).next().show();
          // });

          // $('a.less', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).parent().hide().prev().show().prev().show();
          // });
         $(".search-results").html(obj.count);
          // $(".widget-title").html(obj.count+' Matches for your search');
          if(obj.count == 0){
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }


          if(obj.current_page_no == 1 && obj.count < 5){
            $('page_no_hidden').val(1);
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }



          if(obj.total_page > obj.current_page_no && obj.total_page !=0 ){                               
            $('#load_more_btn').removeClass('d-none');
            $('#no_more').addClass('d-none');
          }else{                                
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
          }                

        }                     
      }

    });
}

$('#load_more_btn').click(function(){    
 var page_no = $('#page_no_hidden').val(); 
 var current_page_no =0;

 if(page_no == 1){
  current_page_no = 2;
}else{
  current_page_no = Number(page_no) + 1;
}        
$('#page_no_hidden').val(current_page_no);
    notification(1);
});

}






    if(pages =='search'){

    search_doctor(0);

   
    

      function search_doctor(load_more){

        if(load_more == 0){
         $('#page_no_hidden').val(1);
       }

       
       var page = $('#page_no_hidden').val();        
       var order_by = 'ASC';
       var keywords = $("#keywords"). val();

       

     //$('#search-error').html('');

     $.ajax({
       url:  base_url +'admin/dashboard/search_doctor',
       type: 'POST',
       data: {
        page : page,
        order_by : order_by,
        keywords : keywords,
      },
         beforeSend: function(){
     $("#loading").show();
   },
   complete: function(){
     $("#loading").hide();
   },
      success: function(response){
        //$('#doctor-list').html(''); 
        if(response){

          var obj = $.parseJSON(response);
          if(obj.data.length >=1) {
          var html = '';

          $(obj.data).each(function(){ 
           
   
            var services = '';

          if(this.services.length!=0){
            var service=this.services.split(',');
           for(var i=0;i< service.length;i++){             
                services +='<span>'+service[i]+'</span>';                  
             }
          }

          var clinic_images = '';
         
            var clinic_images_file = $.parseJSON(this.clinic_images);
            $.each(clinic_images_file, function(key, item) {
              var userid=item.user_id;
          clinic_images +='<li> <a href="uploads/clinic_uploads/'+userid+'/'+item.clinic_image+'" data-fancybox="gallery"> <img src="uploads/clinic_uploads/'+userid+'/'+item.clinic_image+'" alt="Feature"> </a> </li>';
        
    });

            
                        html +='<div class="col-md-6 col-lg-4 col-xl-3">'+
                          '<div class="profile-widget">'+
                            '<div class="doc-img">'+
                              '<img class="img-fluid" alt="User Image" src="'+this.profileimage+'">'+
                            '</div>'+
                            '<div class="pro-content">'+
                              '<h3 class="title">'+
                                'Dr '+this.first_name+' '+this.last_name+
                                '<i class="fa fa-check-circle verified"></i>'+
                              '</h3>'+
                              '<p class="speciality">'+this.speciality+'</p>'+
                              '<div class="rating">';
                               for(var j=1;j<=5;j++){  
                                if(j <= this.rating_value){                                        
                            html +='<i class="fas fa-star filled"></i>';
                            }else { 
                            html +='<i class="fas fa-star"></i>';
                            }            
                           }
                          html +='<span class="d-inline-block average-rating">('+this.rating_count+')</span>'+
                        '</div>'+
                              '<ul class="available-info">'+
                                '<li>'+
                                  '<i class="fas fa-map-marker-alt"></i>'+this.cityname+', '+this.countryname+' '+
                                '</li>'+
                               
                                '<li>'+
                                  '<i class="fa fa-money"></i>'+this.amount+' '+
                                '</li>'+
                              '</ul>'+
                            '</div>'+
                          '</div>'+
                        '</div>';

                      



        
       
        });

          if(obj.current_page_no == 1){    
            $("#doctor-list").html(html);    
          }else{
            $("#doctor-list").append(html);    
          }  

        }
        else
        {
           var html='<div class="col-md-12">'+
                '<div class="card">'+
                '<div class="card-body">'+
                  '<p class="mb-0">No Doctors found</p>'+
                  '</div>'+
                  '</div>';
                  '</div>';

                  $("#doctor-list").html(html);    
        }

                 


          var minimized_elements = $('h4.minimize');
          minimized_elements.each(function() {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) + '<span>... </span><a href="#" class="more">Load More</a>' +
              '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">Load less</a></span>'
              );
          });

          // $('a.more', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).hide().prev().hide();
          //   $(this).next().show();
          // });

          // $('a.less', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).parent().hide().prev().show().prev().show();
          // });
         $(".search-results").html(obj.count);
          // $(".widget-title").html(obj.count+' Matches for your search');
          if(obj.count == 0){
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }


          if(obj.current_page_no == 1 && obj.count < 5){
            $('page_no_hidden').val(1);
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }



          if(obj.total_page > obj.current_page_no && obj.total_page !=0 ){                               
            $('#load_more_btn').removeClass('d-none');
            $('#no_more').addClass('d-none');
          }else{                                
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
          }                

        }                     
      }

    });
}


  

$('#load_more_btn').click(function(){    
 var page_no = $('#page_no_hidden').val(); 
 var current_page_no =0;

 if(page_no == 1){
  current_page_no = 2;
}else{
  current_page_no = Number(page_no) + 1;
}        
$('#page_no_hidden').val(current_page_no);
    search_doctor(1);
});

}


if(pages =='searchpatient'){

    search_patient(0);

   
    

      function search_patient(load_more){

        if(load_more == 0){
         $('#page_no_hidden').val(1);
       }

       
       var page = $('#page_no_hidden').val();        
       var order_by = 'ASC';
       var keywords = $("#keywords"). val();

       

     //$('#search-error').html('');

     $.ajax({
       url:  base_url +'admin/dashboard/search_patient',
       type: 'POST',
       data: {
        page : page,
        order_by : order_by,
        keywords : keywords,
      },
         beforeSend: function(){
     $("#loading").show();
   },
   complete: function(){
     $("#loading").hide();
   },
      success: function(response){
        //$('#doctor-list').html(''); 
        if(response){

          var obj = $.parseJSON(response);
          if(obj.data.length >=1) {
          var html = '';

          $(obj.data).each(function(){ 
           
   
            


           html += '<div class="col-md-6 col-lg-4 col-xl-3">'+
                  '<div class="card widget-profile pat-widget-profile">'+
                    '<div class="card-body">'+
                      '<div class="pro-widget-content">'+
                        '<div class="profile-info-widget">'+
                          '<a href="#" class="booking-doc-img">'+
                            '<img src="'+this.profileimage+'" alt="User Image">'+
                          '</a>'+
                          '<div class="profile-det-info">'+
                            '<h3>'+this.first_name+' '+this.last_name+'</h3>'+
                            
                            '<div class="patient-details">'+
                              '<h5><b>Patient ID :</b> '+this.patient_id+'</h5>'+
                              '<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i>'+this.cityname+', '+this.countryname+'</h5>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div class="patient-info">'+
                        '<ul>'+
                          '<li>Phone <span>'+this.mobileno+'</span></li>'+
                          '<li>Age <span>'+this.age+' Years, '+this.gender+'</span></li>'+
                          '<li>Blood Group <span>'+this.blood_group+'</span></li>'+
                        '</ul>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>';
                

            
                      
                      



        
       
        });

          if(obj.current_page_no == 1){    
            $("#patient-list").html(html);    
          }else{
            $("#patient-list").append(html);    
          }  

        }
        else
        {
           var html='<div class="col-md-12">'+
                '<div class="card">'+
                '<div class="card-body">'+
                  '<p class="mb-0">No Patients found</p>'+
                  '</div>'+
                  '</div>';
                  '</div>';

                  $("#patient-list").html(html);    
        }

                 


          var minimized_elements = $('h4.minimize');
          minimized_elements.each(function() {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) + '<span>... </span><a href="#" class="more">Load More</a>' +
              '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">Load less</a></span>'
              );
          });

          // $('a.more', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).hide().prev().hide();
          //   $(this).next().show();
          // });

          // $('a.less', minimized_elements).click(function(event) {
          //   event.preventDefault();
          //   $(this).parent().hide().prev().show().prev().show();
          // });
         $(".search-results").html(obj.count);
          // $(".widget-title").html(obj.count+' Matches for your search');
          if(obj.count == 0){
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }


          if(obj.current_page_no == 1 && obj.count < 5){
            $('page_no_hidden').val(1);
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }



          if(obj.total_page > obj.current_page_no && obj.total_page !=0 ){                               
            $('#load_more_btn').removeClass('d-none');
            $('#no_more').addClass('d-none');
          }else{                                
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
          }                

        }                     
      }

    });
}


  

$('#load_more_btn').click(function(){    
 var page_no = $('#page_no_hidden').val(); 
 var current_page_no =0;

 if(page_no == 1){
  current_page_no = 2;
}else{
  current_page_no = Number(page_no) + 1;
}        
$('#page_no_hidden').val(current_page_no);
    search_patient(1);
});

}




  
 

 if(modules=='appointments'){

	    var appoinment_table; 

    appoinment_table= $('#appoinment_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/appointments/appoinments_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


       function appoinments_table()
	  {
        
	      appoinment_table.ajax.reload(null,false);
	  }

      var upappoinment_table; 
    upappoinment_table= $('#upappoinment_table').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+'admin/appointments/upappoinments_list',
                "type": "POST",
                "data": function ( data ) {
                   },
                error:function(){
                      
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [], //first column / numbering column
                "orderable": true, //set not orderable
				//"searchable":true,
            },
            ],

        });

       function upappoinments_table()
      {
          upappoinment_table.ajax.reload(null,false);
      }


var missedappoinment_table; 
    missedappoinment_table= $('#missedappoinment_table').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+'admin/appointments/missedappoinments_list',
                "type": "POST",
                "data": function ( data ) {
                   },
                error:function(){
                      
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [0], //first column / numbering column
                "orderable": true, //set not orderable
				
            },
			
            ],
			

        });
        
		
       function missedappoinments_table()
      {
          missedappoinment_table.ajax.reload(null,false);
      }
}

}

if(modules=='payment_requests'){
    var payment_request_table; 
    payment_request_table= $('#payment_request_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/payment_requests/payment_requests_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,6,7 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

    function payment_requests_table()
    {
      payment_request_table.ajax.reload(null,false);
  }

  function view_bankdetails (bank_name,branch_name,account_no,account_name) 
  {
    $('.bankname').html(bank_name);
    $('.branchname').html(branch_name);
    $('.accountno').html(account_no);
    $('.accountname').html(account_name);
    $('#bank_details_modal').modal('show');
}

function payment_status(id,status)
{

    $.post(base_url+"admin/payment_requests/payment_status",{id:id,status:status},function(data){
        payment_requests_table();
    });

}
}

if(modules=='reviews'){
    var reviews_table; 
    reviews_table= $('#reviews_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/reviews/reviews_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

    function reviews_tables()
    {
      reviews_table.ajax.reload(null,false);
  }

  function delete_reviews(id)
  {
      $('#reviews_id').val(id);  
      $('#delete_reviews').modal('show');
  }
  function reviews_delete()
  {
      var reviews_id=$('#reviews_id').val();
      $('#change_btn').attr('disabled',true);
      $('#change_btn').html('<div class="spinner-border text-light" role="status"></div>');
      $.post(base_url+'admin/reviews/reviews_delete',{reviews_id:reviews_id},function(res){
        toastr.success('Review deleted successfully');

       reviews_tables();

       $('#change_btn').attr('disabled',false);
       $('#change_btn').html('Yes');
       $('#delete_reviews').modal('hide');


   });
  }


}

if(modules=='users'){
    if(pages=='doctors'){
        var doctor_table; 
        doctor_table= $('#doctors_table').DataTable({
            'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/users/doctors_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });
    function delete_doctor(id)
        {
            console.log(id);
            if(confirm('Are you sure delete this Doctor?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/doctor_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#admin_package_form').modal('hide');
                
                        toastr.success('Doctor deleted successfully');
                        doctor_table.ajax.reload();
                        // window.location.href=base_url+'admin/users/package';
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

        function doctors_table()
        {
          doctor_table.ajax.reload(null,false);
      }
  }

  if(pages=='packages'){
    
var doctor_table;

$(document).ready(function(){

  // console.log(modules);
  console.log(modules);
  console.log(pages);
  $("#upload_image_btn").click(function(){        
    $("#avatar-image-modal").css('display','block');
    $("#avatar-image-modal").modal('show');
});

    //datatables
    doctor_table = $('#packagestable').DataTable({
          'ordering':true,
          "processing": true, //Feature control the processing indicator.
          'bnDestroy' :true,
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.

          // Load data for the table's content from an Ajax source
          "ajax": {
               "url": base_url+"admin/users/package_list",
              "type": "POST",
              "data": function ( data ) {
                
                 
              },
              error:function(){
                    //window.location.href=base_url+'admin/dashboard';
                  }
          },

          //Set column definition initialisation properties.
          "columnDefs": [
          { 
              "targets": [ 0,2 ], //first column / numbering column
              "orderable": false, //set not orderable
          },
          ],

      });

});


function add_package_admin()
{
    $('[name="method"]').val('insert');
    $('#admin_package_form')[0].reset(); // reset form on modals            
    $('.modal-title').text('Add Package'); // Set Title to Bootstrap modal title
    $('#product_images').html('');
    $('#product_img').val('');
    $("#imdDsiplay, .uploaded-section").html('');
    $('#modal_form').modal('show'); // show bootstrap modal
    
}

function package_reload_table()
    {
        admin_packages_table.ajax.reload(null,false); //reload datatable ajax 
    }

    function edit_package_admin(id)
    {
      
      $('#admin_package_form')[0].reset(); // reset form on modals
      
      //Ajax Load data from ajax
      $.ajax({
          url : base_url+"admin/specialization/edit_package/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {

            console.log(data);
            //console.log(data.not_included.split(","));
            $('[name="method"]').val('update');
              $('[name="id"]').val(data.id);
              $('[name="package_name"]').val(data.package_name);
              //$('#package_img').val(data.package_image)
              let data1 = {
                    package_img: data.package_image // Replace with your actual URL
                };
               // console.log(data1.package_img);
                let base_url1=base_url+data1.package_img;
                // Set the image preview source
                if (data1.package_img) {
                    
                    $('#imagePreview').attr('src', base_url1).show(); // Show the fetched image
                }

                // Optional: Handle file input change to show a new image preview
                $('#package_img').on('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imagePreview').attr('src', e.target.result); // Set preview to the uploaded image
                        };
                        reader.readAsDataURL(file);
                    }
                });
              //$('#package_img').html('<img src="'+base_url+data.package_img+'" style="width:80px;height:30px">');
              $('#package_description').val(data.description);
              $('#package_destination').val(data.destination);

              
              
        //       let array = data.not_included.split(",");

        //       array.forEach(function(item) {
        //         $('input[name="not_included[]"][value="' + item.trim() + '"]').prop('checked', true);
        //         //console.log(item);
        //     });
        //   //  7 days breakfast with personal chef at location @$25/day-25, Daily Housekeeping for 7days/$30 per day-30
        //     let array1 = data.add_on.split(",");
        //     console.log(array1);
        //       array1.forEach(function(item) {
        //         $('input[name="add_on[]"][value="' + item.trim() + '"]').prop('checked', true);
        //         //console.log(item);
        //     });
              $('#not_included').val(data.not_included);
              $('#add_on').val(data.add_on);
              $('#package_currency').val(data.currency);
              $('#package_days').val(data.package_days);
              $('#package_weeks').val(data.package_weeks);    
              $('#package_months').val(data.package_months);
              $('#package_speciality').val(data.speciality);
              
              $('#package_price').val(data.cost);
           
              $('#product_description').val(data.description);
              
            
              

            //   $('[name="product_name"]').val(data.name);
            //   $('#product_img').val(data.upload_image_url);

              $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
              $('.modal-title').text('Edit Product'); // Set title to Bootstrap modal title

          },
          error:function(){
            // window.location.href=base_url+'admin/dashboard';
          }
      });

      
  }

        

        function remove_image(image_url, preview_image_url, row_id) {

            var url = base_url + 'admin/products/delete_image';

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',

                data: {
                    image_url: image_url, preview_image_url: preview_image_url
                },

                success: function (data) {
                    if (data.html == 1) {

                        $('#remove_image_div_' + row_id).remove();
                        var total_array = $('#upload_image_url').val();
                        var arr = total_array.split(",");
                        var itemtoRemove = data.image_url;
                        arr.splice($.inArray(itemtoRemove, arr), 1);
                        $("#upload_image_url").val(arr);

                        var total_array1 = $('#upload_preview_image_url').val();
                        var arr1 = total_array1.split(",");
                        var itemtoRemove1 = data.image_url;
                        arr1.splice($.inArray(itemtoRemove1, arr1), 1);
                        $("#upload_preview_image_url").val(arr1);
                    }
                }
            });
        }

        function delete_package_admin(id)
        {
            console.log(id);
            if(confirm('Are you sure delete this package?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/package_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#admin_package_form').modal('hide');
                
                        toastr.success('Package deleted successfully');
                        window.location.href=base_url+'admin/users/package';
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

        $(document).ready(function(){
          $('.export').click(function() {
            console.log("Dddddd");
             var imageData = $('.image-editor').cropit('export');
             
             var url=base_url+'admin/products/image_upload';
             var row_id= $('#row_id').val();
             var dataString="img_data="+imageData+"&row_id="+row_id; 
             var file1 = $('#fileopen').val(); 
             $("#error_msg_model").html('');
             if(file1.length>1){
             $.ajax( {
                  url:url,
                type       : 'post',
                data       : dataString,
                enctype    : 'multipart/form-data',
                dataType   : 'json',
                beforeSend: function () {
                   $('.export').attr('disabled',true);
                  $('.export').html('<div class="spinner-border text-light" role="status"></div>');
                },

                success: function (data) {

                  $('.export').attr('disabled',false);
                  $('.export').html('Done');
                
                  if (data.result) {
                                    
                    $(".uploaded-section").append(data.result);
                    $('#error_msg_model').html('');
                    $(".cropit-preview-image").attr('src','');
                    $(".cropit-preview-background").attr('src','');
                    $("#fileopen").val("");

                    var v1 = $("#upload_image_url").val();
                    var p1 = $("#upload_preview_image_url").val();

                                  if (v1.length > 0) {
                        var v2 = [];
                                          v2.push(v1);
                                          v2.push(data.image_url);
                                          $("#upload_image_url").val(v2);
                     } else {
                          var array = [];
                        array.push(data.image_url);
                        $("#upload_image_url").val(array);
                    }

                    if (p1.length > 0) {
                        var p2 = [];
                                          p2.push(p1);
                                          p2.push(data.preview_image_url);
                                          $("#upload_preview_image_url").val(p2);
                     } else {
                          var array = [];
                        array.push(data.preview_image_url);
                        $("#upload_preview_image_url").val(array);
                    }
                                  $('#row_id').val(data.row_id);

                   
                  }
                $("#avatar-image-modal").modal('hide');
              },
              complete: function () {

                    $("#imageimg_loader").hide();
                  $(".export").html('Done');
                }
                });
             }
             else
            {
              $("#upload_image_url").val('');
              $("#upload_preview_image_url").val('');
              toastr.error('Please upload size more than 680*454');      
            }
              });
          });

        $(document).ready(function (e){
            $("#admin_package_form").on('submit',(function(e){
                e.preventDefault();

                var package_name = $('#package_name').val();
                var package_img  = $('#package_img').val();
               var package_destination=$('#package_destination').val(); 
				var package_speciality  = $('#package_speciality').val();
				var package_price = $('#package_price').val();
				var package_days = $('#package_days').val();
				var package_weeks = $('#package_weeks').val();
                 var package_months = $('#package_months').val();
                var package_currency = $('#package_currency').val();
				var package_description = $('#package_description').val();
                var not_included = $('#not_included').val();
                var add_on = $('#add_on').val();
                // console.log(not_included);
                
                
                // var formData = new FormData(this);
                //    formData.append('not_included', not_included);
				//        console.log(formData);   
                 $.ajax({
                    url: base_url+'admin/users/create_admin_packages',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btnproductsave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnproductsave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btnproductsave').html('Submit');
                      $('#btnproductsave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            toastr.success(obj.status);

                            $('#modal_form').modal('hide');
                            $('#admin_package_form')[0].reset();
                            window.location.href=base_url+'admin/users/package';
                            admin_packages_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                        }
                         
                        else
                        {
                            window.location.reload();
                        }
                    },
                    error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });


  }

  if(pages=='pricing_plan'){
    console.log("pricing_plan");

    var pricing_table;

$(document).ready(function(){

  // console.log(modules);
    //datatables
    pricing_table = $('#pricingtable').DataTable({
          'ordering':true,
          "processing": true, //Feature control the processing indicator.
          'bnDestroy' :true,
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.

          // Load data for the table's content from an Ajax source
          "ajax": {
               "url": base_url+"admin/users/pricing_list",
              "type": "POST",
              "data": function ( data ) {
                
                 
              },
              error:function(){
                    //window.location.href=base_url+'admin/dashboard';
                  }
          },

          //Set column definition initialisation properties.
          "columnDefs": [
          { 
              "targets": [ 0,2 ], //first column / numbering column
              "orderable": false, //set not orderable
          },
          ],

      });

});

    function add_pricing_admin()
{
    $('[name="method"]').val('insert');
    $('#admin_pricing_form')[0].reset(); // reset form on modals            
    $('.modal-title').text('Add Pricing Plan'); // Set Title to Bootstrap modal title
    $('#product_images').html('');
    $('#product_img').val('');
    $("#imdDsiplay, .uploaded-section").html('');
    $('#modal_form').modal('show'); // show bootstrap modal
    
}
function pricing_reload_table()
    {
        pricing_table.ajax.reload(null,false); //reload datatable ajax 
    }
function edit_pricing_admin(id)
        {
          
          $('#admin_pricing_form')[0].reset(); // reset form on modals
          
          //Ajax Load data from ajax
          $.ajax({
              url : base_url+"admin/specialization/edit_pricing/"+id,
              type: "GET",
              dataType: "JSON",
              success: function(data)
              {

                console.log(data);
                // console.log(data.not_included.split(","));
                $('[name="method"]').val('update');
                $('[name="id"]').val(data.plan_id);
                $('#plan_features').val(data.feature_text);
                $('#doctor_plan').val(data.role_1);
                $('#clinic_plan').val(data.role_6);
                $('#gtc_plan').val(data.role_0);
                $('#patient_plan').val(data.role_2);
                $('#lab_plan').val(data.role_4);
                $('#pharmacy_plan').val(data.role_5);
                $('#btnproductsave').html('Update');
                  $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                  $('.modal-title').text('Edit Feature'); // Set title to Bootstrap modal title

              },
              error:function(){
                // window.location.href=base_url+'admin/dashboard';
              }
          });

          
      }
      function delete_pricing_admin(id)
        {
            console.log(id);
            if(confirm('Are you sure to delete this feature?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/pricing_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#admin_pricing_form').modal('hide');
                
                        toastr.success('Feature deleted successfully');
                        pricing_reload_table();
                        // window.location.href=base_url+'admin/users/pricing_plan';
                    },
                    error:function(){
                          window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }


$(document).ready(function (e){
    $("#admin_pricing_form").on('submit',(function(e){
        e.preventDefault();

        var plan_features = $('#plan_features').val();
        var doctor_plan  = $('#doctor_plan').val();
       var clinic_plan=$('#clinic_plan').val(); 
        var gtc_plan  = $('#gtc_plan').val(); 
        var patient_plan=$('#patient_plan').val();
        var pharmacy_plan=$('#pharmacy_plan').val();
        var lab_plan=$('#lab_plan').val();
        if(plan_features == ''){
            toastr.error('Please enter plan_features');
            return false;
        } 
         if(doctor_plan == ''){
            toastr.error('Please enter doctor_plan');
            return false;
        } 
         if(clinic_plan == ''){
            toastr.error('Please Select clinic_plan');
            return false;
        } 
        if(gtc_plan == ''){
            toastr.error('Please Select gtc_plan');
            return false;
        } 
        // var formData = new FormData(this);
        //    formData.append('not_included', not_included);
        //        console.log(formData);   
         $.ajax({
            url: base_url+'admin/users/create_pricing_plan',
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend:function() { 

                $('#btnproductsave').html('<div class="spinner-border text-light" role="status"></div>');
                $('#btnproductsave').attr('disabled',true);
          
            },
            success: function(data){

              $('#btnproductsave').html('Submit');
              $('#btnproductsave').attr('disabled',false);
                      
               var obj=jQuery.parseJSON(data);
                if(obj.result=='true')
                {
                    toastr.success(obj.status);

                    $('#modal_form').modal('hide');
                    $('#admin_pricing_form')[0].reset();
                     window.location.href=base_url+'admin/users/pricing_plan';
                    pricing_table.ajax.reload(null,false);
                }
                else if(obj.result=='false')
                {
                    toastr.error(obj.status);
                }
                 
                else
                {
                    window.location.reload();
                }
            },
            error:function(){
              window.location.href=base_url+'admin/dashboard';
            }

        });
    }));
});


  }

  if(pages=='services'){
    console.log("inside services");
    var subcategories_table1;
    var service_table;
    $(document).ready(function() {

       $.ajax({
        type: "GET",
        // url: base_url+"ajax/get_product_category",
        url: base_url+"ajax/get_specialization",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $('#specialization_list').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('#specialization_list').find("option:eq(0)").html("Select Specialization");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#specialization_list').append(option);
         });  

          
        }
      });

      $.ajax({
        type: "GET",
        // url: base_url+"ajax/get_product_category",
        url: base_url+"ajax/get_doctor_list",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $('#doctor_list').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('#doctor_list').find("option:eq(0)").html("Select Doctor");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#doctor_list').append(option);
         });  

          
        }
      });

        //datatables
        subcategories_table1 = $('#subcategoriestable1').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/subcategories/subcategories_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,3 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });

        service_table = $('#servicestable').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
  
            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/users/service_list",
                "type": "POST",
                "data": function ( data ) {
                  
                   //console.log(data)
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },
  
            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,2 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
  
        });


    });

    function add_service_admin()
{
    $('[name="method"]').val('insert');
    $('#admin_service_form')[0].reset(); // reset form on modals       
    if("Afghanistan"){
        $.ajax({
       type: "POST",
       url: base_url+"ajax/get_city_of_country",
       data:{country: "Afghanistan"}, 
       beforeSend :function(){
         $("#city option:gt(0)").remove(); 	
         $('#city').find("option:eq(0)").html("Please wait");	
     },                         
     success: function (data) {
         //alert(data);
         /*get response as json */
         $('#city').find("option:eq(0)").html("Select city");
         var obj=jQuery.parseJSON(data);
         $(obj).each(function()
         {
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#city').append(option);
        });  

         /*ends */

     }
 });
   }else{
  // $('#state').html('<option value="">Select country first</option>');
   $('#city').html('<option value="">Select city first</option>'); 
}     
    $('.modal-title').text('Add Service'); // Set Title to Bootstrap modal title
    $('#product_images').html('');
    $('#product_img').val('');
    $("#imdDsiplay, .uploaded-section").html('');
    $('#modal_form').modal('show'); // show bootstrap modal
    
}

function edit_service_admin(id)
        {
            //var countryID = $('#country').val();
                // alert(countryID);
           
          $('#admin_service_form')[0].reset(); // reset form on modals
          
          //Ajax Load data from ajax
          $.ajax({
              url : base_url+"admin/specialization/edit_service/"+id,
              type: "GET",
              dataType: "JSON",
              success: function(data)
              {
                //alert(data.city)
                var fetchedCity= data.city;
                console.log(data.country);
                if(data.country){
                    $.ajax({
                   type: "POST",
                   url: base_url+"ajax/get_city_of_country",
                   data:{country: data.country}, 
                   beforeSend :function(){
                     $("#city option:gt(0)").remove(); 	
                     $('#city').find("option:eq(0)").html("Please wait");	
                 },                         
                 success: function (data) {
                     //alert(fetchedCity);
                     /*get response as json */
                     //console.log(data.city);
                     
                     var obj=jQuery.parseJSON(data);
                     $(obj).each(function()
                     {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);           
                        $('#city').append(option);
                    });  
                    $('#city').val(fetchedCity);
                     /*ends */
       
                 }
             });
               }else{
              // $('#state').html('<option value="">Select country first</option>');
               $('#city').html('<option value="">Select city first</option>'); 
           }
                $('[name="method"]').val('update');
                  $('[name="id"]').val(data.id);
                  $('[name="specialization_list"]').val(data.specialization_list);
                  $('#operation').val(data.operation);
                  $('#doctor_list').val(data.doctor_list);
                  $('#service_clinic').val(data.service_clinic);
                  $('#country').val(data.country);
                  $('#city').val(data.city);    
                  $('#service_price').val(data.service_price);
                  $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                  $('.modal-title').text('Edit Product'); // Set title to Bootstrap modal title
                
                //   toastr.success('Service edited successfully');

              },
              error:function(){
                // window.location.href=base_url+'admin/dashboard';
              }
          });
      }

      function delete_service_admin(id)
      {
          console.log(id);
          if(confirm('Are you sure delete this service?'))
          {
              // ajax delete data to database
              
              $.ajax({
                  url : base_url+"admin/specialization/service_delete/"+id,
                  type: "POST",
                  dataType: "JSON",
                  success: function(data)
                  {
                      //if success reload ajax table
                      $('#admin_service_form').modal('hide');
              
                      toastr.success('Service deleted successfully');
                      window.location.href=base_url+'admin/users/service';
                  },
                  error:function(){
                       window.location.href=base_url+'admin/dashboard';
                      }
              });

          }
      }
	//For fetching city based on country code
    $('#country').on('change', function(){
        var countryID = $('#country').val();
		// alert(countryID);
		if(countryID){
				 $.ajax({
				type: "POST",
				url: base_url+"ajax/get_city_of_country",
				data:{country: countryID}, 
				beforeSend :function(){
				  $("#city option:gt(0)").remove(); 	
				  $('#city').find("option:eq(0)").html("Please wait");	
			  },                         
			  success: function (data) {
				  //alert(data);
				  /*get response as json */
				  $('#city').find("option:eq(0)").html("Select city");
				  var obj=jQuery.parseJSON(data);
				  $(obj).each(function()
				  {
					 var option = $('<option />');
					 option.attr('value', this.value).text(this.label);           
					 $('#city').append(option);
				 });  
	
				  /*ends */
	
			  }
		  });
			}else{
           // $('#state').html('<option value="">Select country first</option>');
            $('#city').html('<option value="">Select city first</option>'); 
        }
    });

    $(document).ready(function (e){
        $("#admin_service_form").on('submit',(function(e){
            e.preventDefault();

            var specialization_list = $('#specialization_list').val();
            var operation  = $('#operation').val();
           var doctor_list=$('#doctor_list').val(); 
            var service_clinic = $('#service_clinic').val();
            var country = $('#country').val();
            var city = $('#city').val();
             var service_price = $('#service_price').val();
            // var package_currency = $('#package_currency').val();
            // var package_description = $('#package_description').val();
            // var not_included = $('input[name="not_included[]"]:checked').map(function () {
            //     return $(this).val();
            // }).get().join(', ');
            // console.log(not_included);
            
            if(specialization_list == ''){
                toastr.error('Please enter service name');
                return false;
            } 
             if(operation == ''){
                toastr.error('Please enter operation');
                return false;
            } 
             if(doctor_list == ''){
                toastr.error('Please Select doctor');
                return false;
            } 
            if(service_price == ''){
                toastr.error('Please Select price');
                return false;
            } 
            if(service_clinic == ''){
                toastr.error('Please Select Clinic');
                return false;
            } 
            //  var formData = new FormData(this);
            // //    formData.append('not_included', not_included);
            //         console.log(formData);   
             $.ajax({
                url: base_url+'admin/users/create_admin_services',
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnproductsave').html('<div class="spinner-border text-light" role="status"></div>');
                    $('#btnproductsave').attr('disabled',true);
              
                },
                success: function(data){

                  $('#btnproductsave').html('Submit');
                  $('#btnproductsave').attr('disabled',false);
                          
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='true')
                    {
                        toastr.success("Service added successfully");

                        $('#modal_form').modal('hide');
                        $('#admin_service_form')[0].reset();
                        window.location.href=base_url+'admin/users/service';
                        service_table.ajax.reload(null,false);
                    }
                    else if(obj.result=='false')
                    {
                        toastr.error(obj.status);
                    }
                     
                    else
                    {
                        window.location.reload();
                    }
                },
                error:function(){
                  window.location.href=base_url+'admin/dashboard';
                }

            });
        }));
    });

  }

  if(pages=='team'){
    var doctor_table; 
    doctor_table= $('#team_table').DataTable({
        'ordering':true,
    "processing": true, //Feature control the processing indicator.
    'bnDestroy' :true,
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
       "url": base_url+'admin/users/team_list',
       "type": "POST",
       "data": function ( data ) {
       },
       error:function(){

       }
   },

    //Set column definition initialisation properties.
    "columnDefs": [
    { 
        "targets": [], //first column / numbering column
        "orderable": false, //set not orderable
    },
    ],

});


function edit_team(id)
{
    $('[name="method"]').val('update');
    $('#register_form1')[0].reset(); // reset form on modals
    
    //Ajax Load data from ajax
    $.ajax({
       url : base_url+"admin/specialization/team_member_edit/"+id,
       type: "GET",
       dataType: "JSON",
       success: function(data)
       {

        $('[name="id"]').val(data.id);
        $('[name="specialization"]').val(data.specialization);
        $('#specialization_img').val(data.specialization_img);
        $('#specialization_images').html('<img src="'+base_url+data.specialization_img+'">'); 

            $('#user_modal').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Team Member'); // Set title to Bootstrap modal title

        },
        error:function(){
          window.location.href=base_url+'admin/dashboard';
      }
  });
}

    function team_table()
    {
      team_table.ajax.reload(null,false);
  }
  $("#register_form1").validate({
    rules: {
        first_name: "required",
        last_name: "required",
        country_code: "required",
        mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
                url: base_url+"admin/users/check_mobileno",
                type: "post",
                data: {
                    mobileno: function(){ return $("#mobileno").val(); }
                }
            }
        },
        email: {
          required: true,
          email: true,
          remote: {
            url: base_url+"admin/users/check_email",
            type: "post",
            data: {
                email: function(){ return $("#email").val(); }
            }
        }
    },
    password: {
        required: true,
        minlength: 6
    },
    confirm_password: {
       required: true,
       equalTo: "#password"
   }

},
messages: {
first_name: "Please enter your member's first name",
last_name: "Please enter your last name",
   
country_code: "Please select country code",
mobileno: {
    required: "Please enter mobileno",
    maxlength: "Please enter valid mobileno",
    minlength: "Please enter valid mobileno",
    digits: "Please enter valid mobileno",
    remote: "Your mobile no already exists"
},
email: {
    required: "Please enter email",
    email: "Please enter valid email address",
    remote: "Your email address already exists"
},
password: {
    required: "Please enter password",
    minlength: "Your password must be minimum 6 characters"
},
confirm_password: {
    required: "Please enter confirm password",
    equalTo: "Your password does not match"
}



},
submitHandler: function(form) {

$.ajax({
    url: base_url+'admin/users/signup',
    data: $("#register_form1").serialize(),
    type: "POST",
    beforeSend: function(){
        $('#register_btn').attr('disabled',true);
        $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
    },
    success: function(res){
        $('#register_btn').attr('disabled',false);
        $('#register_btn').html('Submit');
        var obj = JSON.parse(res);

        if(obj.status===200)
        {
            $('#register_form1')[0].reset();
            if($('#role').val()=='7')
            {
                toastr.success('Team member details added successfully');
                // team_table();
                $('#user_modal').modal('hide');
            window.location.reload();
            }
            /*if($('#role').val()=='1')
            {
                doctors_table();
            }*/
            
        }
        else
        {
            toastr.error(obj.msg);
        }   
    }
});
return false;
}
});
}

if(pages=='staff'){
    
    var doctor_table; 
    doctor_table= $('#staff_table').DataTable({
        'ordering':true,
    "processing": true, //Feature control the processing indicator.
    'bnDestroy' :true,
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
       "url": base_url+'admin/users/staff_list',
       "type": "POST",
       "data": function ( data ) {
       },
       error:function(){

       }
   },

    //Set column definition initialisation properties.
    "columnDefs": [
    { 
        "targets": [], //first column / numbering column
        "orderable": false, //set not orderable
    },
    ],

});


function add_staff()
    {
        $('[name="method"]').val('insert');
        $('#register_form')[0].reset(); // reset form on modals
        $('#user_modal').modal('show'); // show bootstrap modal
        $('#user_modal .modal-title').text('Add Staff Member'); // Set Title to Bootstrap modal title
        $("#email").prop("readonly", false);
        $('.pass').show();
    }

    function team_table()
    {
      team_table.ajax.reload(null,false);
  }
  $("#register_form1").validate({
    rules: {
        first_name: "required",
        last_name: "required",
        country_code: "required",
        mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
                url: base_url+"admin/users/check_mobileno",
                type: "post",
                data: {
                    mobileno: function(){ return $("#mobileno").val(); }
                }
            }
        },
        email: {
          required: true,
          email: true,
          remote: {
            url: base_url+"admin/users/check_email",
            type: "post",
            data: {
                email: function(){ return $("#email").val(); }
            }
        }
    },
    password: {
        required: true,
        minlength: 6
    },
    confirm_password: {
       required: true,
       equalTo: "#password"
   }

},
messages: {
first_name: "Please enter your member's first name",
last_name: "Please enter your last name",
   
country_code: "Please select country code",
mobileno: {
    required: "Please enter mobileno",
    maxlength: "Please enter valid mobileno",
    minlength: "Please enter valid mobileno",
    digits: "Please enter valid mobileno",
    remote: "Your mobile no already exists"
},
email: {
    required: "Please enter email",
    email: "Please enter valid email address",
    remote: "Your email address already exists"
},
password: {
    required: "Please enter password",
    minlength: "Your password must be minimum 6 characters"
},
confirm_password: {
    required: "Please enter confirm password",
    equalTo: "Your password does not match"
}



},
submitHandler: function(form) {

$.ajax({
    url: base_url+'admin/users/signup',
    data: $("#register_form1").serialize(),
    type: "POST",
    beforeSend: function(){
        $('#register_btn').attr('disabled',true);
        $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
    },
    success: function(res){
        $('#register_btn').attr('disabled',false);
        $('#register_btn').html('Submit');
        var obj = JSON.parse(res);

        if(obj.status===200)
        {
            $('#register_form1')[0].reset();
            if($('#role').val()=='8')
            {
                toastr.success('Staff member details added successfully');
                // team_table();
                $('#user_modal').modal('hide');
            window.location.reload();
            }
            /*if($('#role').val()=='1')
            {
                doctors_table();
            }*/
            
        }
        else
        {
            toastr.error(obj.msg);
        }   
    }
});
return false;
}
});


    function delete_staff_member(id)
{
    if(confirm('Are you sure to delete this Staff Member?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/specialization/staff_member_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                // $('#specialization_form').modal('hide');
                toastr.success('Staff Member deleted successfully');
                window.location.href=base_url+'admin/users/staff';

            },
            error:function(){
                 //window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}
}

  if(pages == 'pharmacies'){
console.log("pharmacy");
        var pharmacy1_table; 
        $(document).ready(function() {
        pharmacy1_table = $('#pharmacy_table').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+'admin/users/pharmacies_list',
                "type": "POST",
                "data": function ( data ) {
                   },
                error:function(){
                      
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,6 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });
    });

        function delete_pharmacy(id)
        {
            console.log(id);
            if(confirm('Are you sure to delete this pharmacy?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/pharmacy_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        // $('#admin_package_form').modal('hide');
                
                        toastr.success('pharmacy deleted successfully');
                         $('#pharmacy_table').DataTable().ajax.reload();
                        // location.reload();
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

        
      
      
      // Edit Parmacy..
      
      function edit_pharmacy(pharmacy_id) {
            $.ajax({
                url : base_url+"admin/users/pharmacy_edit/"+pharmacy_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                   $(data).each(function(){   
                        $('#edit_first_name').val(this.first_name);
                        $('#edit_last_name').val(this.last_name);
                        //$('#edit_pharmacy_name').val(this.pharmacy_name);
                        $('#edit_email').val(this.email);
                        $('#edit_mobileno').val(this.mobileno);
                        if(this.home_delivery == 'yes'){
                            $('#edit_home_delivery').prop('checked', true);
                        }
                        if(this.hrsopen == 'yes'){
                            $('#edit_hrsopen').prop('checked', true);
                        }
                        $('#edit_pharmacy_opens_at').val(this.pharamcy_opens_at);
                        $('#edit_pharmacy_id').val(this.pharmacy_id);
                   });
                    $('#user_edit_modal').modal('show');
                   return false; 
                },
                    error:function()
                    {
                       // window.location.href=base_url+'admin/dashboard';
                    }
            });
        }
        $('#register_form_edit_pharam').submit(function(e){
          //alert("here");
         // return false;

           // $.ajax({
           //      url : base_url+"admin/users/update_pharmacy/",
           //      type: "POST",
           //      data:  new FormData(this),
           //      //dataType: "JSON",
           //      success: function(data)
           //      {
           //         $(data).each(function(){   
           //              e.preventDefault();
           //            return false; 
           //              window.location.href=base_url+'admin/users/pharmacies';
           //         });
           //          $('#user_edit_modal').modal('show');
           //         return false; 
           //      },
           //          error:function()
           //          {
           //            alert("error")
           //            return false;
           //             // window.location.href=base_url+'admin/dashboard';
           //          }
           //  });

          //  e.preventDefault();
            /* $.ajax({
                    url: base_url+'admin/users/update_pharmacy',
                    type: "POST",
                    //data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 
                    },
                    success: function(data){
                      e.preventDefault();
                      return false; 
                        window.location.href=base_url+'admin/users/pharmacies';
                    } 
                });  */
            
                
        });
        
        function delete_pharmac(pharmacy_id) {
            $('#pharmacy_id').val(pharmacy_id);
            $('#pharmacy_delete_confirmation_box').modal('show');
        }
        
        $(document).on('click', '.phamracy_delete', function() {
            pharmacy_id = $('#pharmacy_id').val();
            $('#pharmacy_delete_confirmation_box').modal('hide');
            $.post(base_url+"admin/users/delete_user",{id:pharmacy_id},function(data){});
            pharmacy1_table.ajax.reload(null,false);
        });
    }


  if(pages=='patients'){
    var patient_table; 
    patient_table= $('#patients_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/users/patients_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,8 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });
    function delete_patient(id)
        {
            console.log(id);
            if(confirm('Are you sure delete this Patient?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/patient_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        // $('#admin_package_form').modal('hide');
                
                        toastr.success('Patient deleted successfully');
                         $('#patients_table').DataTable().ajax.reload();
                        // location.reload();
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

    function patients_table()
    {
      patient_table.ajax.reload(null,false);
  }
      $("#register_form").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            country_code: "required",
            clinic_name: "required",
            mobileno: {
                required: true,
                minlength: 7,
                maxlength: 12,
                digits: true,
                remote: {
                    url: base_url+"admin/users/check_mobileno",
                    type: "post",
                    data: {
                        mobileno: function(){ return $("#mobileno").val(); }
                    }
                }
            },
            email: {
              required: true,
              email: true,
              remote: {
                url: base_url+"admin/users/check_email",
                type: "post",
                data: {
                    email: function(){ return $("#email").val(); }
                }
            }
        },
        password: {
            required: true,
            minlength: 6
        },
        confirm_password: {
           required: true,
           equalTo: "#password"
       }

   },
   messages: {
    first_name: "Please enter your patient's first name",
    last_name: "Please enter your last name",
     clinic_name: "Please enter Clinic name",
       
    country_code: "Please select country code",
    mobileno: {
        required: "Please enter mobileno",
        maxlength: "Please enter valid mobileno",
        minlength: "Please enter valid mobileno",
        digits: "Please enter valid mobileno",
        remote: "Your mobile no already exists"
    },
    email: {
        required: "Please enter email",
        email: "Please enter valid email address",
        remote: "Your email address already exists"
    },
    password: {
        required: "Please enter password",
        minlength: "Your password must be minimum 6 characters"
    },
    confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Your password does not match"
    }
   


},
submitHandler: function(form) {

    $.ajax({
        url: base_url+'admin/users/signup',
        data: $("#register_form").serialize(),
        type: "POST",
        beforeSend: function(){
            $('#register_btn').attr('disabled',true);
            $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
        },
        success: function(res){
            $('#register_btn').attr('disabled',false);
            $('#register_btn').html('Submit');
            var obj = JSON.parse(res);

            if(obj.status===200)
            {
                $('#register_form')[0].reset();
                if($('#role').val()=='2')
                {
					toastr.success('Patient details added successfully');
					patients_table();
					
                }
                /*if($('#role').val()=='1')
                {
                    doctors_table();
                }*/
                $('#user_modal').modal('hide');
                //window.location.reload();
            }
            else
            {
                toastr.error(obj.msg);
            }   
        }
    });
    return false;
}
});
}

function change_usersStatus(id)
{
    var stat= $('#status_'+id).prop('checked');

    if(stat==true)
    {
        var status=1;
    }
    else
    {
        var status=2;
    }
    $.post(base_url+"admin/users/change_usersStatus",{id:id,status:status},function(data){});

}

function updateCommission(id) {
	  //preventDefault();
    var rate = $('#commission_input_' + id).val();
    console.log(id);
	console.log(rate);
    $.ajax({
        url: base_url+'ajax/update_commission', // URL to the direct PHP script
        type: 'POST',
        data: {
            id: id,
            rate: rate
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert(response.message);
				//return false;
            } else {
                alert(response.message);
				//return false;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
			//return false;
        }
		
    });
	//return false;
}

function sendmail(name,email) {
    //preventDefault();
  var name = $('#commission_input_' + id).val();
  var email = $('#commission_input_' + id).val();

  console.log(id);
  console.log(rate);
  $.ajax({
      url: base_url+'ajax/update_commission', // URL to the direct PHP script
      type: 'POST',
      data: {
          id: id,
          rate: rate
      },
      dataType: 'json',
      success: function(response) {
          console.log('Response:', response);
          if (response.status === 'success') {
              alert(response.message);
              //return false;
          } else {
              alert(response.message);
              //return false;
          }
      },
      error: function(xhr, status, error) {
          console.error('Error:', error);
          //return false;
      }
      
  });
  //return false;
}

function updateCommissionPhar(id) {
    //e.preventDefault();
    var rate = $('#commission_input_' + id).val();
    console.log(id);
    $.ajax({
        url: base_url+'ajax/update_commission_phar', // URL to the direct PHP script
        type: 'POST',
        data: {
            id: id,
            rate: rate
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function updateCommissionClinic(id) {
    //e.preventDefault();
	console.log(id);
    var rate = $('#commission_input_' + id).val();
    console.log(id);
    $.ajax({
        url: base_url+'ajax/update_commission_Clinic', // URL to the direct PHP script
        type: 'POST',
        data: {
            id: id,
            rate: rate
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function updateCommissionPat(id) {
    //preventDefault();
	console.log(id);
    var rate = $('#commission_input_' + id).val();
    console.log(id);
    $.ajax({
        url: base_url+'ajax/update_commission_Pat', // URL to the direct PHP script
        type: 'POST',
        data: {
            id: id,
            rate: rate
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function updateCommissionLab(id) {
    //preventDefault();
	console.log(id);
    var rate = $('#commission_input_' + id).val();
    console.log(id);
    $.ajax({
        url: base_url+'ajax/update_commission_Lab', // URL to the direct PHP script
        type: 'POST',
        data: {
            id: id,
            rate: rate
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

$(document).ready(function(){

    $.ajax({
        type: "GET",
        url: base_url+"ajax/get_country_code",
        data:{id:$(this).val()}, 
        beforeSend :function(){
          $('#country_code').find("option:eq(0)").html("Please wait..");
      },                         
      success: function (data) {
          /*get response as json */
          $('#country_code').find("option:eq(0)").html("Select Country Code");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
             var option = $('<option />');
             option.attr('value', this.value).text(this.label);           
             $('#country_code').append(option);
         });  


          /*ends */

      }
  });

    $("#register_form").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            country_code: "required",
            clinic_name: "required",
            mobileno: {
                required: true,
                minlength: 7,
                maxlength: 12,
                digits: true,
                remote: {
                    url: base_url+"admin/users/check_mobileno",
                    type: "post",
                    data: {
                        mobileno: function(){ return $("#mobileno").val(); }
                    }
                }
            },
            email: {
              required: true,
              email: true,
              remote: {
                url: base_url+"admin/users/check_email",
                type: "post",
                data: {
                    email: function(){ return $("#email").val(); }
                }
            }
        },
        password: {
            required: true,
            minlength: 6
        },
        confirm_password: {
           required: true,
           equalTo: "#password"
       }

   },
   messages: {
    first_name: "Please enter your first name",
    last_name: "Please enter your last name",
     clinic_name: "Please enter Clinic name",
       
    country_code: "Please select country code",
    mobileno: {
        required: "Please enter mobileno",
        maxlength: "Please enter valid mobileno",
        minlength: "Please enter valid mobileno",
        digits: "Please enter valid mobileno",
        remote: "Your mobile no already exists"
    },
    email: {
        required: "Please enter email",
        email: "Please enter valid email address",
        remote: "Your email address already exists"
    },
    password: {
        required: "Please enter password",
        minlength: "Your password must be minimum 6 characters"
    },
    confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Your password does not match"
    }
   


},
submitHandler: function(form) {

    $.ajax({
        url: base_url+'admin/users/signup',
        data: $("#register_form").serialize(),
        type: "POST",
        beforeSend: function(){
            $('#register_btn').attr('disabled',true);
            $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
        },
        success: function(res){
            $('#register_btn').attr('disabled',false);
            $('#register_btn').html('Submit');
            var obj = JSON.parse(res);

            if(obj.status===200)
            {
                $('#register_form')[0].reset();
                if($('#role').val()=='1')
                {
					
					toastr.success('Doctor details added successfully');
					doctors_table();
					
                }
                if($('#role').val()=='2')
                {
                    patients_table();
                }
                $('#user_modal').modal('hide');
                //window.location.reload();
            }
            else
            {
                toastr.error(obj.msg);
            }   
        }
    });
    return false;
}
});

});
}

if(modules=='specialization')
{ 
    var specialization_table;
    $(document).ready(function() {

    //datatables
    specialization_table = $('#specializationtable').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
         order: [[1, 'desc']], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+"admin/specialization/specialization_list",
           "type": "POST",
           "data": function ( data ) {

           },
           error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,2 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


});

    function add_specialization()
    {
        $('[name="method"]').val('insert');
    $('#specialization_form')[0].reset(); // reset form on modals
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Specialization'); // Set Title to Bootstrap modal title
    $('#specialization_images').html('');
    $('#specialization_img').val('');

}

function edit_specialization(id)
{
    $('[name="method"]').val('update');
    $('#specialization_form')[0].reset(); // reset form on modals
    
    //Ajax Load data from ajax
    $.ajax({
       url : base_url+"admin/specialization/specialization_edit/"+id,
       type: "GET",
       dataType: "JSON",
       success: function(data)
       {

        $('[name="id"]').val(data.id);
        $('[name="specialization"]').val(data.specialization);
        $('#specialization_img').val(data.specialization_img);
        $('#specialization_images').html('<img src="'+base_url+data.specialization_img+'">'); 

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Specialization'); // Set title to Bootstrap modal title

        },
        error:function(){
          window.location.href=base_url+'admin/dashboard';
      }
  });
}

function specialization_reload_table()
{
    specialization_table.ajax.reload(null,false); //reload datatable ajax 
}






function delete_specialization(id)
{
    if(confirm('Are you sure delete this specialization?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/specialization/specialization_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#specialization_form').modal('hide');
                specialization_reload_table();
                toastr.success('Specialization deleted successfully');
            },
            error:function(){
                 //window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}




$(document).ready(function (e){
    $("#specialization_form").on('submit',(function(e){
        e.preventDefault();

        var specialization=$('#specialization').val();
        var specialization_img=$('#specialization_img').val();

        if(specialization==''){
            toastr.error('Please enter specialization');
            return false;
        }

        if(specialization_img==''){
           if(!document.getElementById("specialization_image").files[0]){
              toastr.error('Please upload specialization image');         
              return false;
          }
      }

      var file = document.getElementById("specialization_image").files[0];
      if(file)
      {
         const  fileType = file['type'];
         const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
         if (!validImageTypes.includes(fileType)) {
            toastr.error('Please upload image files only');
            return false;
        }
    }
    console.log(FormData);
    $.ajax({
        url: base_url+'admin/specialization/create_specialization',
        type: "POST",
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend:function() { 

            $('#btnspecializationsave').html('<div class="spinner-border text-light" role="status"></div>');
            $('#btnspecializationsave').attr('disabled',true);

        },
        success: function(data){

          $('#btnspecializationsave').html('Submit');
          $('#btnspecializationsave').attr('disabled',false);

          var obj=jQuery.parseJSON(data);
          console.log(obj); 
          if(obj.result=='true')
          {

            toastr.success(obj.status);

            $('#modal_form').modal('hide');
            $('#specialization_form')[0].reset();

            specialization_table.ajax.reload(null,false);
        }
        else if(obj.result=='false')
        {
            //toastr.error(obj.status);
            toastr.error(obj.msg);

        }
        else if(obj.result=='exe')
        {
            //toastr.error(obj.status);
            toastr.error(obj.msg);
        }
        else
        {
            window.location.reload();
        }
    },
    error:function(){
      //window.location.href=base_url+'admin/dashboard';
  }

});
}));
});
}

if(modules=='settings'){
    var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

        $("#change_password").validate({
            rules: {
                currentpassword: {
                  required: true,
                  remote: {
                    url: base_url+"admin/profile/check_currentpassword",
                    type: "post",
                    data: {
                        currentpassword: function(){ return $("#currentpassword").val(); }
                    }
                }
            },

            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
               required: true,
               equalTo: "#password"
           },
       },
       messages: {
        currentpassword: {
            required: "Please enter current password",
            remote: "Your current password is invalid"
        },
        password: {
            required: "Please enter password",
            minlength: "Your password must be 6 characters"
        },
        confirm_password: {
            required: "Please enter confirm password",
            equalTo: "Your password does not match"
        },

    },
    submitHandler: function(form) {

        $.ajax({
            url: base_url+'admin/profile/change_password',
            data: $("#change_password").serialize(),
            type: "POST",
            beforeSend: function(){
                $('#password_btn').attr('disabled',true);
                $('#password_btn').html('<div class="spinner-border text-light" role="status"></div>');
            },
            success: function(res){
                $('#password_btn').attr('disabled',false);
                $('#password_btn').html('Update');
                var obj = JSON.parse(res);
                
                if(obj.status===200)
                {
                    $('#change_password')[0].reset();
                    toastr.success(obj.msg);

                }
                else
                {
                    toastr.error(obj.msg);
                }   
            }
        });
        return false;
    }

});

  


} 

if(modules=='country'){

    if(pages == 'country'){
     $('#country_table').DataTable();



     $(document).ready(function(){

        $( "#show_countryform" ).click(function() {
          $("#password_tab").show();
      });
        $("#country_edit").on('submit',(function(e){


            e.preventDefault();

            var sortname=$('#esortname').val();
            var country=$('#ecountry').val();
            var phonecode=$('#ephonecode').val();

            if(sortname==''){
                toastr.error('Please enter sortname');
                return false;
            }

            if(country==''){
               toastr.error('Please enter country');
               return false;
           }

           if(phonecode==''){
               toastr.error('Please enter phonecode');
               return false;
           }

           
           $.ajax({
            url: base_url+'admin/country/country_update',
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend:function() { 

                $('#btncountryupdate').html('<div class="spinner-border text-light" role="status"></div>');
                $('#btncountryupdate').attr('disabled',true);

            },
            success: function(data){

              $('#btncountryupdate').html('Submit');
              $('#btncountryupdate').attr('disabled',false);

              var obj=jQuery.parseJSON(data);
              if(obj.result=='true')
              {

                toastr.success(obj.status);

                $('#modal_form').modal('hide');
                $('#country_edit')[0].reset();

                window.location.reload();
            }
            else if(obj.result=='false')
            {
                toastr.error(obj.status);

            }
            else if(obj.result=='exe')
            {
                toastr.error(obj.status);
            }
            else
            {
                window.location.reload();
            }
        },
        error:function(){
          window.location.href=base_url+'admin/dashboard';
      }

  });
       }));



    });



     function edit_country(id)
     {
        $('[name="method"]').val('update');


    //Ajax Load data from ajax
    $.ajax({
       url : base_url+"admin/country/country_edit",
       type: "POST",
       dataType: "JSON",
       data:{id:id},
       success: function(data)
       {



        $('[name="id"]').val(data.countryid);
        $('[name="esortname"]').val(data.sortname);
        $('[name="ecountry"]').val(data.country);
        $('[name="ephonecode"]').val(data.phonecode);


            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Country'); // Set title to Bootstrap modal title

        },
        error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          });
}

function delete_country(id)
{
    if(confirm('Are you sure delete this country?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/country/country_delete/"+id,
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                // console.log(data);
                var obj=jQuery.parseJSON(data);
                
                if(obj.result=='true')
                {

                    toastr.success(obj.status);



                    window.location.reload();
                }
                else if(obj.result=='false')
                {
                    toastr.error(obj.status);

                }
                else if(obj.result=='exe')
                {
                    toastr.error(obj.status);
                }
                else
                {
                    window.location.reload();
                }
            },
            error:function(){
                 window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}




$("#country_add").validate({

    rules: {
        sortname: {
          required: true,
          remote: {
            url: base_url+"admin/country/check_sortname",
            type: "post",
            data: {
                sortname: function(){ return $("#sortname").val(); }
            }
        }
    },

    country: {
      required: true,
      remote: {
        url: base_url+"admin/country/check_country",
        type: "post",
        data: {
            country: function(){ return $("#country").val(); }
        }
    }
},
phone_code: {
  required: true,
  number :  true,
  remote: {
    url: base_url+"admin/country/check_phonecode",
    type: "post",
    data: {
        phone_code: function(){ return $("#phone_code").val(); }
    }
}
},

},
messages: {
    sortname: {
        required: "Please enter sortname",
        remote: "Your sortname is already exists"
    },
    country: {
        required: "Please enter country value",
        remote: "Your country value is already exists"
    },
    phone_code: {
        required: "Please enter country value",
		number : "Phone Code should be numbers only",
        remote: "Your country value is already exists"
    },

}

});

}

if(pages=='city'){

  // define global variable
  var city_table;
     
     function edit_city(id)
     {
        $('[name="method"]').val('update');


    //Ajax Load data from ajax
    $.ajax({
       url : base_url+"admin/country/city_edit",
       type: "POST",
       dataType: "JSON",
       data:{id:id},
       success: function(data)
       {



        $('[name="id"]').val(data.id);
        $('[name="ecity"]').val(data.city);
        


            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit City'); // Set title to Bootstrap modal title

        },
        error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          });
}

function delete_city(id)
{
    if(confirm('Are you sure delete this city?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/country/city_delete/"+id,
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                // console.log(data);
                var obj=jQuery.parseJSON(data);
                
                if(obj.result=='true')
                {

                    toastr.success(obj.status);



                    // window.location.reload();
                    city_table.ajax.reload(); // reload table with new data
                }
                else if(obj.result=='false')
                {
                    toastr.error(obj.status);

                }
                else if(obj.result=='exe')
                {
                    toastr.error(obj.status);
                }
                else
                {
                    window.location.reload();
                }
            },
            error:function(){
                 //window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}

 function city_list(country_id,state_id){

        
  

     // var city_table; 
    city_table= $('#city_list').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+'admin/country/city_list',
                "type": "POST",
                "data": function ( data ) {
                  data.country_id =country_id;
                  data.state_id= state_id;
                  
                   },
                error:function(){
                      
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,2 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


 }





    $(document).ready(function(){


        $('#state1').bind("keyup change",function() {

            
         
           var country_id = $("#country1").val();
           var state_id = $("#state1").val();
           $("#city_list").dataTable().fnDestroy()
           
            city_list(country_id,state_id);

      });




$( "#show_cityform" ).click(function() {
          $("#city_tab").show();

          // initialize select2          
          $("#country").select2();
          $("#state").select2();
      });

$("#city_edit").on('submit',(function(e){


            e.preventDefault();

            var city=$('#ecity').val();
            

            if(city==''){
                toastr.error('Please enter city');
                return false;
            }

            

           
           $.ajax({
            url: base_url+'admin/country/city_update',
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend:function() { 

                $('#btncityupdate').html('<div class="spinner-border text-light" role="status"></div>');
                $('#btncityupdate').attr('disabled',true);

            },
            success: function(data){

              $('#btncityupdate').html('Submit');
              $('#btncityupdate').attr('disabled',false);

              var obj=jQuery.parseJSON(data);
              if(obj.result=='true')
              {

                toastr.success(obj.status);

                $('#modal_form').modal('hide');
                $('#city_edit')[0].reset();

                window.location.reload();
            }
            else if(obj.result=='false')
            {
                toastr.error(obj.status);

            }
            else if(obj.result=='exe')
            {
                toastr.error(obj.status);
            }
            else
            {
                window.location.reload();
            }
        },
        error:function(){
          //window.location.href=base_url+'admin/dashboard';
      }

  });
       }));


$.ajax({
            type: "GET",
            url: base_url+"ajax/get_country",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $('#country').find("option:eq(0)").html("please wait");
          },                         
          success: function (data) {
              /*get response as json */
              $('#country').find("option:eq(0)").html("select country");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#country').append(option);
                 
             });  


              $('#country').val(country);


              /*ends */

          }
      });

        $.ajax({
            type: "GET",
            url: base_url+"ajax/get_country",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $('#country1').find("option:eq(0)").html("please wait");
          },                         
          success: function (data) {
              /*get response as json */
              $('#country1').find("option:eq(0)").html("select country");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#country1').append(option);
                 
             });  


              $('#country1').val(country);state_list


              /*ends */

          }
      });

        $('body').on('change', '.country', function(){


          $.ajax({
            type: "POST",
            url: base_url+"ajax/get_state",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $("#statestate_list option:gt(0)").remove(); 

              $('#state').find("option:eq(0)").html("Please wait");

          },                         
          success: function (data) {
              /*get response as json */
             // alert(data);
			 $('#state').html("");
			 $('#state').find("option:eq(0)").html("Select state");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#state').append(option);
             });  

              /*ends */

          }
      });
      });


        $('body').on('change', '#country1', function(){


          $.ajax({
            type: "POST",
            url: base_url+"ajax/get_state",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $("#state1 option:gt(0)").remove(); 

              $('#state1').find("option:eq(0)").html("Please wait");

          },                         
          success: function (data) {
              /*get response as json */
              $('#state1').find("option:eq(0)").html("Select state");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#state1').append(option);
             });  

              /*ends */

          }
      });
      });


$( "#city_add" ).click(function() {
          var country=$("#country").val();
          var state=$("#state").val();
          var city=$("#city").val();


            if(country=='' || country == null){
                toastr.error('Please select country');
                return false;
            }

            if(state==''){
               toastr.error('Please select state');
               return false;
           }

           if(city==''){
               toastr.error('Please enter city');
               return false;
           }
		   var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
			if(!characterReg.test(city)) {
				toastr.error('No Special Chars or Numbers Allowed in the City Name');
               	return false;
			}          
           var dataString='country='+country+'&state='+state+'&city='+city;
           
           $.ajax({
            url: base_url+'admin/country/city_insert',
            type: "POST",
            data: dataString,

            beforeSend:function() { 

                $('#city_add').html('<div class="spinner-border text-light" role="status"></div>');
                $('#city_add').attr('disabled',true);

            },
            success: function(data){

              $('#city_add').html('Submit');
              $('#city_add').attr('disabled',false);

              var obj=jQuery.parseJSON(data);
              if(obj.result=='true')
              {

                toastr.success(obj.status);

                

                window.location.reload();
            }
            else if(obj.result=='false')
            {
                toastr.error(obj.status);

            }
            else if(obj.result=='exe')
            {
                toastr.error(obj.status);
            }
            else
            {
                window.location.reload();
            }
        },
        error:function(){
          window.location.href=base_url+'admin/dashboard';
      }

  });


      });





            });

  // country and state dropdown make it searchable
  $("#country1").select2();
  $("#state1").select2();
}

if(pages=='state')
{

   // state_list(1);

    function state_list(country_id){

        
     
    //Stock table value
     var state_table; 
    state_table= $('#state_table').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+'admin/country/state_list',
                "type": "POST",
                "data": function ( data ) {
                  data.country_id =country_id;
                   },
                error:function(){
                      
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [0,2], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });

 }

  function edit_state(id)
     {
        $('[name="method"]').val('update');


    //Ajax Load data from ajax
    $.ajax({
       url : base_url+"admin/country/state_edit",
       type: "POST",
       dataType: "JSON",
       data:{id:id},
       success: function(data)
       {



        $('[name="id"]').val(data.id);
        $('[name="estate"]').val(data.statename);
        $('[name="statecode"]').val(data.state_code);
        


            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit State'); // Set title to Bootstrap modal title

        },
        error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          });
}

function delete_state(id)
{
    if(confirm('Are you sure delete this state?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/country/state_delete/"+id,
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                // console.log(data);
                var obj=jQuery.parseJSON(data);
                
                if(obj.result=='true')
                {

                    toastr.success(obj.status);



                    window.location.reload();
                }
                else if(obj.result=='false')
                {
                    toastr.error(obj.status);

                }
                else if(obj.result=='exe')
                {
                    toastr.error(obj.status);
                }
                else
                {
                    window.location.reload();
                }
            },
            error:function(){
                 //window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}

    $(document).ready(function(){

        $('#country1').bind("keyup change",function() {

            
         
           var country_id = $("#country1").val();
           $("#state_table").dataTable().fnDestroy()
           
           state_list(country_id);

      });

$( "#show_stateform" ).click(function() {
          $("#state_tab").show();
      });

$( "#show_cityform" ).click(function() {
          $("#city_tab").show();
      });




$( "#state_add" ).click(function() {
          var country=$("#country").val();
          var state=$("#state").val();

            if(country=='' || country == null){
                toastr.error('Please select country');
                return false;
            }

            if(state==''){
               toastr.error('Please enter state');
               return false;
           }
			var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
			if(!characterReg.test(state)) {
				toastr.error('No Special Chars or Numbers Allowed in the State Name');
               	return false;
			}  
            var state_code = $("#state_code").val();
            if(state_code==''){
                toastr.error('Please enter statecode');
                return false;
            }
            var characterRegExp = /^[a-zA-Z]*$/;
            if(!characterRegExp.test(state_code)) {
				toastr.error('Only Alaphabets Allowed in the State Code');
               	return false;
			} 
           var dataString='country='+country+'&state='+state+'&state_code='+state_code;
           
           $.ajax({
            url: base_url+'admin/country/state_insert',
            type: "POST",
            data: dataString,

            beforeSend:function() { 

                $('#state_add').html('<div class="spinner-border text-light" role="status"></div>');
                $('#state_add').attr('disabled',true);

            },
            success: function(data){

              $('#state_add').html('Submit');
              $('#state_add').attr('disabled',false);

              var obj=jQuery.parseJSON(data);
              if(obj.result=='true')
              {

                toastr.success(obj.status);

                

                window.location.reload();
            }
            else if(obj.result=='false')
            {
                toastr.error(obj.status);

            }
            else if(obj.result=='exe')
            {
                toastr.error(obj.status);
            }
            else
            {
                window.location.reload();
            }
        },
        error:function(){
          window.location.href=base_url+'admin/dashboard';
      }

  });


      });

$("#state_edit").on('submit',(function(e){


            e.preventDefault();

            var statename=$('#estate').val();
            

            if(statename==''){
                toastr.error('Please enter statename');
                return false;
            }

            var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
			if(!characterReg.test(statename)) {
				toastr.error('No Special Chars or Numbers Allowed in the State Name');
               	return false;
			} 

            
            var state_code = $("#statecode").val();
            if(state_code==''){
                toastr.error('Please enter statecode');
                return false;
            }
            var characterRegExp = /^[a-zA-Z]*$/;
            if(!characterRegExp.test(state_code)) {
				toastr.error('Only Alaphabets Allowed in the State Code');
               	return false;
			} 
           
           $.ajax({
            url: base_url+'admin/country/state_update',
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend:function() { 

                $('#btnstateupdate').html('<div class="spinner-border text-light" role="status"></div>');
                $('#btnstateupdate').attr('disabled',true);

            },
            success: function(data){

              $('#btnstateupdate').html('Submit');
              $('#btnstateupdate').attr('disabled',false);

              var obj=jQuery.parseJSON(data);
              if(obj.result=='true')
              {

                toastr.success(obj.status);

                $('#modal_form').modal('hide');
                $('#state_edit')[0].reset();

                window.location.reload();
            }
            else if(obj.result=='false')
            {
                toastr.error(obj.status);

            }
            else if(obj.result=='exe')
            {
                toastr.error(obj.status);
            }
            else
            {
                window.location.reload();
            }
        },
        error:function(){
          //window.location.href=base_url+'admin/dashboard';
      }

  });
       }));



        $.ajax({
            type: "GET",
            url: base_url+"ajax/get_country",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $('#country').find("option:eq(0)").html("please wait");
          },                         
          success: function (data) {
              /*get response as json */
              $('#country').find("option:eq(0)").html("select country");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#country').append(option);
                 
             });  


              $('#country').val(country);


              /*ends */

          }
      });

        $.ajax({
            type: "GET",
            url: base_url+"ajax/get_country",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $('#country1').find("option:eq(0)").html("please wait");
          },                         
          success: function (data) {
              /*get response as json */
              $('#country1').find("option:eq(0)").html("select country");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#country1').append(option);
                 
             });  


              $('#country1').val(country);


              /*ends */

          }
      });






        $('body').on('change', '.country', function(){


          $.ajax({
            type: "POST",
            url: base_url+"ajax/get_state",
            data:{id:$(this).val()}, 
            beforeSend :function(){
              $("#state option:gt(0)").remove(); 

              $('#state').find("option:eq(0)").html("Please wait");

          },                         
          success: function (data) {
              /*get response as json */
              $('#state').find("option:eq(0)").html("Select state");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
                 var option = $('<option />');
                 option.attr('value', this.value).text(this.label);           
                 $('#state').append(option);
             });  

              /*ends */

          }
      });
      });





    });

    // country dropdown make it searchable
    $("#country1").select2();
}

}




if(modules=='profile')
{
    $(document).ready(function(){
        $("#profile_form").validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email:true
                },
                country: "required",
                city: "required",
                biography: { required:true, minlength:50, maxlength:250}

            },
            messages: {
                name: "Please enter name",
                email: {
                    required: "Please enter email",
                    email:"Please enter valid email address"
                },
                country: "Please enter country",
                city: "Please enter city",
                biography: { required : "Please enter biography", minlength: "Minimum Character Length is 50 only", maxlength: "Maximum Character Length is 500 only"}
            },
            submitHandler: function(form) {

                $.ajax({
                    url: base_url+'admin/profile/update_profile',
                    data: $("#profile_form").serialize(),
                    type: "POST",
                    beforeSend: function(){
                        $('#profile_btn').attr('disabled',true);
                        $('#profile_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function(res){
                        $('#profile_btn').attr('disabled',false);
                        $('#profile_btn').html('Update');
                        var obj = JSON.parse(res);

                        if(obj.status===200)
                        {
                            $('.admin_name').html($('#name').val());
                            $('.admin_email').html($('#email').val());
                            $('.admin_location').html($('#city').val()+', '+$('#country').val());
                            $('.admin_biography').html($('#biography').val());
                            $('#profile_modal').modal('hide');
                            toastr.success(obj.msg);

                        }
                        else
                        {
                            toastr.error(obj.msg);
                        }   
                    }
                });
                return false;
            }

        });

        $("#team_member_form").validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email:true
                },
            },
            messages: {
                name: "Please enter name",
                email: {
                    required: "Please enter email",
                    email:"Please enter valid email address"
                },
            },
            submitHandler: function(form) {

                $.ajax({
                    url: base_url+'admin/profile/team_member_profile',
                    data: $("#team_member_form").serialize(),
                    type: "POST",
                    beforeSend: function(){
                        $('#profile_btn').attr('disabled',true);
                        $('#profile_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function(res){
                        $('#profile_btn').attr('disabled',false);
                        $('#profile_btn').html('Update');
                        var obj = JSON.parse(res);

                        if(obj.status===200)
                        {
                            
                            toastr.success(obj.msg);

                        }
                        else
                        {
                            toastr.error(obj.msg);
                        }   
                    }
                });
                return false;
            }

        });

        $("#change_password").validate({
            rules: {
                currentpassword: {
                  required: true,
                  remote: {
                    url: base_url+"admin/profile/check_currentpassword",
                    type: "post",
                    data: {
                        currentpassword: function(){ return $("#currentpassword").val(); }
                    }
                }
            },

            password: {
                required: true,
                minlength: 6,
                remote: {
                  url: base_url+"admin/profile/check_newpassword",
                  type: "post",
                  data: {
                    password: function(){ return $("#password").val(); }
                  }
                }
            },
            confirm_password: {
               required: true,
               equalTo: "#password"
           },
       },
       messages: {
        currentpassword: {
            required: "Please enter current password",
            remote: "Your current password is invalid"
        },
        password: {
            required: "Please enter password",
            minlength: "Your password must be 6 characters",
            remote: "You used this password recently. Please choose a different one."
        },
        confirm_password: {
            required: "Please enter confirm password",
            equalTo: "Your password does not match"
        },

    },
    submitHandler: function(form) {

        $.ajax({
            url: base_url+'admin/profile/change_password',
            data: $("#change_password").serialize(),
            type: "POST",
            beforeSend: function(){
                $('#password_btn').attr('disabled',true);
                $('#password_btn').html('<div class="spinner-border text-light" role="status"></div>');
            },
            success: function(res){
                $('#password_btn').attr('disabled',false);
                $('#password_btn').html('Update');
                var obj = JSON.parse(res);
                
                if(obj.status===200)
                {
                    $('#change_password')[0].reset();
                    toastr.success(obj.msg);

                }
                else
                {
                    toastr.error(obj.msg);
                }   
            }
        });
        return false;
    }

});



    });
	
		//For fetching city based on country code
	 $('#country').on('change', function(){
        var countryID = $('#country').val();
		// alert(countryID);
		if(countryID){
				 $.ajax({
				type: "POST",
				url: base_url+"ajax/get_city_of_country",
				data:{country: countryID}, 
				beforeSend :function(){
				  $("#city option:gt(0)").remove(); 	
				  $('#city').find("option:eq(0)").html("Please wait");	
			  },                         
			  success: function (data) {
				  //alert(data);
				  /*get response as json */
				  $('#city').find("option:eq(0)").html("Select city");
				  var obj=jQuery.parseJSON(data);
				  $(obj).each(function()
				  {
					 var option = $('<option />');
					 option.attr('value', this.value).text(this.label);           
					 $('#city').append(option);
				 });  
	
				  /*ends */
	
			  }
		  });
			}else{
           // $('#state').html('<option value="">Select country first</option>');
            $('#city').html('<option value="">Select city first</option>'); 
        }
    });
}

if(modules=='language'){

    if(pages=='index')
    {
      function change_status(id)
      {
        var status= $('#lang_status'+id).attr('data-status');
        if(status==1)
        {
           update_language = '2';
       }
       if(status==2)
       {
           update_language = '1';
       }

       $.ajax({
          type:'POST',
          url: base_url+'admin/language/update_language_status',
          data : {id:id,update_language:update_language},
          success:function(response)
          {      
              if(response==0)
              {

                toastr.error('Default Language cannot be inactive.');
            }
            else if(response==1)
            {
             if(status==1)
             {
               $('#lang_status'+id).attr('data-status',2);
               $('#lang_status'+id).removeClass("label label-success").addClass("label label-danger");
               $('#texts'+id).text('In Active');
               $('#default_language'+id).hide();

           }
           if(status==2)
           {
               $('#lang_status'+id).attr('data-status',1);
               $('#lang_status'+id).removeClass("label label-danger").addClass("label label-success");
               $('#texts'+id).text('Active');
               $('#default_language'+id).show();
           }
       }
       else
       {

       }
   }                
});

   }


   $(document).ready(function(){     
    $('.active_lang').on('change', function (e, data) { 
        var update_language = ''; 
        var sts_str   = '';
        var id = $(this).attr('data-id');
        if($(this).is(':checked')) { 
         update_language = '1'; 
         sts_str   = 'Active';
     } else { 
         update_language = '2';  
         sts_str   = 'InActive';
     }  
     if(update_language != '') {  

     } 
 })

    $('.default_lang').on('change', function (e, data) { 
      var id = $(this).attr('data-id');
      $.ajax({
        type:'POST',
        url: base_url+'admin/language/update_language_default',
        data : {id:id},
        success:function(response)
        {  
           if(response==0)
           {    
              $('#default_language'+id).attr('checked',false);
          }
          else
          {
              $('#default_language'+id).attr('checked',true);
          }
      }                
  });

  });

    $("#add_language").validate({
        rules: {
            language: {
              required: true,
              remote: {
                url: base_url+"admin/language/check_language",
                type: "post",
                data: {
                    language: function(){ return $("#language").val(); }
                }
            }
        },

        language_value: {
          required: true,
          remote: {
            url: base_url+"admin/language/check_language_value",
            type: "post",
            data: {
                language_value: function(){ return $("#language_value").val(); }
            }
        }
    },
    tag: "required"
},
messages: {
    language: {
        required: "Please enter language..",
        remote: "Your language is already exists"
    },
    language_value: {
        required: "Please enter language value",
        remote: "Your language value is already exists"
    },
    tag:"Please select tag",


}

});



});
}

if(pages=='keywords'){
  var language_table; 
  language_table= $('#language_table').DataTable({
    'ordering':false,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/language/language_list',
           "type": "POST",
           "data": function ( data ) {
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


  function update_language(lang_key, lang)
  {
      var cur_val = $('input[name="'+lang_key+'['+lang+']"]').val();
      var prev_val = $('input[name="prev_'+lang_key+'['+lang+']"]').val();

      $.post(base_url+'admin/language/update_language',{lang_key:lang_key, lang:lang, cur_val:cur_val},function(data){
        if(data == 1)
        {

        }
        else if(data == 0)
        {
          $('input[name="'+lang_key+'['+lang+']"]').val(prev_val);
          toastr.error('Sorry, This keyword already exist!');

      }
      else if(data == 2)
      {
          $('input[name="'+lang_key+'['+lang+']"]').val(prev_val);
          toastr.error('Sorry, This field should not be empty!');

      }
  });

  }

}


if(pages=='add_page')
{
  function keyword_validation(){

    var error =0;
    var page_name = $('#page_name').val().trim();
     var characterReg1 = /^\s*[a-zA-Z,\s]+\s*$/;

    if(page_name==""){
      $('.keyword_error').show();
      error =1; 
    }else{
      $('.keyword_error').hide();
    }
	if(!characterReg1.test(page_name) && page_name!="") {
	$('.keyword_error_spc').show();
	 error =1; 
	}
	else{
	 $('.keyword_error_spc').hide();	
	}

    if(error==0){
      return true;
    }else{
      return false;
    }
  }
}

if(pages=='add_keywords'){
 /*function add_keyword_validation(){ 
 	 var error =0;
      var multiple_key = $('#multiple_key').val().trim();
	   var characterReg1 = /^\s*[_a-zA-Z,\s]+\s*$/;
    
	if(multiple_key==''){
	 $('.keyword_error_empty').show();
	 error =1; 	
	}	
    else if(!characterReg1.test(multiple_key) && multiple_key!="") {
	$('.keyword_error_spc').show();
	 error =1; 
	}
	else{
	 $('.keyword_error_spc').hide();	
	 $('.keyword_error_empty').hide();
	}

      if(error==0){
        return true;
      }else{
        return false;
      }
 
 }*/

 /*submit form ajax template*/
  $("#add_keywords_form").submit(function(e) {
      // e.preventDefault();
  }).validate({
      rules: {
        multiple_key: {
          required: true,
          maxlength: 100,
          // accept_chars: true,
        },
      },
      messages: {
        multiple_key: {
          required: "Please enter the title",
          maxlength: "Title should be maximum 100 characters",
        }
      },
      submitHandler: function (form) {
        return true;
      }
  });
  /*submit form ajax template*/
	
}

if(pages=='add_app_keywords')
{
  function keyword_validation(){

      var error =0;
      var field_name = $('#field_name').val().trim();
      var name = $('#name').val().trim();


      if(field_name==""){
        $('.field_name_error').show();
        error =1; 
      }else{
        $('.field_name_error').hide();

      }

      if(name==""){
        $('.name_error').show();
        error =1; 
      }else{
        $('.name_error').hide();

      }

      if(error==0){
        return true;
      }else{
        return false;
      }
    }

 }


 if(pages=='app_keywords'){
  var language_table; 
  language_table= $('#app_language_table').DataTable({
    'ordering':false,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+'admin/language/app_language_list',
           "type": "POST",
           "data": function ( data ) {
            data.page_key = $('#page_key').val();
           },
           error:function(){

           }
       },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


   function update_multi_lang()
    {
               
        $("#form_id").submit();
    }


}


} 


    
if(modules=='unit')
{ 
  console.log(modules)
    var unit_table;
    $(document).ready(function() {

        //datatables
        unit_table = $('#unittable').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/unit/unit_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [0,2], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


    });

    function add_unit()
    {
        $('[name="method"]').val('insert');
        $('#unit_form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Unit'); // Set Title to Bootstrap modal title
          
    }

    function edit_unit(id)
    {
        $('[name="method"]').val('update');
        $('#unit_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
             url : base_url+"admin/unit/unit_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="unit_name"]').val(data.unit_name);
                
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Unit'); // Set title to Bootstrap modal title

            },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }
        });
    }

    function unit_reload_table()
    {
        unit_table.ajax.reload(null,false); //reload datatable ajax 
    }



    function delete_unit(id)
    {
        if(confirm('Are you sure delete this unit?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"admin/unit/unit_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#unit_form').modal('hide');
                    unit_reload_table();
                    toastr.success('Unit deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }


        $(document).ready(function (e){
            $("#unit_form").on('submit',(function(e){
                e.preventDefault();

                var unit=$('#unit_name').val();
                
                if(unit.trim()==''){
                    toastr.error('Please enter unit');
                    return false;
                }

                $.ajax({
                    url: base_url+'admin/unit/create_unit',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btnunitsave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnunitsave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btnunitsave').html('Submit');
                      $('#btnunitsave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            
                            toastr.success(obj.status);

                              $('#modal_form').modal('hide');
                              $('#unit_form')[0].reset();
                              
                              unit_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                           
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
}

if(modules=='categories' && theme=='admin')
{
  var categories_table;
    $(document).ready(function() {

        //datatables
        categories_table = $('#categoriestable1').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/categories/categories_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [0,2], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


    });

    function add_categories()
    {
        $('[name="method"]').val('insert');
        $('#categories_form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Category'); // Set Title to Bootstrap modal title
        $('#category_images').html('');
        $('#category_img').val('');
       
            
    }

    function edit_categories(id)
    {
        $('[name="method"]').val('update');
        $('#categories_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
             url : base_url+"admin/categories/categories_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="category_name"]').val(data.category_name);
                $('#category_img').val(data.category_image);
                $('#category_images').html('<img src="'+base_url+data.category_image+'" style="width:100px;height:100px">');
                
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Category'); // Set title to Bootstrap modal title

            },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }
        });
    }

    function categories_reload_table()
    {
        categories_table.ajax.reload(null,false); //reload datatable ajax 
    }



    function delete_categories(id)
    {
        if(confirm('Are you sure delete this category?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"admin/categories/categories_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#categories_form').modal('hide');
                    categories_reload_table();
                    toastr.success('Category deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }


        $(document).ready(function (e){
            $("#categories_form").on('submit',(function(e){
                e.preventDefault();

                var category_name=$('#category_name').val();
                var category_img=$('#category_img').val();
                
                if(category_name==''){
                    toastr.error('Please enter category name');
                    return false;
                }

                if(category_img=='')
                {
                   if(!document.getElementById("category_image").files[0]){
                    toastr.error('Please upload category image');         
                          return false;
                    }
                }

                
                var file = document.getElementById("category_image").files[0];
                if(file)
                {
                  const  fileType = file['type'];
                    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
                    if (!validImageTypes.includes(fileType)) {
                        toastr.error('Please upload image files only');
                        return false;
                    }
                }
                 
                            
                 $.ajax({
                    url: base_url+'admin/categories/create_categories',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btncategoriessave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btncategoriessave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btncategoriessave').html('Submit');
                      $('#btncategoriessave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            
                            toastr.success(obj.status);

                              $('#modal_form').modal('hide');
                              $('#categories_form')[0].reset();
                              
                              categories_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                           
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
}

if(modules=='subcategories' && theme=='admin')
{

 var subcategories_table1;
    $(document).ready(function() {

       $.ajax({
        type: "GET",
        url: base_url+"ajax/get_product_category",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $('#category').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('#category').find("option:eq(0)").html("Select Category");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#category').append(option);
         });  

          
        }
      });

        //datatables
        subcategories_table1 = $('#subcategoriestable1').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/subcategories/subcategories_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,3 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


    });

    function add_subcategories()
    {
        $('[name="method"]').val('insert');
        $('#subcategories_form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Subcategory'); // Set Title to Bootstrap modal title
       
            
    }

    function edit_subcategories(id)
    {
        $('[name="method"]').val('update');
        $('#subcategories_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
             url : base_url+"admin/subcategories/subcategories_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="category"]').val(data.category);
                $('[name="subcategory_name"]').val(data.subcategory_name);
                
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Subcategory'); // Set title to Bootstrap modal title

            },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }
        });
    }

    function subcategories_reload_table()
    {
        subcategories_table1.ajax.reload(null,false); //reload datatable ajax 
    }



    function delete_subcategories(id)
    {
        if(confirm('Are you sure delete this subcategory?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"admin/subcategories/subcategories_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#subcategories_form').modal('hide');
                    subcategories_reload_table();
                    toastr.success('Subcategory deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }


        $(document).ready(function (e){
            $("#subcategories_form").on('submit',(function(e){
                e.preventDefault();

                var category=$('#category').val();
                var subcategory_name=$('#subcategory_name').val();
                var characterReg1 = /^\s*[a-zA-Z,\s]+\s*$/;
				
                if(category==''){
                    toastr.error('Please select category');
                    return false;
                }                
                if(subcategory_name==''){
                    toastr.error('Please enter subcategory nameee');
                    return false;
                }
				 
				if(!characterReg1.test(subcategory_name)) {
					toastr.error('No Special Chars or Numbers Allowed in the City Name');
					return false;
				}   
                             
                 $.ajax({
                    url: base_url+'admin/subcategories/create_subcategories',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btnsubcategoriessave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnsubcategoriessave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btnsubcategoriessave').html('Submit');
                      $('#btnsubcategoriessave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            
                            toastr.success(obj.status);

                              $('#modal_form').modal('hide');
                              $('#subcategories_form')[0].reset();
                              
                              subcategories_table1.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                           
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            //window.location.reload();
                        }
                    },
                error:function(){
                     // window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
} 

if(modules == 'pharmacy' && theme=='admin'){

    if(pages=='index')
    {
        var pharmacy_products_table;
        $(document).ready(function() {

            $.ajax({
                type: "GET",
                url: base_url+"admin/pharmacy/get_product_category",
                data:{id:$(this).val()}, 
                beforeSend :function(){
                $('#category_id').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {
                  /*get response as json */
                   $('#category_id').find("option:eq(0)").html("--Select Category--");
                  var obj=jQuery.parseJSON(data);
                  $(obj).each(function()
                  {
                   var option = $('<option />');
                   option.attr('value', this.value).text(this.label);           
                   $('#category_id').append(option);
                 });  
                 
                }
            });

            $.ajax({
                type: "GET",
                url: base_url+"admin/pharmacy/get_product_unit",
                data:{id:$(this).val()}, 
                beforeSend :function(){
                $('#unit_id').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {
                  /*get response as json */
                   $('#unit_id').find("option:eq(0)").html("--Select Unit--");
                  var obj=jQuery.parseJSON(data);
                  $(obj).each(function()
                  {
                   var option = $('<option />');
                   option.attr('value', this.value).text(this.label);           
                   $('#unit_id').append(option);
                 });  
                 
                }
            });

            $('#category_id').on('change',function(){
                $.ajax({
                    type: "POST",
                    url: base_url+"admin/pharmacy/get_product_subcategory",
                    data:{id:$(this).val()},
                    beforeSend :function(){
                    $('#sub_category_id').find('option').not(':first').remove();
                    $('#sub_category_id').find("option:eq(0)").html("Please wait..");
                    },                         
                    success: function (data) {
                      /*get response as json */
                    $('#sub_category_id').find("option:eq(0)").html("--Select Sub Category--");
                      var obj=jQuery.parseJSON(data);
                      $(obj).each(function()
                      {
                       var option = $('<option />');
                       option.attr('value', this.value).text(this.label);           
                       $('#sub_category_id').append(option);
                     });  
                      
                    }
                });
            });

            $('#bulk_upload_form').on('submit',function(e){
              e.preventDefault();

              var file = document.getElementById("products").files[0];
              if(file)
              {
                const  fileType = file['type'];
                // console.log(fileType);
                  const validImageTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'image/png'];
                  if (!validImageTypes.includes(fileType)) {
                      toastr.error('Please upload excel file only');
                      return false;
                  }
              }

            });

            //datatables
            pharmacy_products_table = $('#pharmacyproductstable1').DataTable({
                'ordering':false,
                "processing": true, //Feature control the processing indicator.
                'bnDestroy' :true,
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.

                // Load data for the table's content from an Ajax source
                "ajax": {
                     "url": base_url+"admin/pharmacy/product_list",
                    "type": "POST",
                    "data": function ( data ) {
                       
                    },
                    error:function(){
                          //window.location.href=base_url+'admin/dashboard';
                        }
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                },
                ],

            });


        });

        function add_product()
        {
            $('[name="method"]').val('insert');
            $('#product_form')[0].reset(); // reset form on modals
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add Product'); // Set Title to Bootstrap modal title
            $('#product_images').html('');
            $('#product_img').val('');
           
                
        }

        function edit_product(id)
        {
            $('[name="method"]').val('update');
            $('#product_form')[0].reset(); // reset form on modals

            $('#sub_category_id').find('option').not(':first').remove();
            
            //Ajax Load data from ajax
            $.ajax({
                 url : base_url+"admin/pharmacy/product_edit/"+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {

                    var sub_cat = data.sub_cat;
                    var data = data.data;
                    $('[name="id"]').val(data.id);
                    $('[name="product_name"]').val(data.product_name);
                    $('#product_img').val(data.product_image);
                    $('#product_images').html('<img src="'+base_url+data.product_image+'" style="width:80px;height:30px">');
                    $('[name="category_id"]').val(data.category_id);
                    for(i=0;i<sub_cat.length;i++){
                        // console.log(sub_cat[i].subcategory_name);
                        var option = $('<option />');
                        option.attr('value', sub_cat[i].id).text(sub_cat[i].subcategory_name);
                        $('#sub_category_id').append(option);
                    }
                    $('[name="sub_category_id"]').val(data.sub_category_id);
                    $('[name="product_stock"]').val(data.product_stock);
                    $('[name="unit_id"]').val(data.unit_id);
                    $('[name="unit_value"]').val(data.unit_value);
                    $('[name="product_price"]').val(data.product_price);
                    $('[name="discount_value"]').val(data.discount_value);
                    $('[name="discount_type"]').val(data.discount_type);
                    $('[name="product_description"]').val(data.product_description);
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit Product'); // Set title to Bootstrap modal title

                },
                error:function(){
                    window.location.href=base_url+'admin/dashboard';
                }
            });
        }

        function pharmacy_products_reload_table()
        {
            pharmacy_products_table.ajax.reload(null,false); //reload datatable ajax 
        }



        function delete_product(id)
        {
            if(confirm('Are you sure delete this product?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : base_url+"admin/pharmacy/product_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#product_form').modal('hide');
                        pharmacy_products_reload_table();
                        toastr.success('Product deleted successfully');
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

        function status_product(id)
        {
            if(confirm('Are you sure to change the status?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : base_url+"admin/pharmacy/product_status/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#product_form').modal('hide');
                        pharmacy_products_reload_table();
                        toastr.success('Status Changed Successfully');
                    },
                    error:function(){
                        window.location.href=base_url+'admin/dashboard';
                    }
                });

            }
        }


            $(document).ready(function (e){
                $("#product_form").on('submit',(function(e){
                    e.preventDefault();

                    var product_name=$('#product_name').val();
					 //var characterReg2 = /^\s*[a-zA-Z,\s]+\s*$/;
                    var product_img=$('#product_img').val();
                    var category_id=$('#category_id').val();
                    var sub_category_id=$('#sub_category_id').val();
                    var product_stock=$('#product_stock').val();
                    var unit_value=$('#unit_value').val();
					var unit_id=$('#unit_id').val();                   
                    var product_price=$('#product_price').val();
                    var product_description=$('#product_description').val();
                    
                    if(product_name==''){
                        toastr.error('Please enter product name');
                        return false;
                    }
					
					/*if(!characterReg2.test(product_name)) {
						toastr.error('No Special Chars or Numbers Allowed in the Product Name');
						return false;
					}*/  					
                    if(category_id==''){
                        toastr.error('Please select category');
                        return false;
                    }

                    if(sub_category_id==''){
                        toastr.error('Please select sub-category');
                        return false;
                    }

                    if(product_stock==''){
                        toastr.error('Please enter product stock');
                        return false;
                    }

                    if(unit_value==''){
                        toastr.error('Please enter unit');
                        return false;
                    }

                    if(unit_id==''){
                        toastr.error('Please select unit type');
                        return false;
                    }

                    if(product_price==''){
                        toastr.error('Please enter product price');
                        return false;
                    }

                    if(product_description==''){
                        toastr.error('Please enter product description');
                        return false;
                    }

                    if(product_img=='')
                    {
                       if(!document.getElementById("product_image").files[0]){
                        toastr.error('Please upload product image');         
                              return false;
                        }
                    }

                    
                    var file = document.getElementById("product_image").files[0];
                    if(file)
                    {
                      const  fileType = file['type'];
                        const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
                        if (!validImageTypes.includes(fileType)) {
                            toastr.error('Please upload image files only');
                            return false;
                        }
                    }
                     
                                
                     $.ajax({
                        url: base_url+'admin/pharmacy/create_product',
                        type: "POST",
                        data:  new FormData(this),
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend:function() { 

                            $('#btnproductsave').html('<div class="spinner-border text-light" role="status"></div>');
                            $('#btnproductsave').attr('disabled',true);
                      
                        },
                        success: function(data){

                          $('#btnproductsave').html('Submit');
                          $('#btnproductsave').attr('disabled',false);
                                  
                           var obj=jQuery.parseJSON(data);
                            if(obj.result=='true')
                            {
                                
                                toastr.success(obj.status);

                                  $('#modal_form').modal('hide');
                                  $('#product_form')[0].reset();
                                  
                                  pharmacy_products_reload_table();
                            }
                            else if(obj.result=='false')
                            {
                                toastr.error(obj.status);
                               
                            }
                             else if(obj.result=='exe')
                            {
                                toastr.error(obj.status);
                            }
                            else
                            {
                                window.location.reload();
                            }
                        },
                        error:function(){
                          window.location.href=base_url+'admin/dashboard';
                        }

                    });
                }));
            });
    }

    if(pages=='products')
    {


            $.ajax({
                type: "GET",
                url: base_url+"admin/pharmacy/get_product_category",
                //data:{id:$(this).val()},
                beforeSend :function(){
                $('#category_id').find("option:eq(0)").html("Please wait..");
                },                        
                success: function (data) {
                  /*get response as json */
                   $('#category_id').find("option:eq(0)").html("--Select Category--");
                  var obj=jQuery.parseJSON(data);
                  $(obj).each(function()
                  {
                   var option = $('<option />');
                   option.attr('value', this.value).text(this.label);          
                   $('#category_id').append(option);
                 });  
                 
                }
            });

            $.ajax({
                type: "GET",
                url: base_url+"admin/pharmacy/get_product_unit",
                //data:{id:$(this).val()},
                beforeSend :function(){
                $('#unit_id').find("option:eq(0)").html("Please wait..");
                },                        
                success: function (data) {
                  /*get response as json */
                   $('#unit_id').find("option:eq(0)").html("--Select Unit--");
                  var obj=jQuery.parseJSON(data);
                  $(obj).each(function()
                  {
                   var option = $('<option />');
                   option.attr('value', this.value).text(this.label);          
                   $('#unit_id').append(option);
                 });  
                 
                }
            });

            $.ajax({
                type: "GET",
                url: base_url+"admin/pharmacy/get_product_subcategory",
                // data:{id:$(this).val()},
                beforeSend :function(){
                $('#sub_category_id').find('option').not(':first').remove();
                    $('#sub_category_id').find("option:eq(0)").html("Please wait..");
                },                        
                success: function (data) {
                   $('#sub_category_id').find("option:eq(0)").html("--Select Category--");
                  var obj=jQuery.parseJSON(data);
                  $(obj).each(function()
                  {
                   var option = $('<option />');
                   option.attr('value', this.value).text(this.label);          
                   $('#sub_category_id').append(option);
                  });  
                }
            });

            $('#category_id').on('change',function(){
                $.ajax({
                    type: "POST",
                    url: base_url+"admin/pharmacy/get_product_subcategory",
                    data:{id:$(this).val()},
                    beforeSend :function(){
                    $('#sub_category_id').find('option').not(':first').remove();
                    $('#sub_category_id').find("option:eq(0)").html("Please wait..");
                    },                        
                    success: function (data) {
                      /*get response as json */
                    $('#sub_category_id').find("option:eq(0)").html("--Select Sub Category--");
                      var obj=jQuery.parseJSON(data);
                      $(obj).each(function()
                      {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);          
                        $('#sub_category_id').append(option);
                      });  
                    }
                });
            });






        var admin_products_table;

        $(document).ready(function(){

          // console.log(modules);

          $("#upload_image_btn").click(function(){        
            $("#avatar-image-modal").css('display','block');
            $("#avatar-image-modal").modal('show');
        });

            //datatables
              admin_products_table = $('#adminproductstable').DataTable({
                  'ordering':true,
                  "processing": true, //Feature control the processing indicator.
                  'bnDestroy' :true,
                  "serverSide": true, //Feature control DataTables' server-side processing mode.
                  "order": [], //Initial no order.

                  // Load data for the table's content from an Ajax source
                  "ajax": {
                       "url": base_url+"admin/products/product_list",
                      "type": "POST",
                      "data": function ( data ) {
                         
                      },
                      error:function(){
                            //window.location.href=base_url+'admin/dashboard';
                          }
                  },

                  //Set column definition initialisation properties.
                  "columnDefs": [
                  { 
                      "targets": [ 0,2 ], //first column / numbering column
                      "orderable": false, //set not orderable
                  },
                  ],

              });

        });
        
        function add_product_admin()
        {
            $('[name="method"]').val('insert');
            $('#admin_product_form')[0].reset(); // reset form on modals            
            $('.modal-title').text('Add Product'); // Set Title to Bootstrap modal title
            $('#product_images').html('');
            $('#product_img').val('');
            $("#imdDsiplay, .uploaded-section").html('');
            $('#modal_form').modal('show'); // show bootstrap modal
        }

        function edit_product_admin(id)
        {
          $('[name="method"]').val('update');
          $('#admin_product_form')[0].reset(); // reset form on modals
          
          //Ajax Load data from ajax
          $.ajax({
              url : base_url+"admin/products/edit_product_admin/"+id,
              type: "GET",
              dataType: "JSON",
              success: function(data)
              {


                  //return false;
                  html = '';
                  imageurl = data.upload_image_url;
                  preview = data.upload_preview_image_url;
                      
                  $('#upload_image_url').val(imageurl);
                  $('#upload_preview_image_url').val(preview);
                  

                  var imgData = imageurl.split(",");
                  var previewimgData = preview.split(",");

                   $(".uploaded-section").html('');

                  $.each(imgData, function( index, value ) {
                  html +='<div id="remove_image_div_'+index+'" class="upload-images">'+
                           '<img src="'+base_url+value+'" alt="" height="42" width="42">'+
                           '<a href="javascript:;" onclick="remove_image(\''+value+'\',\''+previewimgData[index]+'\',\''+index+'\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>'+
                           
                          '</div>';
                  });

                  $('#imdDsiplay').html(html);
                        
                  $('[name="id"]').val(data.product_id);
                  $('[name="product_name"]').val(data.name);
                  $('#product_img').val(data.upload_image_url);
                  $('#product_images').html('<img src="'+base_url+data.upload_image_url+'" style="width:80px;height:30px">');
                  $('#pharmacy_name').val(data.pharmacy_name);
                  $('#category_id').val(data.category).change();
                  setTimeout(function(){
                    $('#sub_category_id').val(data.subcategory);
                  },1000);
                  $('#unit_value').val(data.unit_value);
                  $('#unit_id').val(data.unit);
                  $('#product_price').val(data.price);
                  $('#sale_price').val(data.sale_price);
                  $('#discount_value').val(data.discount);
                  $('#discount_percent').val(data.discount);
                  
                  //$('#discount_type').val(data.upload_image_url);
                  $('#manufatured_by').val(data.manufactured_by);
                  $('#product_description').val(data.description);
                  
                  $('#short_description').val(data.short_description);
                  

                  $('[name="product_name"]').val(data.name);
                  $('#product_img').val(data.upload_image_url);

                  $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                  $('.modal-title').text('Edit Product'); // Set title to Bootstrap modal title

              },
              error:function(){
                window.location.href=base_url+'admin/dashboard';
              }
          });
      }

        function admin_products_reload_table()
        {
            admin_products_table.ajax.reload(null,false); //reload datatable ajax 
        }

        function remove_image(image_url, preview_image_url, row_id) {

            var url = base_url + 'admin/products/delete_image';

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',

                data: {
                    image_url: image_url, preview_image_url: preview_image_url
                },

                success: function (data) {
                    if (data.html == 1) {

                        $('#remove_image_div_' + row_id).remove();
                        var total_array = $('#upload_image_url').val();
                        var arr = total_array.split(",");
                        var itemtoRemove = data.image_url;
                        arr.splice($.inArray(itemtoRemove, arr), 1);
                        $("#upload_image_url").val(arr);

                        var total_array1 = $('#upload_preview_image_url').val();
                        var arr1 = total_array1.split(",");
                        var itemtoRemove1 = data.image_url;
                        arr1.splice($.inArray(itemtoRemove1, arr1), 1);
                        $("#upload_preview_image_url").val(arr1);
                    }
                }
            });
        }

        function delete_product_admin(id)
        {
            if(confirm('Are you sure delete this product?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : base_url+"admin/products/delete_product_admin/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        $('#admin_product_form').modal('hide');
                        admin_products_reload_table();
                        toastr.success('Product deleted successfully');
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

        $(document).ready(function(){
          $('.export').click(function() {
             var imageData = $('.image-editor').cropit('export');
             
             var url=base_url+'admin/products/image_upload';
             var row_id= $('#row_id').val();
             var dataString="img_data="+imageData+"&row_id="+row_id; 
             var file1 = $('#fileopen').val(); 
             $("#error_msg_model").html('');
             if(file1.length>1){
             $.ajax( {
                  url:url,
                type       : 'post',
                data       : dataString,
                enctype    : 'multipart/form-data',
                dataType   : 'json',
                beforeSend: function () {
                   $('.export').attr('disabled',true);
                  $('.export').html('<div class="spinner-border text-light" role="status"></div>');
                },

                success: function (data) {

                  $('.export').attr('disabled',false);
                  $('.export').html('Done');
                
                  if (data.result) {
                                    
                    $(".uploaded-section").append(data.result);
                    $('#error_msg_model').html('');
                    $(".cropit-preview-image").attr('src','');
                    $(".cropit-preview-background").attr('src','');
                    $("#fileopen").val("");

                    var v1 = $("#upload_image_url").val();
                    var p1 = $("#upload_preview_image_url").val();

                                  if (v1.length > 0) {
                        var v2 = [];
                                          v2.push(v1);
                                          v2.push(data.image_url);
                                          $("#upload_image_url").val(v2);
                     } else {
                          var array = [];
                        array.push(data.image_url);
                        $("#upload_image_url").val(array);
                    }

                    if (p1.length > 0) {
                        var p2 = [];
                                          p2.push(p1);
                                          p2.push(data.preview_image_url);
                                          $("#upload_preview_image_url").val(p2);
                     } else {
                          var array = [];
                        array.push(data.preview_image_url);
                        $("#upload_preview_image_url").val(array);
                    }
                                  $('#row_id').val(data.row_id);

                   
                  }
                $("#avatar-image-modal").modal('hide');
              },
              complete: function () {

                    $("#imageimg_loader").hide();
                  $(".export").html('Done');
                }
                });
             }
             else
            {
              $("#upload_image_url").val('');
              $("#upload_preview_image_url").val('');
              toastr.error('Please upload size more than 680*454');      
            }
              });
          });

        $(document).ready(function (e){
            $("#admin_product_form").on('submit',(function(e){
                e.preventDefault();

                var product_name = $('#product_name').val();
                var product_img  = $('#product_img').val();
				var category_id  = $('#category_id').val();
				var sub_category_id  = $('#sub_category_id').val();
				var unit_value  =  $('#unit_value').val();
				var unit_id  =  $('#unit_id').val();
				var product_price = $('#product_price').val();
				var sale_price = $('#sale_price').val();
				var manufatured_by = $('#manufatured_by').val();
				var product_description = $('#product_description').val();
				var short_description = $('#short_description').val();
                
                if(product_name == ''){
                    toastr.error('Please enter product name');
                    return false;
                } 
				 if(product_name == ''){
                    toastr.error('Please enter product name');
                    return false;
                } 
				 if(category_id == ''){
                    toastr.error('Please Select Category');
                    return false;
                } 
				 if(sub_category_id == ''){
                    toastr.error('Please Sub Select Category');
                    return false;
                } 
				 if(unit_value == ''){
                    toastr.error('Please Select Unit Value');
                    return false;
                } 
				 if(product_price == ''){
                    toastr.error('Please enter product price');
                    return false;
                } 
				 if(sale_price == ''){
                    toastr.error('Please enter Sales Price');
                    return false;
                } 
				 if(manufatured_by == ''){
                    toastr.error('Please enter Manufactured by');
                    return false;
                } 
				 if(product_description == ''){
                    toastr.error('Please enter Product Description');
                    return false;
                } 
				 if(short_description == ''){
                    toastr.error('Please enter Short Description');
                    return false;
                } 
                            
                 $.ajax({
                    url: base_url+'admin/products/create_admin_products',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btnproductsave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnproductsave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btnproductsave').html('Submit');
                      $('#btnproductsave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            toastr.success(obj.status);

                            $('#modal_form').modal('hide');
                            $('#admin_product_form')[0].reset();
                            
                            admin_products_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    },
                    error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
    }
    if(pages=='orders'){
        pharmacy_products_table = $('#orderstable').DataTable({
            'ordering':false,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/pharmacy/orders_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });
    }

}
if(modules=='users'){
    if(pages=='labs'){
    var labs_table;
    $(document).ready(function() {

    //datatables
    labs_table = $('#labs_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+"admin/users/lab_list_data",
           "type": "POST",
           "data": function ( data ) {

           },
           error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });


});
function delete_lab(id)
{
    console.log(id);
    if(confirm('Are you sure to delete this lab?'))
    {
        // ajax delete data to database
        
        $.ajax({
            url : base_url+"admin/specialization/lab_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                // $('#admin_package_form').modal('hide');
        
                toastr.success('lab deleted successfully');
                labs_table.ajax.reload();
                // window.location.href=base_url+'admin/users/package';
            },
            error:function(){
                 window.location.href=base_url+'admin/dashboard';
                }
        });

    }
}
}

 if(pages=='labtest_booked'){
 var booked_labtest_table;
$(document).ready(function() {

    //datatables
    booked_labtest_table = $('#lab_booking_table').DataTable({
        'ordering':true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy' :true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
           "url": base_url+"admin/users/booked_labtest_list_data",
           "type": "POST",
           "data": function ( data ) {			  
				//console.log(data);
           },
           error:function(){
                  //window.location.href=base_url+'admin/dashboard';
              }
          },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

});
}
}
if(modules=='lab_tests'){
    var lab_tests_table;
    $(document).ready(function() {
        lab_tests_table = $('#lab_tests_table').DataTable({
            'ordering':true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
    
            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"admin/lab_tests/lab_tests_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },
    
            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,3 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
    
        });
    });
}
$('.no_only').on('keypress',function(e){
    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  });

if (pages=='clinic')
{
    var subscription_table;
    $(document).ready(function () {
        //datatables
        subscription_table = $('#clinic_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'admin/users/clinic_list',
                "type": "POST",
                "data": function (data) {
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [8,9,10], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });


    });
    function delete_clinic(id)
        {
            console.log(id);
            if(confirm('Are you sure to delete this Clinic?'))
            {
                // ajax delete data to database
                
                $.ajax({
                    url : base_url+"admin/specialization/clinic_delete/"+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        //if success reload ajax table
                        // $('#admin_package_form').modal('hide');
                
                        toastr.success('Clinic deleted successfully');
                        subscription_table.ajax.reload();
                        // window.location.href=base_url+'admin/users/package';
                    },
                    error:function(){
                         window.location.href=base_url+'admin/dashboard';
                        }
                });

            }
        }

    function show_clinic_doctors(id)
    {   
        $('#hospital_id').val(id);
        $('#clinic_doctor_modal').modal('show');
        // $('.modal-title').text( (name) +" - Doctors List");
        $("#clinic_doctor_table").dataTable().fnDestroy();

        var clinic_doctor_table;  
        clinic_doctor_table = $('#clinic_doctor_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            //"order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'admin/users/get_clinic_doctors/'+id,
                "type": "POST",
                "data": function (data) {
                    
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [], //first column / numbering column
                    "orderable": false, //set not orderable
                },
                
            ],

        });
        // clinic_doctor_table.destroy();
    }
    function change_clinic_doctor_status(id)
    {
        var stat= $('#status_'+id).prop('checked');

        if(stat==true)
        {
            var status=1;
        }
        else
        {
            var status=2;
        }
        $.post(base_url+"admin/users/change_clinic_doctor_status",{id:id,status:status},function(data){});

    }
    function add_clinic()
    {
        $('[name="method"]').val('insert');
        $('#register_form')[0].reset(); // reset form on modals
        $('#user_modal').modal('show'); // show bootstrap modal
        $('#user_modal .modal-title').text('Add Clinic'); // Set Title to Bootstrap modal title
        $("#email").prop("readonly", false);
        $('.pass').show();
    }
    
    function edit_clinic(id)
    {
        $('[name="method"]').val('update');
        $('#register_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
           url : base_url+"admin/users/clinic_edit/"+id,
           type: "GET",
           dataType: "JSON",
           success: function(data)
           {

            $('#id').val(data.id);
           // $('#first_name').val(data.first_name);
            //$('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#country_code').val(data.country_code);
            $('#mobileno').val(data.mobileno);
            //$('#clinic_name').val(data.first_name+' '+data.last_name);
            $("#email").prop("readonly", true);

            $('.pass').hide();
            $('#user_modal .modal-title').text('Edit Clinic');
            $('#user_modal').modal('show');
            
            },
            error:function(){
              // window.location.href=base_url+'admin/dashboard';
          }
      });
    }

    function clinic_reload_table()
    {
        subscription_table.ajax.reload(null,false); //reload datatable ajax 
    }

    function delete_clinic(id)
    {
        if(confirm('Are you sure delete this clinic?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"admin/users/clinic_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    clinic_reload_table();
                    toastr.success('Clinic deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                 }
             });

        }
    }
	$("#register_form_2").validate({
            rules: {
                first_name: { required: true,
				minlength : 6,
				    },

             last_name : {
                required: true,
                minlength: 6
            },
			email : {
                required: true,
                email: true,
            },
			mobileno: {
                required: true,
                minlength: 7,
                maxlength: 12,
                digits: true,
             },
			 password: {
            required: true,
            minlength: 6,
			maxlength: 15
       		 },
			confirm_password: {
			   required: true,
			   equalTo: "#password_2"
		   }

    },
 
       messages: {
        first_name: {
            required: "Enter valid First Name",
            minlength: "First name minimum length should be 5 characters"
        },
      last_name: {
            required: "Enter valid Last name",
            minlength: "Last name minimum length should be 5 characters"
        },
      email: {
            required: "Enter Email addres",
            email: "Enter the valid Email Address"
        },
		 mobileno: {
        required: "Please enter mobileno",
        maxlength: "Please enter valid mobileno",
        minlength: "Please enter valid mobileno",
        digits: "Please enter valid mobileno",
        
    },
	 password: {
        required: "Please enter password",
        minlength: "Your password must be minimum 6 characters",
		maxlength: "Your password must be Maximum of  characters"
    },
	 confirm_password: {
	required: "Please enter Confirm password",
	equalTo: "Your password does not match"
	 }
	
	   },
    submitHandler: function(form) {

        $.ajax({
            url: base_url + 'admin/users/addClinicDoctor',    
            data: $("#register_form_2").serialize(),    
            type: "POST",    
            beforeSend: function () {     
                $('#register_btn').attr('disabled', true);     
                $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');    
            },    
            success: function (res) {     
                $('#register_btn').attr('disabled', false);     
                $('#register_btn').html('Submit');     
                var obj = JSON.parse(res);     
                if (obj.status === 200) {      
                    $('#register_form_2')[0].reset();      
                    toastr.success('Doctor added successfully');      
                    //  clinic_doctordatatable();      
                    /*if($('#role').val()=='1')      {       
                        doctors_table();      }*/      
                        $('#doctor_modal').modal('hide'); 
                        $('#clinic_doctor_modal').modal('hide');     
                        // window.location.reload();     
                    }     else {      
                        toastr.error(obj.msg);     
                    }     // window.location.reload();    
                }
        });
        return false;
    }

  });
	
	
}

// jquery validate method
$(document).ready(function () {
  jQuery.validator.addMethod(
  'accept_chars',
  function (value) { 
  return /^[A-Za-z0-9-,./\:\& ]*$/.test(value); 
  },  
  'Please Enter Valid alphanumeric character only!'
  );
});
// jquery validate method

function add_team()
    {
        $('[name="method"]').val('insert');
        $('#register_form')[0].reset(); // reset form on modals
        $('#user_modal').modal('show'); // show bootstrap modal
        $('#user_modal .modal-title').text('Add Team Member'); // Set Title to Bootstrap modal title
        $("#email").prop("readonly", false);
        $('.pass').show();
    }


    function delete_team_member(id)
{
    if(confirm('Are you sure delete this Team Member?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"admin/specialization/team_member_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                // $('#specialization_form').modal('hide');
                toastr.success('Team Member deleted successfully');
                window.location.href=base_url+'admin/users/team';

            },
            error:function(){
                 //window.location.href=base_url+'admin/dashboard';
             }
         });

    }
}



     
        // if (typeof jQuery !== 'undefined') {
        //     alert('jQuery is loaded!');
        // } else {
        //     alert('jQuery is not loaded.');
        // }

 