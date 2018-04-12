// Add Edit Rubrics Js
// Author : Mritunjay B S
// Date : 20-10-2016
var base_url = $('#get_base_url').val();
$('#generate_scale').on('click',function(){
    var count_of_range = $('#count_of_range').val();
    var num_flag = $.isNumeric(count_of_range);
    //alert(valid_flag);
    if(count_of_range == '' || num_flag == false){
        var data_options = '{"text":"Please Enter Range to Generate Rubrics table. Field Must accept only number as input.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }else{
    var rubrics_type = '';
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val();
	$('.rubrics_type_val').each(function(){
		if($(this).is(':checked')){
			
                        rubrics_type = $(this).attr('rub_typ');
		}
	});
    var post_data = {'count_of_range':count_of_range,
                     'rubrics_type':rubrics_type,
                     'crclm_id':crclm_id,
                     'term_id':term_id,
                     'crs_id':crs_id,
                     'section_id':section_id,
                     'ao_id':ao_id,
                     'ao_type_id':ao_type_id,
                     'ao_method_id':ao_method_id,
                 };
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/generate_scale',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                $('#assessment_scale').empty();
                $('#assessment_scale').append(msg);
                $('.co_id_rubrics').multiselect({
                    includeSelectAllOption: true,
                    buttonWidth: '110px',
                    nonSelectedText: 'Select CO',
                    templates: {
                        button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
                    }

                });
                $('.co_id_rubrics').multiselect('rebuild');
                
                
            }
        });
        if(count_of_range != ''){
            $('#save_rubrics').prop('disabled',false);
        }
        $('#save_rubrics_data').validate();
        $('#save_rubrics_data').valid();
    }
});


//Function to change the criteria dropdown based on radio button selected
$('.rubrics_type_val').on('click',function(){
   var count_of_range = $('#count_of_range').val();
    var rubrics_type = '';
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val();
	$('.rubrics_type_val').each(function(){
		if($(this).is(':checked')){
			
                        rubrics_type = $(this).attr('rub_typ');
		}
	});
    var post_data = {'count_of_range':count_of_range,
                     'rubrics_type':rubrics_type,
                     'crclm_id':crclm_id,
                     'term_id':term_id,
                     'crs_id':crs_id,
                     'section_id':section_id,
                     'ao_id':ao_id,
                     'ao_type_id':ao_type_id,
                     'ao_method_id':ao_method_id,
                 }; 
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/get_rubrics_type_details',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                $('#criteria_id').empty();
                $('#criteria_id').html(msg);
            }
        });
    
});

$(document).on('change','.co_on_change',function(){
    
   var co_id = $('option:selected', this).attr('id');
    $(".co_id_rubrics option:selected").removeAttr("selected");
    $('.co_id_rubrics').multiselect('rebuild');
    $(".co_id_rubrics option[value='"+co_id+"']").attr("selected", "selected");
    $('.co_id_rubrics').multiselect('rebuild');
   
});

//function to allow numbers only
//function to allow decimal numbers only

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^(?=.*?[0-9])[0-9-]+$/i.test(value);
}, "This field must contain only Numbers and '-' as a Special Character ex: 1-2.");

// Form validation 
$('#save_rubrics_data').validate({
    ignore: ':hidden:not(".co_id_rubrics")', // Tells the validator to check the hidden select
    errorClass: 'invalid font_color'
    });
    
// Form validation 
$('#genrate_scale_form').validate({
    //ignore: ':hidden:not(".co_id_rubrics")', // Tells the validator to check the hidden select
    errorClass: 'invalid font_color'
    });

