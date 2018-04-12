
/////////////////////////////////////////////////////////////////////////////////////////////

// Static View JS functions.
var base_url = $('#get_base_url').val();
$("#hint a").tooltip();
var crs_id;
var table_row;
var tee_url;
var model_qp_url;

if ($.cookie('remember_dept') != null) {
	// set the option to selected that corresponds to what the cookie is set to
	$('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
	select_pgm_list();
}

function select_pgm_list() {

	$.cookie('remember_dept', $('#department option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var dept_id = document.getElementById('department').value;

	var post_data = {
		'dept_id' : dept_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/select_pgm_list',
		data : post_data,
		success : function (msg) {
			//document.getElementById('program').innerHTML = msg;
			$('#program').html(msg);
			if ($.cookie('remember_pgm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
				select_crclm_list();

			}
		}
	});
}

function select_crclm_list() {
	$.cookie('remember_pgm', $('#program option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var pgm_id = document.getElementById('program').value;
	var post_data = {
		'pgm_id' : pgm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/select_crclm_list',
		data : post_data,
		success : function (msg) {
			//document.getElementById('curriculum').innerHTML = msg;
			$('#curriculum').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				//get_selected_value();
				select_termlist();
			}
		}
	});
}

function select_termlist() {
	$.cookie('remember_crclm', $('#curriculum option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var crclm_id = document.getElementById('curriculum').value;

	var post_data = {
		'crclm_id' : crclm_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/select_termlist',
		data : post_data,
		success : function (msg) {
			//document.getElementById('term').innerHTML = msg;
			$('#term').html(msg);
			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
				GetSelectedValue();
				// select_termlist();
			}
		}
	});
}

/* Function is used to fetch course details.
 * @param - curriculum id & term id.
 * @returns- an array of course details.
 */
function GetSelectedValue() {
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var dept_id = document.getElementById('department').value;
	var prog_id = document.getElementById('program').value;
	var crclm_id = document.getElementById('curriculum').value;
	var crclm_term_id = document.getElementById('term').value;

	var post_data = {
		'dept_id' : dept_id,
		'prog_id' : prog_id,
		'crclm_id' : crclm_id,
		'crclm_term_id' : crclm_term_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/tee_show_course',
		data : post_data,
		dataType : 'json',
		success : populate_table
	});
}

// List View JS functions.
$('#tee_qp_list').on("click", '.get_crs_id', function () {
	crs_id = $(this).attr('id');
	table_row = $(this).closest("tr").get(0);
});

/* Function is used to generate table grid of  course details.
 * @param -
 * @returns- an array of course details.
 */
function populate_table(msg) {
	var m = 'd';
	$('#tee_qp_list').dataTable().fnDestroy();
	$('#tee_qp_list').dataTable({
		"aoColumns" : [{
				"sTitle" : "Sl No.",
				"mData" : "sl_no"
			}, {
				"sTitle" : "Course Code",
				"mData" : "crs_code"
			}, {
				"sTitle" : "Course Title",
				"mData" : "crs_title"
			}, {
				"sTitle" : "Core / Elective",
				"mData" : "crs_type_name"
			}, {
				"sTitle" : "Credits",
				"mData" : "total_credits"
			}, {
				"sTitle" : "Total Marks",
				"mData" : "total_marks"
			}, {
				"sTitle" : "Course Owner",
				"mData" : "username"
			}, {
				"sTitle" : "Mode",
				"mData" : "crs_mode"
			}, {
				"sTitle" : "View/Edit " + entity_see + " QP",
				"mData" : "crs_id_edit"
			}, {
				"sTitle" : "Add " + entity_see + " QP",
				"mData" : "crs_id_delete"
			},{
				"sTitle" : "Import " + entity_see + " QP",
				"mData" : "upload_link"
			}, {
				"sTitle" : "Status",
				"mData" : "publish"
			}
		],
		"aaData" : msg,
		"sPaginationType" : "bootstrap"
	});
}

$('#tee_qp_list').on('click', '.tee_question_paper', function () {
	var tee_url = $(this).attr('abbr');
	var new_creation = 0;
	var existing_creation = 1;
	$('#modal_title').empty();
	$('#modal_title').html('Create ' + entity_see + ' Question Paper/Rubrics');
	var crclm_id = $(this).attr('data-crclm_id');
	var term_id = $(this).attr('data-term_id');
	var crs_id = $(this).attr('id');
	$('#crs_id').val(crs_id);
	//fetch program id
	var pgm_id = document.getElementById('program').value;
	var topic_count = $(this).attr('data-topic_count'); 
	var topic_status = $(this).attr('data-topic_staus'); 
	var clo_bl_flag =  $(this).attr('data-clo_bl_flag');
	var clo_bl_status =  $(this).attr('data-bl_status'); 
	var post_data = {
		'pgm_id' : pgm_id,
		'crs_id' : crs_id
	}

	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/check_framework_tee',
		data : post_data,
		dataType : 'json',
		success : function (msg) {		
		if(msg.program_fm == 0){ fm_msg = "There is no Question Paper Framework defined for this Program.<br/>";}else{ fm_msg ="";}		
			if (msg.return_data == 1) {
				$.ajax({
					type : "POST",
					url : tee_url,
					dataType : 'json',
					success : function (msg) {
						if(msg.model_qp == 0) {
						
							var tee_qp_text = fm_msg + 'Model Question has not been defined for this Course.<br/><br/> Are you sure you want to proceed with the creation of new TEE Question  Paper for this Course?';

							tee_url = base_url + 'question_paper/manage_model_qp/create_tee_qp/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;
							$('#tee_qp_modal_text').html(tee_qp_text);
                                                        $('#rubrics_table_display_div').empty();
							$('#modal_footer').empty();
                               var tee_rubrics = base_url + 'question_paper/tee_rubrics/tee_add_edit_rubrics/'+ msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;    
                              
							   var button = '<button type="button" id="define_tee_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + tee_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
							   
							   if(clo_bl_flag == 1 && clo_bl_status == 0){
									var data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';
									button  += '<button id="" type="button"  data-link = "curriculum/clo"  data-type_name = " Map Bloom Level"  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New TEE QP</button>';	
							   }else if(topic_status == 1 && topic_count == 0){
									var data = 'Cannot Create Question Paper as topics are not defined for this Course.';				
									button  += '<button id="" type="button" data-link = "curriculum/topic"  data-type_name = " Add Topics"   data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New TEE QP</button>';	
							   }else{
								button += '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "' + tee_url + '"><i class="icon-ok icon-white"></i> Create QP </button>';		
							   }
		
							button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>';
							$('#modal_footer').html(button);							
							$('#tee_qp_modal').modal('show');
						} else {
							var tee_qp_text = fm_msg + 'There is a Model Question paper defined for this Course, If you would like to inherit Model Question Paper, Then click on <font color="maroon">Use Model QP</font> button -OR- Click on <font color="maroon">Create New ' + entity_see + '</font> button to create new ' + entity_see + ' Question Paper.';

							model_qp_url = base_url + 'question_paper/manage_model_qp/copy_existing_model_to_tee_qp/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;

							tee_qp_url = base_url + 'question_paper/manage_model_qp/create_tee_qp/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;
                                                        var tee_rubrics = base_url + 'question_paper/tee_rubrics/tee_add_edit_rubrics/'+ msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;    
							$('#tee_qp_modal_text').html(tee_qp_text);
                                                        $('#rubrics_table_display_div').empty();
							$('#modal_footer').empty();
							var button = '<button type="button" id="define_tee_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + tee_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
							
							button += '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "' + model_qp_url + '" ><i class="icon-ok icon-white"></i> Use Model QP </button>';
							
							button += '<button id="tee_qp_modal_cancel" type="button" class="cancel btn btn-primary" data-dismiss="modal"  abbr = "' + tee_qp_url + '"><i class="icon-ok icon-white"></i> Create New ' + entity_see + '</button>';
							button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
							$('#modal_footer').html(button);
							$('#tee_qp_modal').modal('show');
						}
					}
				});
			} else if(msg.return_data== 2){
			//$('#qp_without_framework_information').modal('show');
				if(msg.program_fm == 0){ fm_msg = "There is no Question Paper Framework defined for this Program.<br/>";}else{ fm_msg ="";}
					$.ajax({
					type : "POST",
					url : tee_url,
					dataType : 'json',
					success : function (msg) {
					var msg_fm = "" ; 
					$('#fm_not_defined_msg').html(msg_fm);
						if (msg.model_qp == 0) {
							var tee_qp_text = fm_msg + 'Model Question has not been defined for this Course.<br/><br/> Are you sure you want to proceed with the creation of new TEE Question Paper for this Course?';

							tee_url = base_url + 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;
							$('#tee_qp_modal_text').html(tee_qp_text);
                                                        $('#rubrics_table_display_div').empty();
							$('#modal_footer').empty();
                                                        var tee_rubrics = base_url + 'question_paper/tee_rubrics/tee_add_edit_rubrics/'+ msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;    
                                                        var button = '<button type="button" id="define_tee_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + tee_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
                         /*   button += '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "' + tee_url + '"><i class="icon-ok icon-white"></i> Create New ' + entity_see + '</button>'; */
						 
						    if(clo_bl_flag == 1 && clo_bl_status == 0){
									var data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';
									button  += '<button id="" type="button"  data-link = "curriculum/clo"  data-type_name = " Map Bloom Level"  data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New TEE QP</button>';	
							   }else if(topic_status == 1 && topic_count == 0){
									var data = 'Cannot Create Question Paper as topics are not defined for this Course.';				
									button  += '<button id="" type="button" data-link = "curriculum/topic"  data-type_name = " Add Topics"   data-error_mag = "'+ data +'"  class="topic_not_defined btn btn-primary"  ><i class="icon-ok icon-white"></i> Create New TEE QP</button>';	
							   }else{
								button += '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "' + tee_url + '"><i class="icon-ok icon-white"></i>  Create New TEE QP </button>';		
							   }
							button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>';
							$('#modal_footer').html(button);							
							$('#tee_qp_modal').modal('show');
						} else {
							var tee_qp_text = fm_msg + 'There is a Model Question paper defined for this Course, If you would like to inherit Model Question Paper, Then click on <font color="maroon">Use Model QP</font> button -OR- Click on <font color="maroon">Create New ' + entity_see + '</font> button to create new ' + entity_see + ' Question Paper.';

							model_qp_url = base_url + 'question_paper/manage_model_qp/copy_existing_model_to_tee_qp/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;

							tee_qp_url = base_url + 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' + msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;

							$('#tee_qp_modal_text').html(tee_qp_text);
                                                        $('#rubrics_table_display_div').empty();
							$('#modal_footer').empty();
                                                        var tee_rubrics = base_url + 'question_paper/tee_rubrics/tee_add_edit_rubrics/'+ msg.pgm_id + '/' + msg.crclm_id + '/' + msg.term_id + '/' + msg.crs_id + '/' + msg.qpd_type;    
                                                        var button = '<button type="button" id="define_tee_rubrics" class="btn btn-success" data-dismiss="modal" aria-hidden="true" data-rubrics_link = "' + tee_rubrics + '" ><i class="icon-ok icon-white"></i> Define Rubrics </button>';
                                                         button += '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "' + model_qp_url + '" ><i class="icon-ok icon-white"></i> Use Model QP </button>';
							button += '<button id="tee_qp_modal_cancel" type="button" class="cancel btn btn-primary" data-dismiss="modal"  abbr = "' + tee_qp_url + '"><i class="icon-ok icon-white"></i> Create New ' + entity_see + '</button>';
							button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
							$('#modal_footer').html(button);
							$('#tee_qp_modal').modal('show');
						}
					}
				});
			}
			else {	
				$('#qp_without_framework_confirmation').modal('show');	
			}
		}
	});
        
        $(document).on('click','#define_tee_rubrics',function(){
            var url_link = $(this).attr('data-rubrics_link');
            window.location=url_link;
        });
	
		$('#qp_without_framework_ok').live('click',function(){
		var dept_id = document.getElementById('department').value;
		var pgm_id = document.getElementById('program').value;
		var crclm_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		var crs_id = document.getElementById('crs_id').value;
		window.location = base_url + 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' + pgm_id + '/' + crclm_id + '/' + term_id + '/' + crs_id + '/5';			 
	});

	/* var tee_url = $(this).attr('abbr');
	var new_creation = 0;
	var existing_creation = 1;

	$.ajax({type: "POST",
	url: tee_url,
	dataType: 'json',
	success: function(msg) {

	if(msg.model_qp == 0){
	var tee_qp_text = 'There is no Model Question Paper defined for this Course. If you would like to continue with creation of new TEE Question Paper for this Course, Then Please Click OK button.';

	tee_url = base_url+'question_paper/manage_model_qp/generate_tee_qp/'+msg.pgm_id+'/'+msg.crclm_id+'/'+msg.term_id+'/'+msg.crs_id+'/'+msg.qpd_type+'/'+new_creation;
	$('#tee_qp_modal_text').html(tee_qp_text);
	$('#modal_footer').empty();
	var button = '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "'+tee_url+'"><i class="icon-ok icon-white"></i> Ok </button>';
	button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';

	$('#modal_footer').html(button);
	//$('#tee_qp_modal_ok').attr('abbr',tee_url);
	$('#tee_qp_modal').modal('show');
	}else{
	var tee_qp_text = 'There is a Model Question paper defined for this Course, If you would like to inherit Model Question Paper, Then click on <font color="blue">Use Model QP</font> button -OR- Click on <font color="blue">Create New TEE</font> button to create new TEE Question Paper';

	model_qp_url = base_url+'question_paper/manage_model_qp/generate_tee_qp/'+msg.pgm_id+'/'+msg.crclm_id+'/'+msg.term_id+'/'+msg.crs_id+'/4/'+existing_creation;

	tee_qp_url = base_url+'question_paper/manage_model_qp/generate_tee_qp/'+msg.pgm_id+'/'+msg.crclm_id+'/'+msg.term_id+'/'+msg.crs_id+'/'+msg.qpd_type+'/'+new_creation;

	$('#tee_qp_modal_text').html(tee_qp_text);
	$('#modal_footer').empty();
	var button = '<button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" abbr = "'+model_qp_url+'" ><i class="icon-ok icon-white"></i> Use Model QP </button>';
	button += '<button id="tee_qp_modal_cancel" type="button" class="cancel btn btn-primary" data-dismiss="modal"  abbr = "'+tee_qp_url+'"><i class="icon-ok icon-white"></i> Create New TEE</button>';
	button += '<button id="btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';

	$('#modal_footer').html(button);


	// $('#tee_qp_modal_ok').attr('abbr',model_qp_url);
	// $('#tee_qp_modal_ok').text('Use MOdel QP');

	// $('#tee_qp_modal_cancel').attr('abbr',tee_qp_url);
	// $('#tee_qp_modal_cancel').text('Create New TEE');
	$('#tee_qp_modal').modal('show');
	}
	}
	}); */

});

