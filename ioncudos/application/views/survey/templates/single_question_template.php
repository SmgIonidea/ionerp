<?php ?>
<div class="option-box-div">
    <div class="option-div" >
        <?php
        echo form_label('Option #1:<font color="red"> * </font>', 'qstn_1_option_input_box[]');
        echo form_input(array('name' => 'qstn_1_option_input_box[]', 'id' => 'qstn_1_option_input_box[]', 'maxlength' => '300', 'value' => set_value(), 'class' => 'input option-input-box'));
        echo anchor('#', "<i class='icon-remove margin-left5'></i>", array('class' => 'Delete delete_option'));
        ?>                                                
        Total chars: <span id="<?php echo "sugcount11"; ?>">0 of 100. </span><br/>
        <span id="errorspan11" class="error"></span>
    </div>
    <div class="option-div">
        <?php
        echo form_label('Option #2:<font color="red"> * </font>', 'qstn_1_option_input_box[]');
        echo form_input(array('name' => 'qstn_1_option_input_box[]', 'id' => 'qstn_1_option_input_box[]', 'maxlength' => '300', 'value' => set_value(), 'class' => 'input option-input-box'));
        echo anchor('#', "<i class='icon-remove margin-left5'></i>", array('class' => 'Delete delete_option'));
        ?>                                                
        Total chars: <span id="<?php echo "sugcount11"; ?>">0 of 100. </span><br/>
        <span id="errorspan11" class="error"></span>
    </div>
    <div class="option-div">
        <?php
        echo form_label('Option #3:<font color="red"> * </font>', 'qstn_1_option_input_box[]');
        echo form_input(array('name' => 'qstn_1_option_input_box[]', 'id' => 'qstn_1_option_input_box[]', 'maxlength' => '300', 'value' => set_value(), 'class' => 'input option-input-box'));
        echo anchor('#', "<i class='icon-remove margin-left5'></i>", array('class' => 'Delete delete_option'));
        ?>                                                
        Total chars: <span id="<?php echo "sugcount11"; ?>">0 of 100. </span><br/>
        <span id="errorspan11" class="error"></span>
    </div>                                            
    <p class='margin-top10'></p>
    <div class="row-fluid">                                    
        <div class="span12 pull-right">    
            <?php echo anchor('#', "<i class='icon-plus-sign icon-white'></i> Add Options", array('class' => 'btn btn-primary addButton pull-right add_options')); ?>
        </div>                                                       
    </div>                                            
</div>
