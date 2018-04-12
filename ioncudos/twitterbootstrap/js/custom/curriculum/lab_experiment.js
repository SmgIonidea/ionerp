
//Lab Experiment

var crclm_id = $('#crclm_id').val();
var term_id = $('#term_id').val();
var course_id = $('#course_id').val();

var crs_id = $('#crs_id').val();
var topic_id = $('#topic_id').val();
var category_id = $('#category_id').val();
post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id}

$.ajax({type: "POST",
    url: base_url + 'curriculum/lab_experiment/fetch_lab_experiment_list',
    data: post_data,
    dataType: 'json',
    success: function (data) {
        populate_table(data);
    }
});
/**To Populate DataTable for tab my_training and development**/
function populate_table(msg) {
    $('#example_view').dataTable().fnDestroy();
    $('#example_view').dataTable(
            {"sSort": true,
                "sPaginate": true,
                "aoColumns": [
                    {"sTitle": "TLO Code", "mData": "Tlo_code"},
                    {"sTitle": "Topic Learning Outcomes", "mData": "tlo_statement"},
                    {"sTitle": "Bloom's Level", "mData": "level"},
                    {"sTitle": "Delivery Method", "mData": "delivery_mtd_name"},
                    {"sTitle": "Delivery Approach", "mData": "delivery_approach"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "Delete"},
                ], "aaData": msg,
                "sPaginationType": "bootstrap",
            });

}
fetch_lab_data();
function fetch_lab_data() {
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var category_id = $('#category_id').val();
    post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': crs_id, 'category_id': category_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/fetch_lab_data',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populate_table_lab(data);
        }
    });

}


function populate_table_lab(msg) {
    $('#example_lab_list').dataTable().fnDestroy();
    $('#example_lab_list').dataTable(
            {"sSort": true,
                "sPaginate": true,
                "aoColumns": [
                    {"sTitle": "Expt. / Job No.", "sClass": "", "mData": "topic_title"},
                    {"sTitle": "Experiment / Job Details", "sClass": "", "mData": "topic_content"},
                    {"sTitle": "No. of Session/s per batch (estimate)", "sClass": "", "mData": "num_of_sessions"},
                    {"sTitle": "Marks / Experiment", "sClass": "", "mData": "marks_expt"},
                    {"sTitle": "Correlation of Experiment with theory", "sClass": "", "mData": "correlation_with_theory"},
                    {"sTitle": "Edit", "sClass": "right", "mData": "edit_topic"},
                    {"sTitle": "Delete", "sClass": "center", "mData": "delete_topic"}
                ], "aaData": msg['topic_data'],
                "sPaginationType": "bootstrap",
            });

}
//Add additional lab experiment
var new_counter = new Array();
new_counter.push(1);
$('#add_more_lab_expt').on('click', function () {
    $('#loading').show();
    var lab_expt_counter = $('#lab_expt_counter').val();
    var post_data = {
        'lab_expt_counter': lab_expt_counter
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/additional_lab_expt',
        data: post_data,
        success: function (additional_lab_expt) {
            ++lab_expt_counter;
            $('#counter').val(counter);
            $("#additional_lab_expt").append(additional_lab_expt);
            new_counter.push(lab_expt_counter);
            $('#lab_expt_counter').val(lab_expt_counter); //num of expt
            $('#counter').val(new_counter); //comma sep val
            $('#loading').hide();
        }
    });
});

//Save lab experiment
$(document).on('click', '#lab_expt_save', function (e) {
    e.preventDefault();
    $('#loading').show();
    if ($('#lab_experiment_add_form').valid()) {
        var values = $("#lab_experiment_add_form").serialize();
        $.ajax({type: "POST",
            url: base_url + 'curriculum/lab_experiment/insert_lab_experiment',
            data: values,
            dataType: 'json',
            success: function (data) {
                success_modal();
                reset_lab_list();
                fetch_lab_data();
            }
        });
        $('#loading').hide();
    } else {
        $('#loading').hide();
    }
});


$(document).on('click', '#lab_expt_update', function (e) {
    e.preventDefault();
    $('#loading').show();
    if ($('#lab_experiment_add_form').valid()) {
        var values = $("#lab_experiment_add_form").serialize();
        $.ajax({type: "POST",
            url: base_url + 'curriculum/lab_experiment/update_lab_experiment',
            data: values,
            dataType: 'json',
            success: function (data) {
                success_modal();
                reset_lab_list();
                fetch_lab_data();
            }
        });
        $('#loading').hide();
    } else {
        $('#loading').hide();
    }
});

