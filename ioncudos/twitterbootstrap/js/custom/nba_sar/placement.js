
//placement.js

var base_url = $('#get_base_url').val();

//Function to check input field should contain only numbers.
$.validator.addMethod("numbers", function (value, element) {
    return this.optional(element) || /^[0-9]+$/i.test(value);
}, "Field must contain only numbers.");

//Function show and hide depended on select type
function change(select_list){        
    $('#select_list').val(select_list);
    if(select_list == 3){
        $('#entrepreneur_display').show();
        $('#universty_display').hide();
        $('#company_display').hide();
    }else if(select_list == 2){
        $('#entrepreneur_display').hide();
        $('#company_display').show();
        $('.university_tab').hide();
    }else if(select_list == 1){
        $('#company_display').show();
        $('#universty_display').hide();
        $('#entrepreneur_display').hide();
        $('.university_tab').show();
    }
}
	
// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#placement_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('error');
    },
    errorPlacement: function (error, element) {

        if (element.next().is('.error_add')) {
            error.insertAfter(element.parent(''));
        }
        else {				 
            error.insertAfter(element.parent(''));
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('error');
        $(element).addClass('success');
    }

}); 

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#intake_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).addClass('success');
    }
});

//Function to check input field should contain only numbers with decimal point.
$.validator.addMethod("onlyNumber", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "Enter a valid number.");

//Function to check input field should contain only numbers with decimal point and comma.
$.validator.addMethod("commaNumber", function (value, element) {
    return this.optional(element) || /^[0-9\.\,]+$/i.test(value);
}, "Field must contain only number,decimal point or comma.");

//function for add form calendar.
var startDate = new Date();
var FromEndDate = new Date();
var ToEndDate = new Date();	
var startDaters = new Date();
var startDaterd = new Date();

//function for add form calendar.
$("#started_date").datepicker({
    format: "dd-mm-yyyy"
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function to focus on add form calender function.
$('#btn_ent').click(function () {
    $(document).ready(function () {
        $("#started_date").datepicker().focus();
    });
});

//function for add form calendar.
startDate = $('#first_visit').val(); 
$("#visit_date").datepicker( {	
    format:"dd-mm-yyyy",
    weekStart: 1,
    startDate: startDate,
    endDate: FromEndDate, 
    autoclose: true,
    todayBtn : true								
}).on('changeDate ', function(selected){
    startDate = new Date(selected.date.valueOf());
    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf()))); 
    $('#interview_date').datepicker('setStartDate', startDate);
});

//function to focus on add form calender function.
$('#btn_visit').click(function(){
    $(document).ready(function(){
        $("#visit_date").datepicker().focus();	
    });
});	

//function for add form calendar.
startDaters = $('#visit_date').val();
$("#interview_date").datepicker( {
    format:"dd-mm-yyyy",
    weekStart: 1,
    startDate: startDaters,			
    autoclose: true,
}).on('changeDate ', function(selected){
    FromEndDate = new Date(selected.date.valueOf());
    FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
    $('#visit_date').datepicker('setEndDate', FromEndDate);
});	

//function to focus on add form calender function.
$('#btn').click(function(){
    $(document).ready(function(){
        $("#interview_date").datepicker().focus();	
    });
});	

//Function to fecth program for selected department 
$('#eligible_dept').on('change', function(){
    change_program_list();
});

//Function to fecth curriculumn for selected department 
$('#program_list').on('change', function(){
    change_curriculum_list();
});

//Function to define validation rules.
$('#eligible_dept').each(function () {
    $(this).rules("add",{
        required: true
    });
});

//Function to define validation rules.
$.validator.addMethod("needsSelection", function (value, element) {
    var count = $(element).find('option:selected').length;
    return count > 0;
});


