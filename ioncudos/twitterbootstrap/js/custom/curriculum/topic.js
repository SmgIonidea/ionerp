// Topic List & Add JS file...
var base_url = $('#get_base_url').val();
var topic_id;
var table_row;
var po_counter = new Array();
var book_sl_no_counter = new Array();
var assessment_name_counter = new Array();
po_counter.push(1);
book_sl_no_counter.push(1);
assessment_name_counter.push(1);
var cloneCntr = 2;

var course_id = document.getElementById('course');
$('.get_topic_id').live("click", function () {
    topic_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

/*
 * Function get number of topics created
 */

function total_topics_number(){
    var courseId = $('#course').val();
    var post_data = {
        'crs_id':courseId,
    };
    $.ajax({type: "POST",
            url: base_url + 'curriculum/topicadd/count_of_topics',
            data:post_data,
            success: function (count_msg) {
                count_msg++;
                $('#slno').val(count_msg);
            }
        });
};

$(document).ready(function(){
    total_topics_number();
});

function delete_topic() {
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topicadd/delete_topic' + '/' + topic_id,
        success: function (msg) {		
            if (msg == 1)
            {
                $('#myModal1').modal('show');
            } else if( msg == 3){
				$('#myModal2').modal('show');
			}else
            {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
            $('#loading').hide();
        }
    });
}

function delete_topic_data() {
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topicadd/delete_topic' + '/' + topic_id,
        success: function (msg) {
            if (msg == 1)
            {
                $('#myModal1').modal('show');
            } else
            {
                success_modal_delete(msg); fetch_topics();
            }
            $('#loading').hide();
        }
    });
}

 fetch_topics();
 function fetch_topics(){
	var crclm_id = $('#crclm').val();
	var term_id = $('#term').val(); 
	var course_id = $('#course').val(); 
	//var unit_id = $('#unit_id').val(); 
	var post_data = {'crclm_id':crclm_id , 'term_id':term_id , 'course_id':course_id , } ;
	 $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/fetch_topics',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
		
			populate_table_topic_list(msg); 
		}});
	
	
 
 }
 
 
    function success_modal_save(msg) {
        var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }      
	function success_modal_update(msg) {
        var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }    
	function success_modal_delete(msg) {
        var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }
 function populate_table_topic_list(msg) {

    $('#example_topic_list').dataTable().fnDestroy();
    $('#example_topic_list').dataTable(
            {
                "aoColumns": [
                    {"sTitle": entity_topic + " Unit", "sClass": "norap", "mData": "topic_unit"},
                    {"sTitle": entity_topic + " Code", "sClass": "norap", "mData": "topic_code"},
                    {"sTitle": entity_topic + " Title", "sClass": "", "mData": "topic_title"},
                    {"sTitle": entity_topic + " Content", "sClass": "", "mData": "topic_content"},
                    {"sTitle": entity_topic + " Hours", "sClass": "", "mData": "topic_hrs"},
                    {"sTitle": entity_topic + " Delivery Methods", "sClass": "", "mData": "delivery_method"},
                    {"sTitle": "Edit", "sClass": "right", "mData": "topic_id"},
                    {"sTitle": "Delete", "sClass": "center", "mData": "topic_id1"},               
                ], "aaData": msg["topic_data"],
                "sPaginationType": "bootstrap"});
    $('#example_topic_list').dataTable().fnDestroy();
    $('#example_topic_list').dataTable({
	"fnDrawCallback": function () {
	    $('.group').parent().css({'background-color': '#C7C5C5'});
	},
	"aoColumnDefs": [
	    {"sType": "natural", "aTargets": [2]}
	],
	"sPaginationType": "bootstrap"

    }).rowGrouping({iGroupingColumnIndex: 0,
	bHideGroupingColumn: true});
}

if ($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
    select_term();

}