// Function to Insert Criteria
$('#button_div').on('click','#save_rubrics',function(){
    //var flag = $('#save_rubrics_data').valid();
    
    $('.criteria_check').each(function() {
        $(this).rules("add", 
            {
                    required : true,						
            });
        });
    
    $('.range_check').each(function() {
        $(this).rules("add", 
            {
                    onlyDigit : true,						
            });
        });
    var flag = $('#save_rubrics_data').valid();

    if(flag == true){
        $('#loading').show();
    var criteria;
    var co_list = $('#co_id_val').val();
    var ao_id = $('#ao_id').val();
    //var criteria_desc = $("input[name='criteria_desc[]']").map(function(){return $(this).val();}).get();
    var criteria_desc = $('.criteria_check').map(function(){ return $(this).val();}).get();
    var range_name = $('.range_name').map(function(){return $(this).val();}).get();
    var range = $('.range_check').map(function(){return $(this).val();}).get();
    var ao_method_id = $('#ao_method_id').val();
    var selected_radio_entity_id = $("input[type='radio'][name='rubrics_type']:checked").attr('entity_id');
    var selected_rubrics_type = $("input[type='radio'][name='rubrics_type']:checked").attr('rub_typ');
    if(selected_radio_entity_id != 0){
        criteria = $('#rubrics_criteria').val();
    }else{
        criteria = $('#criteria_1').val();
    }
    var post_data = {
      'criteria':criteria,  
      'co_list':co_list,  
      'criteria_desc':criteria_desc,  
      'range_name':range_name,  
      'range':range,  
      'ao_method_id':ao_method_id,  
      'selected_radio_entity_id':selected_radio_entity_id,  
      'selected_rubrics_type':selected_rubrics_type,  
    };
//    var flag_check= 0;
//			$("textarea[name='criteria_desc[]']").each(function(nr){
//			if($(this).val() === "") {flag_check= 1;}
//			});
//    if(flag_check == 0){
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/save_criteria_details',
            data: post_data,
            dataType:'JSON',
            success: function(msg) {
                console.log(msg.criteria_size);
                if(msg.criteria_size == 1){
                     $('#rubrics_table').empty();
                     $('#rubrics_table').html(msg.rubrics_list_table);
                     location.reload();
                }else{
                    var data_options = '{"text":"Criteria Inserted Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    $('#loading').hide();
                     $('#rubrics_table').empty();
                     $('#rubrics_table').html(msg.rubrics_list_table);
                     $("#rubrics_criteria option:first").attr("selected", true);
                     $('#criteria_1').val('');
                     $('.criteria_check').each(function(){
                         $(this).val('');
                     });
                     $(".co_id_rubrics option:selected").removeAttr("selected");
                     $('.co_id_rubrics').multiselect('rebuild');
                 }
                 
            }
        });
    }else{
        required_fields('msg');
    }
    console.log('Criteria=>'+criteria+'Co Data =>'+co_list+'Criteria Desc =>'+criteria_desc+'Rubrics Range =>'+range+'Method Id =>'+ao_method_id+'Entity_id =>'+selected_radio_entity_id+'Rubrics Type =>'+selected_rubrics_type);
});

$(document).ready(function(){
   
    $('.co_id_rubrics').multiselect({
                    includeSelectAllOption: true,
                    buttonWidth: '110px',
                    nonSelectedText: 'Select CO',
                    templates: {
                        button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
                    }

                });
                $('.co_id_rubrics').multiselect('rebuild');
    
});

// show warning modal popup
$('#regenerate_scale').on('click',function(){
    $('#regenerate_scale_modal').modal('show');
});

// Regenerate Rubrics Scale a Fresh
$('#regenerate_rubrics_scale').on('click',function(){
    $('#loading').show();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val();
    var post_data = {
                     'crclm_id':crclm_id,
                     'term_id':term_id,
                     'crs_id':crs_id,
                     'section_id':section_id,
                     'ao_id':ao_id,
                     'ao_type_id':ao_type_id,
                     'ao_method_id':ao_method_id,
                 }; 
     $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/regenerate_rubrics_scale',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                console.log(msg);
                if($.trim(msg) == 'true'){
                    $('#loading').hide();
                    location.reload();
                }else{
                    $('#loading').hide();
                }
            }
        });
});