//Function to fecth curriculum 
function change_curriculum_list(){
    var program_list = $('#program_list').val();
    var post_data = {
        'program_list': program_list
    }
    
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/fetch_curriculum_multi_select',
        data:post_data,
        dataType: "json",
        success : function(data){
            var size = data.length;
            $('#curriculum_list').empty();     
            for (i = 0; i < size ; i++) {
                my_ops = $('<option></option>').val(data[i]['crclm_id']).attr('abbr', data[i]['crclm_name']).attr('title', data[i]['crclm_description'] + '.' + data[i]['crclm_name']).text(data[i]['crclm_name']);                    
                $('#curriculum_list').append(my_ops);
            }
            $('#curriculum_list').multiselect('rebuild');
				
            var post_data = {
                'plmt_id': $('#plmt_id').val()
            }

            $.ajax({
                type: "POST",
                url: base_url + 'nba_sar/placement/edit_placement_dept',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    var size_pr = data.crclm.length;  
                    for (var count = 0; count < size_pr; count++) {     
                        $("#curriculum_list option[value='" + data.crclm[count]['crclm_id'] + "']").attr('selected', 'selected');                                            
                        $('#curriculum_list').multiselect('rebuild');
                    }

                }
            });

        }
    });	
}

//Function to fecth program
function change_program_list(){      
    var dept = $('#eligible_dept').val();
    var post_data = {
        'dept': dept
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/fetch_program_multi_select',
        data: post_data,
        dataType: "json",
        success: function (data) {
            var size = data.length;
            $('#program_list').empty();  
            
            for (i = 0; i < size ; i++) {
                my_ops = $('<option></option>').val(data[i]['pgm_id']).attr('abbr', data[i]['pgm_acronym']).attr('title',  data[i]['pgm_title']).text(data[i]['pgm_acronym']);                    
                $('#program_list').append(my_ops);
            }
            
            $('#program_list').multiselect('rebuild'); 
            var post_data = {
                'plmt_id': $('#plmt_id').val()
            }

            $.ajax({
                type: "POST",
                url: base_url + 'nba_sar/placement/edit_placement_dept',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    var size_pr = data.prgm.length;  
                    
                    for (var count = 0; count < size_pr; count++) {                 
                        $("#program_list option[value='" + data.prgm[count]['pgm_id'] + "']").attr('selected', 'selected');                                            
                        $('#program_list').multiselect('rebuild');
                        change_curriculum_list();
                    }
                }
            });
        }
    });
}

//function to save placement details
$('#add_form_submit').click(function () {
    var $form = $("#placement_form");
    var validator = $form.data('validator');
    var select_list = $('#select_list').val();  
    if(select_list == 1){
        validator.settings.ignore = ':hidden:not("#eligible_dept"):hidden:not("#sector_list"):hidden:not("#program_list"):hidden:not("#curriculum_list")';
    }else{
        validator.settings.ignore = ':hidden:not("#eligible_dept"):hidden:not("#program_list"):hidden:not("#curriculum_list")';
    }
	
    var eligible_dept = $("#eligible_dept").val();
    var flag = $('#placement_form').valid();
    if (flag) {
        $("#loading").show();
        var dept_id = $("#dept").val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var company_id = $("#company_id").val();
        var role_offered = $("#role_offered").val();
        var pay = $("#pay").val();
        var place_posting = $("#place_posting").val();
        var cut_off_percent = $("#cut_off_percent").val();
        var interview_date = $("#interview_date").val();
        var description = $("#description").val();
        var program_list = $('#program_list').val();
        var sector_list = $('#sector_list').val();
        var visit_date = $('#visit_date').val();
        var  no_of_vacancies = $('#no_of_vacancies').val();       
        var curriculum_list = $('#curriculum_list').val(); 	
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'company_id': company_id,
            'role_offered': role_offered,
            'pay': pay,
            'place_posting': place_posting,
            'eligible_dept': eligible_dept,
            'cut_off_percent': cut_off_percent,
            'interview_date': interview_date,
            'description': description,
            'department': $("#dept").val(),
            'program_list' : program_list,
            'curriculum_list' : curriculum_list,
            'sector_list' : sector_list,
            'visit_date':visit_date,
            'no_of_vacancies':no_of_vacancies,
            'select_list':select_list
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/placement/insert_placement_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                select_list = $('#select_list_data').val();				
                list_placement_details(select_list);
                $("#loading").hide();
                if (data == 1) {
                    success_modal();
                }
                $('#reset_placement').trigger("click");

            }
        });
    }
});

