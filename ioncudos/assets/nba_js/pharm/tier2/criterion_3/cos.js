
//cos.js

//Section - 3.2.1 js start from here.
//Function is to fetch term list - section 3.2.1.
$('#view').on('change','#curriculum_3_2_1_co_attainment', function() {
    $('#course_assement_occasions').html('');
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();
    var post_data = {
        'curriculum' : curriculum,
        'view_nba_id' : name_value,
        'view_form' : $('#view_form .filter').serializeArray()
    }

    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t2pharm_c3_co_attainment/get_term_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#term_3_2_1_co_attainment').empty();
            $('#term_3_2_1_co_attainment').html(msg);
            $('#course_3_2_1_co_attainment').html('<option value="">Select Course</option>');
        }
    });
});

//Function is to fetch course list - section 3.2.1.
$('#view').on('change','#term_3_2_1_co_attainment', function() {
    $('#course_assement_occasions').html('');
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_3_2_1_co_attainment').val();
    var term_id = $(this).val();
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id
    }
    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t2pharm_c3_co_attainment/get_course_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#course_3_2_1_co_attainment').empty();
            $('#course_3_2_1_co_attainment').html(msg);
        }
    });
});

//Function is to fetch Course Assessment Methods - section 3.2.1.               
$('#view').on('click', '#generate_3_2_1_assessment_report', function(e){
    $('#course_assement_occasions').html('');
    var base_url = $('#get_base_url').val();
    var course_id = $('#course_3_2_1_co_attainment').val();
    var crclm_id = $('#curriculum_3_2_1_co_attainment').val();
    var term_id = $('#term_3_2_1_co_attainment').val();
    var curriculum = $('#curriculum_3_2_1_co_attainment').val();
    var name = $('#curriculum_3_2_1_co_attainment').attr('name');   
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
    
    if(course_id){
        var post_data = {
            'course_id' : course_id,
            'crclm_id' : crclm_id,
            'term_id' : term_id
        }

        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/course_assement_occasions_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#course_assement_occasions').empty();
                $('#course_assement_occasions').html(msg);
            }
        });
    }
});

//Function is to fetch term list for rubrics - section 3.2.1
$('#view').on('change','#curriculum_rubrics_attain', function() {
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	

    var post_data = {
        'curriculum' : curriculum,
        'view_nba_id' : name_value,
        'view_form' : $('#view_form .filter').serializeArray()
    }

    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t2pharm_c3_co_attainment/get_term_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#term_rubrics_list').html(msg);
            $('#course_rubrics_list').html("<option value=''>Select Course</option>");
            $('#rubrics_list').html("<option value=''>Select Rubrics</option>");
        }
    });
});
		
//Function is to fetch course list for rubrics - section 3.2.1
$('#view').on('change','#term_rubrics_list', function() {
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_rubrics_attain').val();
    var term_id = $(this).val();
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id
    }
    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t2pharm_c3_co_attainment/get_course_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#course_rubrics_list').empty();
            $('#course_rubrics_list').html(msg);
            $('#rubrics_list').html("<option value=''>Select Rubrics</option>");
        }
    });
});
    
//Function is to fetch rubric AO list for rubrics - section 3.2.1
$('#view').on('change','#course_rubrics_list', function() {
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_rubrics_attain').val();
    var term_id = $('#term_rubrics_list').val();
    var course_id = $(this).val();
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id,
        'course_id' : course_id
    }
    
    if(course_id){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/get_course_assessment_occasion_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#rubrics_list').empty();
                $('#rubrics_list').html(msg);
            }
        });
    }else{
        $('#rubrics_list').html("<option value=''>Select Rubrics</option>");
    }
});

//Function is to fetch rubrics assessment - section 3.2.1.
$('#view').on('click', '#generate_3_2_1_rubrics_assessment_report', function(e){    
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var course_id = $('#course_rubrics_list').val();
    var crclm_id = $('#curriculum_rubrics_attain').val();
    var term_id = $('#term_rubrics_list').val();
    var ao_method_id = $('#rubrics_list').val();
    var curriculum = $('#curriculum_rubrics_attain').val();
    var name = $('#curriculum_rubrics_attain').attr('name');
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
    
    if(ao_method_id != '') {
        var post_data = {
            'course_id' : course_id,
            'crclm_id' : crclm_id,
            'term_id' : term_id,
            'ao_method_id' : ao_method_id
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/co_attainment_qpd_rubrics',
            async:false,
            data: post_data,
            success: function(rubrics_occasions) {
                $('#course_rubrics_occasions').html(rubrics_occasions);
            }
        });
    }
});

//Section - 3.2.2 js start from here.
//Function is to fetch Course list semester-wise - section 3.2.2
$('#view').on('change','#curriculum_list__3_2_2',function(){
    $('#co_target_level').empty();
    $('#sem_wise_course_grid').empty();
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
        url: $('#get_baseurl').val()+"nba_sar/t2pharm_c3_co_attainment/clear_filter",
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
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/display_crs_grid',
            async:false,
            data: post_data,
            dataType: 'json',
            success: function(msg) {
                $('#sem_wise_course_grid').empty();
                $('#sem_wise_course_grid').html(msg['crs_view']);
                var crclm_val = $('#curriculum_list__3_2_2').attr('name');
                var crclm_name_split = crclm_val.split('__');
                var node_values = crclm_name_split[1].split('_');
                $('.course_checkbox').attr('name','cos_checkbox_list__'+node_values[0]+'_'+node_values[1]);
            }
        });
    }
});