function select_term()
{
    $.cookie('remember_term', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    var term_list_path = base_url + 'curriculum/topic/select_term';
    var data_val = $('#curriculum').val();
    var post_data = {
        'crclm_id': data_val
    }
    $.ajax({type: "POST",
        url: term_list_path,
        data: post_data,
        success: function (msg) {
            $('#term').html(msg);
            if ($.cookie('remember_course') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                select_course();
            }
        }
    });
}

function select_course()
{
    $.cookie('remember_course', $('#term option:selected').val(), {expires: 90, path: '/'});
    var select_course_path = base_url + 'curriculum/topic/select_course';
    var data_val = $('#term').val();
    var post_data = {
        'term_id': data_val
    }
    $.ajax({type: "POST",
        url: select_course_path,
        data: post_data,
        success: function (msg) {
            $('#course').html(msg);
            if ($.cookie('remember_selected_value') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                //select_unit();
                $('#course').trigger('change');
            }
        }
    });
}

//Function to fetch the grid details
/*function select_unit()
{
    $.cookie('remember_unit', $('#units option:selected').val(), {expires: 90, path: '/'});
    var show_unit_path = base_url + 'curriculum/topic/show_unit';
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
   // var t_unit_id = $('#units').val();

    $('#curriculum_id').val(crclm_id);
    $('#term_id').val(term_id);
    $('#course_id').val(course_id);
   // $('#t_unit_id').val(t_unit_id);
    var post_data = {
        'crclm_id': crclm_id,
        'crs_id':course_id,
    };
    $.ajax({type: "POST",
        url: show_unit_path,
        data: post_data,
        dataType:'JSON',
        success: function (msg) {
            if($.trim(msg) == 'fail'){
                $('#tbl_div').hide();
                var err_msg = 'Course CO to PO mapping Review is Pending. Please complete the CO to PO mapping process in order to Create '+entity_topic+' for the Course';
                var note = '<center><b><font color="red">'+err_msg+'</font></b><center>';
                $('#crs_status').empty();
                $('#crs_status').html(note);
                $('#crs_status').show();
                
                $('#units').empty();
                $('#ad_topic').hide();
                $('#ad_topic1').hide();
                $('#submit_to_add_books').hide();
                $('#submit_to_publish').hide();
                $('.inline').hide();
                
            }else{
                
                $('#tbl_div').show();
                $('#crs_status').empty();
                $('#crs_status').hide();
                $('#units').html(msg);
                if ($.cookie('remember_selected_value') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#unit option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                }
                $('#ad_topic').show();
                $('#ad_topic1').show();
                $('#submit_to_add_books').show();
                $('#submit_to_publish').show();
                $('.inline').show();
            }
        }
    });

}*/



function form_submit()
{
    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;
   // var unit_val = document.getElementById('units').value;
    $('#loading').show();
    if (crclm_val && term_val && course_val) {
        var post_data = {
            'crs_id': course_val
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/topic/check_course_delivery_publish_flag' + '/' + course_val,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (parseInt(msg) == 1) {
                    $('#myModal_add_more_topics').modal('show');
                } else {
                    $('#add_form').submit();
                }
                $('#loading').hide();
            }
        });

    } else {
        $('#myModal_submit').modal('show');
        $('#loading').hide();
    }
}

// Add more topics when already released for delivery publish, so re-confirm readiness of the course publish after topic addition completion.
function add_more_topics_for_course()
{
    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;

    if (crclm_val && term_val && course_val) {
        var post_data = {
            'crs_id': course_val
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/topic/update_topic_publish_flag' + '/' + course_val,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#add_form').submit();
            }
        });
    } else {
        $('#myModal_submit').modal('show');
    }

}

//Function to fetch and display help details related to course learning objective to program outcomes
function show_help() {
    $.ajax({
        url: base_url + 'curriculum/topic/topic_help',
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('help_content').innerHTML = msg;
        }
    });
}

