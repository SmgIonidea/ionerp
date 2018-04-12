
//Organization Edit Page

var base_url = $('#get_base_url').val();
var cloneCntr = 2;
/* var mission_counter = new Array();
 mission_counter.push(1); */
var mission_delete_counter = new Array();
//mission_counter.push(1);
//Tiny MCE script

var count_array = new Array(); //global declaration
$(document).ready(function () {
    var count_val = $('#mission_element_counter').val();

    count_array = count_val.split(',');
    for (var i = 1; i <= count_array.length; i++) {
        mission_delete_counter.push(i);
    }

});
//Tiny MCE script

tinymce.init({
    //selector: "textarea",
    mode: "specific_textareas",
    editor_selector: "myTextEditor",
    plugins: [
        "advlist autolink lists autoresize charmap preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime table contextmenu paste moxiemanager",
		"spellchecker"
    ],
	browser_spellcheck: true,
	contextmenu: true,
    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
            //height : 300;
});

$("#form").validate({
    rules: {
        org_name: {
            required: true
        },
    },
    message: {
        org_name: "This field is required"
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

$(document).ready(function () {
    $('#update').click(function () {
        tinyMCE.triggerSave();
        var flag;
        flag = $('#form').valid();
        if (flag) {
            $.post(base_url + 'configuration/organisation/update_organisation_details',
                    $("form").serialize(),
                    function (result) {
                        if (result)
                            //successful message, on data save
                            $('.modal-body p').html('Data has been saved successfully.');
                        else
                            //unsuccessful message, while trying to save data
                            $('.modal-body p').html('Error updating data.');
                        //to display the pop up
                        $('#myModal').modal('show');
                    }, "html");
        }
    });

    $(".add_mission_element").click(function () {
        var mission_counter = $('#mission_counter').val();
        mission_counter++;
        var mission_element_block1 = '<div class="" id="add_me1' + mission_counter + '"><div class="control-group ">';
        var mission_element_block2 = '<div class="row-fluid"><div class="span12"><div class="control-group input-append" ><p>Mission Elements:</p><div class="controls" style=" margin-left:90px;"><textarea type="textarea" class="input-xxlarge org_me_text_size" cols="84" rows="2" style="margin: 0px; height: 60px;" name="mission_element_' + mission_counter + '" id ="mission_element_' + mission_counter + '"></textarea><button id="remove_field' + mission_counter + '" class="btn btn-danger delete_mission_element" type="button"><i class="icon-minus-sign icon-white"></i> Delete </button> </div></div></div></div>';
        var mission_element_block = mission_element_block2;
        var newMissionElement = $(mission_element_block);
        $('#mission_element_insert').append(newMissionElement);
        $('#mission_counter').val(mission_counter);
        mission_delete_counter.push(mission_counter);
        $('#mission_element_counter').val(mission_delete_counter);
        cloneCntr++;
        //tiny_init();
    });


    /*  $('.delete_mission_element').live('click', function() {
     // console.log('in delete');
     $(this).parent().parent().parent().parent().remove();
     var replaced_id = $(this).attr("id").match(/\d+/g);
     var mission_element_counter_index = $.inArray(parseInt(replaced_id),mission_counter);
     mission_counter.splice(mission_element_counter_index,1);
     $('#mission_element_counter').val(mission_counter);
     return false;
     }); */

    $(document).on('click', '.delete_mission_element', function () {

        // console.log('in delete');
        var ele_id = $(this).attr("id");
        //alert(ele_id)
        var replaced_id = ele_id.match(/\d+/g);
        //alert(replaced_id)
        //alert(mission_delete_counter)
        var mission_element_counter_index = $.inArray(parseInt(replaced_id), mission_delete_counter);
        //alert(mission_element_counter_index)
        mission_delete_counter.splice(mission_element_counter_index, 1);
        $('#mission_element_counter').val(mission_delete_counter);
        $('#' + ele_id).parent().parent().parent().remove();

        return false;
    });
    function tiny_init() {
        tinymce.init({
            selector: "textarea",
            //plugins: "paste",
            plugins: [
                "advlist autolink lists charmap preview ",
                "searchreplace visualblocks fullscreen",
                "table contextmenu paste moxiemanager"
            ],
            //paste_data_images: true,
            setup: function (ed) {
                ed.on('change', function (e) {
                    var id = $(this).attr('id');
                    var id_val = id.split("_");
                    $('#error_msg_' + id_val[2]).empty();
                });
            },
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
                    //height : 300;			
        });
    }
});

$('#close_btn').click(function () {
    location.reload();
});

//Organization edit page Scripts Ends Here.
