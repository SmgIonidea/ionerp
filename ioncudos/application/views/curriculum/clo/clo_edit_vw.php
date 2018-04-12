<?php
/**
 * Description	:	Course Learning Outcome grid provides the list of course learning
  Outcome statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:-
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 08-05-2015		Abhinay B Angadi		UI and Bug fixes done for Bloom's Level & Delivery methods
 * 28-12-2015		Bhagyalaxmi S S			Added DataTable grid the edit page placed in modal
  ---------------------------------------------------------------------------------------------- */
?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="" style=" background-color: white;">                     
                    <!--content goes here-->	
                    </head>
                    <form name="clo_edit_form" id="clo_edit_form" class="form-horizontal form-inline" method="POST">      
                        <input type="hidden" id ="clo_bl_flag" name="clo_bl_flag" value="<?php echo $clo_bl_flag; ?>"/>
                        <div class="row-fluid">				
                            <div class="span4">
                                <label><b> Curriculum : </b> </label>                              
                                <?php echo $curriculum[0]['crclm_name']; ?>
                            </div>
                            <div class="span3">
                                <label><b> Term : </b> </label>
                                <?php echo $term[0]['term_name']; ?>
                            </div>
                            <div class="span5">
                                <label><b> Course : </b></label>
                                <?php echo $course[0]['crs_title']; ?> (<?php echo $course[0]['crs_code']; ?>)
                            </div>
                        </div><br>
                        <div class="control-group">
                            <label class="control-label" for="delivery_method_id"> CO Code: <font color="red">  </font></label>
                            <div class="controls">
                                <?php echo form_input($clo_code); ?><font color="red"><span id="co_exist"></span></font>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="delivery_method_id"> Course Outcome (CO) Statement: <font color="red"> * </font></label>
                            <div class="controls">
                                <?php echo form_textarea($clo_statement); ?><?php echo form_input($clo_id); ?><?php echo form_input($course_id); ?>
                                <br/> <span id='char_span_support1' class='margin-left5'>0 of 2000. </span> 
                            </div>
                        </div>

                        <?php
                        $i = 0;
                        foreach ($bloom_domain as $domain) {
                            if ($bld_active[$i] == 1 && $domain['status'] == 1) {
                                ?>
                                <div class="control-group">
                                    <label class="control-label" for="delivery_mtd_description"> <?php echo $domain['bld_name']; ?>: <?php if ($clo_bl_flag == 1) { ?> <font color="red"> * &nbsp;&nbsp;</font><?php } ?></label>
                                    <div class="controls">
                                        <div class="span12">
                                            <div class="span4">
                                                <?php echo form_dropdown_custom_new('bloom_level_edit_' . ($i + 1) . '[]', $bloom_level_data[$i], $mapped_bloom_level_data[$i], 'class="example-getting-started" required multiple="multiple" id="bloom_level_edit_' . ($i + 1) . '"', $bloom_level_data_title[$i], $mapped_bloom_level_data_title[$i]); ?>														
                                            </div>

                                            <div class="span8">
                                                <div style="margin-left:3%;" class="selected_bloom_<?php echo ($i + 1) ?>">
                                                    <?php
                                                    if ($mapped_bloom_level_data_title[$i]) {
                                                        foreach ($mapped_bloom_level_data_title[$i] as $mapped_bloom_level_title) {
                                                            echo $mapped_bloom_level_title . "<br>";
                                                        }
                                                    }
                                                    ?>							
                                                </div>
                                                <input type="hidden" id="bloom_filed_edit_id" name = "bloom_filed_edit_id[]" value="bloom_level_edit_<?php echo ($i + 1); ?>"/>												
                                                <div style="margin-left:3%;" class="bloom_level_edit_actionverbs_<?php echo ($i + 1) ?> span7" id="bloom_level_edit_actionverbs_<?php echo ($i + 1) ?>"></div>
                                            </div><span id="error_placeholder_edit_bl<?php echo ($i + 1); ?>"></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            $i++;
                            echo '<input type="hidden" name="bld_id_' . $i . '" id="bld_id_' . $i . '" value="' . $domain['bld_id'] . '">';
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="delivery_method"> Delivery Method : </label>
                            <div class="controls">
                                <div class="span8">
                                    <?php
                                    echo form_dropdown('delivery_method[]', $delivery_method_data, $mapped_delivery_method_data, 'class="delivery_method_class example-getting-started" multiple="multiple" id="delivery_method"');
                                    ?>
                                </div>										
                            </div>
                        </div><br/>
                        <div id="error_message" style="color:red"></div> 
                    </form>
                    <!--Do not place contents below this line-->	
            </section>
        </div>
    </div>
</div>
<script>

    $('#clo_edit_form').ready(function () {
        $('#bloom_level_edit_1').multiselect({
            maxHeight: 200,
            buttonWidth: 160,
            numberDisplayed: 5,
            nSelectedText: 'selected',
            nonSelectedText: 'Select Blooms Level',
            onChange: function (option, checked) {
                var selectedOptions = $('#bloom_level_edit_1 option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#bloom_level_edit_1 option').filter(function () {
                        return !$(this).is(':selected');
                    });

                    var dropdown = $('#bloom_level_edit_1').siblings('.multiselect-container');
                    nonSelectedOptions.each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                } else {
                    // Enable all checkboxes.
                    var dropdown = $('#bloom_level_edit_1').siblings('.multiselect-container');
                    $('#bloom_level_edit_1 option').each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
                var selections = [];
                var action_verb_data = [];
                $("#bloom_level_edit_1 option:selected").each(function () {
                    var bloom_level_id = $(this).val();
                    var bloom_level = $(this).text();
                    var action_verbs = '';
                    var action_verbs = $(this).attr('title');

                    selections.push(bloom_level_id);
                    action_verb_data.push('<b>' + bloom_level + ' - </b>' + action_verbs + ', ');

                });
                var action_verb = action_verb_data.join("<b></b><br/>");
                //$('#bloom_level_edit_actionverbs_1').html(action_verb.toString());
                //hide old bloom's level details
                $('.selected_bloom_1').hide();
            }
        });
        $('#bloom_level_edit_2').multiselect({
            maxHeight: 200,
            buttonWidth: 160,
            numberDisplayed: 5,
            nSelectedText: 'selected',
            nonSelectedText: 'Select Blooms Level',
            onChange: function (option, checked) {
                var selectedOptions = $('#bloom_level_edit_2 option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#bloom_level_edit_1 option').filter(function () {
                        return !$(this).is(':selected');
                    });

                    var dropdown = $('#bloom_level_edit_2').siblings('.multiselect-container');
                    nonSelectedOptions.each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                } else {
                    // Enable all checkboxes.
                    var dropdown = $('#bloom_level_edit_2').siblings('.multiselect-container');
                    $('#bloom_level_edit_2 option').each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
                var selections = [];
                var action_verb_data = [];
                $("#bloom_level_edit_2 option:selected").each(function () {
                    var bloom_level_id = $(this).val();
                    var bloom_level = $(this).text();
                    var action_verbs = '';
                    var action_verbs = $(this).attr('title');

                    selections.push(bloom_level_id);
                    action_verb_data.push('<b>' + bloom_level + ' - </b>' + action_verbs + ', ');

                });
                var action_verb = action_verb_data.join("<b></b><br/>");
                //$('#bloom_level_edit_actionverbs_2').html(action_verb.toString());
                //hide old bloom's level details
                $('.selected_bloom_2').hide();
            }
        });
        $('#bloom_level_edit_3').multiselect({
            maxHeight: 200,
            buttonWidth: 160,
            numberDisplayed: 5,
            nSelectedText: 'selected',
            nonSelectedText: 'Select Blooms Level',
            onChange: function (option, checked) {
                var selectedOptions = $('#bloom_level_edit_3 option:selected');
                if (selectedOptions.length >= 4) {
                    // Disable all other checkboxes.
                    var nonSelectedOptions = $('#bloom_level_edit_3 option').filter(function () {
                        return !$(this).is(':selected');
                    });

                    var dropdown = $('#bloom_level_edit_3').siblings('.multiselect-container');
                    nonSelectedOptions.each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', true);
                        input.parent('li').addClass('disabled');
                    });
                } else {
                    // Enable all checkboxes.
                    var dropdown = $('#bloom_level_edit_3').siblings('.multiselect-container');
                    $('#bloom_level_edit_3 option').each(function () {
                        var input = $('input[value="' + $(this).val() + '"]');
                        input.prop('disabled', false);
                        input.parent('li').addClass('disabled');
                    });
                }
                var selections = [];
                var action_verb_data = [];
                $("#bloom_level_edit_3 option:selected").each(function () {
                    var bloom_level_id = $(this).val();
                    var bloom_level = $(this).text();
                    var action_verbs = '';
                    var action_verbs = $(this).attr('title');

                    selections.push(bloom_level_id);
                    action_verb_data.push('<b>' + bloom_level + ' - </b>' + action_verbs + ', ');

                });
                var action_verb = action_verb_data.join("<b></b><br/>");
                //$('#bloom_level_edit_actionverbs_3').html(action_verb.toString());
                //hide old bloom's level details
                $('.selected_bloom_3').hide();
            }
        });
        $('#delivery_method').multiselect({
            buttonWidth: 160,
            nonSelectedText: 'Select Delivery Method'
        });
    });
    var length = $("#clo_statement").val().length;
    $("#char_span_support1").text(length + " of 2000 ")
</script>