// Topic Add Script Starts from here.
$(document).ready(function () {
    var cloneCntr = 2;
    $.validator.addMethod("numeric", function (value, element) {
        var regex = /^[0-9\s\.\:]+$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Field must contain only numbers.");

    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");


    $("#topic_add_form").validate({
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (label) {
            $(label).closest('.control-group').addClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.parent().hasClass("input-append")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        onkeyup: false,
        onblur: false,
        success: function (label) {
            $(label).closest('.control-group').removeClass('error');
        }
    });
    $('.topic_save').on('click', function (event) {
        $('#loading').show();
        $('#topic_add_form').validate();
		var flag = $('#topic_add_form').valid();
		if(flag == true){
			var values = $("#topic_add_form").serialize();
			var delivery_method = $('#delivery_method_1').val();
			$.ajax({type:"POST",
					url:base_url+'curriculum/topicadd/topic_add',
					data:values,					
					success:function(data){ 
							reset_topic_data(); 
							success_modal_save(data);
                                                        total_topics_number();
                                                        fetch_topics();
                                                        
					}
			});
		
		}
        $('#loading').hide();
    });
	$('#reset').on('click',function(){
	reset_topic_data();
		$('#delivery_method_1').find('option:selected').prop('selected', false);$('#delivery_method_1').multiselect('rebuild');
		$("#delivery_method_1").multiselect("clearSelection"); 
                $('#units_list option:selected').prop('selected',false);
	});
	function reset_topic_data(){
		$("#topic_add_form").trigger('reset');
		var validator = $('#topic_add_form').validate();
		validator.resetForm();	
		$('#update_topic').hide();$('.topic_save').show();
		$('#delivery_method_1').find('option:selected').prop('selected', false);$('#delivery_method_1').multiselect('rebuild');
		$("#delivery_method_1").multiselect("clearSelection"); 
	}
	$('#update_topic').hide();
        
	$('.edit_topics').live('click',function(){
		$('html,body').animate({ scrollTop: $(".tab1").offset().top},'slow');
		$('#submit').hide(); $('#update_topic').show();
                var topic_title = $(this).attr('data-topic_title');
                var t_unit_id = $(this).attr('data-t_unit_id');
                var sl_no = topic_title.match(/\d+/);
                var topic = new Array();
                if(sl_no){
                    topic = topic_title.split(/[\-\.\:,]+/);
                }else{
                    topic[0] = '';
                    topic[1] = topic_title;
                }
		$('#slno').val($.trim(topic[0]));
		$('#topic_title').val($.trim(topic[1]));
		$('#topic_content').val($(this).attr('data-content'));
		$('#topic_hours_1').val($(this).attr('data-topic_hrs'));
		$('#topic_id_data').val($(this).attr('data-delivery_method'));
                $('#units_list option[value="'+t_unit_id+'"').attr('selected','selected');
		//alert($(this).attr('data-delivery_method'));
		var  post_data = { 'topic_id' : $(this).attr('data-delivery_method')}
		$.ajax({type: "POST",
                url: base_url + 'curriculum/topic/fetch_delivery_methods',
                data: post_data,
                dataType: 'json',
                success: function(msg){
					  var topic_size = msg.length; 
					if(msg != "NO Data" ){					
					    for (var tp = 0; tp < topic_size; tp++) {							
							$("#delivery_method_1 option[value='" + msg[tp]['delivery_mtd_id'] + "']").attr("selected", "selected");
						}
						$("#delivery_method_1").multiselect('rebuild');
					}else{ $("#delivery_method_1").multiselect("clearSelection");} 
				} 
            });		
	});
	
	$('#update_topic').on('click' , function(){
		 $('#loading').show();

        $('#topic_add_form').validate();
		var flag = $('#topic_add_form').valid();
		if(flag == true){
			var values = $("#topic_add_form").serialize();
			$.ajax({type:"POST",
					url:base_url+'curriculum/topicadd/topic_update',
					data:values,					
					success:function(data){ 
							reset_topic_data(); 
							success_modal_update(data);
                                                        total_topics_number();
                                                        fetch_topics();
					}
			});
		
		}
        $('#loading').hide();
	});
	
    var blk = '';
    $(".add_clo").click(function () {
        $('#loading').show();
        var topic_block1 = '<div class="" id="add_me' + cloneCntr + '"><div class="control-group input-append">';
        var topic_block2 = '<br><div class="row-fluid" id="topic"><div class="span12 add_me"><div class="row-fluid"><div class="span6"><div class="control-group"><label class="control-label" for="topictitle">' + entity_topic + ' Title <font color="red">*</font></label><div class="controls"><input type="text"  name="topictitle_' + cloneCntr + '" value="" class="required topic_ttle"/></div></div></div><div class="span6"><div class="control-group"><label class="control-label" id="topic_hours" for="pgm_title">Duration in Hours <font color="red">*</font></label><div class="controls"><input type="text"  name="topic_hours_' + cloneCntr + '"  value="" maxlength="2" class="required numeric topic_hrs span4"/><button id="clo_btn_' + cloneCntr + '" class="btn btn-danger delete_topic" type="button"><i class="icon-minus-sign icon-white"></i> Delete ' + entity_topic + '</button></div></div></div><div class="control-group"><label class="control-label" for="topic_content">' + entity_topic + ' Content<font color="red">*</font></label><div class="controls"><textarea name="topic_content_' + cloneCntr + '" rows="5" cols="20" type="text" class="required content" style="margin: 0px; width: 814px; height: 123px;"></textarea></div></div><div class="control-group"><p class="control-label" for="delivery_mtd_id">Delivery Method: </p><div class="controls"><select name="delivery_method_' + cloneCntr + '[]" multiple="multiple" class="dm_list_data">' + delivery_method_options + '</select></div></div></div></div></div>';
        /*  var topic_bloc3 = '<div class="controls control-group" ><label class="inline" for="topic_conten">&nbsp;&nbsp;&nbsp;&nbsp;'+ entity_topic +' Contents<font color="red">*</font>:</label><textarea name="topic_conten[1]" id="topic_conten" rows="5" cols="20" type="text" style="margin: 0px; width: 552px; height: 122px;"></textarea></div><div class="control-group"><p class="control-label" for="usergroup_id">User Group: <font color="red"> * </font></p></div>'; */
        var topic_block = topic_block2;
        var newclo = $(topic_block);
        $('#topic_insert').append(newclo);
        po_counter.push(cloneCntr);
        $('#counter').val(po_counter);
        cloneCntr++;
        $('.dm_list_data').multiselect({
            includeSelectAllOption: true,
            buttonWidth: '170px',
            nonSelectedText: 'Select Delivery Method',
        });
        $('#loading').hide();
    });

    $('.delete_topic').live('click', function () {
        $(this).parent().parent().parent().parent().parent().remove();
        var replaced_id = $(this).attr('id').replace('clo_btn_', '');

        var po_counter_index = $.inArray(parseInt(replaced_id), po_counter);
        po_counter.splice(po_counter_index, 1);
        $('#counter').val(po_counter);
        return false;
    });
});

// Topic Add Scripts Ends Here.

// Topic Edit Scripts Starts Here.


$(document).ready(function () {
    var counter = 2;
    $("#addButton").click(function () {
        var newTextBoxDiv = $(document.createElement('div'))
                .attr("id", 'TextBoxDiv' + counter);
        var newTextBoxDiv1 = $(document.createElement('div'))
                .attr("id", 'TextBoxDivision' + counter);
        newTextBoxDiv.after().html('<label class="inline">' + entity_topic + ' Title<font color="red">*</font>' + ' ' + '<input type="text" name="topictitle[]" id="topictitle' + counter + '" value="" ></label>');
        newTextBoxDiv1.after().html('<label class="inline">&nbsp;&nbsp;&nbsp;Duration<font color="red">*</font>' + ' ' + '<input type="text" name="topic_hours[]" id="topic_hours' + counter + '" value="" ></label>');
        counter++;
    });

    $("#removeButton").click(function () {
        if (counter == 1) {
            alert("No more textbox to remove");
            return false;
        }

        counter--;

        $("#TextBoxDiv" + counter).remove();
        $("#TextBoxDivision" + counter).remove();

    });

    $("#getButtonValue").click(function () {
        var msg = '';
        for (i = 1; i < counter; i++) {
            msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
        }
    });
});



// Course Readiness to Publish

function course_readiness_to_publish() {

    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;

    if (crclm_val && term_val && course_val) {

        var post_data = {
            'crclm_id': crclm_val,
            'crs_id': course_val
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/topic/check_course_readiness' + '/' + course_val,
            data: post_data,
            dataType: "JSON",
            success: function (msg) {
                if (parseInt(msg.state_id) == 4) {
                    $('#course_owner_name').html(msg.course_owner_name);
                    $('#curriculum_name').html(msg.curriculum_name);
                    $('#myModal_submit_for_publish').modal('show');
                } else {
                    $('#course_owner').html(msg.course_owner_name);
                    $('#curriculum').html(msg.curriculum_name);
                    $('#myModal_submit_for_publish_failure').modal('show');
                }
            }
        });

    } else {
        $('#myModal_submit').modal('show');
    }
}

//Finalized Course Publish Course
function finalized_publish_course() {
    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;
    $('#loading').show();
    var post_data = {
        'crs_id': course_val
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/finalized_publish_course' + '/' + course_val,
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            location.reload();
        }
    });
}


