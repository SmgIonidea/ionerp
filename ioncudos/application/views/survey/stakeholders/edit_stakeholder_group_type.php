<?php ?>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                
                <div class="error">
                    <?php
                        if(validation_errors()) {
                            echo validation_errors();
                        }else if(@$errorMsg) { 
                            echo $errorMsg;
                        }
                    ?>
                 </div>

                <?php                
                    echo form_open('survey/stakeholders/edit_stakeholder_group_type', array('name' => 'add_stakeholder_group_form', 'id' => 'add_stakeholder_group_form', 'method' => 'post', 'class' => 'form-horizontal'));
                ?>
                
                <div class="control-group">
                    <?php echo form_label('Stakeholder Type:<font color="red"> * </font>', 'type_name', array('class' => 'control-label')); ?>
                    <div class="controls">                           
                        <?php echo form_input(array('name' => 'type_name', 'id' => 'type_name', 'value' =>(set_value('type_name'))?set_value('type_name'):$stk_title, 'class' => 'input')); ?>
                    </div>
                </div>
                <br>		

                <div class="control-group">
                    <?php echo form_label('Description:', 'type_desc', array('class' => 'control-label')); ?>
                    <div class="controls"> 
                        <?php echo form_textarea(array('name' => 'type_desc', 'id' => 'type_desc', 'col' => 20, 'rows' => 3, 'value' => (set_value('type_desc'))?set_value('type_desc'):@$description , 'class' => 'peo')); ?>
                    </div>
                </div>  
                <div class="control-group">
                    <?php echo form_label('Student Group:', 'student_group', array('class' => 'control-label')); ?>
                    <div class="controls"> 
                        <?php echo form_checkbox('student_group', '1',($student_group)?true:false);?>
                    </div>
                </div>
                <div class="pull-right margin-top50">       
                    <?php
                    echo form_button(array('type' => 'submit', 'name' => 'type_submit', 'id' => 'type_submit', 'value' => 'submit', 'content' => '<i class="icon-file icon-white"></i> Update', 'class' => 'btn btn-primary margin-right5'));
                    //echo form_button(array('type' => 'reset', 'name' => 'type_reset', 'id' => 'type_reset', 'value' => 'reset', 'content' => '<i class="icon-refresh icon-white"></i><span></span>Reset', 'class' => 'btn btn-info margin-right5'));
                    echo anchor('survey/stakeholders/', "<i class='icon-remove icon-white'></i> Cancel", array('class' => 'btn btn-danger'));
                    ?>
                </div>
                <?php echo form_close(); ?>
            </div> 
        </div> 
    </div>
</div>