$('#tee_qp_modal').on('click', '#tee_qp_modal_ok', function () {
	var qp_url = $('#tee_qp_modal_ok').attr('abbr');
	window.location = qp_url;
});

$('#tee_qp_modal').on('click', '#tee_qp_modal_ok_no_fm', function () {
	var qp_url = $('#tee_qp_modal_ok').attr('abbr');
	$('#qp_without_framework_confirmation').modal('show');
	/* $('#tee_qp_modal_no_fm_data').val(qp_url);
	window.location = qp_url; */
});

$('#tee_qp_modal').on('click', '#tee_qp_modal_cancel', function () {
	var qp_url = $('#tee_qp_modal_cancel').attr('abbr');
	window.location = qp_url;
});

$('#tee_qp_list , #tee_qp_modal').on('click' , '.topic_not_defined', function(){
	$('.topic_error_msg').html( $(this).attr('data-error_mag') + '<br/><a href ="'+ base_url + $(this).attr('data-link') +'"> Click here to  '+ $(this).attr('data-type_name') +' .<a>');
		$('#topic_not_defined_modal').modal('show');
});  
//var i=0;
	
$('#tee_qp_list').on('click', '.fetch_tee_qp_data', function () { 

	var qp_url = $(this).attr('abbr');
	$('#abbr_address').val( $(this).attr('abbr'));
	var crs_id = $(this).attr('value');
	var topic_count = $(this).attr('data-topic_count'); 
	var topic_status = $(this).attr('data-topic_staus');	
	$('#crs_id').val(crs_id);
	$.ajax({
		type : "POST",
		url : qp_url,
		dataType : 'JSON',
		success : function (msg) {                        
			$('#modal_title').empty();
			$('#modal_title').html('' + entity_see + ' Question Paper(s)/Rubrics List')
			$('#tee_qp_modal_text').html(msg.qp_table);
                        $('#rubrics_table_display_div').empty();
                        $('#rubrics_table_display_div').html(msg.rubrics_table);
			$('#modal_footer').empty();
            
			var button = '<a id="analysis_details" href="#" value="' + crs_id + '" class="btn btn-success analyze_qp"><i class="icon-book icon-white"></i> Analyze </a><button id="btn_close" type="button" class="btn btn-danger"data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
			
			$('#modal_footer').html(button);			
			$("#tee_qp_modal").animate({
				"width" : "800px",
				"margin-left" : "-400px",
				"margin-right" : "-300px"
			}, 600, 'linear');			
                        if(msg.tee_qp == 0 ){
                            $('#analysis_details').hide();
                        }else{
                            $('#analysis_details').show();
                        }
			$('#tee_qp_modal').modal('show');

		}

	});

});


