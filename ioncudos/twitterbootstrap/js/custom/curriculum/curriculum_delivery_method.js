
//Delivery Method List Page...

var base_url = $('#get_base_url').val();
var counter;
var new_counter = new Array();
new_counter.push(1);

//Function is to delete delivery method by sending the delivery method id to controller.
$(document).ready(function () {
	var table_row;
	var data_val;

	$('.get_id').live('click', function (e) {
		data_val = $(this).attr('id');
		table_row = $(this).closest("tr").get(0);
	});

	//Function is to delete po type by sending the po type id to controller.
	$('.delete_dm').click(function (e) {
		$('#loading').show();
		e.preventDefault();
		var base_url = $('#get_base_url').val();

		var post_data = {
			'crclm_dm_id' : data_val,
		}

		$.ajax({
			type : "POST",
			url : base_url + 'curriculum/curriculum_delivery_method/crclm_dm_delete',
			data : post_data,
			datatype : "JSON",
			success : function (msg) {
				if (msg == 1) {
					//delete complete
		 			var oTable = $('#example').dataTable();
					oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
					$('#loading').hide();
				} else {
					//delete incomplete
					$('#cant_delete').modal('show');
					$('#loading').hide();
				}
			}
		});
	});
	//Added by shivaraj B 13-11-2015
	//code to reset multiselect button 
	
	$('.clear_all').on('click', function () {
		$("#bloom_level_1").multiselect("clearSelection");
		$("#bloom_level_1").multiselect('refresh');
	});
	
	//delivery method add page
	$('.bloom_level').multiselect({
		maxHeight : 200,
		buttonWidth : 170,
		numberDisplayed : 5,
		nSelectedText : 'selected',
		nonSelectedText : "Select Bloom's Level"
	});

	$('#delivery_method_1').multiselect({
		nSelectedText : 'selected',
		nonSelectedText : 'Select Delivery Method'
	});

	//delivery method edit page
	$('.bloom_level_edit').multiselect({
		maxHeight : 200,
		buttonWidth : 160,
		numberDisplayed : 5,
		nSelectedText : 'selected',
		nonSelectedText : "Select Bloom's Level",
		onChange : function (option, checked) {
			var selectedOptions = $('#bloom_level option:selected');
			var selections = [];
			var action_verb_data = [];

			$("#bloom_level option:selected").each(function () {
				var bloom_level_id = $(this).val();
				var bloom_level = $(this).text();
				var action_verbs = $(this).attr('title');
				selections.push(bloom_level_id);
				action_verb_data.push(bloom_level + ' - ' + action_verbs + '<br>');
			});

			var action_verb = action_verb_data.join("<b></b>");
			$('#bloom_level_edit_actionverbs').html(action_verb.toString());
		}
	});

	$('#delivery_method').multiselect({
		nonSelectedText : 'Select Delivery Method'
	});
});

