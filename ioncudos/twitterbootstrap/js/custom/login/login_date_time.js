//else{empty_modal();}
var log = $('#login_dat').val(); 	
var atm = $('#login_failed_count').val();
var prevent_log_history = $('#prevent_log_history').val();  //alert(atm);
if(log != undefined && log != ""){ if( prevent_log_history == 0 ){ if($('#logout_date').val()!='' && $('#login_date').val()!='' ){if(atm == 0){success_modal();}else{fail_modal();}}}}

 $('.noty').click(function () {

    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});
function success_modal() {	
	last_login = $('#login_date').val(); last_logout = $('#logout_date').val(); last_failed = $('#login_failed_date').val();
    var data_options = '{"text":" <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<custom_tag_head>Your Recent Log History &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</custom_tag_head><br/><br/> <custom_tag1><b>Last login date & time: </b></custom_tag1><br/><custom_tag2>'+last_login+'</custom_tag2><br/> <br/><custom_tag1>Last logged out date & time:</custom_tag1><br/> <custom_tag2>'+ last_logout+'<br/><br/></div>","layout":"center","type":"notification","timeout":"","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);	
}
function empty_modal() {	

	Current_date = $('#date_current_formatted').val(); 
     var data_options = '{"text":" <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<custom_tag_head>Your Recent Log history &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</custom_tag_head><br/><br/><custom_tag_fail>No log History found <br/><br/></custom_tag_fail> <custom_tag1><b>Current login date & time: </b></custom_tag1><br/><custom_tag2>'+Current_date+'</custom_tag2><br/><br/> </div>","layout":"center","type":"notification","timeout":"","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);	
}

function fail_modal() {
	$('.log_history_hide').hide();
	last_login = $('#login_date').val();	last_logout = $('#logout_date').val(); last_failed = $('#login_failed_date').val();
    var data_options = '{"text":" <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<custom_tag_head>Your Recent Log History &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</custom_tag_head><br/><br/> <custom_tag1>Last login date & time: </custom_tag1><br/><custom_tag2>'+last_login+'</custom_tag2><br/> <br/><custom_tag1>Last logged out date & time:</custom_tag1><br/> <custom_tag2>'+ last_logout+'</custom_tag2><br/><br/><custom_tag1>Last failed attempt date & time: </custom_tag1><br/><custom_tag_fail>'+last_failed+'</custom_tag_fail><br/><br/></div>","layout":"center","type":"notification","timeout":"","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

$('.noty_close').on('click',function(){
		$.ajax({type: "POST",
        url: base_url + 'login/update_failed_attempt',      
        success: function(data){}
    });
});

$('#log_history_dissable').on('click',function(){
if($('#log_history_dissable').attr('checked')) {
post_data = {'val':1}
		$.ajax({type: "POST",
        url: base_url + 'login/prevent_log_history',
        data: post_data,
        success: function(data){}
    });
} else {
 post_data = {'val':0}
		$.ajax({type: "POST",
        url: base_url + 'login/prevent_log_history',
        data: post_data,
        success: function(data){}
    });
}
});

