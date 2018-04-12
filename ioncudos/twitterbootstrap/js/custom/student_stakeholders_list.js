$(document).ready(function () {
    getDepartmentList();
});
var base_url = $('#get_base_url').val();
$('#dept_name').live('change', function () {
    var dept_id = $('#dept_name').val();
    $.cookie('remember_dept_id', $('#dept_name option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var post_data = {
        'dept_id': dept_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadProgramList',
        data: post_data,
        success: function (pgm_list) {
            $('#program_name').html(pgm_list);
            $('#dept_id').val($('#dept_name').val());
            if ($.cookie('remember_pgm_id') != null) {
                $('#program_name option[value="' + $.cookie('remember_pgm_id') + '"]').prop('selected', true);
                $('#program_name').trigger('change');
            }
        }
    });
});
$('#program_name').live('change', function () {
    var pgm_id = $('#program_name').val();
    $.cookie('remember_pgm_id', $('#program_name option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var post_data = {
        'pgm_id': pgm_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadCurriculumList',
        data: post_data,
        success: function (msg) {
            $('#curriculum_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
            if ($.cookie('remember_crclm_id') != null) {
                $('#curriculum_name option[value="' + $.cookie('remember_crclm_id') + '"]').prop('selected', true);
                $('#curriculum_name').trigger('change');
            }
        }
    });
});
$('#curriculum_name').live('change', function () {
$('#crclm_id').val($('#curriculum_name').val());
var crclm_id = $('#curriculum_name').val();
    $.cookie('remember_crclm_id', $('#curriculum_name option:selected').val(), {
        expires: 90,
        path: '/'
    });
	$.cookie('remember_section_id', $('#section_name option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var post_data = {
        'crclm_id': crclm_id,
    }	
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadSectionList',
        data: post_data,
        success: function (msg) {
		
            $('#section_name').html(msg);   
			$('#et_section_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
            if ($.cookie('remember_section_id') != null) {
                $('#section_name option[value="' + $.cookie('remember_section_id') + '"]').prop('selected', true);
                $('#section_name').trigger('change'); 
            }		
		if($('#section_name').val() == ""){
			
		var dept_id = $('#dept_id').val();
		var pgm_id = $('#pgm_id').val();
		var crclm_id = $('#crclm_id').val(); 
		var section_id = $('#section_name').val();
		var post_data = {
			'dept_id': dept_id,
			'pgm_id': pgm_id,
			'crclm_id': crclm_id,			
		}
			$.ajax({
				type: "POST",
				url: base_url + 'survey/import_student_data/getStudentsList_crclm',
				data: post_data,
				success: function (stud_list) {		
					var data = $.parseJSON(stud_list);
					var i = 0;var k;
					var table = blank ="";
					//console.log(stud_list);
					table += "<table class='table table-bordered dataTable' id='example'>";
					table += "<thead><tr><th><center>Sl No.</center></th><th><center>PNR</center></th><th><center>Title</center></th><th><center>First Name</center></th><th><center>Last Name</center></th><th><center>Email</center></th><th><center>Contact Number</center></th><th><center>DOB</center></th><th><center>Edit</center></th><th><center>Status</center></th><th><center>Action</center></th></tr></thead>";
					table += "<tbody>";
					$.each(data, function () {
						var contact = '';
						if (data[i].contact_number == 0 || data[i].contact_number == null) {
							contact = ''
						} else {
							contact = data[i].contact_number;
						}
						if(data[i].last_name != null ){ blank = data[i].last_name; } else { blank = " ";} 
						$('#student_usn_data').val(data[i].student_usn);
						table += "<tr>";
						table += "<td>" + (i + 1) + "</td>";
						table += "<td>" + data[i].student_usn + "</td>";
						table += "<td>" + data[i].title + "</td>";
						table += "<td>" + data[i].first_name + "</td>";
						table += "<td>" + blank + "</td>";
						table += "<td>" + data[i].email + "</td>";
						table += "<td><center>" + contact + "</center></td>";
						table += "<td>" + data[i].dob + "</td>";
						table += "<td><center><a href='#edit' class='status edit_data' id='" + data[i].ssd_id + "' role='button' data-refresh='true'><i class='icon-pencil'></i></a></center></td>";
						var link = new_link = "";
						var ssd_id_data =  data[i].ssd_id; 
						if (data[i].status_active == 1) {
							link += "<a href='#disable' class='status' id='" + data[i].ssd_id + "' role='button' data-toggle='modal'><i class='icon-ban-circle'></i></a>";
						} else { 			
							link += "<a href='#enable' class='status' id='" + data[i].ssd_id + "' data-usn= '" + data[i].student_usn + "' role='button' data-toggle='modal'><i class='icon-ok-circle'></i></a>";
						}

						table += "<td><center>" + link + "</center></td>";
						table += "<td><center><a href='#' class='status delete_data' id='" + data[i].ssd_id + "' data-user_id = '" + data[i].user_id + "' role='button' data-refresh='true'><i class='icon-remove'></i></a></center></td>";
						table += "</tr>";
						i++;
					});
					table += "</tbody></table>";

					$('#student_data').html(table);
					$('#example').dataTable({
						"sPaginationType": "bootstrap"
					});
				}
			});
			
			} 
        }
    });

		
	
	
});

 $('#section_name').on('change', function () {
    $('#section_id').val($('#section_name').val());
    $.cookie('remember_section_id', $('#section_name option:selected').val(), {
        expires: 90,
        path: '/'
    });
    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $('#crclm_id').val(); 
	var section_id = $('#section_name').val();
    var post_data = {
        'dept_id': dept_id,
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
		'section_id':section_id
    }
    if($('#section_name').val() != ""){
	$.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/getStudentsList',
        data: post_data,
        success: function (stud_list) {		
            var data = $.parseJSON(stud_list);
            var i = 0;var k;
            var table = blank ="";
			
            table += "<table class='table table-bordered dataTable' id='example'>";
            table += "<thead><tr><th><center>Sl No.</center></th><th><center>PNR</center></th><th><center>Title</center></th><th><center>First Name</center></th><th><center>Last Name</center></th><th><center>Email</center></th><th><center>Contact Number</center></th><th><center>DOB</center></th><th><center>Edit</center></th><th><center>Status</center></th><th><center>Action</center></th></tr></thead>";
            table += "<tbody>";
            $.each(data, function () {
                var contact = '';
                if (data[i].contact_number == 0 || data[i].contact_number == null) {
                    contact = ''
                } else {
                    contact = data[i].contact_number;
                }
				if(data[i].last_name != null ){ blank = data[i].last_name; } else { blank = " ";} 
				$('#student_usn_data').val(data[i].student_usn);
                table += "<tr>";
                table += "<td>" + (i + 1) + "</td>";
                table += "<td>" + data[i].student_usn + "</td>";
                table += "<td>" + data[i].title + "</td>";
                table += "<td>" + data[i].first_name + "</td>";
                table += "<td>" + blank + "</td>";
                table += "<td>" + data[i].email + "</td>";
                table += "<td><center>" + contact + "</center></td>";
                table += "<td>" + data[i].dob + "</td>";
                table += "<td><center><a href='#edit' class='status edit_data' id='" + data[i].ssd_id + "' role='button' data-refresh='true'><i class='icon-pencil'></i></a></center></td>";
                var link = new_link = "";
				var ssd_id_data =  data[i].ssd_id; 
                if (data[i].status_active == 1) {
                    link += "<a href='#disable' class='status' id='" + data[i].ssd_id + "' role='button' data-toggle='modal'><i class='icon-ban-circle'></i></a>";
                } else { 			
                    link += "<a href='#enable' class='status' id='" + data[i].ssd_id + "' data-usn= '" + data[i].student_usn + "' role='button' data-toggle='modal'><i class='icon-ok-circle'></i></a>";
                }

                table += "<td><center>" + link + "</center></td>";
                table += "<td><center><a href='#' class='status delete_data' id='" + data[i].ssd_id + "' data-user_id = '" + data[i].user_id + "' role='button' data-refresh='true'><i class='icon-remove'></i></a></center></td>";
                table += "</tr>";
                i++;
            });
            table += "</tbody></table>";

            $('#student_data').html(table);
            $('#example').dataTable({
                "sPaginationType": "bootstrap"
            });
        }
    });
	}else{
		var dept_id = $('#dept_id').val();
		var pgm_id = $('#pgm_id').val();
		var crclm_id = $('#crclm_id').val(); 
		var section_id = $('#section_name').val();
		var post_data = {
			'dept_id': dept_id,
			'pgm_id': pgm_id,
			'crclm_id': crclm_id,			
		}
			$.ajax({
				type: "POST",
				url: base_url + 'survey/import_student_data/getStudentsList_crclm',
				data: post_data,
				success: function (stud_list) {		
					var data = $.parseJSON(stud_list);
					var i = 0;var k;
					var table = blank ="";
					//console.log(stud_list);
					table += "<table class='table table-bordered dataTable' id='example'>";
					table += "<thead><tr><th><center>Sl No.</center></th><th><center>PNR</center></th><th><center>Title</center></th><th><center>First Name</center></th><th><center>Last Name</center></th><th><center>Email</center></th><th><center>Contact Number</center></th><th><center>DOB</center></th><th><center>Edit</center></th><th><center>Status</center></th><th><center>Action</center></th></tr></thead>";
					table += "<tbody>";
					$.each(data, function () {
						var contact = '';
						if (data[i].contact_number == 0 || data[i].contact_number == null) {
							contact = ''
						} else {
							contact = data[i].contact_number;
						}
						if(data[i].last_name != null ){ blank = data[i].last_name; } else { blank = " ";} 
						$('#student_usn_data').val(data[i].student_usn);
						table += "<tr>";
						table += "<td>" + (i + 1) + "</td>";
						table += "<td>" + data[i].student_usn + "</td>";
						table += "<td>" + data[i].title + "</td>";
						table += "<td>" + data[i].first_name + "</td>";
						table += "<td>" + blank + "</td>";
						table += "<td>" + data[i].email + "</td>";
						table += "<td><center>" + contact + "</center></td>";
						table += "<td>" + data[i].dob + "</td>";
						table += "<td><center><a href='#edit' class='status edit_data' id='" + data[i].ssd_id + "' role='button' data-refresh='true'><i class='icon-pencil'></i></a></center></td>";
						var link = new_link = "";
						var ssd_id_data =  data[i].ssd_id; 
						if (data[i].status_active == 1) {
							link += "<a href='#disable' class='status' id='" + data[i].ssd_id + "' role='button' data-toggle='modal'><i class='icon-ban-circle'></i></a>";
						} else { 			
							link += "<a href='#enable' class='status' id='" + data[i].ssd_id + "' data-usn= '" + data[i].student_usn + "' role='button' data-toggle='modal'><i class='icon-ok-circle'></i></a>";
						}

						table += "<td><center>" + link + "</center></td>";
						table += "<td><center><a href='#' class='status delete_data' id='" + data[i].ssd_id + "' data-user_id = '" + data[i].user_id + "' role='button' data-refresh='true'><i class='icon-remove'></i></a></center></td>";
						table += "</tr>";
						i++;
					});
					table += "</tbody></table>";

					$('#student_data').html(table);
					$('#example').dataTable({
						"sPaginationType": "bootstrap"
					});
				}
			}); 
	}
}); 

