
//facilities_and_tecnhical_support.js

$(function(){
    //Function check whether cookie is set
    if ($.cookie('stud_perm_department') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#department option[value="' + $.cookie('stud_perm_department') + '"]').prop('selected', true);
        $('#department').trigger("change");
    }
});

//Function is to fetch details
$('#department').on('change',function(){
    $.cookie('stud_perm_department', $('#department option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var department = $('#department').val(); 
    
    if(department){
        var pgm_type_id = $("#department option:selected").attr("data-pgm_type_id");
        var post_data = {
            "pgm_type_id":pgm_type_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/facilities_and_technical_support/fetch_details',
            data: post_data,
            success: function (msg) {
                $("#details").html(msg);
                if(pgm_type_id==42){
                    fetch_lab();
                    fetch_adequate();
                }else if(pgm_type_id==44){
                    fetch_adequate();
                }else if(pgm_type_id==45){
                    fetch_laboratory();
                    fetch_equipment();
                    fetch_nts();
                    //function for add form calendar.
                    $("#purchase_date").datepicker({
                        format: "dd-mm-yyyy",
                    }).on('changeDate', function (ev) {
                        $(this).blur();
                        $(this).datepicker('hide');
                    });
                    //function to focus on add form calender function.
                    $('#btn').click(function () {
                        $(document).ready(function () {
                            $("#purchase_date").datepicker().focus();
                        });
                    });
                    //function for add form calendar.
                    $("#joining_date").datepicker({
                        format: "dd-mm-yyyy",
                    }).on('changeDate', function (ev) {
                        $(this).blur();
                        $(this).datepicker('hide');
                    });
                    //function to focus on add form calender function.
                    $('#btn_date').click(function () {
                        $(document).ready(function () {
                            $("#joining_date").datepicker().focus();
                        });
                    });
                }
            }
        });
    }else{
        $("#details").html("");
    }
});

//Function is to allow only letter and numberes
$.validator.addMethod("loginRegex_spec", function(value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\s]+([.\,\_\-\a-zA-Z 0-9\s\&])*$/i.test(value);
}, "Field must contain only numbers,letters,spaces or dashes,comma,full stop.");

//Function is to fetch Safety measures in laboratories tabel
function fetch_lab(){
    $('#loading').show();
    var publications_awards_path = base_url + 'nba_sar/facilities_and_technical_support/show_facilities_and_technical_support';
    var department = $('#department').val(); 
    var post_data = {
        'dept_id':department
    }
    $.ajax({
        type: "POST",
        url: publications_awards_path,
        data: post_data,
        dataType: 'json',
        success:function(msg){ 
            $('#loading').hide();
            populate_table(msg);
        }	
    });
}

//Function is to fetch Adequate tabel
function fetch_adequate(){
    $('#loading').show();
    var publications_awards_path = base_url + 'nba_sar/facilities_and_technical_support/show_adequates';
    var department = $('#department').val(); 
    var post_data = {
        'dept_id':department
    }
    $.ajax({
        type: "POST",
        url: publications_awards_path,
        data: post_data,
        dataType: 'json',
        success:function(msg){ 
            populate_table_adaquate(msg);
            $('#loading').hide();
        }	
    });
}
	
//Function is to save and update Safety measures in laboratories         
$('#details').on('click',"#update_safety_measures_in_laboratories,#save_safety_measures_in_laboratories",function(e){
    $('#loading').show();
    var department = $('#department').val(); 
    $("#laboratories_maintenance").validate({
        rules: {
            lab_name:{
                required: true,	
                maxlength: 5000,
                loginRegex_spec : true						
            },
            safety_measures:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:false,
            },
            messages: {  
                lab_name:{
                    required: "This field is required.",					
                }
            },
            errorPlacement : function(error, element) {
                if (element.attr('name') == "lab_name") {
                    error.appendTo('#lab_name');
                } else {
                    error.insertAfter(element);
                }
            }
				  	
        }
    });
    var idClicked = $(this).attr("id"); 
    var flag = $('#laboratories_maintenance').valid();
    if(department != ""){
        if(flag == true && department != ""){
            $('#dept_error').html("");
            if(idClicked == 'update_safety_measures_in_laboratories'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_safety_measures_in_laboratories'){
                buttonvalue = 'save'
            }
            var values = $("#laboratories_maintenance").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&dept_id='+department;
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/facilities_and_technical_support/save_update_laboratories_maintenance',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    $('#update_safety_measures_in_laboratories').hide();
                    $('#save_safety_measures_in_laboratories').show();
                    fetch_lab();
                    $("#reset_safety_measures_in_laboratories").trigger("click");
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();				
        }
				
    }else{
        $('#loading').hide();
        $('#Exist').modal('show');
    }
});

