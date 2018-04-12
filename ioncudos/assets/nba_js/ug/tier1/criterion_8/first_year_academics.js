	
//first_year_academics.js
	
//section 8.1
$(function() {
    $('#view').on('change','#curriculum_list_8_1',function() {
        $('#fysfr_vw_id').empty();
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        //obtained from nba_sar_view
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	
        var dept_id = $('#dept_id').val();
        var pgm_id = $('#pgm_id').val();
        
        var post_data = {
            'curriculum_id' : curriculum,
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
        
        var post_data = {
            'curriculum' : curriculum,
            'dept_id' : dept_id,
            'pgm_id' : pgm_id
        }
        
        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url + 'nba_sar/t1ug_c8_first_year_academics/display_fysfr_grid',
                async:false,
                data: post_data,
                success: function(msg) {
                    $('#fysfr_vw_id').html(msg);
                }
            });
        }
    });
});
	
//section 8.2
$(function() {
    $('#view').on('change','#curriculum_list_8_2',function() {
        $('#fycc_vw_id').empty();
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        //obtained from nba_sar_view
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	
        var dept_id = $('#dept_id').val();
        var pgm_id = $('#pgm_id').val();
        
        var post_data = {
            'curriculum_id' : curriculum,
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
        
        var post_data = {
            'curriculum' : curriculum,
            'dept_id' : dept_id,
            'pgm_id' : pgm_id
        }
        
        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url + 'nba_sar/t1ug_c8_first_year_academics/display_facutly_teaching_fycc',
                async:false,
                data: post_data,
                success: function(msg) {
                    $('#fycc_vw_id').html(msg);
                }
            });
        }
    });
});
	
//section 8.3
$(function() {
    $('#view').on('change','#curriculum_list_8_3',function() {
        $('#fyap_vw_id').empty();
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        //obtained from nba_sar_view
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	
        var dept_id = $('#dept_id').val();
        var pgm_id = $('#pgm_id').val();
        
        var post_data = {
            'curriculum_id' : curriculum,
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
        
        var post_data = {
            'curriculum' : curriculum,
            'dept_id' : dept_id,
            'pgm_id' : pgm_id
        }

        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url + 'nba_sar/t1ug_c8_first_year_academics/display_fyap',
                async:false,
                data: post_data,
                success: function(msg) {
                    $('#fyap_vw_id').html(msg);
                }
            });
        }
    });
});
	
// JS Functions for SECTION 8.4.1 starts from here.
//Function is to fetch term list - Section 8.4.1
$('#view').on('change','#curriculum_list_c8_4_1', function() {
    $('#course_assement_occasions').empty();
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
        url: base_url+'nba_sar/t1ug_c8_first_year_academics/get_term_list',
        async: false,
        data: post_data,
        success: function(msg) {
            $('#term_list_c8_4_1').empty();
            $('#term_list_c8_4_1').html(msg);
            $("#course_list_c8_4_1").html("<option value=''>Select Course</option>");
        }
    });
});
		
//Function is to fetch course list - Section 8.4.1
$('#view').on('change','#term_list_c8_4_1', function() {
    $('#course_assement_occasions').empty();
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_list_c8_4_1').val();
    var term_id = $(this).val();
		
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id
    }
		
    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t1ug_c8_first_year_academics/get_course_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#course_list_c8_4_1').empty();
            $('#course_list_c8_4_1').html(msg);
        }
    });
});

//Function is to fetch Course Assessment Methods for course - section 8.4.1
$('#view').on('click','#generate_8_4_1_assessment_report', function() {
    $('#course_assement_occasions').empty();
    var base_url = $('#get_base_url').val();
    var course_id = $("#course_list_c8_4_1").val();
    var crclm_id = $('#curriculum_list_c8_4_1').val();
    var term_id = $('#term_list_c8_4_1').val();
    var name = $('#curriculum_list_c8_4_1').attr('name');   
    var name_value = name.replace('curriculum_list__', '');
    var post_data = {
        'course_id' : course_id,
        'crclm_id' : crclm_id,
        'term_id' : term_id,
        'curriculum' : crclm_id,
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
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/course_assement_occasions_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#course_assement_occasions').html(msg);
            }
        });
    }
});

