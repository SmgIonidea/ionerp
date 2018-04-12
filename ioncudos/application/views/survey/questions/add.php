<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
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
                $attributes = array(
                    'class' => 'form-horizontal',
                    'method' => 'POST',
                    'id' => 'add_question_type_frm',
                    'name' => 'add_question_type_frm'
                );
                echo form_open('survey/questions/add', $attributes);
                ?>
                <div class="control-group">
                    <?php
                    echo form_label('Question Category Name:<font color="red">*</font>', '', array('class' => 'control-label', 'for' => 'question_type'));
                    ?>
                    <div class="controls">
                        <?php echo form_input(array('id' => 'question_type', 'name' => 'question_type', 'value' =>  set_value('question_type'), 'class' => 'input')); ?> 
                    </div>
                </div>

                <div class="control-group">
                    <?php
                    echo form_label('Description:', '', array('class' => 'control-label', 'for' => 'question_description'));
                    ?>
                    <div class="controls">
                        <?php echo form_textarea(array('rows' => '5', 'class' => 'textarea_class', 'id' => 'question_description', 'name' => 'question_description', 'value' => set_value('question_description'))); ?>
                    </div>
                </div>

                <div class="pull-right margin-top50">
                    <?php
                    echo form_button(array('type' => 'submit', 'id' => 'save_btn', 'class' => 'add_form_submit btn btn-primary margin-right5', 'name' => 'save_btn', 'value' => 'submit'), '<i class="icon-file icon-white"></i> Save', "");
                    echo form_button(array('type' => 'reset', 'class' => 'btn btn-info margin-right5'), '<i class="icon-refresh icon-white"></i> Reset', '');
                    echo anchor('survey/questions/', '<span class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</span>', '');
                    echo "</div>";
                    echo form_close();
                    ?>
                </div> 
            </div> 
        </div>
    </div>