function getDepartmentList() {
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadDepartmentList',
        data: '',
        success: function (dept_list) {
            $('#dept_name').html(dept_list);
            if ($.cookie('remember_dept_id') != null) {
                $('#dept_name option[value="' + $.cookie('remember_dept_id') + '"]').prop('selected', true);
                $('#dept_name').trigger('change');
            }
        }
    });
}

var user_id;
var student_usn_val =''; 
$(document).on('click', '.status', function () {
    user_id = $(this).attr("id");    student_usn_val = $(this).attr("data-usn"); 
	
});
$('.edit_data').live('click', function () {
    $('#ssdid').val(user_id);
    $('label.error').html('');
    var post_data = {
        'user_id': user_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/get_student_data_by_id',
        data: post_data,
        success: function (stud_data) {
            var data = $.parseJSON(stud_data);
            $.each(data, function () {
                $('#et_ssd_id').val(data[0].ssd_id);
                $('#et_student_usn').val(data[0].student_usn);
                $('#et_title').val(data[0].title);
                $('#et_first_name').val(data[0].first_name);
                $('#et_last_name').val(data[0].last_name);
                $('#et_email').val(data[0].email);
               if(data[0].contact_number == 0){ $('#et_contact').val('');}else{
			   $('#et_contact').val(data[0].contact_number);
			   }
                $('#dp3').val(data[0].dob);
				$('#et_section_name').val(data[0].section_id);
				
            });
            $('#edit').modal('show');
        }
    });
});