// Add view JS functions
$.validator.addMethod("loginRegex", function (value, element) {
	return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

$.validator.addMethod("needsSelection", function(value, element) {
        return $(element).multiselect("getChecked").length > 0;
    });




// Form validation rules are defined & checked before form is submitted to controller.
$("#delivery_method_add_form").validate({
	rules : {
		delivery_method_name : {
			loginRegex : true,
			required:true,
		},
	},
	     errorClass: "help-inline font_color",
            errorElement: "span",
			highlight: function(element, errorClass, validClass) {
                $(element).parent().parent().addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().parent().removeClass('error');
                $(element).parent().parent().addClass('success');
            }
});

//Function to call the uniqueness checks of po type name to controller
$('.delivery_method_add_form_submit').on('click', function (e) {
	
	e.preventDefault();
	var flag;
	flag = $('#delivery_method_add_form').valid();
	var crclm_id = $('#crclm_id').val();
	var delivery_mtd_name = $('#delivery_method_name').val();
	var delivery_mtd_description = $('#delivery_method_description').val();
	var bloom_level_1 = $('#bloom_level_1').val();
	var base_url = $('#get_base_url').val();

	var post_data = {
		'crclm_id' : crclm_id,
		'delivery_mtd_name' : delivery_mtd_name,
		'delivery_mtd_description' : delivery_mtd_description,
		'bloom_level_1' : bloom_level_1
	}
        var options = $('#bloom_level_1 > option:selected');

	if (flag == true ) {
            $('#loading').show();
		//$("#error").html(" ");
		$.ajax({
			type : "POST",
			url : base_url + 'curriculum/curriculum_delivery_method/crclm_add_search_dm_name',
			data : post_data,
			datatype : "JSON",
			success : function (msg) {
				if (msg == 1) {
					$.ajax({
						type : "POST",
						url : base_url + 'curriculum/curriculum_delivery_method/crclm_delivery_method_insert_record',
						data : post_data,
						datatype : "JSON",
						success : function (msg) {
							$.ajax({
								type : "POST",
								url : base_url + 'curriculum/curriculum_delivery_method/crclm_dm_table_generate',
								data : post_data,
								dataType : 'json',
								success : [populate_table, success_modal, reset_achive,$('#loading').hide()]
							});
						}
					});
				} else {
					$('#loading').hide();
					$('#myModal_exists').modal('show');
				}
			}
		});
	}
        //else{	
//	    //$("#error").html("This field is required.");
//	    $('#loading').hide();
//	}
});

function reset_achive() {

	$("#delivery_method_add_form").trigger('reset');
	$("#bloom_level_1").multiselect("clearSelection");
	$("#bloom_level_1").multiselect( 'refresh' );
	$('#bloom_level_1').val('');
}
$('#example').on('click', '.edit_cr', function (e) {
	var id = $(this).attr('id');
	var delivery_mtd_name = $(this).attr('id-name');
	var delivery_mtd_desc = $(this).attr('id-descr');
	var id_dm = $(this).attr('id-dm');
        $('#loading').show();
	var post_data = {

		'delivery_mtd_id' : id_dm,
		'crclm_id' : id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'curriculum/curriculum_delivery_method/crclm_delivery_method_edit_record',
		data : post_data,
		datatype : 'json',
		success : function (msg) {
			var dropdown = JSON.parse(msg); //alert(dropdown);
			var c = dropdown.mapped_bloom_level_data.length;
			var data = (dropdown.mapped_bloom_level_data);
			$("#bloom_level_2").val(data);
			$('#bloom_level_2').multiselect('rebuild');

			$('#delivery_method_id_edit').val(id_dm);
			$('#crclm_id_edit').val(id);
			$('#delivery_method_name_edit').val(delivery_mtd_name);
			$('#delivery_method_description_edit').val(delivery_mtd_desc);
			$('#edit').modal('show');
			$('#loading').hide();
		}
	});

});

//Function to call the uniqueness checks of po type name to controller
$('.delivery_method_edit_form_submit').on('click', function (e) {
	e.preventDefault();
	var flag;
	flag = $('#delivery_method_edit_form').valid();
	var crclm_id = $('#crclm_id_edit').val();
	var bloom_level = $('#bloom_level_2').val();
	var delivery_mtd_description = $('#delivery_method_description_edit').val();
	var delivery_mtd_name = $('#delivery_method_name_edit').val();
	var delivery_mtd_id = $('#delivery_method_id_edit').val();

	var base_url = $('#get_base_url').val();
	var post_data = {

		'delivery_mtd_id' : delivery_mtd_id,
		'crclm_id' : crclm_id,
		'bloom_level' : bloom_level,
		'delivery_mtd_name' : delivery_mtd_name,
		'delivery_mtd_description' : delivery_mtd_description
	}
var options = $('#bloom_level_2 > option:selected');
	if (flag == true) {
	//$("#error1").html(" ");
		$.ajax({
			type : "POST",
			url : base_url + 'curriculum/curriculum_delivery_method/crclm_edit_search_dm_name',
			data : post_data,
			datatype : "JSON",
			success : function (msg) {

				if (msg == 1) {

					//	$("#delivery_method_edit_form").submit();
					$.ajax({
						type : "POST",
						url : base_url + 'curriculum/curriculum_delivery_method/crclm_delivery_method_update_record',
						data : post_data,
						dataType : 'json',
						success : [populate_table, success_modal_update, close]
					});
				} else {
					$('#myModal_exists').modal('show');
				}
			}
		});
	}
        //else{	$("#error1").html("This field is required.");}
});

function close() {
	$("#edit").modal('hide');
}
// Edit view JS functions
$.validator.addMethod("loginRegex", function (value, element) {
	return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.
$("#delivery_method_edit_form").validate({
	rules : {
		delivery_mtd_name : {
			loginRegex : true,
		},
	},
	errorClass : "help-inline",
	errorElement : "span",
	highlight : function (element, errorClass, validClass) {
		$(element).parent().parent().addClass('error');
	},
	unhighlight : function (element, errorClass, validClass) {
		$(element).parent().parent().removeClass('error');
		$(element).parent().parent().addClass('success');
	}
});

var bloom_level_options = "<?php echo $bloom_level_options; ?>";
var delivery_method_options = "<?php echo $delivery_method_options; ?>";

$(".add_crclm_dm_button").on('click', function () {
	var crclm_id = $('#crclm_id').val();
	window.location = base_url + 'curriculum/curriculum_delivery_method/crclm_delivery_method_add_record/' + crclm_id;
});
	//set cookie
    	if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm_id option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		dm_table_generate();
    	}
//generate table using curriculum id
function dm_table_generate() {
	$.cookie('remember_curriculum', $('#crclm_id option:selected').val(), { expires: 90, path: '/'});
	var crclm_id = $('#crclm_id').val();
	$('#loading').show();
	if (crclm_id) {
		//enable add button
		$('button').prop('disabled', false);
	} else {
		//disable add button
		$('button').prop('disabled', true);
	}

	var post_data = {
		'crclm_id' : crclm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'curriculum/curriculum_delivery_method/crclm_dm_table_generate',
		data : post_data,
		dataType : 'json',
		success : function(msg){
			$('#loading').hide();
                        if(crclm_id){
			 populate_table(msg);
                     }
		}
	});
}
/**Calling the modal on success**/
function success_modal(msg) {
	var data_options = '{"text":"Your data has been saved successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
	var options = $.parseJSON(data_options);
	noty(options);
}
/**Calling the modal on success**/
function success_modal_update(msg) {
	var data_options = '{"text":"Your data has been updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
	var options = $.parseJSON(data_options);
	noty(options);
}
//generates a grid on select of curriculum from the dropdown
function populate_table(msg) { 
	$('#example').dataTable().fnDestroy();
	$('#example').dataTable({
		"aoColumns" : [{
				"sTitle" : "Sl No.",
				"mData" : "sl_no",
                                "sClass": "alignRight"
			},{
				"sTitle" : "Curriculum Delivery Method",
				"mData" : "delivery_mtd_name"
			}, {
				"sTitle" : "Description",
				"mData" : "delivery_mtd_desc"
			 },{
				"sTittle": "Bloom's Level",
				"mData":"bloom_actionverbs" 
			},{
				"sTitle" : "Edit",
				"mData" : "crclm_dm_edit"
			}, {
				"sTitle" : "Delete",
				"mData" : "crclm_dm_delete"
			}
		],
		"aaData" : msg["crclm_dm_details"],
		"sPaginationType" : "bootstrap"
	});
}
var crclm_id = $('#crclm_id').val();

if (crclm_id) {
	//enable add button
	$('button').prop('disabled', false);
} else {
	//disable add button
	$('button').prop('disabled', true);
}
