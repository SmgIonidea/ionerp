<?php
/**
* Description	:	Staic (read only) List View for Course Module.
* Created		:	09-04-2013. 
* Modification History:
* Date				Modified By				Description
* 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>
<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
    <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course List
                                </div>
                            </div>
                            <form class="form-inline">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label">Curriculum:<font color='red'>*</font></label>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_termlist();" >
                                            <option value="">Select Curriculum</option>
                                            <?php foreach ($curriculum_data as $listitem): ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="control-label">Term:<font color='red'>*</font></label>
                                        <select id="term" name="term" class="input-xlarge span2" onchange="static_get_selected_value();">
                                            <option>Select Term</option>
                                        </select>
                                    </div>
                                    <br/>
                                    <div>
                                        <div>
                                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Code</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Title</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Core / Elective</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >L</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >T</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >P</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >SS</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
                                                        <!--<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Contact Hours / Week</th>-->
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_cie'); ?> Marks</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_see'); ?> Marks</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Total Marks</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Owner</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Reviewer</th>
														<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_see'); ?> Duration (hours)</th>
                                                    </tr>
                                                </thead>
                                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                </tbody>
                                            </table>
                                        </div>
                                        </br></br>
                                    </div>
                                </div>
                        </div>
                </div>
            </div>
        </div>	
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course.js'); ?>" type="text/javascript"> </script>