// GA to PO mapping List View JS functions.
	
	/*You may use scrollspy along with creating and removing elements form DOM. 
	* But if you do so, you have to call the refresh method . 
	* The following code shows how you may do that.
	*/
	var base_url = $('#get_base_url').val();
	
	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});

    $('.show_help').on('click',function(){
	
        $.ajax({
			url: base_url+'curriculum/map_po_ga/ga_po_help',
			datatype: "JSON",
			success: function(msg) {
				document.getElementById('po_ga_help_content_id').innerHTML = msg;
			}
        });
    });
    //set cookie
    if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#curriculum_list option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		select_curriculum();
    }
    function select_curriculum()  {
	$.cookie('remember_curriculum', $('#curriculum_list option:selected').val(), { expires: 90, path: '/'});
        document.getElementById('error').innerHTML = '';
       
        var curriculum_id = document.getElementById('curriculum_list').value;
	var val=$('#curriculum_list').find(':selected').attr('data-id');
	$('#loading').show();
        if (curriculum_id != 'Select Curriculum')   {
            var post_data = {
		'crclm_id': curriculum_id,
		'pgm_id':val
	   }
				
            $.ajax({type: "POST",
		url: base_url+'curriculum/map_po_ga/map_table',
                data: post_data,
                success: function(msg)
                {
                    $('#loading').hide();
                    document.getElementById('mapping_table').innerHTML = msg;

                }
            });
	} else {
            $('#loading').hide();
            document.getElementById('mapping_table').innerHTML = '';

        }
    }
	

	
	//to display po statement to the user
    function writetext2(ga)  {
        document.getElementById('ga_display_textbox_id').innerHTML = ga;
    }
	


    var globalid;
	
	//
    $('.check').live("click", function() {
        var id = $(this).attr('value');
        globalid = $(this).attr('id');
        window.id = id;
        var curriculum_id = document.getElementById('curriculum_list').value;
        window.curriculum_id = curriculum_id;
	$('#loading').show();
        var post_data = {
				'po': id,
				'crclm_id': curriculum_id,
			}
        if ($(this).is(":checked")) {
            $.ajax({
				type: "POST",
				url: base_url+'curriculum/map_po_ga/add_mapping',
                data: post_data,
                success: function(msg) {
			$('#loading').hide();

			    var curriculum_id = document.getElementById('curriculum_list').value;
				var val=$('#curriculum_list').find(':selected').attr('data-id');

				var post_data = {'crclm_id': curriculum_id,'pgm_id':val}
				
				$.ajax({type: "POST",
						url: base_url+'curriculum/map_po_ga/map_table',
						data: post_data,
						success: function(msg){
							$('#loading').hide();
							document.getElementById('mapping_table').innerHTML = msg;
						}
				});
                }
            });
        } else {
	    $('#loading').hide();
            $('#uncheck_mapping_dialog_id').modal('show');
        }
    });

	//
    function cancel_uncheck_mapping_dialog() {
        $('#' + globalid).prop('checked', true);
    }
//$('.comment').popover();
//$('.comment').popover({ trigger: "hover" });
	//from modal2
    function unmapping() {
        var curriculum_id = document.getElementById('curriculum_list').value;
        var post_data = {
				'po': id,
				'crclm_id': curriculum_id,
			}
        $.ajax({
			type: "POST",
			url: base_url+'curriculum/map_po_ga/unmap',
            data: post_data,
            success: function(msg) {
                $('#uncheck_mapping_dialog_id').modal('hide');
				
				var curriculum_id = document.getElementById('curriculum_list').value;
				var val=$('#curriculum_list').find(':selected').attr('data-id');

				var post_data = {'crclm_id': curriculum_id,'pgm_id':val}
				
				$.ajax({type: "POST",
						url: base_url+'curriculum/map_po_ga/map_table',
						data: post_data,
						success: function(msg){
							$('#loading').hide();
							document.getElementById('mapping_table').innerHTML = msg;
						}
				});
            }
        });
    }
	
	//
    function send_mapping_approval_dialog() {
	$('#loading').show();
        $('#sent_for_approval_dialog_id').modal('show');
    }

	//function is to insert curriculum id into the hidden input field
    function submit_mapping_form() {
        document.getElementById('crclm_id').value = document.getElementById('curriculum_list').value;
        $('#frm').submit();
    }


	function textout2() {
        document.getElementById('po_display_textbox_id').innerHTML = '';
    }
