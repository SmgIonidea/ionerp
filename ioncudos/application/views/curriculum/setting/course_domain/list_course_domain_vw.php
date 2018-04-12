<?php
/**
 * Description          :	List View for Course Domain Module.
 * Created		:	07-06-2013.. 
 * Modification History:
 * Date			 Modified By				Description
 * 10-09-2013		Abhinay B.Angadi            Added file headers, indentations.
 * 11-09-2013		Abhinay B.Angadi	    Variable naming, Function naming & Code cleaning.
 * 29-09-2015		Bhgayalaxmi		    Mergerd the Add and list_view files
 * 04-01-2016		Shayista 		    Added loading image.
 * 11-03-2016 		Shayista Mulla		    Removed ! and UI improvement.
  --------------------------------------------------------------------------------------------------------
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
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents-->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <?php $this->load->view('includes/crclm_tab'); ?>	
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Course Domain (Department Verticals) List
                        </div>
                    </div>	  

                    <br><br>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="gradeU even" role="row">

                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:50px;">Sl No.</th>
                                    <th class="header headerSortDown" style='width: 220px;' role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Course Domain Name</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Course Domain Description </th>
                                    <th class="header" style='width: 40px;' role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                    <th class="header" style='width: 50px;' role="columnheader" tabindex="0" aria-controls="example">Delete
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <br><br><br>
                        <br>	
                    </div>
                    <br>


                    <!-----Content------------>

                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Course Domain (Department Verticals)
                        </div>
                    </div>	
                    <form  class="form-horizontal"  id="add_form_id" name="add_form_id" >
                        <div class="span6">					
                            <p class="control-label" for="crs_domain_name">Course Domain Name :<font color="red">*</font></p>
                            <div class="controls">

                                <input type="text" id="crs_domain_name" name="crs_domain_name" class="required"/>
                            </div>					
                        </div>

                        <div class="span6">					
                            <p class="control-label" for="crs_domain_description">Course Domain Description :</p>
                            <div class="controls"> 

                                <textarea id="crs_domain_description" name="crs_domain_description"></textarea>
                            </div>
                        </div>                         
                        <br/><br/><br/>
                        <!--Cannot delete modal-->
                        <div id="cannot_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comments">
                                <p >This Course Domain is assigned to the Course.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <!--Checkbox Modal-->
                        <div id="add_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comments">
                                <p >This Course Domain Name already exists.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <div class="pull-right">       
                            <button class="add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button></form>
                    <!----End-Form--------------------->
                    <!-- Modal -->


                    <div id="edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="delete_warning_dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit Course Domain (Department Verticals)
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form  class="form-horizontal" id="edit_form_id" name="edit_form_id" >

                                <div class="control-group">
                                    <p class="control-label" >Course Domain Name :<font color="red">*</font></p>
                                    <div class="controls">

                                        <input type="text" required id="crs_domain_name_edit" name="crs_domain_name_edit"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <p class="control-label" for="crs_domain_description">Course Domain Description :</p>
                                    <div class="controls"> 

                                        <textarea id="crs_domain_description_edit" name="crs_domain_description_edit"></textarea>
                                    </div>
                                </div>
                                <div class="control-group"for="crs_domain_id">
                                    <div class="controls"> 

                                        <input type="hidden" id="crs_domain_id_edit" name="crs_domain_id_edit"/>
                                    </div>
                                </div>
                                <!--Checkbox Modal-->

                        </div>

                        <div class="modal-footer">

                            <button class=" edit_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Update</button>								                              
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                        </form>

                    </div>
                    <div id="edit_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="edit_warning_dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="comments">
                            <p >This Course Domain Name already exists.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>
                    <div id="delete_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="delete_warning_dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to Delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_course_domain btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>



                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>           
                        </div>
                        <div class="modal-body" id="comment">
                            <p> You cannot delete this Course Domain. It is used in Course. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>


                    <br><br><br><br/> 
                </div><br>
                <!--Do not place contents below this line-->	
            </section>	
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<!---place js.php here -->
<style media="all" type="text/css">
    td.alignRight { text-align: right; }
</style> 

<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course_domain.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
