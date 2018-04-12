
//course_po.js

//Function is to fetch program articulation matrix and course articulation matrix - section 3.1.
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
        url: $('#get_baseurl').val()+"nba_sar/t1ug_c3_co_attainment/clear_filter",
        success: function(msg){
        }
    });
    
    $.ajax({
        data:post_data,
        async:false,
        type:'post',
        url: $('#get_baseurl').val()+"nba_sar/nba_sar/generate_report",
        success: function(msg){
        }
    });

    if(curriculum){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c3_cos/display_course_po',
            async:false,
            data: post_data,
            dataType: 'json',
            success: function(msg) {
                $('#course_po').empty();
                $('#course_po').html(msg);
            }
        });
    } else{
        $('#course_po').empty();
    }
});

//Function is to fetch semester course co to po mapping - section 3.1.
$('#view').on('click','.select_all_box',function(){
    var inc_val = $(this).attr('data-count');
    var crclm_id = $('#curriculum_list_course_po').val();
    var crs_id_array = new Array();
    
    if($('.select_all_chk_'+inc_val).is(':checked')){
        $('.select_all__'+inc_val).each(function(){
            $(this).prop('checked',true);
        });
        
        $('.cos_checkbox').each(function(){
            if($(this).is(':checked')){
                crs_id_array.push($(this).val());
            }
        });
        
        var post_data = {
            'crclm_id':crclm_id,
            'crs_id_array':crs_id_array
        };
        
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c3_cos/clo_po_mapping',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#sem_clo_po_matrix').empty();
                $('#sem_clo_po_matrix').html(msg);
                $('.h4_margin').each(function(){
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
            }
        });
    } else {
        $('.select_all__'+inc_val).each(function(){
            $(this).removeAttr('checked');
        });
	
        $('.cos_checkbox').each(function(){
            if($(this).is(':checked')){
                crs_id_array.push($(this).val());
            }
        });
        
        var post_data = {
            'crclm_id':crclm_id,
            'crs_id_array':crs_id_array
        };
        
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c3_cos/clo_po_mapping',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#sem_clo_po_matrix').empty();
                $('#sem_clo_po_matrix').html(msg);
                
                $('.h4_margin').each(function(){
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
            }
        });
    }	
        
    var curriculum = $('#curriculum_list_course_po').attr('name');
    var crclm_name_split = curriculum.split('__');
    var node_values = crclm_name_split[1].split('_');
    var name = 'cos_checkbox_list__'+node_values[0]+'_'+node_values[1];
    //obtained from nba_sar_view
    var name_value = name.replace('cos_checkbox_list__', '');
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
        
});	
	
//Function is to fetch course co to po mapping - section 3.1.
$('#view').on('click','.cos_checkbox',function(){
    var crclm_id = $('#curriculum_list_course_po').val();
    var crs_id_array = new Array();
    
    $('.cos_checkbox').each(function(){
        if($(this).is(':checked')){
            crs_id_array.push($(this).val());
        }
    });
    
    var post_data = {
        'crclm_id':crclm_id,
        'crs_id_array':crs_id_array
    };

    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t1ug_c3_cos/clo_po_mapping',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#sem_clo_po_matrix').empty();
            $('#sem_clo_po_matrix').html(msg);
            $('.h4_margin').each(function(){
                $('#row_value').addClass('orange').addClass('background-nba');
                $('.color_class').addClass('orange').addClass('background-nba');
            });
        }
    });
        
    var curriculum = $('#curriculum_list_course_po').attr('name');
    var crclm_name_split = curriculum.split('__');
    var node_values = crclm_name_split[1].split('_');
    var name = 'cos_checkbox_list__'+node_values[0]+'_'+node_values[1];
    //obtained from nba_sar_view
    var name_value = name.replace('cos_checkbox_list__', '');
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
});

//File ends here.