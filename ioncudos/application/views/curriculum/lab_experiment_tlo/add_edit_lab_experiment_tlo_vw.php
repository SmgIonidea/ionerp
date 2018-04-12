<?php
/**
 * Description          :	Lab experiment TLO controller model and view

 * Created		:	March 25th, 2015

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 *   05-01-2016		Shayista Mulla			Added loading image.
 *   15-01-2016		Bhagyalaxmi S S			Added bloom's level, delivery method and delivery approch 
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <!--content goes here-->	
                <div class="bs-docs-example" >
                    <!--content goes here-->
                    <div class="row-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Add Lab Experiment <?php echo $this->lang->line('entity_tlo_singular'); ?>
                            </div>
                        </div> 
                    </div> 
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span4">
                                Curriculum : <span style="color:blue;font-size:12px;"><?php echo $crclm_name; ?></span>
                            </div>
                            <div class="span4">
                                Term : <span style="color:blue;font-size:12px;"><?php echo $term; ?></span>
                            </div>
                            <div class="span4">
                                Course : <span style="color:blue;font-size:12px;"><?php echo $course; ?> (<?php echo $course_code; ?>)</span>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span4">
                                Category : <span style="color:blue;font-size:12px;"><?php echo $category; ?></span>
                            </div>
                            <div class="span4">
                                Experiment : <span style="color:blue;font-size:12px;"><?php echo $expt; ?></span>
                            </div>
                        </div>
                    </div><br />

                    <div id="list_tlo">
                        <table class="table table-bordered table-hover" style="font-size:12px;" id="example_view" >
                            <thead>
                                <tr>
                                    <th style = "width : 60px;"><?php echo $this->lang->line('entity_tlo'); ?> Code</th>
                                    <th style = "width : 210px;">Topic Learning Outcome</th>
                                    <th style = "width : 200px;">Bloom's Level</th>
                                    <th style = "width : 100px;">Delivery Method</th>
                                    <th style = "width : 100px;">Delivery Approach</th>
                                    <th style = "width : 20px;">Edit</th>
                                    <th style = "width : 30px;">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- add_tlo--><br /><br/><br/>									
                    <form  class="form-horizontal" method="POST" id="lab_experiment_tlo_add_form" name="lab_experiment_tlo_add_form">
                    <input type="hidden" id ="clo_bl_flag" name="clo_bl_flag" value="<?php echo $clo_bl_flag[0]; ?>"/>
                        <?php
                        echo form_input($crclm_id);
                        echo form_input($term_id);
                        echo form_input($crs_id);
                        echo form_input($category_id);
                        echo form_input($topic_id);
                        ?>
                        <div class="navbar-inner-custom">
                            Add <?php echo $this->lang->line('entity_tlo_singular'); ?>
                        </div>                     
                        
                        <div id="add_tlo">
                            <div id="remove" class="tlo">
                                <div id="tlo_statement1" data-spy="scroll" class="bs-docs-example" style="width:auto; height:520px; padding-top:30px;">
                                    <div class="span12">											
                                        <div class="row-fluid">
                                            
                                            <div class="span6">
                                                <div class="control-group">
                                                    <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo_singular'); ?>) Statement: <font color='red'>*</font>														
                                                    <div class="">
                                                        <br/>
                                                        <label id="tiny_mce" style="color:red"></label>
                                                        <?php echo form_textarea($tlo_name); ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="delivery_methods">Delivery  Method: <font color='red'></font></label>
                                                    <div class="controls">
                                                        <select id="delivery_methods1" name="delivery_methods1" class="input-large">
                                                            <option value="" selected>Select Delivery Method</option>
                                                            <?php foreach ($delivery_method as $method) { ?>
                                                                <option value="<?php echo $method['crclm_dm_id']; ?> " title = "<?php echo $method['delivery_mtd_name']; ?>"><?php echo $method['delivery_mtd_name']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="delivery_approach">Delivery Approach: </label>
                                                    <div class="controls">
                                                        <textarea class="" style="width:100%; height:125px;" cols="40" rows="4" name="delivery_approach1" id="delivery_approach"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="span12">
                                        <table style="width:90%">
                                            <tr>
                                                <?php
                                                $i = 0;
                                                foreach ($bloom_domain as $domain) {
                                                    if ($bld_active[$i] == 1 && $domain['status'] == 1) {
                                                        ?>
                                                        <td>
                                                            <p style="font-size:12px;">
                                                                <?php echo $domain['bld_name']; ?> : <?php if ($clo_bl_flag[0] == 1) { ?> <font color="red"> * &nbsp;&nbsp;</font><?php } ?>
                                                                <select class="example-getting-started required" name="bloom_level_<?php echo ($i + 1); ?>[]" id="bloom_level_<?php echo ($i + 1); ?>" multiple="multiple"  ><?php echo $bloom_level_options[$i]; ?>
                                                            </p>
                                                             <input type="hidden" id="bloom_filed_id" name = "bloom_filed_id[]" value="bloom_level_<?php echo ($i + 1); ?>"/>
                                                             </br><span class= "error_placeholder_bl" id="error_placeholder_bl<?php echo ($i + 1); ?>"> </span>
                                                        </td>
                                                        <?php
                                                    }
                                                    $i++;
                                                    echo '<input type="hidden" name="bld_id_' . $i . '" id="bld_id_' . $i . '" value="' . $domain['bld_id'] . '">';
                                                }
                                                ?>
                                            </tr>
                                            <tr style="font-size:12px;">
                                                <td>
                                                    <div class="span7" id="bloom_level_1_actionverbs"></div>
                                                </td>
                                                <td>
                                                    <div class="span7" id="bloom_level_2_actionverbs"></div></td>
                                                <td>
                                                    <div class="span7" id="bloom_level_3_actionverbs"></div></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div><!-- tlo_statement-->
                            </div><!-- remove-->
                        </div>
                        <div class='pull-right'>
                            <input type='hidden' name='lab_tlo_counter' id='lab_tlo_counter' value='1'>
                            <input type='hidden' name='lab_expt_tlo_counter' id='lab_expt_tlo_counter' value='1'>                           
                        </div><br /><br />
                        <div class="control-group pull-right">
                            <button  class="lab_expt_tlo_save btn btn-primary" id="lab_expt_tlo_save" ><i class="icon-file icon-white"></i><span></span> Save </button>										
                            <a class="btn btn-danger" href="<?php echo base_url('curriculum/lab_experiment/lab_experiment'); ?>"><i class="icon-remove icon-white"></i><span></span> Cancel </a>
                        </div>
                        <br />
                        <!-- To delete tlo div-->
                        <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class=" navbar-inner-custom">
                                    Delete Confirmation <?php echo $this->lang->line('entity_tlo_singular'); ?>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Selected Lab Experiment <?php echo $this->lang->line('entity_tlo_singular'); ?> ?.</p>
                                <input type="hidden" value="" name="deleteId" id="deleteId"/>
                            </div>
                            <div class="modal-footer">
                                <button class="delete_lab_expt_tlo btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <div id="editLabExpermentTLO" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;width:900px;left:500px;" data-controls-modal="" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class=" navbar-inner-custom">
                                    Edit Lab Experiment <?php echo $this->lang->line('entity_tlo_singular'); ?>
                                    <input type="hidden" value="" name="edit_tlo_id" id="edit_tlo_id"/>
                                </div>
                            </div>
                            <div class="modal-body">
                                <table id="edit_tlo">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="span2">
                                                    <label class="control-label span8" ><?php echo $this->lang->line('entity_tlo_singular'); ?> Code: <font color='red'></font></label>
                                                    <div class="span1">
                                                        <input type="text" id="tlo_code_edit" class="input-small"/>
                                                    </div>
                                                    <!--edit_tlo_statement-->
                                                </div>
                                                <div class="span10">
                                                    <label class="control-label" ><?php echo $this->lang->line('entity_tlo_singular'); ?> Statement: <font color='red'>*</font></label>
                                                    <div class="controls">
                                                        <label id="tiny_mce_edit" style="color:red"></label>
                                                        <textarea class="tlo" id="edit_tlo_statement"></textarea>	
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="span12">
                                    <table style="width:100%;"><tr>
                                            <?php
                                            $i = 0;
                                            foreach ($bloom_domain as $domain) {
                                                if ($bld_active[$i] == 1 && $domain['status'] == 1) {
                                                    ?>
                                                    <td>
                                                        <p style='font-size:12px;'>
                                                            <?php echo $domain['bld_name']; ?>:<?php if ($clo_bl_flag[0] == 1) { ?> <font color="red"> * &nbsp;&nbsp;</font><?php } ?>
                                                            <select class="example-getting-started required" name="edit_bloom_level_<?php echo ($i + 1); ?>[]" id="edit_bloom_level_<?php echo ($i + 1); ?>" multiple="multiple"  ><?php echo $bloom_level_options[$i]; ?>
                                                        </p>
                                                        <input type="hidden" id="bloom_filed_edit_id" name = "bloom_filed_edit_id[]" value="edit_bloom_level_<?php echo ($i + 1); ?>"/>
                                                         </br><span class= "error_placeholder_edit_bl" id="error_placeholder_edit_bl<?php echo ($i + 1); ?>"> </span>
                                                    </td>
                                                    <?php
                                                }
                                                $i++;
                                                echo '<input type="hidden" name="bld_id_' . $i . '" id="bld_id_' . $i . '" value="' . $domain['bld_id'] . '">';
                                            }
                                            ?>
                                        </tr>
                                        <tr style="font-size:12px;">
                                            <td>
                                                <div class="span7" id="edit_bloom_level_1_actionverbs"></div>
                                            </td>
                                            <td>
                                                <div class="span7" id="edit_bloom_level_2_actionverbs"></div></td>
                                            <td>
                                                <div class="span7" id="edit_bloom_level_3_actionverbs"></div></td>
                                        </tr>
                                    </table>
                                    <div class="control-group span6">                                   
                                        Delivery Method: <font color='red'></font>        
                                        <select id="dlm" name="dlm" class="input-large  fetch_action_verbs">
                                            <option value="" selected>Select Delivery Method</option>
                                            <?php foreach ($delivery_method as $method) { ?>
                                                <option value="<?php echo $method['crclm_dm_id']; ?>" title = "<?php echo $method['delivery_mtd_name']; ?>"><?php echo $method['delivery_mtd_name']; ?> </option>
                                            <?php } ?>
                                        </select>       
                                    </div>
                                    <div class="control-group span6">                                      
                                        <label class="span4">Delivery Approach: <font color='red'></font></label>

                                        <textarea class="span8" style="width:60%; height:80px;"rows="2" name="delivery_approach_edit" id="delivery_approach_edit"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="edit_lab_expt_tlo_confirm btn btn-primary"  id="edit_lab_expt_tlo_confirm" ><i class="icon-file icon-white"></i> Update</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>
                        <!-- To delete tlo-->
                        <div id="deleteLabExpermentTLO" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class=" navbar-inner-custom">
                                    Delete Confirmation
                                    <input type="hidden" value="" name="delete_tlo_id" id="delete_tlo_id"/>
                                </div>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to Delete?
                            </div>
                            <div class="modal-footer">
                                <button class="delete_lab_expt_tlo_confirm btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>

    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/lab_experiment.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script>
    var entity_tlo_singular = "<?php echo $this->lang->line('entity_tlo_singular'); ?>";
    var entity_tlo_full = "<?php echo $this->lang->line('entity_tlo_full'); ?>";
    $('#bloom_level_1').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#bloom_level_1 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#bloom_level_1 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#bloom_level_1').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#bloom_level_1').siblings('.multiselect-container');
                $('#bloom_level_1 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#bloom_level_1 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#bloom_level_1_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
    $('#bloom_level_2').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#bloom_level_2 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#bloom_level_2 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                $('#bloom_level_2 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#bloom_level_2 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#bloom_level_2_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
    $('#bloom_level_3').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#bloom_level_3 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#bloom_level_3 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#bloom_level_3').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#bloom_level_3').siblings('.multiselect-container');
                $('#bloom_level_3 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#bloom_level_3 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#bloom_level_3_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
    $('#delivery_method_1').multiselect({
        nSelectedText: 'selected',
        nonSelectedText: 'Select Delivery Method'
    });
    $('#edit_bloom_level_1').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#edit_bloom_level_1 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#edit_bloom_level_1 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#edit_bloom_level_1').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#edit_bloom_level_1').siblings('.multiselect-container');
                $('#bloom_level_1 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#edit_bloom_level_1 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#edit_bloom_level_1_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
    $('#edit_bloom_level_2').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#edit_bloom_level_2 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#edit_bloom_level_2 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#edit_bloom_level_2').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                $('#edit_bloom_level_2 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#edit_bloom_level_2 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#edit_bloom_level_2_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
    $('#edit_bloom_level_3').multiselect({
        onChange: function (option, checked) {
            var selectedOptions = $('#edit_bloom_level_3 option:selected');
            if (selectedOptions.length >= 4) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#edit_bloom_level_3 option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#edit_bloom_level_3').siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            } else {
                // Enable all checkboxes.
                var dropdown = $('#edit_bloom_level_3').siblings('.multiselect-container');
                $('#edit_bloom_level_3 option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
            var selections = [];
            var action_verb_data = [];
            $("#edit_bloom_level_3 option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = '';
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
            });
            var action_verb = action_verb_data.join("<b></b>");
            $('#edit_bloom_level_3_actionverbs').html(action_verb.toString());

        },
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });
</script>

<!-- End of file add_lab_experiment_vw.php 
                        Location: .curriculum/lab_experiment/add_lab_experiment_vw.php -->