$('.disable_ok').live('click', function () {
    var post_data = {
        'user_id': user_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/disable_student_status',
        data: post_data,
        success: function (status) {
            $('#curriculum_name').trigger('change');
        }
    });
});

$('.enable_ok').live('click', function () {	
			var dept_id = $('#dept_id').val();
			var pgm_id = $('#pgm_id').val();
			var crclm_id = $('#crclm_id').val(); 		
					var post_data = {
						'dept_id': dept_id,
						'pgm_id': pgm_id,
						'crclm_id': crclm_id,
						'student_usn':student_usn_val,
						'ssd_id' : user_id
					}
					$.ajax({
						type: "POST",
						url: base_url + 'survey/import_student_data/count_student',
						data: post_data,
						success: function (msg) { 
						if(msg >=1){ $('#cant_enable_modal').modal('show');}else{
						  var post_data = {
								'user_id': user_id,
							}
							$.ajax({
								type: "POST",
								url: base_url + 'survey/import_student_data/enable_student_status',
								data: post_data,
								success: function (status) {									
									$('#curriculum_name').trigger('change');
								}
							}); 
						}
						}}); 


});
	$.validator.addMethod('selectcheck_year', function (value) {
        return (value != '0000-00-00');
    }, "This field is required.");	
	
$.validator.addMethod("name_valid", function(value, element) {
	 return this.optional(element) ||/^([A-Za-z ])+$/i.test(value);
}, "This field only contain letters and spaces.");

