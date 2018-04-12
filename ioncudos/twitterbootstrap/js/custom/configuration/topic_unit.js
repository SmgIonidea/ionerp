// Unit list page script starts from here.
var base_url = $('#get_base_url').val();
$(document).ready(function() {
	var currentID;

        function currentIDSt(id)
        {
            currentID = id;
        }

            $("#hint a").tooltip();
            var table_row;
            var data_val;

            $('.get_id').live('click', function(e)
            {
                data_val = $(this).attr('id');
                table_row = $(this).closest("tr").get(0);
            });

            /* Function is to delete unit by sending the unit id to controller.
             * @param - unit id.
             * @returns- updated list view.
             */
			$('.topic_unit_delete').click(function(e){
			var topic_unit_delete_path = 'topic_unit/delete_topic_unit_msg';
			var t_unit_id = $(this).attr('id');
			
			e.preventDefault();
                var post_data = {
                    't_unit_id': t_unit_id,
                }
				
                $.ajax({type: "POST",
                    url: topic_unit_delete_path,
                    data: post_data,
                    datatype: "JSON",
                    success: function(msg)
                    {
					if(msg == 1) {
						$('#myModaldelete').modal('show');
						}
						else {
						$('#myModaldelete1').modal('show');
						}
						
                    }
                });
			
			
			});
			 
            $('.delete_topic_unit').click(function(e) {
			
			var topic_unit_del_path = 'topic_unit/topic_unit_delete';
				
                e.preventDefault();
                var post_data = {
                    't_unit_id': data_val,
                }
                $.ajax({type: "POST",
                    url: topic_unit_del_path,
                    data: post_data,
                    datatype: "JSON",
                    success: function(msg)
                    {
					if(msg != 2) {
                        var oTable = $('#example').dataTable();
                        oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
						$('#myModaldeleteSuccess').modal('show');
						}
						else {
						$('#myModaldelete1').modal('show');
						}
						
                    }
                });
            });
        });

 // unit list page script ends here.
 
 // Unit Add page script starts from here.
 
 $(document).ready(function() {
	 $.validator.addMethod("loginRegex", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s\'\0-9]+$/i.test(value);
                }, "Field must contain only letters, spaces or '.");

                // Form validation rules are defined & checked before form is submitted to controller.
                $("#form1").validate({
                    rules: {
                        t_unit_name: {
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
        
            //Function to call the uniqueness checks of program mode name to controller
            $('.submit1').on('click', function(e) {

                e.preventDefault();
                var flag;
                flag = $('#form1').valid();
                var data_val = document.getElementById("t_unit_name").value;

                var post_data = {
                    't_unit_name': data_val
                }
                if (flag)
                {
                    $.ajax({type: "POST",
                        url:base_url+'configuration/topic_unit/topic_unit_uniqueness',
                        data: post_data,
                        datatype: "JSON",
                        success: function(msg) { 
                            if (msg == 1) {
                                $("#form1").submit();
                            } else {
                                $('#myModal1').modal('show');
							}
                        }
                    });
                }
            });
			
			//Function to call the uniqueness checks of program mode name to controller
            $('.submit_edit').on('click', function(e) {

                e.preventDefault();
                var flag;
                flag = $('#form_edit').valid();
                var data_val = document.getElementById("t_unit_name").value;
				var data_val1 = document.getElementById("t_unit_id").value;

                var post_data = {
                    't_unit_name': data_val,
					't_unit_id': data_val1
                }
                if (flag)
                {
                    $.ajax({type: "POST",
                        url:base_url+'configuration/topic_unit/edit_topic_unit_uniqueness',
                        data: post_data,
                        datatype: "JSON",
                        success: function(msg) { 
                            if (msg == 1) {
                                $("#form_edit").submit();
                            } else {
                                $('#uniqueness_dialog_edit').modal('show');
							}
                        }
                    });
                }
            });
 
 // Unit Add page script ends here.
 
 //Unit edit page script starts from here.
 
  $(document).ready(function() {
	  $.validator.addMethod("loginRegex", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s\'\0-9]+$/i.test(value);
        }, "Field must contain only letters, spaces or '.");

        //Form validation rules are defined & to check the form before it is submitted to controller.
        $("#form1").validate({
            rules: {
                unit_name: {
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
 
 // Unit edit page script ends here.
        