//Function is to reset Safety measures in laboratories
$('#details').on('click','#reset_safety_measures_in_laboratories',function(e){
    $("label.error").hide();
    $(".error").removeClass("error");	
    $('#dept_error').html("");
    $('#update_safety_measures_in_laboratories').hide();
    $('#save_safety_measures_in_laboratories').show();

});

//Function is to populate Safety measures in laboratories table
function populate_table(msg){
    $('#example_laboratories').dataTable().fnDestroy();
    $('#example_laboratories').empty();
    var table = $('#example_laboratories').dataTable(
    {
        "sSort": true,
        "sPaginate": true,
        "scrollX": true,
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Name of the Laboratory", 
            "mData": "lab_name"
        },

        {
            "sTitle": "Safety measures", 
            "mData": "safety_measures"
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
        "aaData": msg,					
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }					
    });	
}	

//Function is to populate adaquate
function populate_table_adaquate(msg){
    $('#example_adequate').dataTable().fnDestroy();
    $('#example_adequate').empty();
    var table = $('#example_adequate').dataTable(
    {
        "sSort": true,
        "sPaginate": true,
        "scrollX": true,
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Name of the Laboratory", 
            "mData": "lab_name"
        },

        {
            "sTitle": "Batch size ", 
            "mData": "no_of_stud"
        },

        {
            "sTitle": "Name of the equipment", 
            "mData": "equipment_name"
        },

        {
            "sTitle": "Weekly utilization status", 
            "mData": "utilization_status"
        },						

        {
            "sTitle": "Name of the technical staff", 
            "mData": "technical_staff_name"
        },		

        {
            "sTitle": "Designation", 
            "mData": "designation"
        },

        {
            "sTitle": "Qualification", 
            "mData": "qualification"
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
        "aaData": msg,					
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }				
    });	
}	

//Function is to edit Safety measures in laboratories
$('#details').on('click','.edit_lab',function(e){
    var validator = $('#laboratories_maintenance').validate();
    validator.resetForm();

    $('#safety_lab_id').val($(this).attr('id'));
    $('#lab_name').val($(this).attr('data-lab_name'));
    $('#safety_measures').val($(this).attr('data-safety_measures'));	
    $('#update_safety_measures_in_laboratories').show();
    $('#save_safety_measures_in_laboratories').hide();
}); 
	
//Function is to delete Safety measures in laboratories details		
$('#details').on('click','.delete_lab',function(){
    $('#safety_lab_id').val($(this).attr('id')); 
    $('#delete_confirm').modal('show');
		
});
	
//Function is to delete Safety measures in laboratories	
$('#delete_lab').on('click',function(){
    $('#loading').show();
    var safety_lab_id = $('#safety_lab_id').val();
    var dept_id = $('#department').val();
    var post_data ={
        'safety_lab_id':safety_lab_id ,
        'dept_id':dept_id
    }
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/facilities_and_technical_support/delete_lab',
        data:post_data,					
        success:function(data){ 
            if(data == 1){ }
            success_modal_delete();
            $('#loading').hide();
            fetch_lab();
            $('#update_safety_measures_in_laboratories').hide();
            $('#save_safety_measures_in_laboratories').show();
            $("#reset_safety_measures_in_laboratories").trigger("click");
        }
    }); 
});	
	
//Function is to save or update adequate        
$('#details').on('click',"#update_facility_adequate,#save_facility_adequate",function(e){
    $('#loading').show();
    var department = $('#department').val(); 
    $("#facility_adequate").validate({
        rules: {
            lab_name_1:{
                required: true,	
                maxlength: 5000,
                loginRegex_spec : true						
            },
            equipment_name:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:true,
            }, 	  
            no_of_stud:{
                required: true,	
                maxlength: 8,
            }, 
            utilization_status:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:true
            },	
            tech_staff_name:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:true,
            },		
            designation:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:true,
            },		
            qualification:{
                required: false,
                maxlength: 5000,
                loginRegex_spec:true,
            },
            messages: {  
                lab_name:{
                    required: "This field is required.",					
                }
            },
            errorPlacement : function(error, element) {
                if (element.attr('name') == "lab_name_1") {
                    error.appendTo('#lab_name_1');
                } else {
                    error.insertAfter(element);
                }
            }
				  	
        }
    });
    var idClicked = $(this).attr("id"); 
    var flag = $('#facility_adequate').valid();
    if(department != ""){
        if(flag == true ){
            $('#dept_error').html("");
            if(idClicked == 'update_facility_adequate'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_facility_adequate'){
                buttonvalue = 'save'
            }
            var values = $("#facility_adequate").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&dept_id='+department;
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/facilities_and_technical_support/save_update_facility_adequate',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    $('#update_facility_adequate').hide();
                    $('#save_facility_adequate').show();
                    fetch_adequate();
                    $("#reset_facility_adequate").trigger("click");
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();	
        }
    } else{
        $('#loading').hide();					
        $('#Exist').modal('show');
    }
});