var date = new Date();
date.setDate(date.getDate() - 1);

$("#book_publication_year_1").datepicker({
    format: " yyyy",
    viewMode: "years",
    minViewMode: "years"

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#btn').click(function () {
    $(document).ready(function () {
        $("#book_publication_year_1").datepicker().focus();

    });
});


//Add book details

$('#submit_to_add_books').on('click', function () {
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();

    if (crclm_id && term_id && crs_id) {

        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/topic/crs_unitization_topic_count',
            data: post_data,
            datatype: "JSON",
            success: function (count_msg) {
                var count = parseInt($.trim(count_msg));
                if (count != 0) {
                    window.location = base_url + 'curriculum/topic/add_books_evaluation/' + crclm_id + '/' + term_id + '/' + crs_id;
                } else {
                    $('#myModal_topic_Error').modal('show');
                }
                console.log(parseInt($.trim(count_msg)));
            }
        });

        //
    } else {
        $('#myModalError').modal('show');
    }
});

// multiselect function
$(function () {

    $('.dm_list_data').multiselect({
        includeSelectAllOption: true,
            buttonWidth: '170px',
            nonSelectedText: 'Select Delivery Method',
    });

});
// Added by bhagya S S		
function sortDropDownListByText() {
    // Loop for each select element on the page.
    $("select").each(function () {

        // Keep track of the selected option.
        var selectedValue = $(this).val();

        // Sort all the options by text. I could easily sort these by val.
        $(this).html($("option", $(this)).sort(function (a, b) {
            return a.text == b.text ? 0 : a.text > b.text ? -1 : 1
        }));

        // Select one option.
        $(this).val(selectedValue);
    });
}
//sortDropDownListByText();
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
$.validator.addMethod("numeric_1", function (value, element) {
    var regex = /^[0-9\s\.]+$/; //this is for numeric... you can do any regular expression you like...
    return this.optional(element) || regex.test(value);
}, "Field must contain only numbers.");
$('#topic_edit_form').validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (label) {
        $(label).closest('.control-group').addClass('error');
    },
    errorPlacement: function (error, element) {
        if (element.parent().hasClass("input-append")) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
    onkeyup: false,
    onblur: false,
    success: function (label) {
        $(label).closest('.control-group').removeClass('error');
    }
});