function regenerate_tee_data(crs_id){

	var qp_url = $('#abbr_address').val();
	var topic_count = $(this).attr('data-topic_count'); 
	var topic_status = $(this).attr('data-topic_staus');	
	$('#crs_id').val(crs_id);
	$.ajax({
		type : "POST",
		url : qp_url,
		dataType : 'JSON',
		success : function (msg) {                        
			$('#modal_title').empty();
			$('#modal_title').html('' + entity_see + ' Question Paper(s)/Rubrics List')
			$('#tee_qp_modal_text').html(msg.qp_table);
                        $('#rubrics_table_display_div').empty();
                        $('#rubrics_table_display_div').html(msg.rubrics_table);
			$('#modal_footer').empty();
            
			var button = '<a id="analysis_details" href="#" value="' + crs_id + '" class="btn btn-success analyze_qp"><i class="icon-book icon-white"></i> Analyze </a><button id="btn_close" type="button" class="btn btn-danger"data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
			
			$('#modal_footer').html(button);			
			$("#tee_qp_modal").animate({
				"width" : "800px",
				"margin-left" : "-400px",
				"margin-right" : "-300px"
			}, 600, 'linear');			
                        if(msg.tee_qp == 0 ){
                            $('#analysis_details').hide();
                        }else{
                            $('#analysis_details').show();
                        }
			$('#tee_qp_modal').modal('show');

		}

	});
}

// function to check tee qp rollout status

$('#tee_qp_modal').on('click', '.edit_tee_qp', function () {
	var qp_status_abbr = $(this).attr('qp_status_abbr');
	var edit_tee_qp_url = $(this).attr('abbr');
	$.ajax({
		type : "POST",
		url : qp_status_abbr,
		dataType : 'JSON',
		success : function (msg) {
			console.log(msg);
			if (msg == 1) {

				$('#tee_qp_edit_yes').attr('abbr', edit_tee_qp_url);
				$('#qp_rollout_forcible_edit').modal('show');

			} else if (msg == 2) {

				$('#qp_cannot_edit').modal('show');
			} else {
				
				window.location = edit_tee_qp_url;
				//$(this).attr('href',edit_tee_qp_url);

			}
		}

	});

});

$('#qp_rollout_forcible_edit').on('click', '#tee_qp_edit_yes', function () {
	window.location = $(this).attr('abbr');
});

$('#tee_qp_modal').on('click', '.delete_individual_qp', function () {

	table_row = $(this).closest("tr").get(0);
	var delete_qp_url = $(this).attr('abbr');
	$('#tee_qp_delete_modal').modal('show');
	$('#tee_qp_delete_ok').attr('abbr', delete_qp_url);
});

$('#tee_qp_delete_modal').on('click', '#tee_qp_delete_ok', function () {
	var delete_qp_url = $(this).attr('abbr');
	table_row.remove();
	$.ajax({
		type : "POST",
		url : delete_qp_url,
		async : false,
		dataType : 'JSON',
		success : function (msg) {}
	});
});

$('#tee_qp_modal').on('click', '.delete_individual_qp_message', function () {
	$('#cannot_delete_modal').modal('show');
});
$('#tee_qp_modal').on('click', '.roll_out_update_not', function () {
	$('#roll_out_update_not').modal('show');
});


$('#tee_qp_modal').on('click', '.roll_out_update', function () {
	var rollout_abbr = $(this).attr('abbr');
	var fetch_data = $(this).attr('data-fetch_data');
	var crs_id = $(this).attr('value');
	var this_id = $(this).attr('id');
	var all_data = $(this).attr('data-all_data'); 
	 var comment_array = all_data.split('/'); 
	 var pgm_id = comment_array[0];
	 var crclm_id = comment_array[1];
	 var term_id = comment_array[2];
	 var crs_id = comment_array[3];
	 var qpd_type = comment_array[4];
	 var qpd_id = comment_array[5]; 
	 post_data =  {'pgm_id':pgm_id,'crclm_id':crclm_id,'term_id':term_id,'crs_id':crs_id,'qpd_type':qpd_type , 'qpd_id':qpd_id}
			$.ajax({type:"POST",
					url:base_url+'question_paper/manage_model_qp/check_data_imported',
					data:post_data,					
					success:function(data){					
							if(data == 1){
									$('#qp_rollout_modal_cant').modal('show');}else{
									$('#roll_out_yes').attr('abbr', rollout_abbr);
									$('#roll_out_yes').attr('user_defined_id', this_id);
									$('#roll_out_yes').attr('fetch_data', fetch_data);
									$('#roll_out_yes').attr('crs_id', crs_id);
									$('.rollout_msg').html(data);
									$('#qp_rollout_modal').modal('show');
							}
					}
			});
});
$('#tee_qp_modal').on('click', '.roll_out_reset', function () {
	var rollout_abbr = $(this).attr('abbr');
	var fetch_data = $(this).attr('data-fetch_data');
	var crs_id = $(this).attr('value');
	var this_id = $(this).attr('id');
	var all_data = $(this).attr('abbr'); 
	 var comment_array = all_data.split('/'); 
	 var pgm_id = comment_array[0];
	 var crclm_id = comment_array[1];
	 var term_id = comment_array[2];
	 var crs_id = comment_array[3];
	 var qpd_type = comment_array[4];
	 var qpd_id = comment_array[5]; 
	post_data =  {'pgm_id':pgm_id,'crclm_id':crclm_id,'term_id':term_id,'crs_id':crs_id,'qpd_type':qpd_type , 'qpd_id':qpd_id}  
			$.ajax({type:"POST",
					url:base_url+'question_paper/manage_model_qp/check_data_imported',
					data:post_data,					
					success:function(data){					
							if(data == 1){
									$('#qp_rollout_modal_cant').modal('show');}else{
									$('#roll_out_yes').attr('abbr', rollout_abbr);
									$('#roll_out_yes').attr('user_defined_id', this_id);
									$('#roll_out_yes').attr('fetch_data', fetch_data);
									$('#roll_out_yes').attr('crs_id', crs_id);
									$('#qp_rollout_modal').modal('show');
							}
					}
			});
});