// Function to delete warning modal show
$('.delete_criteria').live('click',function(){
   var criteria_id = $(this).attr('data-criteria_id'); 
   var ao_method_id = $('#ao_method_id').val();
   $('#criteria_id').val(criteria_id);
    $('#delete_criteria_modal').modal('show');
  
});

// Function to delete the criteria
$('#delete_criteria_modal').on('click','#delete_criteria_data',function(){
    var criteria_id = $('#criteria_id').val(); 
    var ao_method_id = $('#ao_method_id').val();
    var ao_id = $('#ao_id').val();
    var post_data = {
       'criteria_id':criteria_id,
       'ao_method_id':ao_method_id,
       'ao_id':ao_id,
   };
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/delete_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                $('#rubrics_table').empty();
                $('#rubrics_table').html(msg);
                $('#criteria_1').val('');
                $('.criteria_check').each(function(){
                    $(this).val('');
                });
                $(".co_id_rubrics option:selected").removeAttr("selected");
                $('.co_id_rubrics').multiselect('rebuild');
                $('#updata_criteria').hide();
                $('#save_rubrics').show();
                $('#rubrics_type').show();
            }
        });
});

// Function to Edit criteria modal show
$('.edit_criteria').live('click',function(){
    
   var criteria_id = $(this).attr('data-criteria_id'); 
   $('#edit_criteria_id').val(criteria_id);
   var ao_method_id = $('#ao_method_id').val();
   var crs_id = $('#crs_id').val();
   var post_data = {
       'criteria_id':criteria_id,
       'ao_method_id':ao_method_id,
       'crs_id':crs_id,
   };
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/edit_rubrics_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
               $('#assessment_scale').empty();
                $('#assessment_scale').html(msg);
                 $('.co_id_rubrics').multiselect({
                    includeSelectAllOption: true,
                    buttonWidth: '110px',
                    nonSelectedText: 'Select CO',
                    templates: {
                        button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
                    }

                });
                $('.co_id_rubrics').multiselect('rebuild');
                $('#updata_criteria').show();
                $('#criteria_1').focus();
                 $('html, body').animate({
                    scrollDown: $("#criteria_1").offset().down
                }, 2000);
                $('#save_rubrics').hide();
                $('#rubrics_type').hide();
                
              //  $('#edit_criteria_modal').modal('show');
           }
        });
    
  
});

$('#updata_criteria').on('click',function(){
       var flag = $('#save_rubrics_data').validate({
                   ignore: ':hidden:not(".co_id_rubrics")'
               });
    
    $('.criteria_check').each(function() {
        $(this).rules("add", 
            {
                    required : true,						
            });
        });
    $('.co_id_rubrics').each(function(){
        $(this).rules("add", 
            {
                    required : true,						
            });
        
    });
       // ignore: ':hidden:not(".co_id_rubrics")', 
    var flag = $('#save_rubrics_data').valid();
   
    if(flag == true){
    $('#loading').show();
    var criteria_id = $('#edit_criteria_id').val();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val();
    var criteria_desc = $('.criteria_check').map(function(){ return $(this).val();}).get();
    var criteria_desc_id = $("input[name='criteria_desc_edit[]']").map(function(){ return $(this).val();}).get();
    var co_list_id = $('#co_id_val').val();
    var criteria = $('#criteria_1').val();
    
    var post_data = {
        'criteria_id':criteria_id,
        'criteria':criteria,
        'ao_method_id':ao_method_id,
        'criteria_desc_id':criteria_desc_id,
        'criteria_desc':criteria_desc,
        'co_list_id':co_list_id,
    };
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/update_rubrics_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
               if($.trim(msg) == 'true'){
                   $('#loading').hide();
                   location.reload();
               }else{
                   $('#loading').hide();
               }
           }
        });
    }
   
     
});

// Noty required error msg
function required_fields(msg){//$('#myModal_fail').modal('show');				
                $('#loading').hide();
                var data_options = '{"text":"All Fields must be filled before proceeding..","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
	} 
        
