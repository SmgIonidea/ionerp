var base_url = $('#get_base_url').val();
$(document).ready(function(){
$('#multi_dept_modal').modal({dynamic:true});
});

$('#multi_dept_modal').on('click','#submit_button_id', function(){
    var dept_id = $('#department_list').val();
     if(!dept_id){
       $('#err_msg').html('<font color=red><b>This field is required</b></fon>');
    }else{
    var post_data = {'dept_id': dept_id};
    
    $.ajax({type: "POST",
        url: base_url + 'login/multi_dept',
        data: post_data,
        //dataType: 'json',
        success: function(data){
            if(data){
             //  window.location = base_url+'home/';
			 {window.location = base_url+'home/?set=set &attempt='+data;}
            }
        }
    });
    }
});


$('#multi_dept_modal').on('click','#log_in_my_dept', function(){
    var dept_id = $(this).attr('base_dept_id');
    
    var post_data = {'dept_id': dept_id};
    $.ajax({type: "POST",
        url: base_url + 'login/multi_dept',
        data: post_data,
        //dataType: 'json',
        success: function(data){
            if(data){		
				//if(data == -1)
				{window.location = base_url+'home/?set=set &attempt='+data;}				
			   //window.location.replace("/newpage/page.php?id='"+product_id+"'");
            }
        }
    });
    
});


$('#multi_dept_modal').on('change','#department_list', function(){
    var dept_id = $('#department_list').val();
    if(!dept_id){
       $('#err_msg').html('<font color=red><b>This field is required</b></fon>');
    }else{
        $('#err_msg').empty();
    }
});