$('#qp_rollout_modal').on('click', '#roll_out_yes', function () {

	var rolled_out_btn_id = $('#rollout_btn_id').val();
	var rollout_url = $(this).attr('abbr'); 
	var rollout_btn_id = $(this).attr('user_defined_id');
	$('#rollout_btn_id').val(rollout_btn_id);
	$('#' + rollout_btn_id).removeClass('btn-warning').addClass('btn-success');
	$('#' + rollout_btn_id).attr('title', 'Rolled-out');
	$('#' + rollout_btn_id).text('Rolled-out');
	$('#' + rolled_out_btn_id).removeClass('btn-success').addClass('btn-warning');
	$('#' + rolled_out_btn_id).attr('title', 'Roll-out Pending');
	$('#' + rolled_out_btn_id).text('Pending');
	$.ajax({
		type : "POST",
		async : false,
		url : rollout_url,
		dataType : 'JSON',
		success : function (msg) {}
	});
	var qp_url = $(this).attr('fetch_data');
	var crs_id = $(this).attr('crs_id');
	$.ajax({
		type : "POST",
		url : qp_url,
		dataType : 'JSON',
		success : function (msg) {
			$('#modal_title').empty();
			$('#modal_title').html('' + entity_see + ' Question Paper(s) List')
			$('#tee_qp_modal_text').html(msg.qp_table);
                        $('#rubrics_table_display_div').empty();
                        $('#rubrics_table_display_div').html(msg.rubrics_table);
			$('#modal_footer').empty();
			var button = '<a id="analysis_details" href="#" value="' + crs_id + '" class="btn btn-success analyze_qp"><i class="icon-book icon-white"></i> Analyze </a><button id="btn_close" type="button" class="btn btn-danger"data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>';
			$('#modal_footer').html(button);
			//$('#tee_qp_modal').addClass('modal_custom');
			$("#tee_qp_modal").animate({
				"width" : "800px",
				"margin-left" : "-400px",
				"margin-right" : "-300px"
			}, 600, 'linear');
			//$('#tee_qp_modal').removeClass('modal').addClass('modal_custom');
			$('#tee_qp_modal').modal('show');

		}

	});
});

$('#tee_qp_modal').on('click', '.view_qp', function () {
	document.getElementById('qp_content_display').innerHTML = "";
	$('#loading').prependTo($('#qp_content_display'));
	$('#loading').show();
	var abbr_val = $(this).attr('abbr');
	var data = abbr_val.split("/");
	var pgmtype = data[0];
	var crclm_id = data[1];
	var term_id = data[2];
	var crs_id = data[3];
	var qp_type = data[4];
	var qpd_id = data[5];
	var post_data = {
		'pgmtype' : pgmtype,
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
		'qp_type' : qp_type,
		'qpd_id' : qpd_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/generate_model_qp_modal_tee',
		data : post_data,
		cache : false,
		success : function (msg) {
			document.getElementById('qp_content_display').innerHTML = msg;
			$('#loading').hide();
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
				//"aaSorting" : [[1, 'asc']],

			}).rowGrouping({
				iGroupingColumnIndex : 0,
				bHideGroupingColumn : true
			});
			//BloomsPlannedCoverageDistribution

			$('#chart1').empty();
			$('#chart2').empty();
			$('#chart3').empty();
			$('#chart4').empty();
		/* 	$('#bloomslevelplannedcoveragedistribution > tbody:first').empty();
			$('#bloomslevelplannedcoveragedistribution_note').empty(); */
			$('#bloomslevelplannedmarksdistribution > tbody:first').empty();
			$('#bloomslevelplannedmarksdistribution_note').empty();
			$('#coplannedcoveragesdistribution > tbody:first').empty();
			$('#coplannedcoveragesdistribution_note').empty();
			$('#topicplannedcoveragesdistribution > tbody:first').empty();
			$('#topicplannedcoveragesdistribution_note').empty();
			var plot1,
			plot2,
			plot3;
			var BloomsLevel = $('#BloomsLevel').val();
			var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
			var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
			var BloomsLevelDescription = $('#BloomsLevelDescription').val();
			var pln = PlannedPercentageDistribution.split(",");
			var actual = ActualPercentageDistribution.split(",");
			var ticks = BloomsLevel.split(",");
			var level = BloomsLevelDescription.split(",");
			var i = 0;
			var s1_arr = new Array();
			$.each(pln, function () {
				s1_arr.push(Number(pln[i++]));
			});
			var j = 0;
			var s2_arr = new Array();
			$.each(actual, function () {
				s2_arr.push(Number(actual[j++]));
			});
/* 
			plot1 = jQuery.jqplot('chart1', [s1_arr, s2_arr], {
					seriesDefaults : {
						renderer : $.jqplot.BarRenderer,
						pointLabels : {
							show : true
						}
					},
					series : [{
							label : 'Framework Level Distribution'
						}, {
							label : 'Planned Distribution'
						}
					],
					axes : {
						xaxis : {
							renderer : $.jqplot.CategoryAxisRenderer,
							tickRenderer : $.jqplot.CanvasAxisTickRenderer,
							ticks : ticks
						},
						yaxis : {
							min : 0,
							max : 100,
							tickOptions : {
								formatString : '%d%%'
							}
						}
					},
					highlighter : {
						show : true,
						tooltipLocation : 'e',
						tooltipAxes : 'x',
						fadeTooltip : true,
						showMarker : false,
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return level[pointIndex];
						}
					},
					legend : {
						show : true,
						location : 'ne',
						placement : 'inside'
					}
				});

			var k = 0;
			$('#bloomslevelplannedcoveragedistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			$.each(ticks, function () {
				$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>' + ticks[k] + '</center></td><td><center>' + pln[k] + ' %</center></td><td><center>' + actual[k] + ' %</center></td></tr>');
				k++;
			});
			$('#bloomslevelplannedcoveragedistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual Blooms Level planned coverage percentage distribution and Blooms Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Blooms Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>'); */

			//BloomsLevelMarksDistribution
			var blooms_level_marks_dist = $('#blooms_level_marks_dist').val();
			var total_marks_marks_dist = $('#total_marks_marks_dist').val();
			var percentage_distribution_marks_dist = $('#percentage_distribution_marks_dist').val();
			var bloom_level_marks_desc = $('#bloom_level_marks_description').val();
			blooms_level = blooms_level_marks_dist.split(",");
			total_marks_dist = total_marks_marks_dist.split(",");
			percentage_dist = percentage_distribution_marks_dist.split(",");
			bloom_lvl_marks_desc = bloom_level_marks_desc.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(blooms_level, function () {
				var bloom_lvl = blooms_level[i];
				var percent_distr = percentage_dist[i];
				data = new Array();
				data.push(bloom_lvl, Number(percent_distr));
				i++;
				actual_data[j++] = data;
			});
			plot2 = jQuery.jqplot('chart2', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return bloom_lvl_marks_desc[pointIndex];
						}
					}
				});
			$('#bloomslevelplannedmarksdistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Marks Distribution</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
			var l = 0;
			$.each(blooms_level, function () {
				$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>' + blooms_level[l] + '</center></td><td><center>' + total_marks_dist[l] + '</center></td><td><center>' + percentage_dist[l] + ' %</center></td></tr>');
				l++;
			});
			$('#bloomslevelplannedmarksdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</td></tr><tr><td> <b> X = Individual Bloom\'s Level marks  <br/> Y = Sum of all Bloom\'s Level marks <br/> Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//COPlannedCoverageDistribution
			var clo_code = $('#clo_code').val();
			var clo_total_marks_dist = $('#clo_total_marks_dist').val();
			var clo_percentage_dist = $('#clo_percentage_dist').val();
			var clo_statement_dist = $('#clo_statement_dist').val();
			clo_code = clo_code.split(",");
			clo_total_marks_dist = clo_total_marks_dist.split(",");
			clo_percentage_dist = clo_percentage_dist.split(",");
			clo_statement_dist = clo_statement_dist.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(clo_code, function () {
				var clo_code_data = clo_code[i];
				var clo_percentage_dist_data = clo_percentage_dist[i];
				data = new Array();
				data.push(clo_code_data, Number(clo_percentage_dist_data));
				i++;
				actual_data[j++] = data;
			});
			plot3 = jQuery.jqplot('chart3', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return clo_statement_dist[pointIndex];
						}
					}
				});
			$('#coplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>' + entity_clo + ' Level</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			var m = 0;
			$.each(clo_code, function () {
				$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + clo_code[m] + '</center></td><td><center>' + clo_total_marks_dist[m] + '</center></td><td><center>' + clo_percentage_dist[m] + ' %</center></td></tr>');
				m++;
			});
			$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> 	X = Individual '+ entity_clo_full_singular +' marks <br/> Y = Sum of all '+ entity_clo_full +' marks <br/> Planned Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//topicCoverageDistribution
			var topic_title = $('#topic_title').val();
			var topic_marks_dist = $('#topic_marks_dist').val();
			var topic_percentage_dist = $('#topic_percentage_dist').val();
			topic_title = topic_title.split(",");
			topic_marks_dist = topic_marks_dist.split(",");
			topic_percentage_dist = topic_percentage_dist.split(",");
			actual_topic_data = new Array();
			var i = 0;
			var j = 0;
			$.each(topic_title, function () {
				var topic_title_data = topic_title[i];
				var topic_percentage_dist_data = topic_percentage_dist[i];
				topic_data = new Array();
				topic_data.push(topic_title_data, Number(topic_percentage_dist_data));
				i++;
				actual_topic_data[j++] = topic_data;
			});
			topic_chart_plot = jQuery.jqplot('chart4', [actual_topic_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return topic_title[pointIndex];
						}
					}
				});
			$('#topicplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>' + entity_topic + '</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			var m = 0;
			$.each(topic_title, function () {
				console.log(topic_title[m]);
				$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + topic_title[m] + '</center></td><td><center>' + topic_marks_dist[m] + '</center></td><td><center>' + topic_percentage_dist[m] + ' %</center></td></tr>');
				m++;
			});
			$('#topicplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the ' + entity_topic + ' Coverage wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual ' + entity_topic + ' marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');
			$('.myModalQPdisplay').on('shown', function () {
				plot1.replot();
				plot2.replot();
				plot3.replot();
				topic_chart_plot.replot();
			});
		}
	});
	$('.myModalQPdisplay').modal('show');
});