//Function is to reset adequate form 
$('#details').on('click','#reset_facility_adequate',function(e){
    $("label.error").hide();
    $(".error").removeClass("error");	
    $('#dept_error').html("");
    $('#update_facility_adequate').hide();
    $('#save_facility_adequate').show();

});

//Function is to edit adequate details		
$('#details').on('click','.edit_adequate',function(e){
    var validator = $('#facility_adequate').validate();
    validator.resetForm();

    $('#fa_id').val($(this).attr('id'));
    $('#lab_name_1').val($(this).attr('data-lab_name'));
    $('#no_of_stud').val($(this).attr('data-no_of_stud'));		
    $('#equipment_name').val($(this).attr('data-equipment_name'));
    $('#utilization_status').val($(this).attr('data-utilization_status'));	
    $('#tech_staff_name').val($(this).attr('data-technical_staff_name'));
    $('#designation').val($(this).attr('data-designation'));	
    $('#qualification').val($(this).attr('data-qualification'));	

    $('#details #update_facility_adequate').show();
    $('#details #save_facility_adequate').hide();
}); 

//Function is to delete adequate details
$('#details').on('click','.delete_adequates',function(){
    $('#fa_id').val($(this).attr('id')); 
    $('#delete_confirm_adequate').modal('show');
		
});

//Function is to delete adequate details
$('#delete_adequate').on('click',function(){
    $('#loading').show();
    var fa_id = $('#fa_id').val();
    var dept_id = $('#department').val();
    var post_data ={
        'fa_id':fa_id ,
        'dept_id':dept_id
    }
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/facilities_and_technical_support/delete_adequate',
        data:post_data,					
        success:function(data){ 
            if(data == 1){ }
            success_modal_delete();
            $('#loading').hide();
            fetch_adequate();
            $('#update_facility_adequate').hide();
            $('#save_facility_adequate').show();
            $("#reset_facility_adequate").trigger("click");
        }
    }); 
});	
	

/**Calling the modal on success**/
function success_modal(msg) { 
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);				
}	

/**Calling the modal on success**/
function enable_modal(msg) { 
    var data_options = '{"text":"Your log history display has been enabled successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);				
} 		
		
/**Calling the modal on success**/
function dissable_modal(msg) { 
    var data_options = '{"text":"Your log history display has been dissabled successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);				
} 

/**Calling the model on successfull update**/
function success_modal_update(msg) { 
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);											
}
		
function success_modal_delete(msg) { 
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);											
}
	
function fail_modal(msg){			
    $('#loading').hide();
    var data_options = '{"text":"This Data already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
} 

