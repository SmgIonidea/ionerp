
//course_po.js

$(function(){
    // JS Functions for SECTION 3.1.1 starts from here.
    //Function is to fetch grid of course list semester-wise - section 3.1.1
    $('#view').on('change','#curriculum_list_cos',function(){
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	
        var post_data = {
            'curriculum' : curriculum,
            'view_nba_id' : name_value,
            'view_form' : $('#view_form .filter').serializeArray()
        }
        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2ug_c3_cos/display_co',
                async:false,
                data: post_data,
                dataType: 'json',
                success: function(msg) {
                    $('#co_vw_id').empty();
                    $('#clo_vw_id').empty();
                    $('#co_vw_id').html(msg['co_view']);
                    $('#clo_vw_id').html(msg['clo_view']);
                }
            });
        } else{
            $('#co_vw_id').empty();
            $('#clo_vw_id').empty();
        }
    });
        
    //Function is to fetch co details - section 3.1.1
    $('#view').on('click','#generate_clo',function(){
        var postdata={
            'view_form' : $('#view_form .filter').serializeArray(),
            'nba_sar_id' : $('#nba_sar_id').val()
        };
        $.ajax({
            data:postdata,
            async:false,
            type:'post',
            url: $('#get_baseurl').val()+"nba_sar/nba_sar/generate_report",
            success: function(msg){
            }
        });
		
        var base_url = $('#get_base_url').val();
        var curriculum = $('#curriculum_list_cos').val();
        var crs_id_array = new Array();
        $('.cos_checkbox').each(function(){
            if($(this).is(':checked')){
                crs_id_array.push($(this).val());
            }
        });
        var post_data = {
            'curriculum' : curriculum,
            'crs_id_array':crs_id_array
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c3_cos/display_clo',
            async:false,
            data: post_data,
            dataType: 'json',
            success: function(msg) {
                $('#clo_vw_id').empty();
                $('#clo_vw_id').html(msg['clo_view']);
            }
        });
    });
});

//Function is to select all course of selected semester - section 3.1.1
$('#view').on('click','.select_all_box',function(){
    var inc_val = $(this).attr('data-count');
    
    if($('.select_all_chk_'+inc_val).is(':checked')){
        $('.select_all__'+inc_val).each(function(){
            $(this).prop('checked',true);
        });
    } else {
        $('.select_all__'+inc_val).each(function(){
            $(this).removeAttr('checked');
        });
    }	
});	
 
// JS Functions for SECTION 3.1.3 starts from here.
//Function is to fetch course to po mapping details - section 3.1.3 
$('#view').on('change','#curriculum_list_course_po',function(){
    $('#sem_course_matrix').empty();
    $('#sem_clo_po_matrix').empty();
    var base_url = $('#get_base_url').val();
    var curriculum = $(this).val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var post_data = {
        'curriculum' : curriculum,
        'view_nba_id' : name_value,
        'nba_sar_id' : $('#nba_sar_id').val(),
        'view_form' : $('#view_form .filter').serializeArray()
    }
    $.ajax({
        data:post_data,
        async:false,
        type:'post',
        url: $('#get_baseurl').val()+"nba_sar/nba_sar/generate_report",
        success: function(msg){
        }
    });
    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t2ug_c3_cos/display_course_po',
        async:false,
        data: post_data,
        dataType: 'json',
        success: function(msg) {
            $('#course_po').empty();
            $('#course_po').html(msg);
        }
    });
});
//File ends here.