//To Compare or analyze the Model QP and TEE QP
$('#tee_qp_modal').on('click', '.analyze_qp', function () {
	var data = $(this).attr('value');
	$('#analysis_data').val(data);
	//$('#my-tab-content').html('');
	$('.co_planned_coverage_dist_cls').click();
});

$('.blm_lvl_planned_coverage_dist_cls').on('click', function () {
	//$('#my-tab-content').html('');
	var crs_id = $('#analysis_data').val();
	var post_data = {
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/getQPBloomLevelCoverageGraphData',
		data : post_data,
		dataType : 'json',
		success : function (blm_lvl_cov) {
			actual_percent_data_array = new Array();
			planned_percent_data_array = new Array();
			model_actual_percent_data_array = new Array();
			tee_actual_percent_data_array = new Array();
			blm_desc_array = new Array();
			blm_lvl_array = new Array();
			series_name = new Array();
			series_name.push({
				label : "Framework Level Distribution"
			});
			var i = 0;
			$.each(blm_lvl_cov['tee_qp'][0], function () {
				planned_percent_data = new Array();
				planned_percent_data.push(blm_lvl_cov['tee_qp'][0][i]['BloomsLevel'], parseFloat(blm_lvl_cov['tee_qp'][0][i]['PlannedPercentageDistribution']).toFixed(2));
				blm_lvl_array[i] = blm_lvl_cov['tee_qp'][0][i]['BloomsLevel'];
				blm_desc_array[i] = blm_lvl_cov['tee_qp'][0][i]['description'];
				planned_percent_data_array[i] = planned_percent_data;
				i++;
			});
			var j = 0;
			series_name.push({
				label : "Model QP"
			});
			if (blm_lvl_cov['model_qp'].length > 0) {
				$.each(blm_lvl_cov['model_qp'], function () {
					model_planned_percent_data = new Array();
					model_planned_percent_data.push(blm_lvl_cov['model_qp'][j]['BloomsLevel'], parseFloat(blm_lvl_cov['model_qp'][j]['ActualPercentageDistribution']).toFixed(2));
					model_actual_percent_data_array[j] = model_planned_percent_data;
					j++;
				});
			} else {
				k = 0;
				$.each(blm_lvl_cov['tee_qp'][0], function () {
					model_planned_percent_data = new Array();
					model_planned_percent_data.push(0);
					model_actual_percent_data_array[k] = planned_percent_data;
					k++;
				});
			}
			var chartData = new Array();
			chartData.push(planned_percent_data_array, model_actual_percent_data_array);
			var th_data = '';
			for (m = 0; m < blm_lvl_cov['tee_qp'].length; m++) {
				var tee_qp_name = "TEE_QP_" + (m + 1);
				th_data = th_data + '<th>' + tee_qp_name + ' ActualPercentageDistribution</th>';
				series_name.push({
					label : tee_qp_name
				});
				tee_actual_percent_data = new Array();
				for (n = 0; n < blm_lvl_cov['tee_qp'][m].length; n++) {
					tee_actual_percent_data.push([blm_lvl_cov['tee_qp'][m][n]['BloomsLevel'], parseFloat(blm_lvl_cov['tee_qp'][m][n]['ActualPercentageDistribution'])]);
				}
				chartData.push(tee_actual_percent_data);
			}
			$('#my-tab-content').html('<div class="tab-pane active" id="bloom_planned_coverage_dist"><div id="modelQPBloomCovDist_div"><div class="row-fluid"><div class="span12"><div class="span9" id="bloom_cov_dist_graph" style="height:300px;" class="jqplot-target"><div id="chartTooltip" style="font-size: 12px;color: rgb(15%, 15%, 15%);background-color: rgba(95%, 95%, 95%, 0.8);border: 1px solid #CCCCCC;padding: 2px;position: absolute; z-index: 99; display:none;"></div></div></div></div></div></div>');
			$('#modelQPBloomCovDist_div').append('<div class="row-fluid"><div class="span12"><b>Blooms Level Planned Vs. Coverage Distribution:</b><table id="bloom_level_planned_cov_table_analysis" border=1 class="table table-bordered"><thead></thead><tbody></tbody></table></div>');
			var plot2 = $.jqplot('bloom_cov_dist_graph', chartData, {
					title : '',
					seriesDefaults : {
						pointLabels : {
							//show: true
						}
					},
					axes : {
						xaxis : {
							pad : 1,
							renderer : $.jqplot.CategoryAxisRenderer
						},
						yaxis : {
							min : 0,
							max : 100
						}
					},
					series : series_name,
					legend : {
						show : true,
						location : 'ne',
						placement : 'outside'
					},
					title : 'Blooms Planned Vs. Coverage Distribution'
				});
			i = 0;
			$('#bloom_level_planned_cov_table_analysis > thead:first').append('<th>Blooms Level</th><th>Framework Level Distribution</th><th>Model QP ActualPercentageDistribution</th>' + th_data);
			$.each(blm_lvl_cov['tee_qp'][0], function () {
				var row_data = '<tr><td>' + blm_lvl_cov['tee_qp'][0][i]['BloomsLevel'] + '(' + blm_lvl_cov['tee_qp'][0][i]['description'] + ')</td><td><center>' + blm_lvl_cov['tee_qp'][0][i]['PlannedPercentageDistribution'] + '%</center></td>';
				if (blm_lvl_cov['model_qp'].length > 0) {
					row_data = row_data + '<td><center>' + blm_lvl_cov['model_qp'][i]['ActualPercentageDistribution']+'%';
				} else {
					row_data = row_data + '<td><center>0';
				}
				for (m = 0; m < blm_lvl_cov['tee_qp'].length; m++) {
					var tee_row_data = '';
					tee_row_data = '<td><center>' + blm_lvl_cov['tee_qp'][m][i]['ActualPercentageDistribution'] + '%</center></td>';
					row_data = row_data + tee_row_data;
				}
				$('#bloom_level_planned_cov_table_analysis > tbody:last').append(row_data + '</tr>');
				i++;
			});
			$('#modelQPBloomCovDist_div').append('<br><div class="row-fluid"><div class="span12"><b>Blooms Level Marks Distribution:</b><br><table id="bloom_level_marks_distribution" border=1 class="table table-bordered"><thead></thead><tbody></tbody></table></div>');
			var i = 0;
			actual_data = new Array();
			blm_desc_array = new Array();
			blm_lvl_array = new Array();
			$.each(blm_lvl_cov['blm_mks_dist_tee_qp'][0], function () {
				data = new Array();
				if (blm_lvl_cov['blm_mks_dist_model_qp'].length > 0) {
					data.push(parseFloat(blm_lvl_cov['blm_mks_dist_model_qp'][i]['PercentageDistribution']));
				} else {
					data.push(0);
				}
				for (var k = 0; k < blm_lvl_cov['blm_mks_dist_tee_qp'].length; k++) {
					data.push(parseFloat(blm_lvl_cov['blm_mks_dist_tee_qp'][k][i]['PercentageDistribution']));
					blm_desc_array[i] = blm_lvl_cov['blm_mks_dist_tee_qp'][k][i]['description'];
					blm_lvl_array[i] = blm_lvl_cov['blm_mks_dist_tee_qp'][k][i]['BloomsLevel'];
				}
				actual_data[i] = data;
				i++;
			});
			ticks_data = new Array(); // TO create Ticks along x axis
			ticks_data.push('Model_QP');
			var num_ticks = 1;
			$.each(blm_lvl_cov['blm_mks_dist_tee_qp'], function () {
				ticks_data.push('' + entity_see + '_QP_' + num_ticks);
				num_ticks++;
			});
			var ticks_td_val = '';
			var ticks_pm_pd_val = '';
			var n = 0;
			$.each(ticks_data, function () {
				var ticks_td = '<td colspan=2 style="white-space: nowrap;"><center><b>' + ticks_data[n++] + '</b></center></td>';
				ticks_pm_pd = '<td style="white-space: nowrap;"><center><b>Marks Distribution</b></center></td><td style="white-space: nowrap;"><center><b>% Distribution</b></center></td>';
				ticks_td_val = ticks_td_val + ticks_td;
				ticks_pm_pd_val = ticks_pm_pd_val + ticks_pm_pd;
			});
			$('#bloom_level_marks_distribution > thead:first').append('<tr><td style="white-space: nowrap;"><center><b>Blooms Level</b></center></td>' + ticks_td_val + '</tr>');
			$('#bloom_level_marks_distribution > thead:first').append('<tr><td></td>' + ticks_pm_pd_val + '</tr>');
			var pm_dm_rows_val = new Array();
			var p = 0;
			$.each(blm_desc_array, function () {
				if (blm_lvl_cov['blm_mks_dist_model_qp'].length > 0) {
					td_val = '<td><center>' + blm_lvl_cov['blm_mks_dist_model_qp'][p]['TotalMarks'] + '</center></td><td><center>' + blm_lvl_cov['blm_mks_dist_model_qp'][p]['PercentageDistribution'] + '%</center></td>';
				} else {
					td_val = '<td><center>0</center></td><td><center>0.00%</center></td>';
				}
				for (var k = 0; k < blm_lvl_cov['blm_mks_dist_tee_qp'].length; k++) {
					td_val = td_val + '<td><center>' + blm_lvl_cov['blm_mks_dist_tee_qp'][k][p]['TotalMarks'] + '</td><td><center>' + blm_lvl_cov['blm_mks_dist_tee_qp'][k][p]['PercentageDistribution'] + '%</center></td>';
				}
				pm_dm_rows_val[p] = td_val;
				p++;
			});
			for (var m = 0; m < blm_lvl_array.length; m++) {
				$('#bloom_level_marks_distribution > tbody:last').append('<tr><td class="rightJustified">' + blm_lvl_array[m] + ' (' + blm_desc_array[m] + ')</td>' + pm_dm_rows_val[m] + '</tr>');
			}
		}
	});
});