//Function is to check allow number with decimal in the field
$("#details").on("keypress keyup blur",".allownumericwithdecimal",function (event) {
    //this.value = this.value.replace(/[^0-9\.]/g,'');
    $(this).val($(this).val().replace(/[^0-9\.]/g,''));
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

//Function is to check allow number in the field
$("#details").on("keypress keyup blur",".allownumericwithoutdecimal",function (event) {    
    $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

//Function is to save or update laboratory details      
$('#details').on('click',"#update_lab,#save_lab",function(e){
    $('#loading').show();
    var department = $('#department').val(); 
    $("#laboratory").validate({
        rules: {
            lab_description:{
                required: true,	
                maxlength: 2000,
                loginRegex_spec : true,
            },
            manual_availabitity:{
                required: true,
            }, 	  
            batch_size:{
                required: true,	
                maxlength: 8,
            }, 
            instrument_quality:{
                required: false,
                maxlength: 200,
                loginRegex_spec:true,
            },	
            safety_measures:{
                required: true,
                maxlength: 2000,
                loginRegex_spec:true,
            },		
            remarks:{
                required: false,
                maxlength: 2000,
                loginRegex_spec:true,
            }
				  	
        }
    });
    var idClicked = $(this).attr("id"); 
    var flag = $('#laboratory').valid();
    if(department != ""){
        if(flag == true ){
            $('#dept_error').html("");
            if(idClicked == 'update_lab'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_lab'){
                buttonvalue = 'save'
            }
            var values = $("#laboratory").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&dept_id='+department;
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/facilities_and_technical_support/save_update_laboratory',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    fetch_laboratory();
                    $("#reset_lab").trigger("click");
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();	
        }
    } else{
        $('#loading').hide();	
    }
});

//Function is to fetch laboratory tabel
function fetch_laboratory(){
    $('#loading').show();
    var department = $('#department').val(); 
    var post_data = {
        'dept_id':department
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/facilities_and_technical_support/list_laboratory',
        data: post_data,
        dataType: 'json',
        success:function(msg){ 
            populate_table_laboratory(msg);
            $('#loading').hide();
        }	
    });
}

//Function is to populate laboratory
function populate_table_laboratory(msg){
    $('#example_laboratory').dataTable().fnDestroy();
    $('#example_laboratory').empty();
    var table = $('#example_laboratory').dataTable(
    {
        "sSort": true,
        "sPaginate": true,
        "scrollX": true,
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Lab Description", 
            "mData": "lab_description"
        },

        {
            "sTitle": "Batch Size ", 
            "mData": "batch_size"
        },

        {
            "sTitle": "Availability of Manuals", 
            "mData": "manual_availabitity"
        },

        {
            "sTitle": "Quality of Instruments", 
            "mData": "instrument_quality"
        },						

        {
            "sTitle": "Safety Measures", 
            "mData": "safety_measures"
        },		

        {
            "sTitle": "Remarks", 
            "mData": "remarks"
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
        "aaData": msg,					
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }				
    });	
}

//Function is to edit laboratory details		
$('#details').on('click','.edit_laboratory',function(e){
    var validator = $('#laboratory').validate();
    validator.resetForm();
    $('#lab_id').val($(this).attr('id'));
    $('#lab_description').val($(this).attr('data-lab_description'));
    $('#batch_size').val($(this).attr('data-batch_size'));		
    $('#manual_availabitity').val($(this).attr('data-manual_availabitity'));
    $('#instrument_quality').val($(this).attr('data-instrument_quality'));	
    $('#safety_measures').val($(this).attr('data-safety_measures'));
    $('#remarks').val($(this).attr('data-remarks'));	

    $('#details #update_lab').show();
    $('#details #save_lab').hide();
});

//Function is to reset laboratory form 
$('#details').on('click','#reset_lab',function(e){
    $("label.error").hide();
    $(".error").removeClass("error");	
    $('#update_lab').hide();
    $('#save_lab').show();

});

//Function is to delete laboratory details
$('#details').on('click','.delete_laboratory',function(){
    $('#lab_id').val($(this).attr('id')); 
    $("#delete_sel").attr("onclick","delete_laboratory();");
    $('#delete_modal').modal('show');
		
});

//Function is to delete laboratory details
function delete_laboratory(){
    $('#loading').show();
    var lab_id = $('#lab_id').val();
    var dept_id = $('#department').val();
    var post_data ={
        'lab_id':lab_id ,
        'dept_id':dept_id
    }
    
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/facilities_and_technical_support/delete_laboratory',
        data:post_data,					
        success:function(data){ 
            if(data == 1){ }
            success_modal_delete();
            $('#loading').hide();
            fetch_laboratory();
            $("#reset_lab").trigger("click");
        }
    }); 
}

//Function is to save or update equipment details      
$('#details').on('click',"#update_eqpt,#save_eqpt",function(e){
    $('#loading').show();
    var department = $('#department').val(); 
    $("#equipment").validate({
        rules: {
            equipment_at:{
                required: true
            }, 
            equipment_name:{
                required: true,	
                maxlength: 200,
                loginRegex_spec : true						
            },
            make_model:{
                required: false,
                maxlength: 200,
                loginRegex_spec:true
            }, 	  
            sop_name_code:{
                required: false,
                maxlength: 200,
                loginRegex_spec:true
            }, 
            sop:{
                required: true,
            },
            log_book:{
                required: true
            },	
            price:{
                maxlength: 8,
            },
            remarks:{
                required: false,
                maxlength: 2000,
            }
				  	
        }
    });
    var idClicked = $(this).attr("id"); 
    var flag = $('#equipment').valid();
    if(department != ""){
        if(flag == true ){
            $('#dept_error').html("");
            if(idClicked == 'update_eqpt'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_eqpt'){
                buttonvalue = 'save'
            }
            var values = $("#equipment").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&dept_id='+department;
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/facilities_and_technical_support/save_update_equipment',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    fetch_equipment();
                    $("#reset_eqpt").trigger("click");
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();	
        }
    } else{
        $('#loading').hide();	
    }
});