$(document).on('click', '#lab_expt_update_update', function (e) {
    //e.preventDefault(); 	
    $('#loading').show();
    if ($('#lab_experiment_edit_form').valid()) {
        var values = $("#lab_experiment_edit_form").serialize();
        $.ajax({type: "POST",
            url: base_url + 'curriculum/lab_experiment/update_lab_experiment',
            data: values,
            dataType: 'json',
            success: function (data) {
                $('#loading').hide();
                success_update_modal();              
                fetch_lab_data();
            }
        });
        $('#loading').hide();
    } else {
        $('#loading').hide();
    }

});

var expt_id;
var table_row;

var course_id = document.getElementById('course');

$('.get_topic_id').live("click", function () {
    expt_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});
function delete_experiment() {
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/delete_experiment' + '/' + expt_id,
        success: function (msg) {
            success_delete_modal();
            fetch_lab_data();
            $('#loading').hide();
        }
    });
}
$('#lab_expt_update').hide();
function reset_lab_list() {
    $("#lab_experiment_add_form").trigger('reset');
    var validator = $('#lab_experiment_add_form').validate();
    validator.resetForm();
    $('#lab_expt_save').show();
    $('#lab_expt_update').hide();
    return;
}

$(".edit_lab_data").live('click', function () {
    $('html,body').animate({scrollTop: $(".tab1").offset().top}, 'slow');
    $('#lab_expt_save').hide();
    $('#lab_expt_update').show();
    var session_int = Math.trunc($(this).attr('data-num_of_sessions'));
    var marks_int = Math.trunc($(this).attr('data-marks_expt'));
    $('#expt_no_1').val($(this).attr('data-topic_title'));
    $('#sessions_1').val(session_int);
    $('#marks_1').val(marks_int);
    $('#expt_1').val($(this).attr('data-topic_content'));
    $('#correlation_1').val($(this).attr('data-correlation_with_theory'));
    $('#topic_id').val($(this).attr('topic_id'));
	$('#category_id_val').val($(this).attr('data-category_id'));
	

});


$.validator.addMethod("numeric", function (value, element) {
    var regex = /^[0-9\s]+$/; //this is for numeric... you can do any regular expression you like...
    return this.optional(element) || regex.test(value);
}, "Field must contain only numbers.");

$("#lab_experiment_add_form").validate({
    rules: {
        sessions_1: {
            numeric: true,
            required: true
        },
        marks_1: {
            numeric: true,
            required: true
        }
    },
    errorClass: "help-inline font_color",
    errorElement: "label",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error').removeClass('success');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success').removeClass('error');
    }
});

//Invoked when user click to delete lab experiment
$('.Delete').live('click', function () {
    $('#myModaldelete').modal('show');
    var row = $(this).attr("id").match(/\d+/g);
    $('#deleteId').val(row);
});

//Function to delete the newly created lab experiment upon user confirmation to delete
$('.delete_confirm').on('click', function () {
    $('#loading').show();
    var row_id = $('#deleteId').attr('value');
    $('#lab_expt_main_div_' + row_id).remove();
    var id_val = 'remove_lab_expt_' + row_id;
    var deletedId_replaced = id_val.replace('remove_lab_expt_', '');
    var criteria_counter_index = $.inArray(parseInt(deletedId_replaced), new_counter);
    new_counter.splice(criteria_counter_index, 1);
    $('#counter').val(new_counter);
    $('#loading').hide();
});



//count number of characters entered in the description box
$('.char-counter').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

/********************************************** LAB EXPERIMENT SLO *************************************************/
//Add additional lab experiment tlo
var new_counter_lab_tlo = new Array();
new_counter_lab_tlo.push(1);
$('#add_more_lab_expt_tlo').on('click', function () {
    var lab_expt_tlo_counter = $('#lab_expt_tlo_counter').val();
    var post_data = {
        'lab_expt_tlo_counter': lab_expt_tlo_counter
    }
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/additional_lab_expt_tlo',
        data: post_data,
        success: function (additional_lab_expt_tlo) {
            console.log(additional_lab_expt_tlo);
            ++lab_expt_tlo_counter;
            $('#lab_expt_tlo_counter').val(lab_expt_tlo_counter);
            $("#additional_lab_expt_tlo").append(additional_lab_expt_tlo);
            new_counter_lab_tlo.push(lab_expt_tlo_counter);
            $('#lab_expt_tlo_counter').val(lab_expt_tlo_counter); //num of expt
            $('#lab_tlo_counter').val(new_counter_lab_tlo); //comma sep val
            $('#loading').hide();
        }
    });
});

