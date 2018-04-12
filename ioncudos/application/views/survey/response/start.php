<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="row-fluid">
    <div class="span12">
        <section id="contents">
            <div class="row-fluid">
                <center> <b><?php echo $survey[0]->intro_text; ?></b></center>
            </div>
            <div class="pull-right">
                <?php echo anchor('survey/response/questions/','Start Survey',array('class' => 'btn btn-primary pull-right','style'=>'margin-top: 100px;')); ?>
            </div>
        </section>
    </div>
</div>

<?php
//echo "<pre>";
//print_r($survey);
//echo "</pre>";
//echo "<pre>";
//print_r($survey_questions);
//echo "</pre>";
?>