//Function is to fetch equipment tabel
function fetch_equipment(){
    $('#loading').show();
    var department = $('#department').val(); 
    var post_data = {
        'dept_id':department
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/facilities_and_technical_support/fetch_equipment',
        data: post_data,
        dataType: 'json',
        success:function(msg){ 
            populate_table_equipment(msg);
            $('#loading').hide();
        }	
    });
}

//Function is to populate equipment
function populate_table_equipment(msg){
    $('#example_equipment').dataTable().fnDestroy();
    $('#example_equipment').empty();
    var table = $('#example_equipment').dataTable(
    {
        "sSort": true,
        "sPaginate": true,
        "scrollX": true,
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Equipment At", 
            "mData": "equipment_at"
        },

        {
            "sTitle": "Name of the Equipment", 
            "mData": "equipment_name"
        },

        {
            "sTitle": "Make & Model", 
            "mData": "make_model"
        },

        {
            "sTitle": "SOP", 
            "mData": "sop"
        },						
        {
            "sTitle": "Log Book", 
            "mData": "log_book"
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
        "aaData": msg,					
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        },
        "fnDrawCallback" : function () {
            $('.group').parent().css({
                'background-color' : '#C7C5C5'
            });
        }				
    }).rowGrouping({
        iGroupingColumnIndex : 1,
        bHideGroupingColumn : true
    });	
}

//Function is to edit equipment details		
$('#details').on('click','.edit_equipment',function(e){
    var validator = $('#laboratory').validate();
    validator.resetForm();
    $('#eqpt_id').val($(this).attr('id'));
    $('#equipment_at').val($(this).attr('data-equipment_at'));
    $('#equipment_name').val($(this).attr('data-equipment_name'));		
    $('#make_model').val($(this).attr('data-make_model'));
    $('#sop_name_code').val($(this).attr('data-sop_name_code'));	
    $('#sop').val($(this).attr('data-sop'));
    $('#log_book').val($(this).attr('data-log_book'));
    $('#purchase_date').val($(this).attr('data-purchase_date'));
    $('#price').val($(this).attr('data-price'));
    $('#remarks').val($(this).attr('data-remarks'));

    $('#details #update_eqpt').show();
    $('#details #save_eqpt').hide();
});

//Function is to delete equipment details
$('#details').on('click','.delete_equipment',function(){
    $('#eqpt_id').val($(this).attr('id')); 
    $("#delete_sel").attr("onclick","delete_equipment();");
    $('#delete_modal').modal('show');
		
});

//Function is to delete equipment details
function delete_equipment(){
    $('#loading').show();
    var eqpt_id = $('#eqpt_id').val();
    var dept_id = $('#department').val();
    var post_data ={
        'eqpt_id':eqpt_id ,
        'dept_id':dept_id
    }
    
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/facilities_and_technical_support/delete_equipment',
        data:post_data,					
        success:function(data){ 
            if(data == 1){ 
                success_modal_delete();
                $('#loading').hide();
                fetch_equipment();
                $("#reset_eqpt").trigger("click");
            }
        }
    }); 
}

//Function is to reset equipment form 
$('#details').on('click','#reset_eqpt',function(e){
    $("label.error").hide();
    $(".error").removeClass("error");	
    $('#update_eqpt').hide();
    $('#save_eqpt').show();
});