$(document).ready(function () {
    $('.comment').live('click focus', function () {	
	//$('a[rel=popover]').not(this).popover('destroy');
		$('a[rel=popover]').popover({
			html: 'true',
			placement: 'top'
		})
	 });
	$('.close_btn').live('click', function () {
	    $('a[rel=popover]').not(this).popover('destroy');
	});

   

    $('.cmt_submit').live('click', function () {
	$('a[rel=popover]').not(this).popover('hide');
	var po_id = $('#po_id').val();
	var clo_id = $('#clo_id').val();
	var crclm_id = $('#crclmid').val();

	var post_data = {
	    'po_id': po_id,
	    'clo_id': clo_id,
	    'crclm_id': crclm_id,
	    'status': 0,

	}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clopomap_review/clo_po_cmt_update',
	    data: post_data,
	    success: function (msg) {

	    }
	});
    });
 $('.comment_just').live('click', function(e) {
	 e.preventDefault();
	 var comment_map_val = $(this).attr('abbr');
	 var comment_array = comment_map_val.split('|'); 
	 var po_id = comment_array[0];
	 var clo_id = comment_array[1];
	 var crclm_id = comment_array[2];
	 var clo_po_id = comment_array[3];
	 
	  var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
			'clo_po_id':clo_po_id,
            }	
			$.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/co_po_mapping_justification',
            data: post_data,
			dataType: 'JSON',
            success: function(msg) {
//			console.log(msg[0].cmt_statement);
			if(msg.length > 0){
			$('#justification').text(msg[0].justification);
			}else{
			$('#justification').text('');
			}
            }
        });
			
		//$(this).attr('data-content').popover('show');
        $('a[rel=popover]').not(this).popover('destroy');
		$('a[rel=popover]').popover({
			html: 'true',
			trigger: 'manual',
            placement: 'left'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function() {
            $('a[rel=popover]').not(this).popover('destroy');
        });
    });
	
		$('.save_justification').live('click', function () {
		var data = $('.map_select').val(); 
		var crclm_id = document.getElementById('curriculum_list').value;
		var ga_po_id = $('#ga_po_id').val();
			
		var justification =$('#justification').val(); 
		var post_data ={'ga_po_id':ga_po_id , 'crclm_id':crclm_id , 'justification':justification}
		    $.ajax({type: "POST",
            url: base_url + 'curriculum/map_po_ga/save_justification',
            data: post_data,
            success: function (msg) {
			
			var curriculum_id = document.getElementById('curriculum_list').value;
				var val=$('#curriculum_list').find(':selected').attr('data-id');

				var post_data = {'crclm_id': curriculum_id,'pgm_id':val}
				
				$.ajax({type: "POST",
						url: base_url+'curriculum/map_po_ga/map_table',
						data: post_data,
						success: function(msg){
							$('#loading').hide();
							document.getElementById('mapping_table').innerHTML = msg;
						}
				});
              $('a[rel=popover]').not(this).popover('destroy');			 
			}

			});
		}); 		
	 $('[data-toggle="popover"]').popover();  
	
	});
	
	
//Approver's PO to PEO mapping List View JS functions.	
	
	//to display po statement to the user
	function write_po_statement(po)	{
		document.getElementById('po_display_textbox_id').innerHTML = po;
		//approver_fetch_po_peo_mapping_comment_notes();
	}
	function erase_po_statement()	{
		document.getElementById('po_display_textbox_id').innerHTML = '';
	}


	$("textarea#approver_po_peo_comment_box_id").bind("keyup", function() {
		po_peo_mapping_comment_notes_insert(this.value) 
	});


//Static PO to PEO mapping List View JS functions.		
		//select the curriculum from the drop down
	
	function static_select_curriculum()
	{
		document.getElementById('error').innerHTML = '';
		document.getElementById('mapping_table').innerHTML = '';
		static_fetch_po_peo_mapping_comment_notes();
		$('#loading').show();
		var curriculum_id = document.getElementById('curriculum_list').value;
		if (curriculum_id != 'Select Curriculum')
		{
			var post_data = {
				'crclm_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/map_po_ga/static_map_table',
				data: post_data,
				success: function(msg)
				{	
					$('#loading').hide();
					document.getElementById('mapping_table').innerHTML = msg;
				}
			});
		} else {
			$('#loading').hide();
			document.getElementById('error').innerHTML = 'Select Curriculum';
		}

	}

