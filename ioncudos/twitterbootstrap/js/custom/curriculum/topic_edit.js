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



function GetSelectedValue()
{
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
    var show_topic_path = base_url + 'curriculum/topic/show_topic_new';
    var post_data = {
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
    };
    $.ajax(
            {type: "POST",
                url: show_topic_path,
                data: post_data,
                dataType: 'json',
                success: populate_table
            });
}

function populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
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
                    {"sTitle": "Add / Edit " + entity_tlo, "sClass": "center", "mData": "tlo_add"},
                    {"sTitle": "View " + entity_tlo, "sClass": "center", "mData": "view"},
                    {"sTitle": "Manage Lesson Schedule", "sClass": "center", "mData": "Lesson_Schedule"},
                    {"sTitle": entity_tlo + " to CO Mapping", "sClass": "center", "mData": "proceed"}
                    //	{"sTitle": entity_tlo+ " to CO Mapping Status", "sClass": "center", "mData": "tlo_status"}
                ], "aaData": msg["topic_data"],
                "sPaginationType": "bootstrap"});

    if (msg["topic_publish_flag"]["publish_flag"] == 0) {
        $('#submit_to_publish').attr("disabled", false);
    } else {
        $('#submit_to_publish').attr("disabled", true);
    }
}

$('#course').on('change', function () {
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
   // var unit_id = $('#units').val();
    var show_topic_path = base_url + 'curriculum/topic/unit_wise_show_topic_new';
    var post_data = {
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
        //'unit_id': unit_id,
    };
    $.ajax(
            {type: "POST",
                url: show_topic_path,
                data: post_data,
                dataType: 'json',
                success: function(msg){
                     if($.trim(msg.status) == 'fail'){
                         $('#tbl_div').hide();
                        var err_msg = 'Course CO to PO mapping Review is Pending. Please complete the CO to PO mapping process in order to Create '+entity_topic+' for the Course';
                        var note = '<center><b><font color="red">'+err_msg+'</font></b><center>';
                        $('#crs_status').empty();
                        $('#crs_status').html(note);
                        $('#crs_status').show();
                        $('#ad_topic').hide();
                        $('#ad_topic1').hide();
                        $('#submit_to_add_books').hide();
                        $('#submit_to_publish').hide();
                        $('.inline').hide();
                         
                     }else{
                         
                        $('#tbl_div').show();
                        $('#crs_status').empty();
                        $('#crs_status').hide();
                        if ($.cookie('remember_selected_value') != null) {
                            // set the option to selected that corresponds to what the cookie is set to
                            $('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                        }
                        $('#ad_topic').show();
                        $('#ad_topic1').show();
                        $('#submit_to_add_books').show();
                        $('#submit_to_publish').show();
                        $('.inline').show();
                        unit_wise_populate_table(msg);
                     }
                    
                }
            });
});

function unit_wise_populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {
                "aoColumns": [
                    {"sTitle": entity_topic + " Unit", "sClass": "", "mData": "topic_unit"},
                    {"sTitle": entity_topic + " Code", "sClass": "", "mData": "topic_code"},
                    {"sTitle": entity_topic + " Title", "sClass": "", "mData": "topic_title"},
                    {"sTitle": entity_topic + " Content", "sClass": "", "mData": "topic_content"},
                    {"sTitle": entity_topic + " Hours", "sClass": "", "mData": "topic_hrs"},
                    {"sTitle": entity_topic + " Delivery Methods", "sClass": "", "mData": "delivery_method"},
                    {"sTitle": "Edit", "sClass": "right", "mData": "topic_id"},
                    {"sTitle": "Delete", "sClass": "center", "mData": "topic_id1"},
                    {"sTitle": "Add / Edit " + entity_tlo, "sClass": "center", "mData": "tlo_add"},
                    {"sTitle": "View " + entity_tlo, "sClass": "center", "mData": "view"},
                    {"sTitle": "Manage Lesson Schedule", "sClass": "center", "mData": "Lesson_Schedule"},
                    {"sTitle": entity_tlo + " to CO Mapping", "sClass": "center", "mData": "proceed"}
                    //{"sTitle": entity_tlo + " to CO Mapping Status", "sClass": "center", "mData": "tlo_status"}
                ], "aaData": msg["topic_data"],
                "aaSorting": [[1,'asc']],
                "sPaginationType": "bootstrap"});
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
	"fnDrawCallback": function () {
	    $('.group').parent().css({'background-color': '#C7C5C5'});
	},
	"aoColumnDefs": [
	    {"sType": "natural", "aTargets": [2]}
	],
	"sPaginationType": "bootstrap"

    }).rowGrouping({iGroupingColumnIndex: 0,
	bHideGroupingColumn: true});

    if (msg["topic_publish_flag"]["publish_flag"] == 0) {
        $('#submit_to_publish').attr("disabled", false);
    } else {
        $('#submit_to_publish').attr("disabled", true);
    }
}


//call view function