//function to display placement details.
function list_placement_details(select_list) {
    $("#loading").show();   
    change( select_list );
    var pgm_id = $("#program").val();
    var crclm_id = $("#curriculum").val();
    var dept_id = $("#dept").val();
    $('#select_list_data').val(select_list);  
    var post_data = {
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
        'dept_id': dept_id,
        'select_list':select_list
    }
    var url_fetch_comp_university = 'nba_sar/placement/fetch_comp_university';
	
    $.ajax({
        type: "POST",
        url: base_url + url_fetch_comp_university,
        data: post_data,
        success: function (data) {
            $('#company_id').html(data);
        }
    });
    
    if(select_list==1){
        $("#selected_type_name").html(industry_title +" : <font color='red'>*</font>");
    }else if(select_list==2){
        $("#selected_type_name").html("University / College: <font color='red'>*</font>");
    }
    
    if(select_list == 1 || select_list == 2){
        var url = 'nba_sar/placement/list_placement_details';
    }
    else if(select_list == 3){      
        var url = 'nba_sar/placement/list_placement_details_entreprenuer';     
    }
    
    $.ajax({
        type: "POST",
        url: base_url + url,
        data: post_data,
        dataType: 'json',
        success: function (data) {
            if(select_list == 1){
                populateTableCompany(data);
            }else if(select_list == 2){
                populateTableUniversity(data);
            }
            else if(select_list == 3){				
                $('#example').hide();
                populateTableEntreprenuer(data);
            }                        
            $("#loading").hide();
        }
    });
}

//function to update the list of placement of type company display in the view page.
function populateTableCompany(data) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
    {
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },
        {
            "sTitle": industry_title, 
            "mData": "company_name"
        },
        {
            "sTitle": "Positions being offered", 
            "mData": "role_offered"
        },
        {
            "sTitle": "Eligible Departments", 
            "mData": "elgible_dept"
        },
        {
            "sTitle": "CGPA / Percentage (%)", 
            "mData": "cut_off_percent"
        },
        {
            "sTitle": "Interview date", 
            "mData": "interview_date"
        },
        {
            "sTitle": "No. of students placed", 
            "mData": "stud_intake"
        },
        {
            "sTitle": "Upload", 
            "mData": "upload"
        },
        {
            "sTitle": "Edit", 
            "mData": "edit"
        },
        {
            "sTitle": "Delete", 
            "mData": "delete"
        }
        ], 
        "aaData": data,
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            $('td:eq(4)', nRow).css("text-align", "right");
            $('td:eq(5)', nRow).css("text-align", "right");
            return nRow;
        }
    });
}

//function to update the list of placement of type University / College display in the view page.
function populateTableUniversity(data) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
    {
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },
        {
            "sTitle": "University / College ", 
            "mData": "company_name"
        },
        {
            "sTitle": "Positions being offered", 
            "mData": "role_offered"
        },
        {
            "sTitle": "Eligible Departments", 
            "mData": "elgible_dept"
        },
        {
            "sTitle": "CGPA / Percentage(%)", 
            "mData": "cut_off_percent"
        },
        {
            "sTitle": "Interview date", 
            "mData": "interview_date"
        },
        {
            "sTitle": "No. of students placed", 
            "mData": "stud_intake"
        },
        {
            "sTitle": "Upload", 
            "mData": "upload"
        },
        {
            "sTitle": "Edit", 
            "mData": "edit"
        },
        {
            "sTitle": "Delete", 
            "mData": "delete"
        }
        ], 
        "aaData": data,
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            $('td:eq(4)', nRow).css("text-align", "right");
            $('td:eq(5)', nRow).css("text-align", "right");
            return nRow;
        }
    });
}

//function to update the list of placement of type Entreprenuer display in the view page.
function populateTableEntreprenuer(data){
    $('#example_entrepre').dataTable().fnDestroy();
    $('#example_entrepre').dataTable(
    {
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },
        {
            "sTitle": "Name ", 
            "mData": "name"
        },
        {
            "sTitle": "Started Date", 
            "mData": "date"
        },
        {
            "sTitle": "Sector", 
            "mData": "sector"
        },
        {
            "sTitle": "Location", 
            "mData": "location"
        },  
        {
            "sTitle": "No. of students placed", 
            "mData": "stud_intake"
        },                
        {
            "sTitle": "Upload", 
            "mData": "upload"
        },
        {
            "sTitle": "Edit", 
            "mData": "edit"
        },
        {
            "sTitle": "Delete", 
            "mData": "delete"
        }
        ], 
        "aaData": data,
        "sPaginationType": "bootstrap"    ,
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }     
    });
}

