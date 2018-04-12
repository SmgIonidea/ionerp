<?php
//print_r($preview);
?>
<div class="row-fluid">

    <div class="span6">
        <div class="span4 text-right">
            <b>Department: </b>
        </div>
        <div class="span7 text-left">
            <?php echo $deptName; ?>
        </div>
    </div>
    <div class="span6">
        <div class="span4 text-right">
            <b>Program: </b>
        </div>
        <div class="span7 text-left">
            <?php echo $pgmTitle; ?>
        </div>
    </div>
    <?php if(isset($crsTitle)){ ?>
        <div class="span6">
        <div class="span4 text-right">
            <b>Course: </b>
        </div>
        <div class="span7 text-left">
            <?php echo $crsTitle; ?>
        </div>
    </div>
   <?php }
    ?>
    <?php if(isset($crclmTitle)){ ?>
        <div class="span6">
        <div class="span4 text-right">
            <b>Curriculum: </b>
        </div>
        <div class="span7 text-left">
            <?php echo $crclmTitle; ?>
        </div>
    </div>
   <?php }
    ?>
</div>
<?php
    $question = 1;
    $checkBoxId = 1;
    foreach ($survey_questions as $key => $val) {
        echo '<div class="tab-pane" id="tab">';

        /************* Display questions and options *************/
        echo '<div class="row-fluid resp-questions bs-docs-example"><p><b>'.$question.'.</b> '.$val->question.'</p>';
        if($val->is_multiple_choice==1){
            echo '<div id="checkBox'.$checkBoxId.'">';
            foreach($options[$key] as $okey=>$oval){
                echo form_checkbox(array('name'=>'question_'.$val->survey_question_id.'[]','id'=>'questionp_'.$val->survey_question_id),$oval->survey_qstn_option_id,FALSE);
                echo $oval->option.'<br />';
            }
            $checkBoxId++;
            echo '</div>';
            //$checkBoxId++;
        }else{
            foreach($options[$key] as $okey=>$oval){
                echo form_radio(array('name'=>'question_'.$val->survey_question_id,'disabled'=>'disabled','id'=>'questionp_'.$val->survey_question_id),$oval->survey_qstn_option_id,FALSE);
                echo $oval->option.'<br />';
            }
        }
        echo '<br />';
        echo '</div></div>';

        $question++;
    }
?>
