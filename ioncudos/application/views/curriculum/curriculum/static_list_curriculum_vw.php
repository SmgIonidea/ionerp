<!DOCTYPE html>
<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Curriculum view page, provides the list of curriculums  and progress of each curriculum.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<html lang="en">
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Curriculum List
                                </div>
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Curriculum</th>
                                            <th class="header span2"  role="columnheader" tabindex="0" aria-controls="example" >Program</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >Dept</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >From</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >To</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >Owner</th>
                                            <!--<th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >View Progress</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example">Details</th>-->
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($curriculum_list_result as $curriculum_list): ?>
                                            <tr class="gradeU even">
                                                <td class="sorting_1 table-left-align"><a href="<?php echo base_url('curriculum/curriculum/static_details_curriculum') . '/' . $curriculum_list['crclm_id']; ?>"><?php echo $curriculum_list['crclm_name']; ?> </a></td>
                                                <td class="sorting_1"><?php echo $curriculum_list['pgm_title']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['dept_acronym']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['start_year']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['end_year']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['first_name'] . " " . $curriculum_list['last_name']; ?> </td>
                                                <!-- ################# Progress Bar ################# 
                                                <td>
                                                    <a  data-toggle = "modal" 
                                                        href = "#" 
                                                        id = "<?php echo $curriculum_list['crclm_id']; ?>"
                                                        name="progress_button"
                                                        Onclick="display_progress(<?php echo $curriculum_list['crclm_id']; ?>);"
                                                        class = "btn btn-small btn-primary"><i class="icon-align-left icon-white"></i> View Progress </a>
                                                </td>
                                                ################# ##################### ################# 
                                                <td>
                                        <center>
                                            <a class="" href="<?php echo '#'; //echo base_url('curriculum/curriculum/static_edit').'/'.$curriculum_list['crclm_id'];   ?>"><i class="icon-list "></i></a>
                                        </center>
                                        </td>	-->

                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <br><br>
                                <br />
                                <!-- Modal for progress bar -->
                                <!-- ################# Progress Bar ################# 
                                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                    <div class="container-fluid"><br>
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Progress of Curriculum
                                            </div>
                                        </div><br>
                                    </div>
                                    <div class="modal-body" id="status">
                                    </div>		
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal"> Close </button>
                                    </div>
                                </div> -->
                            </div>
                            <br />
                            <br />
                        </div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    </body>
    <script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum.js'); ?>"></script>
</html> 