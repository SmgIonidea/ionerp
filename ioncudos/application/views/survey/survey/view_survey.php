<?php ?>
<div class="row-fluid">
    <div class="span12">

        <?php if (@$message): ?>
            <div class="message error" id="infoMessage">
                <?php echo @$message; ?>
            </div>
        <?php endif ?>

        <?php
        @$attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'view_survey');
        //echo form_open("configuration/users/create_user", @$attributes);
        ?>				

        <br/>
        <div data-spy="scroll" class="edit_survey_div pull-right bs-docs-example span3 ">	
            <b>Survey Respondents : </b><br/>
            <div class="bs-docs-example span15 st_survey_div">

                <?php
                @$i = 1;
                $st_data = array();
                foreach ($st_data as $st) {
                    echo @$i++ . ". " . @$st['fname'] . " " . @$st['lname'] . "<br/>";
                }
                ?>
            </div>
            <?php echo "<br/>"; ?>&nbsp;


        </div>
        <div class="bs-docs-example span8">
            <br/>
            <div class="control-group">
                <label class="control-label" for="survey_name"> Survey Name: </label>
                <div class="controls">
                    <?php echo form_input(@$survey_name); ?><br>
                </div>
            </div>




            <div class="control-group">
                <label class="control-label" for="st_type">Stakeholder Type: </label>
                <div class="controls">
                    <?php echo form_input(@$st_type); ?><br>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="start_date">Start Date:</label>
                <div class="controls">
                    <?php echo form_input(@$start_date); ?>
                </div>				
            </div>

            <div class="control-group">
                <label class="control-label" for="end_year">End Date:</label>
                <div class="controls">

                    <?php echo form_input(@$end_date); ?>

                </div>
            </div>

            <br>
            <div class="pull-right">
                <a href="<?php echo base_url('survey/my_survey_controller'); ?>" class="btn btn-primary" ><i class="icon-ok icon-white"></i><span></span> Ok </a>
            </div><br />

        </div>


        <?php echo form_close(); ?>
    </div>
    <!--Do not place contents below this line-->	

</div>