// Function to generate the question paper or finalizing rubrics
$('#gernerate_qp_button_div').on('click','#generate_qp',function(){
$('#finalize_modal_confirmation').modal('show');
});
$(document).on('click','#finalize_rubrics_data',function(){
   finalizing_rubrics_data();
});
function finalizing_rubrics_data(){
    $('#loading').show();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val(); 
    var qpd_id = $('#qpd_id').val(); 
    
    var post_data = {
        'crclm_id':crclm_id,
        'term_id':term_id,
        'crs_id':crs_id,
        'section_id':section_id,
        'ao_id':ao_id,
        'ao_type_id':ao_type_id,
        'ao_method_id':ao_method_id,
        'qpd_id':qpd_id,
    };
    
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/generate_question_paper',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                if($.trim(msg)=='true'){
                    $('#finalize_modal_confirmation').modal('hide');
                    $('#loading').hide();
                    var data_options = '{"text":"Rubrics Finalized Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    window.setTimeout(function(){location.reload()},2000)
                }else{
                    $('#loading').hide();
                    var data_options = '{"text":"Warning - Kindly associate each of the criteria with atleast one CO and then Finalize the Criterias.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
                
           }
        });
}

$('#force_save_rubrics').on('click',function(){
     var flag = $('#save_rubrics_data').validate();
    
    $('.criteria_check').each(function() {
        $(this).rules("add", 
            {
                    required : true,						
            });
        });
    var flag = $('#save_rubrics_data').valid();
    if(flag == true){
        $('#delete_question_paper_warning_modal').modal('show');
    }else{
        required_fields('msg');
    }
    
});

$('#delete_created_qp').on('click',function(){
    $('#loading').show();
   var ao_id = $('#ao_id').val();
   var post_data = {'ao_id':ao_id};
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/delete_qp_force_insert_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                if($.trim(msg)=='true'){
                    force_insert_criteria();
                }else{
                    var data_options = '{"text":"Criteria Insertion failed.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
           }
        });
  
});

function force_insert_criteria(){
    var criteria;
    var co_list = $('#co_id_val').val();
    var ao_id = $('#ao_id').val();
    //var criteria_desc = $("input[name='criteria_desc[]']").map(function(){return $(this).val();}).get();
    var criteria_desc = $('.criteria_check').map(function(){ return $(this).val();}).get();
    var range = $('.range_check').map(function(){return $(this).val();}).get();
    var ao_method_id = $('#ao_method_id').val();
    var selected_radio_entity_id = $("input[type='radio'][name='rubrics_type']:checked").attr('entity_id');
    var selected_rubrics_type = $("input[type='radio'][name='rubrics_type']:checked").attr('rub_typ');
    if(selected_radio_entity_id != 0){
        criteria = $('#rubrics_criteria').val();
    }else{
        criteria = $('#criteria_1').val();
    }
    var post_data = {
      'criteria':criteria,  
      'co_list':co_list,  
      'criteria_desc':criteria_desc,  
      'range':range,  
      'ao_method_id':ao_method_id,  
      'selected_radio_entity_id':selected_radio_entity_id,  
      'selected_rubrics_type':selected_rubrics_type,  
    };

    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/save_criteria_details',
            data: post_data,
            dataType:'JSON',
            success: function(msg) {
                    
                    var data_options = '{"text":"Criteria Inserted Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    window.setTimeout(function(){location.reload()},3000)
                    $('#loading').hide();
                     $('#rubrics_table').empty();
                     $('#rubrics_table').html(msg.rubrics_list_table);
                     $('#criteria_1').val('');
                     $('.criteria_check').each(function(){
                         $(this).val('');
                     });
                     $(".co_id_rubrics option:selected").removeAttr("selected");
                     $('.co_id_rubrics').multiselect('rebuild');
                 
            }
        });
}

