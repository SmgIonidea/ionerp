<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<!DOCTYPE html>
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <h2>Topic List </h2>
                            <br/>
                            <form class="form-inline">
                                <fieldset>
                                    <!-- Form Name -->
                                    <!-- Select Basic -->
                                    <div class="control-group ">
                                        <label class="control-label">Curriculum:</label>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_term();">
                                            <option value="">Curriculum</option>
                                            <?php foreach ($crclm_name_data as $listitem): ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                        <label class="control-label">Term:</label>
                                        <select id="term" name="term" class="input-xlarge span2" onchange="select_course();">
                                            <option>Terms</option>

                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="control-label">Course:</label>
                                        <select id="course" name="course" class="input-xlarge span3">
                                            <option>Course</option>
                                        </select>
                                    </div>
                                    <!-- Select Basic -->
                                </fieldset>
                            </form>
                            <div class="row">
                                <a  class="btn btn-primary pull-right" onclick="GetSelectedValue();"><i class="icon-plus-sign icon-white" ></i>Show</a>
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Topic Name</th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="topic_list">
                                    </tbody>
                                </table>
                            </div>
                            </br></br>
                            <div class="row">
                                <a  class="btn btn-primary pull-right""" href="<?php echo base_url('configuration/topicadd'); ?>"><i class="icon-plus-sign icon-white"></i>ADD Topic</a>
                            </div>
                            <!--Modal-->
                            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-header">
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure that you want to Enable?</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true" Onclick="javascript:enable();">Ok</button>
                                    <button type="reset" class="cancel btn btn-primary" data-dismiss="modal">Cancel</button>
                                </div>
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
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/topic.js'); ?>">
    </script>
</body>
</html>



