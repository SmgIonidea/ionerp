//SLO Edit script starts from here.
var base_url = $('#get_base_url').val();
var cloneCntr;
var tlo_counter = new Array();
	//po_counter.push(1);
$(document).ready(function() {    
      $('.clone .clone_counter').each(function() {
		cloneCntr = $(this).val(); 
		tlo_counter.push(cloneCntr);
	  });
	  $('#counter').val(tlo_counter);
	  
		  function fixIds(elem, cntr) {
                $(elem).find("[id]").add(elem).each(function() {					
                    this.id = this.id.replace(/\d+$/, cntr);
                    this.name = this.id;
                });
				$(elem).find("[name=tlo_id]").remove();
            }
            //var cloneCntr = "<?php echo $cloneCntr; ?>";
            $("#add_field").on('click',function() {
				++cloneCntr;
				$('#clone_counter').val(cloneCntr);
				tlo_counter.push(String(cloneCntr));
				
                var table = $("#add_tlo").clone(true, true)
                fixIds(table,cloneCntr);
                table.insertBefore("#add_before");
				
                $('textarea#tlo_statement' + cloneCntr).val('');
                $('textarea#delivery_approach'+ cloneCntr).val('');
                $("#bloom_level" + cloneCntr + " option:selected").prop("selected", false);
                $("#delivery_methods" + cloneCntr + " option:selected").prop("selected", false);
				$('#action_verb_display_'+cloneCntr).html('Note : Select Bloom\'s Level to View its respective Action Verbs');

                $('#add_tlo' + cloneCntr + ' div div textarea').attr('name', 'tlo_statement'+ cloneCntr);
                $('#tlo_statement_div' + cloneCntr + ' div input').attr('name', 'tlo_statement'+ cloneCntr);
                $('#bloom_level' + cloneCntr).attr('name', 'bloom_level'+ cloneCntr);
                $('#clone_counter').val(cloneCntr);
                $('#counter').val(tlo_counter);
            });

            $('.Delete').click(function() {
                rowId = $(this).attr("id").match(/\d+/g);
                if (rowId != 1)
                {
                    $(this).parent().parent().parent().parent().parent().parent().remove();
					var replaced_id = $(this).attr('id').replace('remove_field_','');
					var tlo_counter_index = $.inArray(replaced_id,tlo_counter);
					tlo_counter.splice(tlo_counter_index,1);
					$('#counter').val(tlo_counter);
                    return false;
                }
            });
            function valid()
            {
                for (var i = 1; i < cloneCntr; i++)
                {

                    var dataval = document.getElementById('tlo_statement' + i);
                    var dataval1 = document.getElementById('bloom_level' + i);
                    if (dataval)
                    {
                        if (dataval1)
                        {
                            if (dataval.value == "")
                            {
                                if (dataval1.value == "")
                                {
                                    dataval.focus();
                                    dataval1.focus();
                                    dataval.style.borderColor = "#FF0000";
                                    dataval1.style.borderColor = "#FF0000";
                                    return false;
                                }
                                dataval.style.borderColor = "#1eb486";
                                dataval1.style.borderColor = "#1eb486";
                            }
                        }
                    }
                }
                return true;
            }
	});

	$(document).ready(function() {
$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
		}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

$("#update_tlo").validate({
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

$('.update').on('click', function(event) {
		$('#update_tlo').validate();
			
            // adding rules for inputs with class 'comment'
        $('.edit_tlo').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true
                    });
            });
		$('.tlo_bloom_level').each(function() {
                $(this).rules("add", 
                    {
						loginRegex: true
                    });
            });
	});
});

            $(document).ready(function() {
                $('#tlo').click(function() {
                    
                $('#update_tlo').submit();
                  
                });
            });
			
$(document).on('change', '.fetch_action_verbs', function(){

		 var bloom_id = $(this).val();
		 var id = $(this).attr("id").match(/\d+/g);
		 
		var post_data = {'bloom_id' : bloom_id};
		if(bloom_id){
		 $.ajax({type: "POST",
				url: base_url+'curriculum/tlo/fect_action_verbs', 
				data: post_data,
				dataType: 'json',
				success: function(msg) {
				console.log(msg);
							$('#action_verb_display_'+id).html('<b>Bloom\'s Level Action Verbs : </b>'+msg[0].bloom_actionverbs);
				}
			});  
			}else{
					$('#action_verb_display_'+id).html('Note : Select Bloom\'s Level to view its respective Action Verbs');
			}
		 
});