//Function is to fetch Course - wise CO attainment - section 3.2.2
$('#view').on('click','.course_checkbox',function(){
    var crclm_id = $('#curriculum_list__3_2_2').val();
    var crs_id_array = new Array();
    $('.course_checkbox').each(function(){
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
        url: base_url+'nba_sar/t2pharm_c3_co_attainment/co_target_levels',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#co_target_level').empty();
            $('#co_target_level').html(msg);
            $('.h4_margin').each(function(){
                $('#row_value').addClass('orange').addClass('background-nba');
                $('.color_class').addClass('orange').addClass('background-nba');
            });                               
        }
    });
    var curriculum = $('#curriculum_list__3_2_2').attr('name');
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

//Function is to fetch Course - wise CO attainment - section 3.2.2
$('#view').on('click','.select_all_course_box',function(){
    var inc_val = $(this).attr('data-count');
    var crclm_id = $('#crclm_list').val();
    var crs_id_array = new Array();
    
    if($('.select_all_chk_'+inc_val).is(':checked')){
        $('.select_all__'+inc_val).each(function(){
            $(this).prop('checked',true);
        });
        $('.course_checkbox').each(function(){
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
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/co_target_levels',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#co_target_level').empty();
                $('#co_target_level').html(msg);
                $('.h4_margin').each(function(){
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
            }
        });
    }else{
        $('.select_all__'+inc_val).each(function(){
            $(this).removeAttr('checked');
        });	
        $('.course_checkbox').each(function(){
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
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/co_target_levels',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#co_target_level').empty();
                $('#co_target_level').html(msg);
                $('.h4_margin').each(function(){
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
            }
        });
    }	
    
    var curriculum = $('#curriculum_list__3_2_2').attr('name');
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

//Section - 3.3.1 js start from here.
//Function is to fetch survey dropdown list - section 3.3.1
$('#view').on('change','#curriculum_list__3_3_1_survey', function() {
    $('#curriculum_survey_attainment_tools_div').html('');
    $('#survey_list__3_3_1_survey').html('');
    var base_url = $('#get_base_url').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $(this).val();
    var post_data = {
        'pgm_id' : pgm_id,
        'crclm_id' : crclm_id
    }
    
    if(crclm_id){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/fetch_curriculum_survey_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#survey_list__3_3_1_survey').html(msg);
            }
        });
    }else{
        $('#survey_list__3_3_1_survey').html("<option value=''>Select Survey</option>");
    }
});

//Function is to fetch curriculum survey attainment - section 3.3.1
$('#view').on('change', '#survey_list__3_3_1_survey', function(e){    
    $('#curriculum_survey_attainment_tools_div').html('');
    var base_url = $('#get_base_url').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $('#curriculum_list__3_3_1_survey').val();
    var survey_id = $(this).val();
    var curriculum = $('#curriculum_list__3_3_1_survey').val();
    var name = $('#curriculum_list__3_3_1_survey').attr('name');
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
            
    if(survey_id){
        var post_data = {
            'pgm_id' : pgm_id,
            'crclm_id' : crclm_id,
            'survey_id' : survey_id
        }
        $('#curriculum_survey_attainment_tools_div').html('');
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/fetch_curriculum_survey_attainment_tools',
            async:false,
            data: post_data,
            success: function(curriculum_survey) {
                $('#curriculum_survey_attainment_tools_div').html(curriculum_survey);
            }
        });
    }
});
    
//Section - 3.3.2 js start from here.
//Function is to fetch PO attainment details - sectin 3.3.2
$('#view').on('change','#curriculum_list__3_3_2', function() {
    $('#course_wise_po_attainment_grid').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	

    var post_data = {
        'curriculum' : curriculum,
        'view_nba_id' : name_value,
        'view_form' : $('#view_form .filter').serializeArray(),
        'nba_sar_id' : $('#nba_sar_id').val()
    }

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
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/course_wise_po_attainment',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#course_wise_po_attainment_grid').html(msg);
            }
        });
    }
});
    
//Function is to fetch survey dropdown list - section 3.3.2. 
$('#view').on('change','#curriculum_list__3_3_2_survey', function() {
    $('#curriculum_survey_attainment_div').html('');
    $('#survey_list__3_3_2_survey').html('');
    var base_url = $('#get_base_url').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $(this).val();
    var post_data = {
        'pgm_id' : pgm_id,
        'crclm_id' : crclm_id
    }
    
    if(crclm_id){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/fetch_curriculum_survey_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#survey_list__3_3_2_survey').html(msg);
            }
        });
    }
});
  
//Function is to fetch curriculum survey attainment - section 3.3.2.  
$('#view').on('change', '#survey_list__3_3_2_survey', function(e){     
    $('#curriculum_survey_attainment_div').html('');
    var base_url = $('#get_base_url').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $('#curriculum_list__3_3_2_survey').val();
    var survey_id = $(this).val();
    var curriculum = $('#curriculum_list__3_3_2_survey').val();
    var name = $('#curriculum_list__3_3_2_survey').attr('name');
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
    
    if(survey_id){
        var post_data = {
            'pgm_id' : pgm_id,
            'crclm_id' : crclm_id,
            'survey_id' : survey_id
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c3_co_attainment/fetch_curriculum_survey_attainment',
            async:false,
            data: post_data,
            success: function(curriculum_survey) {
                $('#curriculum_survey_attainment_div').html(curriculum_survey);
            }
        });
    }
});

//Files ends here.