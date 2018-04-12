<?php
/**
 * Description	:	Course Learning Outcome grid provides the list of Course Learning
					Outcome statements

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ---------------------------------------------------------------------------------------------- */
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
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Outcomes (COs) List
                                </div>
                            </div><br/>
                            <form class="form-inline" method="post" name="clo" id="clo" action="<?php echo base_url('curriculum/clo/clo_add'); ?>" >
                                <fieldset>
                                    <div class="control-group ">
                                        <label class="control-label">Curriculum:<font color='red'> * </font></label>

                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_term();">
                                            <option value="">Select Curriculum</option>
                                            <?php foreach ($curriculum_name_data as $listitem): ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        <label class="control-label"> Term: <font color='red'> * </font></label>
                                        <select id="term" name="term" class="input-xlarge span2" onchange="select_course();">
                                            <option> Select Term</option>
                                        </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        <label class="control-label"> Course: <font color='red'> * </font></label>
                                        <select id="course" name="course" class="input-xlarge span3" onchange="static_get_selected_value();">
                                            <option> Select Course </option>
                                        </select>
                                    </div>
                                    <div></br>
                                        <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Course Outcomes (COs) </th>	
                                                </tr>
                                            </thead>
                                            <tbody  role="alert" aria-live="polite" aria-relevant="all">
                                            </tbody>
                                        </table>
                                    </div>
                                    </br></br>
                                </fieldset>
                            </form>
                        </div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/static_clo.js'); ?>" type="text/javascript"> </script>

<!-- End of file static_list_clo_vw.php 
                        Location: .curriculum/clo/static_list_clo_vw.php -->