//function to load data into fields to edit data
$('#example').on('click', '.edit_placement_details', function () {
    $('html,body').animate({
        scrollTop: $(".tab").offset().top
    },'slow');
    $('#reset_placement').trigger("click");
    $("#update_placement").show();
    $("#add_form_submit").hide();
    $("#higher_placement").show();
    $("#plmt_id").val($(this).attr('data-id'));
    $("#company_id").val($(this).attr('data-company_id'));
    $("#role_offered").val($(this).attr('data-role_offered'));
    $("#pay").val($(this).attr('data-pay'));
    $("#place_posting").val($(this).attr('data-place_posting'));
    $("#cut_off_percent").val($(this).attr('data-cut_off_percent'));
    $("#interview_date").val($(this).attr('data-interview_date'));
    $("#description").val($(this).attr('data-description'));
    $('#visit_date').val($(this).attr('data-visit_date')); 
    $('#no_of_vacancies').val($(this).attr('data-no_of_vac'));
    var desc_length = $('#description').val().length;
    $('#char_span_support').text(desc_length + ' of 2000');
    $('#eligible_dept').find('option:selected').prop('selected', false);
    $('#eligible_dept').multiselect('rebuild');
    $('#program_list').find('option:selected').prop('selected', false);
    $('#program_list').multiselect('rebuild');   
    $('#plmt_id').val($(this).attr('data-id')); 
    if($('#select_list').val() == 1){
        $('.university_tab').show();
    }else{
        $('.university_tab').hide();
    }
    change_sector();
    var post_data = {
        'plmt_id': $(this).attr('data-id')
    }
   
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/edit_placement_dept',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            var size = data.dept.length; 
            for (var count = 0; count < size; count++) {                
                $("#eligible_dept option[value='" + data.dept[count]['dept_id'] + "']").attr("selected", "selected");
                $('#eligible_dept').multiselect('rebuild');
            }
            change_program_list();            
        }
    });
});

//function to update placement details
$('#update_placement').click(function () {
    var flag = $('#placement_form').valid();
    if (flag) {
        $("#loading").show();
        var plmt_id = $("#plmt_id").val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var company_id = $("#company_id").val();
        var role_offered = $("#role_offered").val();
        var pay = $("#pay").val();
        var place_posting = $("#place_posting").val();
        var eligible_dept = $("#eligible_dept").val();
        var cut_off_percent = $("#cut_off_percent").val();
        var interview_date = $("#interview_date").val();
        var description = $("#description").val();
        var visit_date = $("#visit_date").val();
        var no_of_vacancies  = $('#no_of_vacancies').val();
        var select_list = $('#select_list').val();
        var program_list = $('#program_list').val();
        var sector_list = $('#sector_list').val();
        var curriculum_list = $('#curriculum_list').val();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'plmt_id': plmt_id,
            'company_id': company_id,
            'role_offered': role_offered,
            'pay': pay,
            'place_posting': place_posting,
            'eligible_dept': eligible_dept,
            'cut_off_percent': cut_off_percent,
            'interview_date': interview_date,
            'description': description,
            'department': $("#dept").val(),
            'visit_date': visit_date,
            'no_of_vacancies': no_of_vacancies,
            'select_list':select_list,
            'program_list':program_list,
            'sector_list':sector_list,
            'curriculum_list':curriculum_list
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/placement/update_placement_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                list_placement_details(select_list);
                $("#loading").hide();
                if (data == 1) {
                    update_modal();
                }
                $('#reset_placement').trigger("click");

            }
        });
    }
});

