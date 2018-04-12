<?php
/**
 * Description	:	To display Program Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 21-10-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ----------------------------------------------------------------------------------------- */
?>

<!--head here -->
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                   <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>
                                </div>
                            </div>

                            <div class="span7">
                                <label> Curriculum:
                                    <select name="curriculum_list" id="curriculum_list" onChange = "static_select_curriculum();">
                                        <option> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>" > <?php echo $curriculum['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
								</label>
								<h4><center><b id="po_current_state"></b></center></h4>
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:95px">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info" style="font-size: 14px;">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Statements </th>
											<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Type </th>
										</tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">

                                    </tbody>
                                </table>
                                <!--Do not place contents below this line-->	
                            </div>
                            <div class="clear">
                            </div></br></br></br></br>
                    </section>
						</div>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po.js'); ?>" type="text/javascript"> </script>