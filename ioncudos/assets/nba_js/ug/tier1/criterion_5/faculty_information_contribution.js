
//faculty_informataion_contribution.js

// Section 5 JS functions start from here.	
//Function is to Faculty Information - section 5
$('#view').on('change','#year_list__5', function() {
    $('#faculty_information_contributions_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('year_list__', '');
    var year = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
        
    var post_data = {
        'year_id' : year,
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
        'year' : year,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }
    
    if(year){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_information_contributions',
            async:false,
            data: post_data,
            success: function(student_faculty_ratio_info) {
                $('#faculty_information_contributions_info').html(student_faculty_ratio_info);
            }
        }); 
    }
});
    
// Section 5.1 JS functions start from here.	
//function is to fetch student faculty ratio - section 5.1
$('#view').on('change','#curriculum_list__5_1', function() {
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

    $.ajax({
        type:"POST",
        url: base_url+'nba_sar/t1ug_c5_faculty/fetch_student_faculty_ratio_sfr',
        async:false,
        data: post_data,
        success: function(student_faculty_ratio_info) {
            $('#student_faculty_ratio_info').empty();
            $('#student_faculty_ratio_info').html(student_faculty_ratio_info);
        }
    });
});
    
// Section 5.2 JS functions start from here.	
//Function is to fetch faculty cadre proportion - section 5.2
$('#view').on('change','#curriculum_list__5_2', function() {
    $('#faculty_cadre_info').empty();
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
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_cadre_proportion',
            async:false,
            data: post_data,
            success: function(faculty_cadre_info) {
                $('#faculty_cadre_info').html(faculty_cadre_info);
            }
        });
    }
});
	
// Section 5.3 JS functions start from here.	
//Function is to fetch faculty qualification - section 5.3
$('#view').on('change','#curriculum_list__5_3', function() {
    $('#faculty_qualification_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();

    var post_data = {
        'curriculum_id' : curriculum,
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
        
    var post_data = {
        'curriculum' : curriculum,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }

    if(curriculum){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_qualification',
            async:false,
            data: post_data,
            success: function(faculty_qualification_info) {
                $('#faculty_qualification_info').html(faculty_qualification_info);
            }
        }); 
    }
});
	    
// Section 5.4 JS functions start from here.	
//Function is to fetch faculty Retention - section 5.4
$('#view').on('change','#curriculum_list__5_4', function() {
    $('#faculty_retention_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var curriculum = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();

    var post_data = {
        'curriculum_id' : curriculum,
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
        
    var post_data = {
        'curriculum' : curriculum,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }
    
    if(curriculum){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_retention',
            async:false,
            data: post_data,
            success: function(faculty_retention_info) {
                $('#faculty_retention_info').html(faculty_retention_info);
            }
        });
    }
});            
    
// Section 5.5 JS functions start from here.	
//Function is to fetch faculty Competencies - section 5.5
$('#view').on('change','#department_list__5_5', function() {
    $('#faculty_competencies_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('curriculum_list__', '');
    var department = $(this).val();	

    var post_data = {
        'department' : department,
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
        'dept_id' : department
    }
    
    if(department){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_competencies',
            async:false,
            data: post_data,
            success: function(faculty_competencies_info) {
                $('#faculty_competencies_info').html(faculty_competencies_info);
            }
        });
    }
});      

// Section 5.7 JS functions start from here.
//Function is to fetch faculty development - section 5.7
$('#view').on('change','#year_list__5_7', function() {
    $('#faculty_development_training_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('year_list__', '');
    var year = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
        
    var post_data = {
        'year_id' : year,
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
        'year' : year,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }

    if(year){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_development_training',
            async:false,
            data: post_data,
            success: function(student_faculty_ratio_info) {
                $('#faculty_development_training_info').html(student_faculty_ratio_info);
            }
        });
    }
});

// Section 5.9 JS functions start from here.
//Function is to fetch faculty performance appraisal - section 5.9
$('#view').on('change','#year_list__5_9', function() {
    $('#faculty_performance_appraisal_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('year_list__', '');
    var year = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
        
    var post_data = {
        'year_id' : year,
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
        'year' : year,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }

    if(year){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_performance_appraisal',
            async:false,
            data: post_data,
            success: function(student_faculty_ratio_info) {
                $('#faculty_performance_appraisal_info').html(student_faculty_ratio_info);
            }
        });
    }
});

// Section 5.10 JS functions start from here.
//Function is to fetch faculty performance appraisal - section 5.10
$('#view').on('change','#year_list__5_10', function() {
    $('#faculty_adjunct_info').empty();
    var base_url = $('#get_base_url').val();
    var name = $(this).attr('name'); 
    //obtained from nba_sar_view
    var name_value = name.replace('year_list__', '');
    var year = $(this).val();	
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
        
    var post_data = {
        'year_id' : year,
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
        'year' : year,
        'dept_id' : dept_id,
        'pgm_id' : pgm_id
    }

    if(year){
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t1ug_c5_faculty/fetch_faculty_adjunct',
            async:false,
            data: post_data,
            success: function(student_faculty_ratio_info) {
                $('#faculty_adjunct_info').html(student_faculty_ratio_info);
            }
        });
    }
});

//File ends here.