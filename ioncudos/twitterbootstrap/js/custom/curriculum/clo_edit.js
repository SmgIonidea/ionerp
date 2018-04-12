
	// Course Learning Objectives (CLOs).....
	var clo_id;
	var table_row;
	var cloneCntr = 2;
	var base_url = $('#get_base_url').val();	

	$('#clo_edit_form').ready(function(){
		$('#bloom_level_edit').multiselect({
			//includeSelectAllOption: true,
			maxHeight: 200,
			buttonWidth: 160,
			numberDisplayed: 5,
			nSelectedText: 'selected',
			nonSelectedText: 'Select Blooms Level',
			onChange: function(option, checked) {
				var selectedOptions = $('#bloom_level_edit option:selected'); 
				if (selectedOptions.length >= 4) {
					// Disable all other checkboxes.
					var nonSelectedOptions = $('#bloom_level_edit option').filter(function() {
						return !$(this).is(':selected');
					});

					var dropdown = $('#bloom_level_edit').siblings('.multiselect-container');
					nonSelectedOptions.each(function() {
						var input = $('input[value="' + $(this).val() + '"]');
						input.prop('disabled', true);
						input.parent('li').addClass('disabled');
					});
				}
				else {
					// Enable all checkboxes.
					var dropdown = $('#bloom_level_edit').siblings('.multiselect-container');
					$('#bloom_level_edit option').each(function() {
						var input = $('input[value="' + $(this).val() + '"]');
						input.prop('disabled', false);
						input.parent('li').addClass('disabled');
					});
				}
				var selections = [];
				var action_verb_data = [];
				$("#bloom_level_edit option:selected").each(function(){
					var bloom_level_id = $(this).val();
					var bloom_level = $(this).text();
					var action_verbs = $(this).attr('data-title');										
					//var action_verbs = '';				
					selections.push(bloom_level_id);
					action_verb_data.push('<b>'+bloom_level+'</b>'+action_verbs+', ');
			
				});
				var action_verb = action_verb_data.join("<b></b>");
				$('#bloom_level_edit_actionverbs').html(action_verb.toString());				
				//hide old bloom's level details
				$('.selected_bloom').hide();
			}
		});
		$('#delivery_method').multiselect({
			//includeSelectAllOption: true,
			nonSelectedText: 'Select Delivery Method'
		});
	});
	
	//Course Learning Objective Static Page
	//Function to fetch the grid details
	function static_get_selected_value() {
		var curriculum_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}

		$.ajax({type: "POST",
			url: base_url+'curriculum/clo/show_clo',
			data: post_data,
			dataType: 'json',
			success: static_populate_table
		});
	}
	
	//Function to generate data table grid
	function static_populate_table(msg) {
		var m = 'd';

		$('#example').dataTable().fnDestroy();
		$('#example').dataTable(
				{"aoColumns": [
						{"sTitle": "COs", "mData": "clo_statement"}
					], "aaData": msg});
	}
	
	$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\'\`\(\/)']+$/i.test(value)
		}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
		
	$("#clo_edit_form").validate({
		rules: {
			'clo_statement': {
				//maxlength: 200,
				required: true,
				loginRegex:true
			},
		},
		messages: {
			clo_statement: {
				required: "This Field is required"
			},
		},
		errorClass: "help-inline font_color",
		errorElement: "span",
		// highlight: function(element, errorClass, validClass) {
			// $(element).parent().parent().parent().addClass('error');
		// },
		// unhighlight: function(element, errorClass, validClass) {
			// $(element).parent().parent().parent().removeClass('error');
			// $(element).parent().parent().parent().addClass('success');
		// }
		  errorPlacement: function(error, element) {
            if (element.parent().hasClass("input-append")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        onkeyup: false,
        onblur: false,
        success: function(label) {
            $(label).closest('.control-group').removeClass('error');
        }
	});
	

$('#cancel_edit').on('click',function(){
$('#myModal_edit_clo').modal('hide');
});

