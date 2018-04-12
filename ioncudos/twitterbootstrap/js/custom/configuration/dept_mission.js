
//Department Mission Vision

var base_url = $('#get_base_url').val();
var cloneCntr = 2;
var mission_delete_counter = new Array();


var count_array = new Array(); //global declaration
$(document).ready(function () {
    /*     var count_val = $('#mission_element_counter').val();
     
     count_array = count_val.split(',');
     for (var i = 1; i <= count_array.length; i++) {
     mission_delete_counter.push(i);
     } */

});


fetch_mission_data();
function fetch_mission_data() {
    var post_data = {'num': 1}
    $.ajax({type: "POST",
        url: base_url + 'configuration/dept_mission_vision/fetch_miision_elements',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            populate_table(msg);
        }
    });
}
function populate_table(msg) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
        "aoColumns": [
            {"sTitle": "Sl No.", "mData": "sl_no", "sType": "numeric"},
            {"sTitle": "Mission Elements", "mData": "mission"},
            {"sTitle": "Edit", "mData": "edit"},
            {"sTitle": "Delete", "mData": "delete"},
        ], "aaData": msg,
        "sPaginationType": "bootstrap",
    });
}

$(document).ready(function () {

    $('#update_dept_mission').on('click', function () {
        var v = $('#add_form_dept_vission').valid();
        if (v) {
            $.post(base_url + 'configuration/dept_mission_vision/update_dept_data',
                    $("#add_form_dept_vission").serialize(),
                    function (result) {

                        if (result) {
                            //successful message, on data save
                            $('#succ_note').html('Data has been saved successfully.');
                            $("#mission_element_1").val("");
                            fetch_mission_data();
                        } else
                            //unsuccessful message, while trying to save data
                            $('#succ_note').html('Error updating data.');
                        //to display the pop up
                        $('#myModal').modal('show');
                    }, "html");
        }

    });

    $('#update ').on('click', function () {
        var v = $('#add_form').valid();
        if (v) {
            $.post(base_url + 'configuration/dept_mission_vision/update_dept_details',
                    $("form").serialize(),
                    function (result) {

                        if (result) {
                            //successful message, on data save
                            $('.modal-body p').html('Data has been saved successfully.');
                            $("#mission_element_1").val("");
                            fetch_mission_data();
                        } else
                            //unsuccessful message, while trying to save data
                            $('.modal-body p').html('Error updating data.');
                        //to display the pop up
                        $('#myModal').modal('show');
                    }, "html");
        }

    });
    $('#update_mission_element').hide();
    function reset_mission() {
        $("#add_form").trigger('reset');
        var validator = $('#add_form').validate();
        validator.resetForm();
        $('#update').show();
        $('#update_mission_element').hide();
        return;
    }

    $('#update_mission_element').on('click', function () {
        // $('#update').click(function() {


        var v = $('#add_form').valid();
        if (v) {
            $.post(base_url + 'configuration/dept_mission_vision/update_mission_details',
                    $("form").serialize(),
                    function (result) {

                        if (result) {
                            //successful message, on data save
                            $('#succ_note').html('Data has been updated successfully.');
                            $("#mission_element_1").val("");
                            reset_mission();
                            fetch_mission_data();
                        } else
                            //unsuccessful message, while trying to save data
                            $('#succ_note').html('Error updating data.');
                        //to display the pop up
                        $('#myModal').modal('show');
                    }, "html");
        }

    });

    $('#example').on('click', '.delete_mission', function () {
        $('#mission_id').val($(this).attr('data-me_id'));
        var mission_id = $('#mission_id').val();
        var dept_id = $('#dept_id').val();
        var post_data = {'mission_id': mission_id, 'dept_id': dept_id}
        $.ajax({type: "POST",
            url: base_url + 'configuration/dept_mission_vision/count_miision_elements',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                if (msg == "cant delete") {
                    $('#myModal_cant_delete').modal('show');
                } else {
                    $('#delete_myModal').modal('show');
                }
                //fetch_mission_data();
            }
        });

    });

    $('#delete_mission').on('click', function () {
        var mission_id = $('#mission_id').val();
        var dept_id = $('#dept_id').val();
        var post_data = {'mission_id': mission_id, 'dept_id': dept_id}
        $.ajax({type: "POST",
            url: base_url + 'configuration/dept_mission_vision/delete_miision_elements',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                //if(msg == "cant delete"){ $('#myModal_cant_delete').modal('show');}
                fetch_mission_data();
            }
        });
    });

    $('.edit_mission').live('click', function () {
        $('html,body').animate({scrollTop: $(".tab1").offset().top}, 'slow');
        $('#update').hide();
        $('#update_mission_element').show();
        $('#mission_id').val($(this).attr('data-me_id'));
        $('#mission_element_1').val($(this).attr('data-mission'));

    });

    $(".add_mission_element").click(function () {
        var mission_counter = $('#mission_counter').val();

        mission_counter++;
        var mission_element_block1 = '<div class="add_me1">';
        var mission_element_block2 = '<div class="control-group"><div class="controls input-append"><div class="span11"><textarea type="textarea" class="required" cols=100 rows=2 style="width: 93%; height: 45px;" name="mission_element_' + mission_counter + '" id ="mission_element_' + mission_counter + '"></textarea></div><div class="span1"><button id="remove_field' + mission_counter + '" class="btn btn-danger delete_mission_element" type="button"><i class="icon-minus-sign icon-white"></i> Delete </button></div></div></div></div>'

        var mission_element_block = mission_element_block2;
        var newMissionElement = $(mission_element_block);
        $('#mission_element_insert').append(newMissionElement);
        $('#mission_counter').val(mission_counter);
        mission_delete_counter.push(mission_counter);
        $('#mission_element_counter').val(mission_delete_counter);

        cloneCntr++;
        //tiny_init();
    });

    $(document).on('click', '.delete_mission_element', function () {

        // console.log('in delete');
        var ele_id = $(this).attr("id");
        var replaced_id = ele_id.match(/\d+/g);
        var mission_element_counter_index = $.inArray(parseInt(replaced_id), mission_delete_counter);
        mission_delete_counter.splice(mission_element_counter_index, 1);
        $('#mission_element_counter').val(mission_delete_counter);
        $('#' + ele_id).parent().parent().remove();

        return false;
    });

});

if ($.cookie('remember_dept') !== null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_dept_mission_vision();
}

/* Function is used to fetch dept mission vision for admin.
 * @param-
 * @retuns - the view of department mission vision details.
 */
function select_dept_mission_vision()
{
    $.cookie('remember_dept', $('#dept option:selected').val(), {expires: 90, path: '/'});
    var base_url = $('#get_base_url').val();
    var data_val1 = document.getElementById('dept').value;

    var post_data = {
        'dept': data_val1,
    }

    $.ajax({type: "POST",
        url: base_url + 'configuration/dept_mission_vision/fetch_mission_details',
        data: post_data,
        success: function (msg) {
            document.getElementById('dept_mission_vw_id').innerHTML = msg;
        }
    });
}

//Department mission vision  Scripts Ends Here.