$('.co_planned_coverage_dist_cls').on('click', function () {
	var crs_id = $('#analysis_data').val();
	var post_data = {
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/getQPCOCoverageGraphData',
		data : post_data,
		dataType : 'json',
		async : false,
		cache : false,
		success : function (co_cov) {
			if (co_cov) {
				var i = 0;
				actual_data = new Array();
				co_array = new Array();
				co_code_array = new Array();
				$.each(co_cov['tee_qp'][0], function () {
					data = new Array();
					if (co_cov['model_qp'].length > 0)
						data.push(parseFloat(co_cov['model_qp'][i]['PercentageDistribution']));
					else
						data.push(0);
					for (var k = 0; k < co_cov['tee_qp'].length; k++) {
						data.push(parseFloat(co_cov['tee_qp'][k][i]['PercentageDistribution']));
						co_array[i] = co_cov['tee_qp'][k][i]['clo_statement'];
						co_code_array[i] = co_cov['tee_qp'][k][i]['clocode'];
					}
					actual_data[i] = data;
					i++;
				});
				$('#my-tab-content').html('<div class="tab-pane active" id="co_planned_coverage_dist"><div id="modelQPCOCoverage_div"><div class="row-fluid"><div class="span12"><div class="span9" id="co_graph" style="height:300px;" class="jqplot-target"></div><div id="chartTooltip" style="font-size: 12px;color: rgb(15%, 15%, 15%);background-color: rgba(95%, 95%, 95%, 0.8);border: 1px solid #CCCCCC;padding: 2px;position: absolute; z-index: 99; display:none;"></div></div></div></div><div class="row-fluid"><div class="span12"><table id="coplannedcoveragesdistribution" border=1 class="table table-bordered"><thead></thead><tbody></tbody></table></div></div>');

				ticks_data = new Array(); // TO create Ticks along x axis
				ticks_data.push('Model_QP');
				var num_ticks = 1;
				$.each(co_cov['tee_qp'], function () {
					ticks_data.push('' + entity_see + '_QP_' + num_ticks);
					num_ticks++;
				});
				var ticks_td_val = '';
				var ticks_pm_pd_val = '';
				var n = 0;
				$.each(ticks_data, function () {
					var ticks_td = '<td colspan=2 style="white-space: nowrap;"><center><b>' + ticks_data[n++] + '</b></center></td>';
					ticks_pm_pd = '<td style="white-space: nowrap;"><center><b>Planned Marks</b></center></td><td style="white-space: nowrap;"><center><b>Planned Distribution</b></center></td>';
					ticks_td_val = ticks_td_val + ticks_td;
					ticks_pm_pd_val = ticks_pm_pd_val + ticks_pm_pd;
				});
				$('#coplannedcoveragesdistribution > thead:last').html('<tr><td style="white-space: nowrap;"><center><b>CO</b></center></td>' + ticks_td_val + '</tr>');
				$('#coplannedcoveragesdistribution > thead:last').append('<tr><td></td>' + ticks_pm_pd_val + '</tr>');
				var pm_dm_rows_val = new Array();
				var p = 0;
				$.each(co_array, function () {
					if (co_cov['model_qp'].length > 0)
						td_val = '<td><center>' + co_cov['model_qp'][p]['TotalMarks'] + '</center></td><td><center>' + co_cov['model_qp'][p]['PercentageDistribution'] + '%</center></td>';
					else
						td_val = '<td><center>0</center></td><td><center>0%</center></td>';
					for (var k = 0; k < co_cov['tee_qp'].length; k++) {
						td_val = td_val + '<td><center>' + co_cov['tee_qp'][k][p]['TotalMarks'] + '</td><td><center>' + co_cov['tee_qp'][k][p]['PercentageDistribution'] + '%</center></td>';
					}
					pm_dm_rows_val[p] = td_val;
					p++;
				});
				for (var m = 0; m < co_array.length; m++) {
					$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td class="rightJustified">' + co_code_array[m] + ' (' + co_array[m] + ')</td>' + pm_dm_rows_val[m] + '</tr>');
				}
				var series = [];
				var series_stmt = [];
				for (var m = 0; m < co_array.length; m++) {
					var label = co_code_array[m];
					var stmt = co_array[m];
					series.push({
						label : label
					});
					series_stmt.push({
						label : stmt
					});
				}
				var ticks = ticks_data;
				$('#myModalQPCompare').modal('show');
				$('#myModalQPCompare').on('shown', function (ev) {
					$('#co_graph').empty();
					var plot = $.jqplot('co_graph', actual_data, {
							stackSeries : true,
							seriesDefaults : {
								renderer : $.jqplot.BarRenderer,
								rendererOptions : {
									barWidth : 40,
									barMargin : 20,
								},
								pointLabels : {
									show : true,
									stackedValue : false,
									location : 's'
								}

							},
							axes : {
								xaxis : {
									renderer : $.jqplot.CategoryAxisRenderer,
									ticks : ticks
								},
								yaxis : {
									padMin : 0,
									min : 0,
									max : 110,
									tickInterval : 10,
									tickOptions : {
										formatString : '%.2f%'
									}
								}
							},
							legend : {
								show : true,
								location : 'ne',
								placement : 'outside'
							},
							series : series,
							title : "Course Outcome Coverage"
						});
					$('#co_graph').bind('jqplotDataHighlight',
						function (ev, seriesIndex, pointIndex, data) {
						var spanStart = '<span class="highlight_msg">';
						var chart_left = $('#co_graph').offset().left;
						var chart_top = $('#co_graph').offset().top;
						var x = ev.pageX;
						var y = ev.pageY;
						var left = x;
						var top = y;
						var chartTooltipHTML = spanStart;
						if (plot.axes.xaxis.u2p && plot.axes.yaxis.u2p) {
							left = chart_left + plot.axes.xaxis.u2p(data[0]);
							top = chart_top + plot.axes.yaxis.u2p(data[1]);
						}
						if (plot.series[0].barDirection === "vertical")
							left -= plot.series[0].barWidth / 2;
						else if (plot.series[0].barDirection === "horizontal")
							top -= plot.series[0].barWidth / 2;
						top = chart_top;
						var sum = 0;
						for (var i = 0; i < seriesIndex + 1; i++)
							sum += plot.series[i].data[pointIndex][1];
						top += plot.axes.yaxis.u2p(sum);
						var seriesName = plot.series[seriesIndex].label;
						var series_statement = series_stmt[seriesIndex].label;
						var series_val = data[1].toFixed(2);
						chartTooltipHTML += '' + series_statement + '(' + series_val + '%)</span>';
						var ct = $('#chartTooltip');
						ct.css({
							left : left,
							top : top
						}).html(chartTooltipHTML).show();
						var new_left = ev.pageX - $(this).offset().left;
						var new_top = ev.pageY - ($(this).offset().top / 2);
						if (plot.series[0].barDirection === "vertical") {
							ct.css({
								top : new_top,
								left : new_left
							});
						}
					});
					$('#co_graph').bind('jqplotDataUnhighlight', function (ev, seriesIndex, pointIndex, data) {
						$('#chartTooltip').empty().hide();
					});
				});
			}
		}
	});
});

