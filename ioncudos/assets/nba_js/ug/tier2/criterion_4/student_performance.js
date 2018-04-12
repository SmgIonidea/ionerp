	            
//student_performance.js

//criterion 4 - section 4.1 - Students Performance JS
$('#view').on('change','#c4_crclm_list', function() {
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    $('.table_nba_without_backlogs').html('');
    $('.table_nba_students_graduated').html('');
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum_year = $(this).val();
    var pgm_id = $('#pgm_id').val();
    var post_data = {
        'curriculum_id' : curriculum_year,
        'pgm_id' : pgm_id,
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
    
    if(curriculum_year) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_student_performance',
            async:false,
            data: post_data,
            success: function(student_performance) {
                $("#section").html(student_performance);
            }                    
        });
    } else {
        $("#section").html("");
    } 	
});
	
// criterion 4. section 4.1 Students Performance - JS.
$('#view').on('change','#c4_1_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 

    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_enrolment_ratio',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});
		
// Section 4.2.1 JS functions start from here.	
$('#view').on('change','#c4_2_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
		
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_success_rate_in_program',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});
		
// Section 4.2.2 JS functions start from here.	
$('#view').on('change','#c4_2_2_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_success_rate_in_stipulated_period',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});
	
// Section 4.3 JS functions start from here.	
$('#view').on('change','#c4_3_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_academic_performance_third_yr',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});

// Section 4.4 JS functions start from here.	
$('#view').on('change','#c4_4_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_academic_performance_sec_yr',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});

// Section 4.4 JS functions start from here.	
$('#view').on('change','#c4_5_crclm_list', function() {
    $('#enrolment_ratio').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
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
    
    if(curriculum) {	
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2ug_c4_student_performance/fetch_placement_higher_studies',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#enrolment_ratio').html(msg);
            }
        });
    }
});
	
//File ends here.