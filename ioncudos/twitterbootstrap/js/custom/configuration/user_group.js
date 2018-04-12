
	//User Group List Page
	
	var base_url = $('#get_base_url').val();

	$(document).ready(function() {
        var user_group_id;
		
        $('.get_id').live('click', function(e) {
            user_group_id = $(this).attr("id");
        });

		//script facilitate delete operation
        $('.usergroup_delete').live('click', function(e) {
            e.preventDefault();

            var post_data = {
                'user_group_id': user_group_id
            }

            $.ajax({
				type: "POST",
				url: base_url + 'configuration/user_groups/user_groups_delete',
                data: post_data,
                datatype: "JSON",
                success: function(msg) {
                    location.reload();
                }
            });
        });

		//fetches the permission for the user group
        $('.security').live('click', function(e) {
            e.preventDefault();

            var post_data = {
                'user_group_id': user_group_id
            }

            $.ajax({type: "POST",
				url: base_url + 'configuration/user_groups/get_permission',
                data: post_data,
                datatype: "JSON",
                success: function(msg) {
                    document.getElementById('permission').innerHTML = msg;
                }
            });
        });
		
		$.validator.addMethod("loginRegex", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\_\-\s\']+$/i.test(value);
        }, "Field must contain only letters, spaces ,underscore ' or dashes.");

        $("#roleform").validate({
            rules: {
                name: {
                    required: true,
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
    });
	
	//User Group List Page Ends Here