//Function to reset number of characters entered in the Add description box
$('#reset_placement').click(function () {
    $("#update_placement").hide();
    $("#add_form_submit").show();
    $("#higher_placement").hide();
    $("#char_span_support").text('0 of 2000');
    var validator = $('#placement_form').validate();
    validator.resetForm();
    $("input,select,textarea").css({
        "color": "#555555", 
        "border-color": "#cccccc"
    });
    $('#eligible_dept').find('option:selected').prop('selected', false);
    $('#eligible_dept').multiselect('rebuild');
    $('#program_list').html("");
    $('#sector_list').html("");
    $('#curriculum_list').html("");
    $('#program_list').find('option:selected').prop('selected', false);
    $('#program_list').multiselect('rebuild');
    $('#sector_list').find('option:selected').prop('selected', false);
    $('#sector_list').multiselect('rebuild');
    $('#curriculum_list').find('option:selected').prop('selected', false);
    $('#curriculum_list').multiselect('rebuild');
});

//function to load data into fields to edit data
$('#example_entrepre').on('click', '.edit_entrepreneur_details', function (){
    $('html,body').animate({
        scrollTop: $(".tab").offset().top
    },'slow');
    var validator = $('#entrepreneur_form').validate();
    validator.resetForm();
    $("#name_entrepreneur").val($(this).attr('data-name')); 
    $("#sector_entrepreneur").val($(this).attr('data-sector'));
    $("#started_date").val($(this).attr('data-start_date'));
    $("#location_entrepreneur").val($(this).attr('data-location'));
    $("#description_entrepreneur").val($(this).attr('data-description'));
    $("#e_id").val($(this).attr('data-e_id'));
    $('#update_entrepreneur').show();
    $('#add_entrepreneur').hide();
});

//Function is to reset entrepreneur form
function reset_entrepreneur(){
    $("#entrepreneur_form").trigger('reset');
    var validator = $('#entrepreneur_form').validate();
    validator.resetForm();	
    $('#update_entrepreneur').hide();
    $('#add_entrepreneur').show();
}

//function to save and update placement details of type entrepreneur
$('#add_entrepreneur,#update_entrepreneur').on('click',function(e){
    $('#loading').show();
    var select_type = $('#select_list').val(); 
    var idClicked = e.target.id; 
    var idClicked = e.target.id; 
    var validation_flag = $('#entrepreneur_form').valid();
    if(validation_flag == true){
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var dept_id  = $("#dept").val();
        if(idClicked == 'update_entrepreneur'){
            var buttonvalue = 'update'
        } else if( idClicked == 'add_entrepreneur'){
            var buttonvalue = 'save'
        }
        var values = $("#entrepreneur_form").serialize();
        values = values + '&button_update='+buttonvalue;
        values = values + '&pgm_id='+pgm_id;
        values = values + '&crclm_id='+crclm_id;
        values = values + '&dept_id='+dept_id;	
        
        $.ajax({
            type:"POST",
            url:base_url+'nba_sar/placement/save_update_entrepreneur',
            data:values,				
            success:function(data){
                $('#loading').hide();
                list_placement_details(select_type);
                reset_entrepreneur();
                success_modal();
            }
        });
    }else{
        $('#loading').hide();
    }
});

//function to store delete id.
function storeId(delete_id) {
    $("#delete_placement_id").val(delete_id);
}

//function to store delete id.
function storeId_entrepreneur(delete_id) {    
    $("#delete_entrepreneur_id").val(delete_id);
}

//function to delete placement details
$('#delete_placement_selected').click(function () {
    var delete_id = $("#delete_placement_id").val();
    var select_list = $('#select_list').val(); 
    $("#loading").show();
    var post_data = {
        'plmt_id': delete_id,
        'select_list' : select_list
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/placement_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            list_placement_details(select_list);
            $('#delete_placement_details').modal('hide');
            $("#loading").hide();
            if (data == 1) {
                delete_modal();
            }
        }
    });
});

//function to delete placement details
$('#delete_entrepreneur_selected').click(function () {
    var delete_id = $("#delete_entrepreneur_id").val();
    var select_list = $("#select_list").val();
    $("#loading").show();
    var post_data = {
        'e_id': delete_id,
        'select_list':select_list
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/entreprenuer_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            list_placement_details(select_list);
            $('#delete_entrepreneur_details').modal('hide');
            $("#loading").hide();
            if (data == 1) {
                delete_modal();
            }
        }
    });
});