$('.delete_lab_expt_tlo').on('click', function () {
    $('#loading').show();
    var row_id = $('#deleteId').attr('value');
    $('#tlo_statement' + row_id).remove();
    var id_val = 'remove_lab_expt_tlo' + row_id;
    var deletedId_replaced = id_val.replace('remove_lab_expt_tlo', '');
    var criteria_counter_index = $.inArray(parseInt(deletedId_replaced), new_counter_lab_tlo);
    new_counter_lab_tlo.splice(criteria_counter_index, 1);
    $('#lab_tlo_counter').val(new_counter_lab_tlo);
    $('#loading').hide();
});

$(document).on('click', '#lab_expt_tlo_save', function (e) {
    e.preventDefault();
    $('#loading').show();
    tinyMCE.triggerSave();
    var str = $('#tlo1').val();
    var str1 = str.replace("<p>", " ");
    var dataval = str1.replace("</p>", " ");
    if (dataval == "") {
        $('#tiny_mce').html("This field is required.");
    } else {
        $('#tiny_mce').html(" ");
    }
        var clo_bl_flag = $('#clo_bl_flag').val();
           if(clo_bl_flag == 1){
        var i=1;
     $('input[name="bloom_filed_id[]').each(function() {
                id = '#'+ $(this).val();
               var values = $(id).val();                 
            if(values === null){  
                flag1 = 0; return false;

            }else{
                console.log("");
                $('#error_placeholder_bl'+i).html("");  
                flag1 = 1 ;      
            }
            i++;
          });
         }else{ 
                $('#error_placeholder_bl'+i).html("");  
                 flag1 = 1;
         }
    
    if ($('#lab_experiment_tlo_add_form').valid() && dataval != "" && flag1 == 1) {
        var lab_tlo_counter = $('#lab_tlo_counter').val();
        var crclm_id = $('#crclm_id').val();
        var term_id = $('#term_id').val();
        var crs_id = $('#crs_id').val();
        var category_id = $('#category_id').val();
        var delivery_methods = $('#delivery_methods1').val();
        var delivery_approach = $('#delivery_approach').val();
        var topic_id = $('#topic_id').val();
        var counter = lab_tlo_counter.split(",");
        var counter_count = counter.length;
        var tlo_stmt_array = new Array();
        tinyMCE.triggerSave();
        var str = $('#tlo1').val();
        var str1 = str.replace("<p>", " ");
        var str2 = str1.replace("</p>", " ");
        var res = str2.replace('alt=""', 'alt="image"');
        var dataval = res;
        for (var i = 0; i < counter_count; i++) {
            tlo_stmt_array[i] = dataval;
        }

        var bloom_domain_id = new Array;
        var bloom_level = $("#bloom_level_1").val();
        var bloom_level_1 = $("#bloom_level_2").val();
        var bloom_level_2 = $("#bloom_level_3").val();
        bloom_domain_id.push($("#bld_id_1").val());
        bloom_domain_id.push($("#bld_id_2").val());
        bloom_domain_id.push($("#bld_id_3").val());
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
            'category_id': category_id,
            'topic_id': topic_id,
            'count': counter_count,
            'tlo_stmt_array': tlo_stmt_array,
            'delivery_methods': delivery_methods,
            'delivery_approach': delivery_approach,
            'bloom_domain_id': bloom_domain_id,
            'bloom_level': bloom_level,
            'bloom_level_1': bloom_level_1,
            'bloom_level_2': bloom_level_2
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/lab_experiment/insert_lab_experiment_tlo',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                var topic_id = $('#topic_id').val();
                post_data = {'topic_id': topic_id}

                $.ajax({type: "POST",
                    url: base_url + 'curriculum/lab_experiment/fetch_lab_experiment_list',
                    data: post_data,
                    dataType: 'json',
                    success: function (data) {
                        $('#lab_experiment_tlo_add_form').trigger('reset');
                        populate_table(data);
                        success_modal(data);
                        $('#bloom_level_1').find('option:selected').prop('selected', false);
                        $('#bloom_level_1').multiselect('rebuild');
                        $("#bloom_level_1_actionverbs").html("");
                        $('#bloom_level_2').find('option:selected').prop('selected', false);
                        $('#bloom_level_2').multiselect('rebuild');
                        $("#bloom_level_2_actionverbs").html("");
                        $('#bloom_level_3').find('option:selected').prop('selected', false);
                        $('#bloom_level_3').multiselect('rebuild');
                        $("#bloom_level_3_actionverbs").html("");
                    }
                });
                $('#loading').hide();
            }
        });
    } else {     
        $('#loading').hide();
            if(clo_bl_flag == 1){

              var j=1;
                $('input[name="bloom_filed_id[]').each(function() {
                      id = '#'+ $(this).val();
                     var values = $(id).val();                        
                  if(values === null){                                  
                      $('#error_placeholder_bl'+j).html("<span style='color:red;font-size:12px;'> This field is required. </span>");                  
                  }else{
                       $('#error_placeholder_bl'+j).html("");  
                  }
                  j++;
                });      
            }
        }
});