//Function is to clear the div
function clear_all() {
    $('#nba_qp_div').html('');
    $('#co_outcome_chart').html('')
    $('#coplannedcoveragesdistribution > thead').html('');
    $('#coplannedcoveragesdistribution > tbody').html('');
    $('#bloom_level_marks_chart').html('');
    $('#bloomslevelplannedmarksdistribution > thead').html('');
    $('#bloomslevelplannedmarksdistribution > tbody').html('');
}

//Function is to fetch course dropdown - 8.4.1
$('#view').on('change','#curriculum_8_4_1', function() {
    clear_all();
    var base_url = $('#get_base_url').val();
    var curriculum = $(this).val();	
        
    var post_data = {
        'curriculum' : curriculum
    }

    if(curriculum){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/display_curriculum_courses',
            async:false,
            data: post_data,
            success: function(courses) {
                $('#curriculum_course_examination_8').html(courses);
            }
        });
    }else{
        $('#curriculum_course_examination_8').html("<option value=''>Select Course</option>");
        $('#course_qp_examination_8').html("<option value=''>Select Question Paper</option>");
    }
});
    
//Function is to fetch  question paper dropdown - 8.4.1    
$('#view').on('change','#curriculum_course_examination_8', function() {
    clear_all(); 
    var base_url = $('#get_base_url').val();
    var curriculum = $('#curriculum_8_4_1').val(); 
    var selected_course = $('#curriculum_course_examination_8').val(); 
        
    if(selected_course){
        var element = $("option:selected", this);
        var course_data = (element.attr('attr')).split('/');
        var course = course_data[0];
        
        var post_data = {
            'curriculum' : curriculum,
            'course' : course
        }

        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c2_teachingLearningProcess/display_course_qp',
            async:false,
            data: post_data,
            success: function(question_paper) {
                clear_all();
                $('#course_qp_examination_8').html(question_paper);
            }
        });
    }else{
        $('#course_qp_examination_8').html("<option value=''>Select Question Paper</option>");
    }
});

//Function is to clear div - section 8.4.1
$('#view').on('change', '#course_qp_examination_8', function() {
    clear_all(); 
});

//Function is to get question paper table - section 8.4.1.
$('#view').on('click','#generate_8_4_1_report', function() {
    clear_all();
    var base_url = $('#get_base_url').val();
    var curriculum = $('#curriculum_8_4_1').val();
    var program = $('#pgm_id').val();
    var selected_qp = $('#course_qp_examination_8').val();    
    var name = $('#curriculum_course_examination_8').attr('name');   
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
    
    if(selected_qp) {
        var crs_element = $("option:selected", $('#curriculum_course_examination_8'));
        var course_data = crs_element.attr('attr').split('/');
        var course = course_data[0];
        var term = course_data[1];
        var qp_element = $("option:selected", $('#course_qp_examination_8'));
        var qp_data = qp_element.attr('attr').split('/');
        var qpd_id = qp_data[0];
        var qp_type = qp_data[1];
        var post_data = {
            'pgmtype' : program,
            'crclm_id' : curriculum,
            'crs_id' : course,
            'term_id' : term,
            'qp_type' : qp_type,
            'qpd_id' : qpd_id,
            'nba_flag' : 1
        }
        
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c2_teachingLearningProcess/generate_model_qp_modal_tee',
            async:false,
            data: post_data,
            success: function(question_paper) {
                $('#question_paper_display_div').html(question_paper);
                $('#qp_table_data').dataTable().fnDestroy();

                $('#qp_table_data').dataTable({
                    "fnDrawCallback" : function () {
                        $('.group').parent().css({
                            'background-color' : '#C7C5C5'
                        });
                    },
                    "bPaginate" : false,
                    "bFilter" : false,
                    "bInfo" : false,
                    "aaSorting" : [[1, 'asc']]
                }).rowGrouping({
                    iGroupingColumnIndex : 0,
                    bHideGroupingColumn : true
                });
            }
        });
    } 
});

