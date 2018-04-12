<?php
/**
 * Description           :	List View for Assessment Method Module.
 * Created		:	3/15/2016
 * Author		:	Bhagyalaxmi S S
 * Modification History:
 * Date				Modified By				Description
 * 
  --------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
<link href="<?php echo base_url('twitterbootstrap/css/jsgrid.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('twitterbootstrap/css/jsgrid-theme.min.css'); ?>" rel="stylesheet"  type="text/css"/>
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
            <!-- Contents
================================================== -->
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example fixed-height"">
                    <?php $this->load->view('includes/crclm_tab'); ?>
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Assessment Method List
                        </div>
                    </div>	
                    <div>
                        <label>
                            Program :<font color="red"> * </font>
                            <select size="1" id="pgmid" name="pgmid" autofocus = "autofocus" aria-controls="example" style="width:35%;" onchange="select_pgm_list();">
                                <option value="0" selected>Select Program </option>
                                <?php foreach ($results as $list_item): ?>
                                    <option value="<?php echo $list_item['pgm_id']; ?>"> <?php echo $list_item['pgm_title']; ?> </option>
                                <?php endforeach; ?>
                            </select>									
                        </label>
                        <input type="hidden" name="pgm_id_h" id="pgm_id_h" value="" />
                    </div><br>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style  = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Sl No.</th><th class="header headerSortDown span3" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Assessment Method</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description </th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example">Manage Rubrics </th>
                                    <th class="header headerSortDown " role="columnheader" tabindex="0" aria-controls="example">View Rubrics </th>
                                    <th class="header" style  = "width: 40px;" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                    <th class="header" style  = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="">

                            </tbody>
                        </table>
                        <br><br><br>

                    </div>	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Assessment Method
                        </div>
                    </div>	
                    <!--	<div id="jsGrid">jsGrid</div>-->

                    <!--  Insert Assessment Method ----->
                    <div id="ao_insert_div">
                        <form  class="form-horizontal"  id="ao_method_add_form" name="ao_method_add_form">

                            <div class="control-group">
                                <label class="control-label" for="assessment_method_name">Assessment Method:<font color="red">*</font>
                                </label>
                                <div class="controls">
                                    <input type="text"  id = "assessment_method_name" name  = "assessment_method_name" class = "required" maxlength = "50" />
                                </div>
                            </div>
                            <div class="control-group">
                                <p class="control-label" for="ao_method_description">Outcome Assessed:</p>
                                <div class="controls">
                                    <?php //echo form_textarea($ao_method_description); ?>
                                    <textarea name ='ao_method_description' id	= 'ao_method_description' class = 'ao_method_textarea_size' rows  = '3' cols  = '50' style = "margin: 0px;">
                                    </textarea>
                                </div>
                            </div>		
                        </form>

                        <div class="pull-right"><br>
                            <a class="ao_method_add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white" onclick="return false"></i> Save </a>
                            <button class="clear_all btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset </button>									
                        </div>
                        <br><br>

                    </div>
                    <!-- Modal -->
                    <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        </br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="comments">
                            <p >Assessment Method already exists under the selected Program.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>


                    <input type="hidden" id="ao_method_id" name="ao_method_id" value=""/>                  
                    <input type="hidden" id="ao_method_id_edit" name="ao_method_id_edit" value=""/>

                    <input type="hidden" id="ao_method_id_criteria" name="ao_method_id_criteria" value=""/>
                    <input type="hidden" id="criteria_id" name="criteria_id" value=""/>
                    <div id="myModalEdit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Edit Assessment Method
                            </div>
                        </div>	

                        <div class="modal-body">
                            <form id="ao_edit">  
                                <div class="control-group">                                    
                                    <div class="controls">
                                        Assessment Method:<font color="red">*</font>
                                        <input type="text" name  = "ao_method_name_edit" id = "ao_method_name_edit" class = "required" maxlength = "50" />
                                    </div>
                                </div>
                                <div class="control-group">                                    
                                    <div class="control">
                                        <p class="control-label" for="ao_method_description">Outcome Assessed:</p>
                                        <textarea name ='ao_method_description_edit' id	= 'ao_method_description_edit' class = 'ao_method_textarea_size' rows  = '3' value="" cols  = '50' style = "margin: 0px;">
                                        </textarea>
                                    </div>
                                </div>	
                            </form> 
                        </div>
                        <div class="modal-footer">
                            <button class=" update_assessment btn btn-primary "  id="update_assessment" name="update_assessment"><i class="icon-file icon-white"></i> Update</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>

                    <div id="add_rubrics" class="modal hide fade" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; width:1100px;left:400px;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Manage Rubrics Definition
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="loading_data" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                            </div>
                            <table><tr><td class="span8"><b> Program: </b><a id="display_pgm_name" style="text-decoration: none;color: black;font-size: 14;"></a></td><td class="span8">
                                        <b>Assessment Method:</b> <a id="display_ao_method_name" style="text-decoration: none;color: black;font-size: 14;"></a></td></tr></table>
                            <div class="span8 pull-right" >	

                                <form id="rubrics">
                                    <!-- <div class="accordion">
                                                  <div class="accordion-group">-->	
                                    <div  id="generate_rb_btn">
                                        <div class="control-group pull-right">
                                            <div class="controls">													
                                                <button type="button" id="generate_rubrics" name="generate_rubrics" class="btn btn-primary">Generate Rubrics table</button>
                                            </div>
                                        </div>
                                        <div class="span7 pull-right">Enter No. of Columns (Scale of Assessment) for Rubrics <font color="red">*</font> 
                                            <input type="text" id="rubrics_count"  name="rubrics_count" class="input-mini" required/></div>
                                    </div>
                                    <div class="control-group pull-right" id="regenerate_rb_btn">
                                        <div class="controls">
                                            <button type="button" id="regenerate_rubrics" name="regenerate_rubrics" class="btn btn-primary ">  Re-Generate Rubrics table</button>
                                        </div>
                                    </div>

                                    <!--</div>
                            </div>-->
                                </form>

                                <input type="hidden" name="range_count" id="range_count" value="4" />
                                <input type="hidden" name="is_define_rubrics" id="is_define_rubrics" value="0" readonly />

                            </div>						
                            <!--<div id="jsGrid">dasda</div>-->
                            <div  class="bs-docs-example" style="width:1030px;height:100%;overflow:auto;">
                                <div id="rubrics_data"></div></div>
                            <div id="check_main" class="bs-docs-example" style="width:1030px;height:100%;overflow:auto;" >
                                <div id="check"><!--style="width:1100px;height:100%;overflow:auto;">-->

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="save_rubrics" > <i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-primary" id="update_rubrics" > <i class="icon-file icon-white"></i> Update</button>
                            <!--<button type="reset" class="btn btn-primary" id="reset" > <i class="icon-file icon-white"></i> Reset</button>-->	
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Cancel</button>
                                                                <!--<button type="reset" id="reset_criteria"  class="clear_all btn btn-info" onclick="reset_criteria()" ><i class="icon-refresh icon-white"></i>Reset</button>	-->
                        </div>
                    </div>


                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot delete this Assessment Method, as it is refereed under Assessment Occasions. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <div id="regerate_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to Redefine the Rubrics definition ? <br />All the Criteria and Scale of Assessment defined earlier will be deleted and you need to define all a fresh. <br/>
                                <br/>Press Ok to continue. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="re_generate_table"> <i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <br>			
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>
                        </div>		
                        <div class="modal-body">
                            <p> Are you sure you want to Delete? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_ao_method btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>                


                    <div id="display_rubrics_data_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; width:1100px;left:400px;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                View Rubrics Definition
                            </div>
                        </div>		
                        <div class="modal-body">
                            <table><tr><td class="span8"><b> Program:  </b><a id="display_pgm_name_view" style="text-decoration: none;color: black;font-size: 14;"></a></td><td class="span8">
                                        <b>Assessment Method: </b><a id="display_ao_method_name_view" style="text-decoration: none;color: black;font-size: 14;"></a></td></tr></table>
                            <div id="display_rubrics_data"> </div>
                        </div>
                        <div class="modal-footer">

                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>


                    <div id="myModaldelete_criteria" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>
                        </div>		
                        <div class="modal-body">
                            <!--<p>Are you sure you want to delete this Criteria ?</p>-->
                            <p> Are you sure you want to Delete? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_ao_method_criteria btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <!--Do not place contents below this line-->	
            </section>	
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<style media="all" type="text/css">
        td.alignRight { text-align: right; }
</style> 

<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "></script>
<script src="<?php echo base_url('twitterbootstrap/js/jsgrid.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/assessment_method.js'); ?>" type="text/javascript"></script>