$('.force_edit_criteria').on('click',function(){
   $('#force_edit_criteria_warning_modal').modal('show');
   var criteria_id = $(this).attr('data-criteria_id'); 
   $('#edit_criteria_id').val(criteria_id);
});

$('#force_editing_critiria').on('click',function(){
   $('#loading').show();
   var ao_id = $('#ao_id').val();
   var post_data = {'ao_id':ao_id};
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/delete_qp_force_insert_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                if($.trim(msg)=='true'){
                    force_edit_rubrics_criteria();
                }else{
                    var data_options = '{"text":"Criteria Updattion failed.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
           }
        });
  

});

//Function for force editing the criteria

function force_edit_rubrics_criteria(){
    //   var criteria_id = $(this).attr('data-criteria_id'); 
  var criteria_id = $('#edit_criteria_id').val();
   var ao_method_id = $('#ao_method_id').val();
   var crs_id = $('#crs_id').val();
   var post_data = {
       'criteria_id':criteria_id,
       'ao_method_id':ao_method_id,
       'crs_id':crs_id,
   };
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/edit_rubrics_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
               $('#assessment_scale').empty();
                $('#assessment_scale').html(msg);
                 $('.co_id_rubrics').multiselect({
                    includeSelectAllOption: true,
                    buttonWidth: '110px',
                    nonSelectedText: 'Select CO',
                    templates: {
                        button: '<button type="button" class="input-small multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
                    }

                });
                $('.co_id_rubrics').multiselect('rebuild');
                $('#updata_criteria').show();
                $('#criteria_1').focus();
                 $('html, body').animate({
                    scrollDown: $("#criteria_1").offset().down
                }, 2000);
                $('#save_rubrics').hide();
                $('#force_save_rubrics').hide();
                $('#rubrics_type').hide();
                $('#loading').hide();
              //  $('#edit_criteria_modal').modal('show');
           }
        });
}

$('.force_delete_criteria').on('click',function(){
    var criteria_id = $(this).attr('data-criteria_id');
   $('#criteria_id').val(criteria_id);
   $('#force_delete_criteria_warning_modal').modal('show');
});

$('#force_delete_critiria').on('click',function(){
    $('#loading').show();
    var ao_id = $('#ao_id').val();
   var post_data = {'ao_id':ao_id};
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/delete_qp_force_insert_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                if($.trim(msg)=='true'){
                    force_delete_rubrics_criteria();
                }else{
                    var data_options = '{"text":"Criteria Updattion failed.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
           }
        });
  
});

//Function to force delete the criteria
function force_delete_rubrics_criteria(){
    var criteria_id = $('#criteria_id').val(); 
    var ao_method_id = $('#ao_method_id').val();
    var ao_id = $('#ao_id').val();
    var post_data = {
       'criteria_id':criteria_id,
       'ao_method_id':ao_method_id,
       'ao_id':ao_id,
   };
   $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/delete_criteria',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                var data_options = '{"text":"Criteria Deleted Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    window.setTimeout(function(){location.reload()},3000)
                    $('#loading').hide();
            }
        });
}

$(document).on('click','#export_to_pdf',function(){
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var section_id = $('#section_id').val();
    var ao_id = $('#ao_id').val();
    var ao_type_id = $('#ao_type_id').val();
    var ao_method_id = $('#ao_method_id').val();
    var post_data = {
        'crclm_id':crclm_id,
        'term_id':term_id,
        'crs_id':crs_id,
        'section_id':section_id,
        'ao_id':ao_id,
        'ao_type_id':ao_type_id,
        'ao_method_id':ao_method_id,
    };
   
    $.ajax({type: "POST",
            url: base_url + 'assessment_attainment/cia_rubrics/get_rubrics_table_modal_view',
            data: post_data,
            dataType:'html',
            success: function(msg) {
                $('#report_in_pdf').val(msg);
                $('#rubrics_report').submit();
              console.log(msg);
            }
        });
});