$('#example_view').on('click', '.deleteLabExpermentTLO', function () {
    var tlo_id = $(this).attr('value');
    $('#delete_tlo_id').val(tlo_id);
    $('#deleteLabExpermentTLO').modal('show');
});

$('.delete_lab_expt_tlo_confirm').on('click', function () {
    $('#loading').show();
    var tlo_id = $('#delete_tlo_id').val();
    var post_data = {
        'tlo_id': tlo_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/delete_lab_experiment_tlo',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            var topic_id = $('#topic_id').val();
            post_data = {'topic_id': topic_id}

            $.ajax({type: "POST",
                url: base_url + 'curriculum/lab_experiment/fetch_lab_experiment_list',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    populate_table(data);
                    success_delete_modal(data);
                }
            });
            $('#loading').hide();
        }
    });
});

$('#example_view').on('click', '.editLabExpermentTLO', function () {
    tinymce.remove();
    var tlo_data = $(this).attr('value');
    var tlo_values = tlo_data.split("-");
    var bloom_actionverbs = $(this).attr('data-bloom_actionverbs');
    var delivery_mtd_name = $(this).attr('data-delivery_mtd_name');
    var crclm_dm_id = $(this).attr('data-crclm_dm_id');
    var data_dlm = $(this).attr('data-dlm');
    $('#edit_tlo_id').val(tlo_values[0]);
    $('#tlo_code_edit').val($(this).attr('data-tlo_code'));
    var level = $(this).attr('data-level');
    $('#dmn').val(delivery_mtd_name);
    $('#delivery_approach_edit').val(data_dlm);
    $('#dlm').val(crclm_dm_id);
    $('#dlm').attr("selected", 'selected');
    var tlo_id = tlo_data;
    $('#edit_tlo_statement').val(tlo_values[1]);
    $('#edit_bloom_level_1').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_1').multiselect('rebuild');
    var bld_id = $("#bld_id_1").val();
    var select = 1;
    fetch_dropdown(tlo_id, bld_id, select);

    $('#edit_bloom_level_2').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_2').multiselect('rebuild');
    var bld_id = $("#bld_id_2").val();
    var select = 2;
    fetch_dropdown(tlo_id, bld_id, select);

    $('#edit_bloom_level_3').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_3').multiselect('rebuild');
    var bld_id = $("#bld_id_3").val();
    var select = 3;
    fetch_dropdown(tlo_id, bld_id, select);
    $('#editLabExpermentTLO').modal('show');
    tiny_init();
});

$('#add_tlo').on('change', '.fetch_action_verbs', function () {
    var bloom_id = $(this).val();
    var id = $(this).attr("id").match(/\d+/g);
    var post_data = {'bloom_id': bloom_id};
    $('#loading').show();
    if (bloom_id) {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo/fect_action_verbs',
            data: post_data,
            dataType: 'json',
            success: function (msg) {

                $('#action_verb_display_' + id).html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bloom\'s Level Action Verbs : </b>' + msg[0].bloom_actionverbs);
                $('#loading').hide();
            }
        });
    } else {
        $('#action_verb_display_' + id).html('Note : Select Bloom\'s Level to view its respective Action Verbs');
        $('#loading').hide();
    }

});

