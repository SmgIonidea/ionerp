var base_url = $('#get_base_url').val();
$(document).ready(function(){
    $.ajax({type: "POST",
        url: base_url + 'login/swich_dept_validate',
        //data: post_data,
        //dataType: 'json',
        success: function(data){
            //console.log(data);
            var count = data.trim();
            if(count == 0)
            {
                $('#switch_dept').hide();
            }else{
                $('#switch_dept').show();
            }
//            if(data){
//               window.location = base_url+'dashboard/dashboard';
//            }
        }
    });
});


$('#multi_dept_modal_show').on('click','#submit_button_id', function(){
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
               //window.location = base_url+'dashboard/dashboard';
               window.location = base_url+'home/';
            }
        }
    });
       
    }
});

$('#multi_dept_modal_show').on('change','#department_list', function(){
    var dept_id = $('#department_list').val();
    if(!dept_id){
       $('#err_msg').html('<font color=red><b>This field is required</b></fon>');
    }else{
        $('#err_msg').empty();
    }
});


$('#multi_dept_modal_show').on('click','#log_in_my_dept', function(){
    var dept_id = $(this).attr('base_dept_id');
    var post_data = {'dept_id': dept_id};
    $.ajax({type: "POST",
        url: base_url + 'login/multi_dept',
        data: post_data,
        //dataType: 'json',
        success: function(data){
            if(data){
               //window.location = base_url+'dashboard/dashboard';
               window.location = base_url+'home/';
            }
        }
    });
});

$('#multi_dept').on('click',function(){
    $.ajax({type: "POST",
        url: base_url + 'login/load_dept_modal',
       // data: post_data,
        //dataType: 'json',
        success: function(data){
            if(data){
               $('#place_multi_dept_modal').empty();
               $('#place_multi_dept_modal').html(data);
                $('#multi_dept_modal_show').modal({dynamic:true});
            }
        }
    });
   
});