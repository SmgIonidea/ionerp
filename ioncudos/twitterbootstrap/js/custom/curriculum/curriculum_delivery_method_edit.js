
	//Delivery Method List Page....

	var base_url = $('#get_base_url').val();
	
	$(document).ready(function() {
		//delivery method edit page
		$('.bloom_level_edit').multiselect({
			maxHeight: 200,
			buttonWidth: 170,
			numberDisplayed: 5,
			nSelectedText: 'selected',
			nonSelectedText: "Select Bloom's Level",
			onChange: function(option, checked) {
				var selectedOptions = $('#bloom_level option:selected'); 
				
				var selections = [];
				var action_verb_data = [];
				$("#bloom_level option:selected").each(function(){
					var bloom_level_id = $(this).val();
					var bloom_level = $(this).text();
					var action_verbs = $(this).attr('title');				
					selections.push(bloom_level_id);
					action_verb_data.push(bloom_level+' - '+action_verbs+'<br>');
				});
				
				var action_verb = action_verb_data.join("<b></b>");
				//display newly selected bloom's level details
				$('#bloom_level_edit_actionverbs').html(action_verb.toString());
				
				//hide old bloom's level details
				$('.selected_bloom').hide();
			}
		});
		
		$('#delivery_method').multiselect({
			nonSelectedText: 'Select Delivery Method'
		});
	});
	
	//Function to call the uniqueness checks of po type name to controller
	$('.delivery_method_edit_form_submit').on('click', function(e) {
		e.preventDefault();
		var flag;
		
		flag = $('#delivery_method_edit_form').valid();
		
		var delivery_mtd_name = $('#delivery_mtd_name').val();
		var delivery_mtd_id = $('#delivery_mtd_id').val();
		var crclm_id = $('#crclm_id').val();
		
		var base_url = $('#get_base_url').val();
		var post_data = {
			'crclm_id': crclm_id,
			'delivery_mtd_name': delivery_mtd_name,
			'delivery_mtd_id': delivery_mtd_id
		}
		if (flag) {
			$.ajax({type: "POST",
				url: base_url+'curriculum/curriculum_delivery_method/crclm_edit_search_dm_name',
				data: post_data,
				datatype: "JSON",
				success: function(msg) {
					if (msg == 1) {
						$("#delivery_method_edit_form").submit();
					} else {
						$('#myModal_edit_view').modal('show');
					}
				}
			});
		}
	});	
	
	// Edit view JS functions
	$.validator.addMethod("loginRegex", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
	}, "Field must contain only letters, numbers, spaces or dashes.");
	
	// Form validation rules are defined & checked before form is submitted to controller.		
	$("#delivery_method_edit_form").validate({
		rules: {
			delivery_mtd_name: {
				loginRegex: true,
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).parent().parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().parent().removeClass('error');
			$(element).parent().parent().addClass('success');
		}
	});