/**********artifacts***********/

	//displaying the modal
	$('#artifacts_modal').click(function(e) {
		e.preventDefault();
		display_artifact();
	});
	
	//displaying the modal content
	function display_artifact() {
		var artifact_value = $('#art_val').val();
		var crclm_id = $('#curriculum_list').val();
		$('#loading').show();
		if(crclm_id != 'Select Curriculum'){
			var post_data = {
				'art_val': artifact_value,
				'crclm': crclm_id
			}

			$.ajax({
			      type: "POST",
			      url: base_url+'upload_artifacts/artifacts/modal_display',
			      data: post_data,
			      async: false,
			      success: function(data){
					$('#art').html(data); 
					$('#loading').hide();
					$('#mymodal').modal('show');
			      }
			});
		}
		else {
			$('#loading').hide();
			$('#select_crclm').modal('show');
		}
         }

	//uploading the file 
	$('.art_facts,#curriculum_list').on('click change',function(e) {
		var uploader = document.getElementById('uploaded_file');
		var crclm_id = $('#curriculum_list').val();
		var art = $('#art_val').val();
		var val = $(this).attr('uploaded-file'); 
		var folder_name = $('#curriculum_list option:selected').val(); 
		var post_data = {
	 		'crclm' : crclm_id,
			'art_val' : art,
			'crclm': folder_name
		}	
		upclick({
			element: uploader,
			action_params : post_data,
			action: base_url+'upload_artifacts/artifacts/modal_upload',	
			onstart:function(filename) {
					(document).getElementById('loading_edit').style.visibility='visible';
				},
			oncomplete:function(response_data) { 
					if(response_data=="file_name_size_exceeded") { 
						$('#file_name_size_exc').modal('show');
					} else if(response_data=="file_size_exceed") {
						$('#larger').modal('show');
					} 
				 	display_artifact();
					(document).getElementById('loading_edit').style.visibility='hidden';
				}	
		});
	});	

	//deleting the file
	$('#art').on('click','.artifact_entity',function(e) {
		var del_id = $(this).attr('data-id');
		
		$('#delete_file').modal('show');
			$('#delete_selected').click(function(e){
				$('#loading').show();
				$.ajax({
					type: "POST",
					url: base_url + 'upload_artifacts/artifacts/modal_delete_file',
					data: {'artifact_id' : del_id },
					success: function(data) {
							$('#loading').hide();
							display_artifact();
					}
				});
			$('#delete_file').modal('hide');
		});	
					
        });

	$('body').on('focus', '.std_date', function () {
	    $("#af_actual_date").datepicker({
		format: "yyyy-mm-dd",
		//endDate:'-1d'
	    }).on('changeDate', function (ev) {
		$(this).blur();
		$(this).datepicker('hide');
	    });
	    $(this).datepicker({
		format: "yyyy-mm-dd",
		//endDate:'-1d'
	    }).on('changeDate', function (ev) {
		$(this).blur();
		$(this).datepicker('hide');
	    });
	});

	$('#art').on('click', '.std_date_1', function () {
	    $(this).siblings('input').focus();
	});
	//on save artifact description and date
	$('#save_artifact').live('click', function(e) {
		e.preventDefault();
		$('#myform').submit();
	});
	
	$('#myform').on('submit', function(e) {
		e.preventDefault();
		var form_data = new FormData(this);
		var form_val = new Array();
		
		$('.save_form_data').each(function() {
			//values fetched will be inserted into the array
			form_val.push($(this).val());
		});
		
		//check whether file any file exists or not
		 if(form_val.length > 0) {
			//if file exists
			$.ajax({
				  type: "POST",
				  url: base_url+'upload_artifacts/artifacts/save_artifact',
				   data : form_data,
				   contentType : false,
				   cache : false,
				   processData : false,
				   success: function(msg) {
						if($.trim(msg) == 1) {
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
	});
	
	//message to display animated notification instead of modal
	$('.noty').click(function () {
        var options = $.parseJSON($(this).attr('data-noty-options'));
        noty(options);
    });