$.validator.addMethod("phone_number_valid", function(value, element) {
	 return this.optional(element) ||/^\d{10}$/i.test(value);
}, "Field should contain only 10 digits.");
	
	
$('.update_ok').live('click', function () {
    $('#student_data_update_form').validate({
         focusCleanup: true,
        rules: {
            'et_student_usn': 'required',
            'et_first_name': {
					required : true,
					name_valid : true
			},
			'et_last_name':{
				name_valid : true
			},
            'et_contact' : 'required',
            'et_title': 'required',
            'et_email': {
                required: true,
                email: true
            },
            'et_contact' : {
			phone_number_valid : true
			}
            //'et_dob' : 'required',
        }
    });
    if ($('#student_data_update_form').valid()) {
        $('#student_data_update_form').submit();
    }
});
$('#student_data_update_form').live('submit', function (e) {
    e.preventDefault();
    var post_data = {
        'ssd_id': $('#et_ssd_id').val(),
        'student_usn': $('#et_student_usn').val(),
        'title': $('#et_title').val(),
        'first_name': $('#et_first_name').val(),
        'last_name': $('#et_last_name').val(),
        'email': $('#et_email').val(),
        'contact_number': $('#et_contact').val(),
		'section_id': $('#et_section_name').val(),
        'dob': $('#dp3').val(),
		
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/update_student_details',
        data: post_data,
        success: function (status) {
            console.log(status);
            $('#curriculum_name').trigger('change');
            $('#edit').modal('hide');
        }
    });
});
var studid = '';
var stud_user_id = '';
$('.delete_data').live('click', function () {
    studid = $(this).attr('id');
	stud_user_id = $(this).attr('data-user_id');
    $('#delete_stud_modal').modal('show');
});
$('#confirm_delete').click(function () {
    $.ajax({
        type: 'POST',
        url: base_url + 'survey/import_student_data/delete_student_stakeholder',
        data: {
            'ssid': studid,
			'stud_user_id' : stud_user_id,
        },
        success: function (msg) { 
            if ($.trim(msg) == 2) {
                $('#delete_stud_modal').modal('hide');
                $('#modal_header').html('Warning !');
                $('#modal_content').html('Can not delete Student Stakeholder as Stakeholder is associated with Survey data.');
                $('#sucs_del_stud_modal').modal('show');
            } else if ($.trim(msg) == 0) {
                $('#delete_stud_modal').modal('hide');
                $('#modal_header').html('Success');
                $('#modal_content').html('Student Stakeholder deleted successfully.');
                $('#sucs_del_stud_modal').modal('show');
            } else {
                $('#delete_stud_modal').modal('hide');
                $('#modal_header').html('Error !');
                $('#modal_content').html('Something went wrong. Try again.');
                $('#sucs_del_stud_modal').modal('show');
            }
        },
    });
});
$('#success_delete').live('click', function () {
    $('#curriculum_name').trigger('change');
});

$("#dp3").datepicker({
			format:"mm-dd-yyyy",
			endDate:'-1d',
			changeYear: true 
			// viewMode: 'years'
	}).on('changeDate',function(ev){
			$(this).blur();
			$(this).datepicker('hide');
	});	
	$('#btn').click(function(){
		$(document).ready(function(){
			$('#dp3').datepicker().focus();
		});
	});	