$('#example').on('click', '.tlo_list', function (e) {
    var show_topic_path = base_url + 'curriculum/tlo_list/sh_edit';
    var id = $(this).attr('id');
    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;
    var crclm_title = $(this).attr('data-title');
    var post_data = {
        'crclm_id': crclm_val,
        'term_id': term_val,
        'course_id': course_val,
        'topic_id': id,
    }
    $.ajax({type: "POST",
        url: show_topic_path,
        data: post_data,
        dataType: 'json',
        //success: populate_table_view
        success: [populate_table_view,
            function (msg) {
                $('#crclm_title').html(crclm_title);
                $('#view_modal').modal('show');
            }]

    });
});


/*Function to populate tlo modal*/
function populate_table_view(msg) {

    $('#example_view').dataTable().fnDestroy();
    $('#example_view').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": entity_tlo + " Code", "mData": "tlo_code"},
                    {"sTitle": "Session Learning Outcomes", "mData": "tlo_statement"},
                    {"sTitle": "Bloom's Level", "mData": "bloom_level"},
                    {"sTitle": "Delivery Method", "mData": "delivery_mtd_name"},
                    {"sTitle": "Delivery Approach", "mData": "delivery_approach"},
                ], "aaData": msg["tlo_list"],
                //'sDom': 't',
                'sDom': '"top"i',
                "sPaginationType": "bootstrap"});
    if (msg["topic_state"]["state_id"] == 5 || msg["topic_state"]["state_id"] == 4 || msg["topic_state"]["state_id"] == 2) {
        var boolean_value = true;
    } else {
        var boolean_value = false;
    }
}

//Function 

$('#example').on('click', '.publish', function (e) {

    e.preventDefault();
    var curriculum_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var topic_id = $(this).attr('data-topic_id');

    var course_id = $(this).attr('data-course_id');
    var bool_val = $(this).attr('data-bool');
    if (bool_val == "active") {
        $("#curriculum_edit").val(curriculum_id);
        $("#term_edit").val(term_id);
        $("#topic_edit").val(topic_id);
        $("#course_edit").val(course_id);

        if (curriculum_id && term_id && topic_id && course_id) {
            var post_data = {
                'crclm_id': curriculum_id,
                'crs_id': course_id,
                'topic_id': topic_id
            }
            $.ajax({type: "GET",
                url: base_url + 'curriculum/clo/fetch_course_owner' + '/' + curriculum_id + '/' + course_id,
                data: post_data,
                dataType: "JSON",
                processData: false,
                success: function (result) {
                    $('#course_owner_name_new').html(result.course_owner_name);
                    $('#crclm_name').html(result.crclm_name);
                }
            });
            $('#myModal4').modal('show');
        }
    } else {

        $('#topic_id1').val(topic_id);
        $('#Warning_modal').modal('show');
    }
});
$('#proceed_tlo_co').on('click', function () {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var topic_id = document.getElementById('topic_edit').value;
    var course_id = document.getElementById('course').value;
    var topic_id = $('#topic_id1').val();

    var post_data = {'topic_id': topic_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/search_topics',
        data: post_data,
        dataType: "JSON",
        success: function (result) {
            if (result['count_ls'].length != 0 || result['topic_state']['state_id'] > 1) {
                if (result['topic_state']['state_id'] == 1 || result['topic_state']['state_id'] == 2) {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/topic/proceed_tlo_co',
                        data: post_data,
                        dataType: "JSON",
                        success: function (result) {}});
                }
                $(location).attr('href', './tlo_clo_map/map_tlo_clo/' + curriculum_id + '/' + term_id + '/' + course_id + '/' + topic_id + '/' + 0);
            } else {
                $('#double_confirm').modal('show');
            }
        }
    });
});

$('#proceed_tlo_co_confirm').on('click', function () {
    var topic_id = $('#topic_id1').val();
    var curriculum_id = document.getElementById('curriculum_edit').value;
    var term_id = document.getElementById('term_edit').value;
    var course_id = document.getElementById('course_edit').value;
    var post_data = {'topic_id': topic_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/proceed_tlo_co',
        data: post_data,
        dataType: "JSON",
        success: function (result) {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/topic/search_topics',
                data: post_data,
                dataType: "JSON",
                success: function (result) {
                    if (result['count_ls'].length != 0 || result['topic_state']['state_id'] > 1) {
                        $(location).attr('href', './tlo_clo_map/map_tlo_clo/' + curriculum_id + '/' + term_id + '/' + course_id + '/' + topic_id + '/' + 0);
                    }
                }
            });
        }
    });
});
function publish_proceed() {
    var curriculum_id = document.getElementById('curriculum_edit').value;
    var term_id = document.getElementById('term_edit').value;
    var topic_id = document.getElementById('topic_edit').value;
    var course_id = document.getElementById('course_edit').value;
    var publish_path = base_url + 'curriculum/tlo/publish_details';

    var post_data = {
        'crclm_id': curriculum_id,
        'topic_id': topic_id,
        'course_id': course_id,
        'term_id': term_id,
    }

    $.ajax({type: "POST",
        url: publish_path,
        data: post_data,
        success: function (msg) {
            $(location).attr('href', './tlo_clo_map/map_tlo_clo/' + curriculum_id + '/' + term_id + '/' + course_id + '/' + topic_id + '/' + 0);
        }
    });
}