//function to show uploaded files in the modal, upload the file and save data.
$('#example ,#example_entrepre').on('click', '.upload_file', function (e) {
    $('#placement_id').val($(this).attr('data-id'));
    $("#upload_name").val($(this).attr('data-name'));
    display_upload_modal();
    var placement_id = $('#placement_id').val();
    var select_list = $("#select_list").val();
    var post_data = {
        'plmt_id': placement_id,
        'select_list':select_list
    }
    var uploader = document.getElementById('uploaded_file');
    upclick({
        element: uploader,
        action_params: post_data,
        multiple: true,
        onstart: function (filename) {
            $("#loading").show();
        },
        action: base_url + 'nba_sar/placement/upload',
        oncomplete: function (response_data) {

            if (response_data == "file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if (response_data == "file_size_exceed") {
                $('#larger').modal('show');
            }

            display_upload_modal();
            $("#loading").hide();
        }
    });
});

//function to list uploaded files in modal.
function display_upload_modal() {
    var placement_id = $('#placement_id').val();
    var upload_name = $("#upload_name").val();
    var select_list = $("#select_list").val();
    var post_data = {
        'plmt_id': placement_id,
        'upload_name': upload_name,
        'select_list':select_list
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/placement/fetch_files',
        data: post_data,
        success: function (data) {
            document.getElementById('upload_files').innerHTML = data;
            $('#upload_modal').modal('show');
        }
    });
}

//function to show delete modal. 
$('#upload_files').on('click', '.delete_file', function (e) {
    var delete_id = $(this).attr('data-id');
    $('#upload_id').val(delete_id);
    $('#delete_upload_file').modal('show');
});

//function to delete the uploaded file.
$('#delete_file').click(function (e) {
    var delete_id = $('#upload_id').val();
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/delete_file',
        data: {
            'upload_id': delete_id
        },
        success: function (data) {
            if (data == 1) {
                delete_modal();
            }

            display_upload_modal();
        }
    });
    $('#delete_upload_file').modal('hide');
    $("#loading").hide();
});

