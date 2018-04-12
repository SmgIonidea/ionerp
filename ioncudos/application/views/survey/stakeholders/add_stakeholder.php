<?php ?>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                <div class="error">
                    <?php
                    if (validation_errors()) {
                        echo validation_errors();
                    } else if (@$errorMsg) {
                        echo $errorMsg;
                    }
                    ?>
                </div>
                <?php
                echo form_open('survey/stakeholders/add_stakeholder', array('name' => 'add_stakeholder_form', 'id' => 'add_stakeholder_form', 'method' => 'post', 'class' => 'form-horizontal'));
                ?>                

                <div class="control-group">
                    <?php echo form_label('Stakeholder Group:<font color="red"> * </font>', 'stakeholder_group_type', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <select class="input" id="stakeholder_group_type" name="stakeholder_group_type">
                            <option std_grp="" value="0">Select stakeholder group</option>
                            <?php
                            foreach ($list as $key => $cols) {
                                if ($stakeholder_group_id == $key) {
                                    echo "<option value='$key' std_grp='$cols[student_group]' selected='selected'>$cols[title]</option>";
                                } else {
                                    echo "<option value='$key' std_grp='$cols[student_group]'>$cols[title]</option>";
                                }
                            }
                            ?>                                
                        </select>                            
                    </div>
                </div>

                <div class="control-group std_crclm hide">
                    <?php echo form_label('PNR:<font color="red"> * </font>', 'student_usn', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'student_usn', 'id' => 'student_usn', 'value' => (set_value('student_usn')) ? set_value('student_usn') : $student_usn, 'class' => 'input', 'disabled' => 'disabled')); ?>
                    </div>
                </div>

                <div class="control-group sh_dept hide">
                    <?php echo form_label('Department:<font color="red"> * </font>', 'department', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_dropdown('department', @$departments, (set_value('department')) ? set_value('department') : '0', "id='department' class='input pgm_list_by_dept' disabled='disabled'"); ?>
                    </div>
                </div>
                <div class="control-group sh_pgm hide">
                    <?php echo form_label('Program:<font color="red"> * </font>', 'program_type', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_dropdown('program_type', @$programs, (set_value('program_type')) ? set_value('program_type') : '0', "id='program_type' class='input crclm_list_by_pgm' disabled='disabled'"); ?>
                    </div>
                </div>
                <div class="control-group crclm hide">
                    <?php echo form_label('Curriculum:<font color="red"> * </font>', 'curriculum', array('class' => 'control-label')); ?>
                    <div class="controls">  
                        <?php echo form_dropdown('curriculum', $crclm_list, (set_value('curriculum')) ? set_value('curriculum') : $crclm_id, "id='curriculum' class='input' disabled='disabled'"); ?>                            
                    </div>
                </div> 
                <div class="control-group">
                    <?php echo form_label('First Name:<font color="red"> * </font>', 'first_name', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'first_name', 'id' => 'first_name', 'value' => set_value('first_name'), 'class' => 'input')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo form_label('Last Name:<font color="red">  </font>', 'last_name', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'last_name', 'id' => 'last_name', 'value' => set_value('last_name'), 'class' => 'input')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo form_label('Email Id:<font color="red"> * </font>', 'email', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'email', 'id' => 'email', 'value' => set_value('email'), 'class' => 'input')); ?>
                    </div>
                </div>

                <div class="control-group">
                    <?php echo form_label('Qualification:', 'qualification', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'qualification', 'id' => 'qualification', 'value' => set_value('qualification'), 'class' => 'input')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo form_label('Contact Number:', 'contact', array('class' => 'control-label')); ?>
                    <div class="controls">   
                        <?php echo form_input(array('name' => 'contact', 'id' => 'contact', 'value' => set_value('contact'), 'class' => 'input')); ?>
                    </div>
                </div>

                <!--Checkbox Modal-->
                <!--Modal Popup starts here-->
                <div id="add_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="false">
                    </br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning !
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" id="comments">
                        <p >The Course with this Stakeholder Type already exists!.</p>
                    </div>
                    <div class="modal-footer">
                        <?php echo form_button(array('name' => 'ok_warning', 'id' => 'ok_warning', 'value' => 'ok', 'content' => '<i class="icon-ok icon-white"></i> Ok', 'class' => 'btn btn-primary close1', 'data-dismiss' => 'modal')); ?>
                    </div>
                </div>
                <!--Modal Popup ends here -->                    
                <div class="pull-right">       
                    <?php
                    echo form_button(array('type' => 'submit', 'name' => 'stkholder_submit', 'id' => 'stkholder_submit', 'value' => 'submit', 'content' => '<i class="icon-file icon-white"></i> Save', 'class' => 'btn btn-primary margin-right5'));
                    echo form_button(array('type' => 'reset', 'name' => 'stkholder_reset', 'id' => 'stkholder_reset', 'value' => 'reset', 'content' => '<i class="icon-refresh icon-white"></i> Reset', 'class' => 'btn btn-info margin-right5'));
                    echo anchor('survey/stakeholders/stakeholder_list', "<i class='icon-remove icon-white'></i> Cancel", array('class' => 'btn btn-danger'));
                    ?>
                </div>
                <?php echo form_close(); ?>
            </div> 

        </div> 

    </div>
</div>