//Function is to fetch term dropdown - section 8.4.1
$('#view').on('change','#curriculum_rubrics_attain_8', function() {
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
        url: base_url+'nba_sar/t1ug_c8_first_year_academics/get_term_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#term_rubrics_list_8').html(msg);
            $('#course_rubrics_list_8').html("<option value=''>Select Course</option>");
            $('#rubrics_list').html("<option value=''>Select Rubrics</option>");
        }
    });
});

//Function is to fetch course dropdown - section 8.4.1
$('#view').on('change','#term_rubrics_list_8', function() {
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_rubrics_attain_8').val();
    var term_id = $(this).val();
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id
    }
    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t1ug_c8_first_year_academics/get_course_list',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#course_rubrics_list_8').empty();
            $('#course_rubrics_list_8').html(msg);
            $('#rubrics_list').html("<option value=''>Select Rubrics</option>");
        }
    });
});
    
//Function is to fetch course AO rubrics
$('#view').on('change','#course_rubrics_list_8', function() {
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var crclm_id = $('#curriculum_rubrics_attain_8').val();
    var term_id = $('#term_rubrics_list_8').val();
    var course_id = $(this).val();
    var post_data = {
        'term_id' : term_id,
        'crclm_id' : crclm_id,
        'course_id' : course_id
    }
    
    if(course_id){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c3_co_attainment/get_course_assessment_occasion_list',
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

//Function is to fetch rubrics assessment - section 8.4.1
$('#view').on('click', '#generate_8_4_1_rubrics_assessment_report', function(e){  
    $('#course_rubrics_occasions').html('');
    var base_url = $('#get_base_url').val();
    var course_id = $('#course_rubrics_list_8').val();
    var crclm_id = $('#curriculum_rubrics_attain_8').val();
    var term_id = $('#term_rubrics_list_8').val();
    var ao_method_id = $('#rubrics_list').val();
    var curriculum = $('#curriculum_rubrics_attain_8').val();
    var name = $('#curriculum_rubrics_attain_8').attr('name');
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
        
    if(ao_method_id) {
        var post_data = {
            'course_id' : course_id,
            'crclm_id' : crclm_id,
            'term_id' : term_id,
            'ao_method_id' : ao_method_id
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/co_attainment_qpd_rubrics',
            async:false,
            data: post_data,
            success: function(rubrics_occasions) {
                $('#course_rubrics_occasions').html(rubrics_occasions);
            }
        });
    }
});

// JS Functions for SECTION 8.4.2 starts from here.
//Function is to fetch Course list semester-wise - section 8.4.2
$('#view').on('change','#t1_c8_crclm_list', function() {
    $('#co_target_level').empty();
    $('#sem_wise_course_grid').empty();
    var crclm_id = $(this).val();
    var name = $(this).attr('name'); 
    var name_value = name.replace('curriculum_list__', '');			
    var post_data = {
        'crclm_id':crclm_id,
        'view_form' : $('#view_form .filter').serializeArray(),
        'view_nba_id' : name_value,
        'nba_sar_id' : $('#nba_sar_id').val()
    };
    $.ajax({
        data:post_data,
        async:false,
        type:'post',
        url: $('#get_baseurl').val()+"nba_sar/t1ug_c8_first_year_academics/clear_filter",
        success: function(msg){
        }
    });
    $.ajax({
        data: post_data,
        async: false,
        type: 'post',
        url: $('#get_baseurl').val() + "nba_sar/nba_sar/generate_report",
        success: function (msg) {
        }
    });
    
    if(crclm_id){
        $.ajax({
            url: base_url+"nba_sar/t1ug_c8_first_year_academics/display_crs_grid",
            data: post_data,
            async: false,
            type: 'post',
            success: function(msg){
                $('#sem_wise_course_grid').empty();
                $('#sem_wise_course_grid').html(msg);
            }
        });
    }
});
		
//Function is to fetch Course - wise CO attainment - section 8.4.2
$('#view').on('click','.c8_4_2_select_all_course_box',function() {
    var inc_val = $(this).attr('data-count');
    var crclm_id = $('#t1_c8_crclm_list').val();
    var crs_id_array = new Array();
		
    if($('.select_all_chk_'+inc_val).is(':checked')) {
        $('.select_all__'+inc_val).each(function() {
            $(this).prop('checked',true);
        });
		
        $('.c8_4_2_course_checkbox').each(function() {
            if($(this).is(':checked')) {
                crs_id_array.push($(this).val());
            }
        });
			
        var post_data = {
            'crclm_id': crclm_id,
            'crs_id_array': crs_id_array
        };
			
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/co_target_levels',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#co_target_level').empty();
                $('#co_target_level').html(msg);
                $('.h4_margin').each(function() {
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
                                                
                var curriculum = $('#t1_c8_crclm_list').attr('name');
                var crclm_name_split = curriculum.split('__');
                var node_values = crclm_name_split[1].split('_');
                var name = 'cos_checkbox_list__'+node_values[0]+'_'+node_values[1];//$(this).attr('name'); 
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
            }
        });
    } else {
        $('.select_all__'+inc_val).each(function() {
            $(this).removeAttr('checked');
        });
		
        $('.c8_4_2_course_checkbox').each(function() {
            if($(this).is(':checked')) {
                crs_id_array.push($(this).val());
            }
        });
			
        var post_data = {
            'crclm_id': crclm_id,
            'crs_id_array': crs_id_array
        };
			
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/co_target_levels',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#co_target_level').empty();
                $('#co_target_level').html(msg);
                $('.h4_margin').each(function() {
                    $('#row_value').addClass('orange').addClass('background-nba');
                    $('.color_class').addClass('orange').addClass('background-nba');
                });
                var curriculum = $('#t1_c8_crclm_list').attr('name');
                var crclm_name_split = curriculum.split('__');
                var node_values = crclm_name_split[1].split('_');
                var name = 'cos_checkbox_list__'+node_values[0]+'_'+node_values[1];//$(this).attr('name'); 
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
            }
        });
    }
});