//function for upload file calender
$('body').on('focus', '.std_date', function () {
    $("#actual_date").datepicker({
        format: "dd-mm-yyyy",
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
    $(this).datepicker({
        format: "dd-mm-yyyy",
    //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
});

//function to focus on input on click on calender icon.
$('#upload_files').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

//submit the form to save data.
$('#save_upload_desc').live('click', function (e) {
    e.preventDefault();
    $('#myform').submit();
});

//Save description and date of each file uploaded.
$('#upload_form').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var form_val = new Array();
    $("#loading").show();

    $('.save_form_data').each(function () {
        //values fetched will be inserted into the array
        form_val.push($(this).val());
    });

    //check whether any file exists or not
    if (form_val.length > 0) {
        //if file exists
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/placement/save_data',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (msg) {

                if (msg == 1) {
                    //display success message on save
                    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {
                    //display error message - if description and date could not be saved
                    var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
            }
        });
    } else {
        //display error message if file does not exist and user tries to click save button
        var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }

    $("#loading").hide();
});

//function to give save message on add.
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give update message on edit.
function update_modal(msg) {
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give delete message on delete.
function delete_modal(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give warning message if data is already exits.
function warning_modal(msg) {
    var data_options = '{"text":"Placement intake details already exists for this Category.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to close modal
$(".modal_close").click(function () {
    var id = $(this).attr('data-id');
    $(id).modal('hide');
});

//function to display Placement intake details modal
$('#example').on('click', '.plmt_intake', function (e) {
    $("#plmt_intake").val($(this).attr('data-id'));
    $("#intake_company_id").val($(this).attr('data-company_id'));
    $("#loading").show();
    display_placement_intake_table();
    $("#loading").hide();
    $("#plmt_intake_view").modal('show');
});

//function to display Placement intake details modal
$('#example_entrepre').on('click', '.plmt_intake', function (e) {
    $("#plmt_intake").val($(this).attr('data-id'));  
    $("#loading").show();
    display_entrepreneur_intake_table();
    $("#loading").hide();
    $("#plmt_intake_view").modal('show');
});

//function to list placement intake details in modal.
function display_placement_intake_table() {
    var crclm_id = $('#curriculum').val(); 
    var placement_id = $('#plmt_intake').val();
    var select_list =  $('#select_list').val();
    var company_id = $("#intake_company_id").val(); 
    var post_data = {
        'plmt_id': placement_id,
        'crclm_id': crclm_id,
        'select_list' : select_list,
        'company_id':company_id
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/placement/fetch_placement_intake',
        data: post_data,
        success: function (data) {
            document.getElementById('intake_table').innerHTML = data;
        }
    });
}

//function to list Entrepreneur intake details in modal.
function display_entrepreneur_intake_table() {
    var crclm_id = $('#curriculum').val(); 
    var placement_id = $('#plmt_intake').val();
    var select_list =  $('#select_list').val();
    var prgm_id =  $('#program').val();
    var dept_id = $('#dept').val();
    var post_data = {
        'plmt_id': placement_id,
        'crclm_id': crclm_id,
        'dept_id': dept_id,
        'select_list' : select_list,
        'prgm_id':prgm_id
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/placement/fetch_entrepreneur_intake',
        data: post_data,
        success: function (data) {
            document.getElementById('intake_table').innerHTML = data;
        }
    });
}

//Function is to fetch first visit of company
$('#company_id').on('change' , function(){  
    var prgm_id =  $('#program').val();
    var dept_id = $('#dept').val();
    var company_id = $('#company_id').val();
    var post_data = {
        'company_id': company_id ,
        'prgm_id' : prgm_id,
        'dept_id': dept_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/fetch_first_visit_date',
        data: post_data,
        dataType: "json",
        success: function (data) { 
            $('#first_visit').val(data);				
            $('#visit_date').trigger('change');
        }
    });
    change_sector();
});

//Function is to fetch sectors for selected company
function change_sector(){
    var company_id = $('#company_id').val(); 
    var post_data = {
        'company_id': company_id 
    }
    
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/fetch_sector_list',
        data: post_data,
        dataType: "json",
        success: function (data) {                
            var size = data.length;
            var my_ops='';
            var text='';
            for (var i = 0; i < size ; i++) {
                if(data[i]['mt_details_name'].length > 20){
                    text = data[i]['mt_details_name'].substring(0,20)+ "...";
                }else{
                    text = data[i]['mt_details_name'];
                }
                my_ops = $('<option></option>').val(data[i]['sector_id']).attr('abbr', data[i]['mt_details_name']).attr('title',  data[i]['mt_details_name']).text(text);                                     
                $('#sector_list').append(my_ops);
            }
            $('#sector_list').multiselect('rebuild');            
            var post_data = {
                'plmt_id': $('#plmt_id').val()
            }

            $.ajax({
                type: "POST",
                url: base_url + 'nba_sar/placement/edit_sector_list',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    var size_pr = data.sector_list.length;                              
                    for (var count = 0; count < size_pr; count++) {                 
                        console.log(data.sector_list[count]['mt_details_id']);                                
                        $("#sector_list option[value='" + data.sector_list[count]['mt_details_id'] + "']").attr('selected', 'selected');                                            
                        $('#sector_list').multiselect('rebuild');
                        $('#visit_date').trigger('change');
                    }
                }
            });
        }
    });
}

//count number of characters entered in the add description box.
$('.char-counter').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';

    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

//count number of characters entered in the add description box.
$('.char-counter_ent').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support_ent';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

//Function is to store or delete from student intake details.
$('.student_check').live("click", function() {
    $("#loading").show();		
    var dept_id = $("#dept").val();
    var pgm_id = $("#program").val();
    var crclm_id = $("#curriculum").val();
    var company_id = $('#plmt_intake').val(); 
    var select_list = $('#select_list').val();   
    var stud_id = $(this).attr('value');                     
    
    if ($(this).is(":checked")) {
        var flag = true;
    }else{
        var flag = '';
    }
    
    var post_data = {
        'flag': flag , 
        'stud_id':stud_id , 
        'dept_id' : dept_id , 
        'pgm_id':pgm_id , 
        'crclm_id': crclm_id , 
        'company_id' : company_id ,
        'select_list':select_list
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/placement/store_student',
        data: post_data,
        datatype: "json",
        success: function (data) {
            if(select_list == 1 || select_list == 2){
                display_placement_intake_table();
            }else{
                display_entrepreneur_intake_table();
            }
            $("#loading").hide();
        }
    });
});
//File ends here.