//Function is to save or update non teaching support details      
$('#details').on('click',"#update_nts,#save_nts",function(e){
    $('#loading').show();
    var department = $('#department').val(); 
    $("#non_teaching_support").validate({
        rules: {
            staff_name:{
                required: true,
                maxlength: 200,
                loginRegex_spec : true
            }, 
            designation:{
                required: true,	
                maxlength: 200,
                loginRegex_spec : true						
            },
            joining_date:{
                required: true
            }, 	  
            quali_at_joining:{
                required: true,
                maxlength: 200,
                loginRegex_spec:true
            }, 
            quali_now:{
                required: false,
                maxlength: 200,
                loginRegex_spec:true
            },
            other_tech_skill_gained:{
                required: true,
                maxlength: 200
            },
            responsibility:{
                maxlength: 2000
            }
				  	
        }
    });
    var idClicked = $(this).attr("id"); 
    var flag = $('#non_teaching_support').valid();
    if(department != ""){
        if(flag == true ){
            $('#dept_error').html("");
            if(idClicked == 'update_nts'){
                buttonvalue = 'update'
            } else if( idClicked == 'save_nts'){
                buttonvalue = 'save'
            }
            var values = $("#non_teaching_support").serialize();
            values = values + '&button_update='+buttonvalue;
            values = values + '&dept_id='+department;
            $.ajax({
                type:"POST",
                url:base_url+'nba_sar/facilities_and_technical_support/save_update_nts',
                data:values,
                dataType:'json',
                success:function(data){
                    $('#loading').hide();
                    fetch_nts();
                    $("#reset_nts").trigger("click");
                    if(data == 1){
                        success_modal(data);
                    }
                    if(data == 0){
                        fail_modal(data);
                    }
                }
            }); 
        }else{
            $('#loading').hide();	
        }
    } else{
        $('#loading').hide();	
    }
});

//Function is to fetch non teaching support tabel
function fetch_nts(){
    $('#loading').show();
    var department = $('#department').val(); 
    var post_data = {
        'dept_id':department
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/facilities_and_technical_support/fetch_nts',
        data: post_data,
        dataType: 'json',
        success:function(msg){ 
            populate_table_nts(msg);
            $('#loading').hide();
        }	
    });
}

//Function is to populate non teaching support
function populate_table_nts(msg){
    $('#example_nts').dataTable().fnDestroy();
    $('#example_nts').empty();
    var table = $('#example_nts').dataTable(
    {
        "sSort": true,
        "sPaginate": true,
        "scrollX": true,
        "aoColumns": [
        {
            "sTitle": "Sl No.", 
            "mData": "sl_no" ,
            "sType": 'numeric',
            "bSortable" : true, 
            "sSortDataType": "dom-text"
        },

        {
            "sTitle": "Name of Technical Staff", 
            "mData": "staff_name"
        },

        {
            "sTitle": "Designation", 
            "mData": "designation"
        },

        {
            "sTitle": "Date of Joining", 
            "mData": "joining_date"
        },						
        {
            "sTitle": "Qualification at Joining", 
            "mData": "quali_at_joining"
        },	
        {
            "sTitle": "Qualification Now", 
            "mData": "quali_now"
        },	
        {
            "sTitle": "Other Technical Skills Gained", 
            "mData": "other_tech_skill_gained"
        },	
        {
            "sTitle": "Responsibility", 
            "mData": "responsibility"
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
        "aaData": msg,					
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            return nRow;
        }				
    });	
}

//Function is to edit non teaching support details		
$('#details').on('click','.edit_nts',function(e){
    var validator = $('#non_teaching_support').validate();
    validator.resetForm();
    $('#nts_id').val($(this).attr('id'));
    $('#staff_name').val($(this).attr('data-staff_name'));
    $('#designation').val($(this).attr('data-designation'));		
    $('#joining_date').val($(this).attr('data-joining_date'));
    $('#quali_at_joining').val($(this).attr('data-quali_at_joining'));	
    $('#quali_now').val($(this).attr('data-quali_now'));
    $('#other_tech_skill_gained').val($(this).attr('data-other_tech_skill_gained'));
    $('#responsibility').val($(this).attr('data-responsibility'));	
    $('#details #update_nts').show();
    $('#details #save_nts').hide();
});

//Function is to reset non teaching support form 
$('#details').on('click','#reset_nts',function(e){
    $("label.error").hide();
    $(".error").removeClass("error");	
    $('#update_nts').hide();
    $('#save_nts').show();
});

//Function is to delete non teaching support details
$('#details').on('click','.delete_nts',function(){
    $('#nts_id').val($(this).attr('id')); 
    $("#delete_sel").attr("onclick","delete_nts();");
    $('#delete_modal').modal('show');
		
});

//Function is to delete non teaching support details
function delete_nts(){
    $('#loading').show();
    var nts_id = $('#nts_id').val();
    var dept_id = $('#department').val();
    var post_data ={
        'nts_id':nts_id ,
        'dept_id':dept_id
    }
    
    $.ajax({
        type:"POST",
        url:base_url+'nba_sar/facilities_and_technical_support/delete_nts',
        data:post_data,					
        success:function(data){ 
            if(data == 1){ 
                success_modal_delete();
                $('#loading').hide();
                fetch_nts();
                $("#reset_nts").trigger("click");
            }
        }
    }); 
}
//File ends here.