$('.topic_planned_coverage_dist_cls').on('click', function () {
	$('#my-tab-content').html('');
	var crs_id = $('#analysis_data').val();
	var post_data = {
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/manage_model_qp/getQPTopicCoverageGraphData',
		data : post_data,
		dataType : 'json',
		success : function (topic_cov) {
			if (topic_cov) {
				var i = 0;
				actual_data = new Array();
				topic_array = new Array();
				$.each(topic_cov['tee_qp'][0], function () {
					data = new Array();
					if (topic_cov['model_qp'].length > 0) {
						data.push(parseFloat(topic_cov['model_qp'][i]['PercentageDistribution']));
					} else {
						data.push(0);
					}
					for (var k = 0; k < topic_cov['tee_qp'].length; k++) {
						data.push(parseFloat(topic_cov['tee_qp'][k][i]['PercentageDistribution']));
						topic_array[i] = topic_cov['tee_qp'][k][i]['topic_title'];
					}
					actual_data[i] = data;
					i++;
				});
				$('#my-tab-content').html('<div class="tab-pane active" id="topic_planned_coverage_dist"><div id="modelQPTopicCoverage_div"><div class="row-fluid"><div class="span12"><div class="span9" id="graph" style="height:300px;" class="jqplot-target"><div id="chartTooltip" style="font-size: 12px;color: rgb(15%, 15%, 15%);background-color: rgba(95%, 95%, 95%, 0.8);border: 1px solid #CCCCCC;white-space: nowrap;padding: 2px;position: absolute; z-index: 99; display:none;"></div></div></div></div></div><div class="row-fluid"><div class="span12"><table id="topicplannedcoveragesdistribution" border=1 class="table table-bordered"><thead></thead><tbody></tbody></table></div></div>');

				ticks_data = new Array(); // TO create Ticks along x axis
				ticks_data.push('Model_QP');
				var num_ticks = 1;
				$.each(topic_cov['tee_qp'], function () {
					ticks_data.push('TEE_QP_' + num_ticks);
					num_ticks++;
				});
				var ticks_td_val = '';
				var ticks_pm_pd_val = '';
				var n = 0;
				$.each(ticks_data, function () {
					var ticks_td = '<td colspan=2 style="white-space: nowrap;"><center><b>' + ticks_data[n++] + '</b></center></td>';
					ticks_pm_pd = '<td style="white-space: nowrap;"><center><b>Planned Marks</b></center></td><td style="white-space: nowrap;"><center><b>Planned Distribution</b></center></td>';
					ticks_td_val = ticks_td_val + ticks_td;
					ticks_pm_pd_val = ticks_pm_pd_val + ticks_pm_pd;
				});
				$('#topicplannedcoveragesdistribution > thead:last').html('<tr><td style="white-space: nowrap;"><center><b>' + entity_topic + '</b></center></td>' + ticks_td_val + '</tr>');
				$('#topicplannedcoveragesdistribution > thead:last').append('<tr><td></td>' + ticks_pm_pd_val + '</tr>');
				var pm_dm_rows_val = new Array();
				var p = 0;
				$.each(topic_array, function () {
					if (topic_cov['model_qp'].length > 0)
						td_val = '<td><center>' + topic_cov['model_qp'][p]['TotalMarks'] + '</center></td><td><center>' + topic_cov['model_qp'][p]['PercentageDistribution'] + '%</center></td>';
					else
						td_val = '<td><center>0</center></td><td><center>0%</center></td>';
					for (var k = 0; k < topic_cov['tee_qp'].length; k++) {
						td_val = td_val + '<td><center>' + topic_cov['tee_qp'][k][p]['TotalMarks'] + '</td><td><center>' + topic_cov['tee_qp'][k][p]['PercentageDistribution'] + '%</center></td>';
					}
					pm_dm_rows_val[p] = td_val;
					p++;
				});
				for (var m = 0; m < topic_array.length; m++) {
					$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td class="rightJustified">' + topic_array[m] + '</td>' + pm_dm_rows_val[m] + '</tr>');
				}
				var series = [];
				for (var m = 0; m < topic_array.length; m++) {
					var label = topic_array[m];
					series.push({
						label : label
					});
				}
				var ticks = ticks_data;
				var plot = $.jqplot('graph', actual_data, {
						stackSeries : true,
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 40,
								barMargin : 20,
							},
							pointLabels : {
								show : true,
								stackedValue : false,
								location : 's'
							}
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : ticks
							},
							yaxis : {
								padMin : 0,
								min : 0,
								max : 110,
								tickInterval : 10,
								tickOptions : {
									formatString : '%.2f%'
								}
							}
						},
						legend : {
							show : true,
							location : 'ne',
							placement : 'outside'
						},
						series : series,
						title : entity_topic + " Coverage"
					});

				$('#graph').bind('jqplotDataHighlight',
					function (ev, seriesIndex, pointIndex, data) {

					var spanStart = '<span class="highlight_msg" style="font-size:12px;font-weight:bold;color:rgb(50%,50%,100%);min-width:50px;">';
					var chart_left = $('#graph').offset().left;
					var chart_top = $('#graph').offset().top;
					var x = ev.pageX;
					var y = ev.pageY;
					var left = x;
					var top = y;
					var chartTooltipHTML = spanStart;
					if (plot.axes.xaxis.u2p && plot.axes.yaxis.u2p) {
						left = chart_left + plot.axes.xaxis.u2p(data[0]);
						top = chart_top + plot.axes.yaxis.u2p(data[1]);
					}
					if (plot.series[0].barDirection === "vertical")
						left -= plot.series[0].barWidth / 2;
					else if (plot.series[0].barDirection === "horizontal")
						top -= plot.series[0].barWidth / 2;
					top = chart_top;
					var sum = 0;
					for (var i = 0; i < seriesIndex + 1; i++)
						sum += plot.series[i].data[pointIndex][1];
					top += plot.axes.yaxis.u2p(sum);
					var seriesName = plot.series[seriesIndex].label;
					var series_val = data[1].toFixed(2);
					chartTooltipHTML += '</span>' + seriesName + '(' + series_val + '%)';
					var new_width = $('.highlight_msg').width();
					var ct = $('#chartTooltip');
					ct.css({
						left : left,
						top : top
					}).html(chartTooltipHTML).show();
					var new_left = ev.pageX - $(this).offset().left;
					var new_top = ev.pageY - $(this).offset().top;
					if (plot.series[0].barDirection === "vertical") {
						ct.css({
							top : new_top,
							left : new_left
						});
					}
				});
				$('#graph').bind('jqplotDataUnhighlight', function (ev, seriesIndex, pointIndex, data) {
					$('#chartTooltip').empty().hide();
				});
			}
		}
	});
});

