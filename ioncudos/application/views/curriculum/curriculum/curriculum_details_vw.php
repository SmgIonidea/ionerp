<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Curriculum Details view page, provides the facility to view the details of curriculum, 
  Term details and mapping Approver details.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
	<!--head here -->
	<?php $this->load->view('includes/head'); ?>
	<body data-spy="scroll" data-target=".bs-docs-sidebar">
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
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                 Curriculum (Regulation) Details
                                </div>
                            </div>
							<div class="pull-right">
                                    <a href="<?php echo base_url('curriculum/curriculum'); ?>" style="margin-left: 10px;" class="btn btn-danger dropdown-toggle pull-right"><i class="icon-remove icon-white"></i><span></span> Close</a>		
                                </div>
                            <div class="control-group">
                                <h4><font color="blue"><label class="control-label" for="curriculum">Name Of the Curriculum : <u><b><?php
                                                foreach ($curr_details as $row) {
                                                    print $row->crclm_name;
                                                }
                                                ?></u></b></label></font></h4>
                                                <?php
                                                $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'curriculum_details');
                                                echo form_open("configuration/curriculum/curriculum_details", $attributes);
                                                ?>				
                                <div class="controls">
                                </div>
                            </div>
                            <div class="bs-docs-example">
                                <div class="pull-left container-fluid">
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('credits'); ?></th>
                                                <th>Total Terms</th>
                                                <th>Minimum Duration(Years)</th>
                                                <th>Maximum Duration(Years)</th>
                                                <th>Curriculum Owner</th>
                                            </tr>
                                        </thead>	
                                        <tbody>
                                            <tr>
                                                <td>
                                        <center>
                                            <?php
                                            foreach ($owner as $row) {
                                                echo $row->total_credits;
                                            }
                                            ?>
                                        </center>
                                        </td>
                                        <td>
                                        <center>
                                            <?php
                                            foreach ($owner as $row) {
                                                echo $row->total_terms;
                                            }
                                            ?>
                                        </center>
                                        </td>
                                        <td>
                                        <center>
                                            <?php
                                            foreach ($owner as $row) {
                                                echo $row->pgm_min_duration;
                                            }
                                            ?>
                                        </center>
                                        </td>
                                        <td>
                                        <center>
                                            <?php
                                            foreach ($owner as $row) {
                                                echo $row->pgm_max_duration;
                                            }
                                            ?>
                                        </center>
                                        </td>

                                        <td>
                                        <center>
                                            <?php
                                            foreach ($owner as $row) {
                                                echo $row->title . " " .$row->first_name . " " . $row->last_name;
                                            }
                                            ?>
                                        </center>
                                        </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pull-left container-fluid">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr><td colspan="6">
                                                    <h4><label class="control-label" for="curriculum"><font color="blue"><b>Curriculum Term Details</b></font></h4>
                                                </td></tr>
                                            <tr>
                                                <th>Sl No. </th>
                                                <th> Name </th>
                                                <th> Duration (Weeks) </th>
                                                <th><?php echo $this->lang->line('credits'); ?> </th>
                                                <th>Theory Courses</th>
                                                <th>Practical Courses</th>
                                            </tr>
                                        </thead>
                                        <tbody id="s">
                                            <?php $i = 1;
                                            foreach ($term_details as $row):
                                                ?>
                                                <tr>
                                                    <td> <?php echo ($i) ?> </td>
                                                    <td> <?php echo $row['term_name']; ?></td>
                                                    <td> <?php echo $row['term_duration']; ?></td>
                                                    <td> <?php echo $row['term_credits']; ?></td>
                                                    <td> <?php echo $row['total_theory_courses']; ?></td>
                                                    <td> <?php echo $row['total_practical_courses'];
                                            $i++; ?></td>
                                                </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <table class="table table-bordered" >
                                    <thead>
                                        <tr>
                                        <tr><td colspan="3">
                                                <h4><label class="control-label" for="curriculum"><font color="blue"><b>Curriculum Approval Details</b></font></h4>
                                            </td></tr>
                                    <th></th>
                                    <th><center><?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>), <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs) and Program Outcomes (PO) to Program Educational Objectives (PEO) Mapping</center></th>
                                    <!--<th style="white-space:nowrap;"><center>Course Learning Outcomes (CLO) to Program Outcomes (PO) Mapping</center></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><center><b>BOS Department</b></center></td>
                                    <td><center><?php
                                        foreach ($curriculum_peo_approver as $row) {
                                            echo $row[0]['dept_name'];
                                        }
                                        ?></center></td>
                                    <!--<td><center>
                                        <?php
                                        foreach ($clo_po_approver as $row) {
                                            echo $row[0]['dept_name'];
                                        }
                                        ?></center></td>-->
                                    </tr>
                                    <tr>
                                        <td><center><b>Approval Authority</b></center></td>
                                    <td><center>  <?php
                                        foreach ($curriculum_peo_approver as $row) {
                                            echo $row[0]['title'] . " " .$row[0]['first_name'] . " " . $row[0]['last_name'];
                                        }
                                        ?></center></td>
                                    <!--<td><center> <?php
                                        foreach ($clo_po_approver as $row) {
                                            echo $row[0]['title'] . " " .$row[0]['first_name'] . " " . $row[0]['last_name'];
                                        }
                                        ?></center></td>-->
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="pull-right">
                                    <a href="<?php echo base_url('curriculum/curriculum'); ?>" style="margin-left: 10px;" class="btn btn-danger dropdown-toggle pull-right"><i class="icon-remove icon-white"></i><span></span> Close</a>		
                                </div>
                                <br><br>
                            </div>
                    <?php echo form_close(); ?>
                        </div>
                </div>
            </div>
        </div>
<?php $this->load->view('includes/footer'); ?> 		   
    </body>
</html>		 