//Function is to fetch Course - wise CO attainment - section 8.4.2
$('#view').on('click','.c8_4_2_course_checkbox',function() {
    var crclm_id = $('#t1_c8_crclm_list').val();
    var crs_id_array = new Array();
		
    $('.c8_4_2_course_checkbox').each(function() {
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
        url: base_url+'nba_sar/t1ug_c8_first_year_academics/co_target_levels',
        async:false,
        data: post_data,
        success: function(msg) {
            $('#co_target_level').empty();
            $('#co_target_level').html(msg);
            $('.h4_margin').each(function() {
                $('#row_value').addClass('orange').addClass('background-nba');
                $('.color_class').addClass('orange').addClass('background-nba');
            });
            var curriculum = $('#t1_c8_crclm_list').attr('name');
            var crclm_name_split = curriculum.split('__');
            var node_values = crclm_name_split[1].split('_');
            var name = 'cos_checkbox_list__'+node_values[0]+'_'+node_values[1];//$(this).attr('name'); 
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
        }
    });
});

//SECTION 8.5 JS function Starts from here
//Function is to fetch Course Wise PO Attainment and PO Indirect Attainment
$('#view').on('change','#c8_5_1_crclm_list_for_po', function() {
    $('#course_wise_po_attainment_grid').empty();
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
            url: base_url+'nba_sar/t1ug_c8_first_year_academics/course_wise_po_attainment',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#course_wise_po_attainment_grid').html(msg);
            }
        });
    }
});
//File ends here.
		