$('#export').on('click', function () {
	var imgData_1 = $('#chart1').jqplotToImageStr({});
	var imgElem_1 = $('<img/>').attr('src', imgData_1);
	$('#chart1_img').html('<b>Blooms Planned Coverage Distribution</b><br><br>');
	$('#chart1_img').append(imgElem_1);
	$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_div').clone().html());
	$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_note').clone().html());

	var imgData_2 = $('#chart2').jqplotToImageStr({});
	var imgElem_2 = $('<img/>').attr('src', imgData_2);
	$('#chart2_img').html('<b>Blooms Planned Marks Distribution</b><br><br>');
	$('#chart2_img').append(imgElem_2);
	$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_div').clone().html());
	$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_note').clone().html());

	var imgData_3 = $('#chart3').jqplotToImageStr({});
	var imgElem_3 = $('<img/>').attr('src', imgData_3);
	$('#chart3_img').html('<b>Course Outcome Planned Coverage Distribution</b><br><br>');
	$('#chart3_img').append(imgElem_3);
	$('#chart3_img').append($('#coplannedcoveragesdistribution_div').clone().html());
	$('#chart3_img').append($('#coplannedcoveragesdistribution_note').clone().html());

	//Topic Coverage Distribution
	var imgData_4 = $('#chart4').jqplotToImageStr({});
	var imgElem_4 = $('<img/>').attr('src', imgData_4);
	$('#chart4_img').html('<b>' + entity_topic + ' Coverage Distribution</b><br><br><br>');
	$('#chart4_img').append(imgElem_4);
	$('#chart4_img').append($('#topicplannedcoveragesdistribution_div').clone().html());
	$('#chart4_img').append($('#topicplannedcoveragesdistribution_note').clone().html());

	var question_paper = $('#qp_content_pdf').clone().html();
	var total_val = $('#total').clone().html();
	var chart1_img = $('#chart1_img').clone().html();
	var chart2_img = $('#chart2_img').clone().html();
	var chart3_img = $('#chart3_img').clone().html();
	var chart4_img = $('#chart4_img').clone().html();
	$('#qp_hidden').val(question_paper);
	$('#total_val_hidden').val(total_val);
	$('#chart1_img_hidden').val(chart1_img);
	$('#chart2_img_hidden').val(chart2_img);
	$('#chart3_img_hidden').val(chart3_img);
	$('#chart4_img_hidden').val(chart4_img);
	$('#export_model_qp_pdf').submit();
});


/*
 * Edit Rubrics Criteria functions starts from here.
 */
$(document).on('click','.edit_tee_rubrics_data',function(){
   var page_link = $(this).attr('tee_rubrics_abbr');
   window.location = page_link;
});

/*
 * Function to Delete the Rubrics 
 */
$(document).on('click','.delete_individual_tee_rubrics_data',function(){
    table_row = $(this).closest("tr").get(0);
    var qpd_id = $(this).attr('data-qpd_id');
    var ao_method_id = $(this).attr('data-ao_method_id');
    
    $('#tee_rubrics_delete_ok').attr('data-qpd_id', qpd_id);
    $('#tee_rubrics_delete_ok').attr('data-ao_method_id', ao_method_id);
    $('#tee_rubrics_modal').modal('show');
});

$('#tee_rubrics_modal').on('click', '#tee_rubrics_delete_ok', function () {
	var qpd_id = $(this).attr('data-qpd_id');
	var ao_method_id = $(this).attr('data-ao_method_id');
        var post_data = {
            'qpd_id':qpd_id,
            'ao_method_id':ao_method_id,
        };
	table_row.remove();
	$.ajax({
		type : "POST",
		url : base_url + 'question_paper/tee_rubrics/delete_tee_rubrics_data',
		data : post_data,
		dataType : 'html',
		success : function (msg) {
                    
                }
	});
});

$(document).on('click','.cant_edit_tee_rubrics_data',function(){
   $('#cant_edit_rubrics_modal').modal('show'); 
});

$(document).on('click','.cant_delete_individual_tee_rubrics_data',function(){
   $('#cant_delete_rubrics_modal').modal('show'); 
});

$(document).on('click','.show_rubrics_pending_modal',function(){
   $('#').modal('show'); 
});

$(document).on('click','.view_tee_rubrics_data',function(){
    var ao_method_id = $(this).attr('data-ao_method_id');
    var qpd_id = $(this).attr('data-qpd_id');
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var post_data = {
                        'ao_method_id':ao_method_id,
                        'qpd_id':qpd_id,
                        'crclm_id':crclm_id,
                        'term_id':term_id,
                        'crs_id':crs_id,
                    };
    $.ajax({type: "POST",
	url: base_url + 'question_paper/tee_rubrics/get_rubrics_table_modal_view',
	data: post_data,
	dataType: 'html',
	success: function (data) {
            if($.trim(data)!='false'){
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html(data);
        }else{
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html('<center><font color="red"><b>No Data to Display</b></font></center>');
        }
            $('#rubrics_table_view_modal').modal('show');
            
        }
    });
});
$(document).on('click','#export_to_pdf',function(){
   var clone_data = $('#rubrics_table_div').clone().html();
  $('#report_in_pdf').val(clone_data);
  $('#rubrics_report').submit();
});

$(document).on('click','.import_qp_template',function(){
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var link = $(this).attr('abbr_href');
    var post_data = {
      'crclm_id':crclm_id,  
      'term_id':term_id,  
      'crs_id':crs_id,  
    };
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_model_qp/check_any_qp_rolled_out',
	data: post_data,
	dataType: 'json',
	success: function (data) {
            if(data.qp_rollout_val == 1){
                $('#qp_crs_id').val(crs_id);
                $('#qp_crclm_id').val(crclm_id);
                $('#qp_upload_continue').attr('data-link',link);
                $('#forcefull_qp_roll_back').modal('show');
            }else if(data.qp_rollout_val == 2){
                $('#qp_marks_uploaded_msg').modal('show');
            }else{
                window.location = link;
            }
            
        }
    });
    
});

$(document).on('click','#qp_upload_continue',function(){
    var crs_id = $('#qp_crs_id').val();
    var crclm_id =  $('#qp_crclm_id').val();
    var link = $(this).attr('data-link');
    var post_data = {
      'crclm_id':crclm_id,
      'crs_id':crs_id,  
    };
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_model_qp/roll_back_qp',
	data: post_data,
	dataType: 'html',
	success: function (data) {
            if($.trim(data) == 'true'){
                window.location = link;
                $('#forcefull_qp_roll_back').modal('hide');
            }else{
               $('#forcefull_qp_roll_back').modal('hide');
            }
            
        }
    });
});