$('#edit_lab_expt_tlo_confirm').on('click', function () {

    tinyMCE.triggerSave();
    var str = $('#edit_tlo_statement').val();
    var str1 = str.replace("<p>", " ");
    var dataval = str1.replace("</p>", " ");
    var tlo_id = $('#edit_tlo_id').val();
    var tlo_code = $('#tlo_code_edit').val();
    var tlo_dm_id = $('#editLabExpermentTLO').data('data');
    var delivery_mtd_id = $('#dlm').val();
    var dlma = $('#delivery_approach_edit').val();
    var level = $('#edit_bloom_level_1').val();
    var level_1 = $('#edit_bloom_level_2').val();
    var level_2 = $('#edit_bloom_level_3').val();
    var bloom_domain_id = new Array;
    bloom_domain_id.push($("#bld_id_1").val());
    bloom_domain_id.push($("#bld_id_2").val());
    bloom_domain_id.push($("#bld_id_3").val());
    var clo_bl_flag = $('#clo_bl_flag').val();     
    if(clo_bl_flag == 1){
        var i=1;
    
     $('input[name="bloom_filed_edit_id[]').each(function() {
           id = '#'+ $(this).val();
                var values = $(id).val();                 
             if(values === null){  
                      $('#error_placeholder_edit_bl'+j).html(" ");  
                 flag1 = 0; return false;

             }else{
                 flag1 = 1 ;    
                  $('#error_placeholder_edit_bl'+i).html(" ");  
             }
             i++;
           });
          }else{

                  flag1 = 1;
                   $('#error_placeholder_edit_bl'+i).html(" ");  
          }    
   // if (flag1 == 1) {
    $('#loading').show();
    var post_data = {
        'tlo_id': tlo_id,
        'tlo_statement': dataval,
        'tlo_code': tlo_code,
        'delivery_mtd_id': delivery_mtd_id,
        'tlo_dm_id': tlo_dm_id,
        'dlma': dlma,
        'level': level,
        'bloom_domain_id': bloom_domain_id,
        'level_1': level_1,
        'level_2': level_2
    }
   console.log(flag1);
    if (dataval != "" && flag1 == 1) {
        $('#bloom_error_edit').html(" ");
        $.ajax({type: "POST",
            url: base_url + 'curriculum/lab_experiment/edit_lab_experiment_tlo',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                var topic_id = $('#topic_id').val();
                post_data = {'topic_id': topic_id}

                $.ajax({type: "POST",
                    url: base_url + 'curriculum/lab_experiment/fetch_lab_experiment_list',
                    data: post_data,
                    dataType: 'json',
                    success: function (data) {
                        populate_table(data);
                        success_update_modal(data);
                    }
                });
                $('#loading').hide();
                $('#editLabExpermentTLO').modal('hide');
            }
        });
    } else {
        if (dataval == "") {
            $('#tiny_mce_edit').html("This field is required.");
        } else {
            $('#tiny_mce_edit').html(" ");
        }
        $('#loading').hide();
      
   
        if(clo_bl_flag == 1){            
          var j=1;
            $('input[name="bloom_filed_edit_id[]').each(function() {
                  id = '#'+ $(this).val();
                 var values = $(id).val();                          
              if(values === null){                     
                  $('#error_placeholder_edit_bl'+j).html("<span style='color:red'> This field is required. </span>");                  
              }else{
                   $('#error_placeholder_edit_bl'+j).html(" ");  
              }
              j++;
            });      
        }
    
        
    }
});



function tiny_init() {

    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "tlo",
        relative_urls: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages",
        ],
        paste_data_images: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
    });
}
//Tiny MCE script
tinymce.init({
    mode: "specific_textareas",
    editor_selector: "tlo",
    relative_urls: false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste jbimages",
    ],
    paste_data_images: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
});
function success_delete_modal(msg) {
    var data_options = '{"text":"Your data has been Deleted successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

function success_modal(msg) {
    var data_options = '{"text":"Your data has been Saved successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

function success_update_modal(msg) {
    var data_options = '{"text":"Your data has been Updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to select mapped bloom's level 
function fetch_dropdown(tlo_id, bld_id, select) {
    $.ajax({
        type: 'POST',
        url: base_url + 'curriculum/lab_experiment/mapped_bloom_levels',
        data: {'id': tlo_id, 'bld_id': bld_id},
        dataType: 'json',
        success: function (data) {
            var size = data.mapped_bloom_levels.length;
            var count = 0;

            if (select == 1) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/lab_experiment/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_1_actionverbs").html(data);
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_1 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_1').multiselect('rebuild');
            }

            if (select == 2) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/lab_experiment/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_2_actionverbs").html(data)
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_2 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_2').multiselect('rebuild');
            }

            if (select == 3) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/lab_experiment/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_3_actionverbs").html(data)
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_3 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_3').multiselect('rebuild');
            }
        }
    });
}
