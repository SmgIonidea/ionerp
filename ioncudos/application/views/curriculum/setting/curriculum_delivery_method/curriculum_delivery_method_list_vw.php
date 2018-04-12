<?php
/**
 * Description          :	List View for Delivery Method Module.
 * Created		:	23-05-2015
 * Author		:	Arihant Prasad
 * Modification History:-
 *   Date                	  Modified By                			Description
 * 22-05-2015			Abhinay Angadi				Edit view functionalities
 * 9-29-2015			Bhagyalaxmi			        Merged Add and edit and list_view files
 * 04-01-2016			Shayista 				Added loading image and cookie.
  -------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 

<!--sidenav.php-->

<div class="container-fluid">
    <div class="row-fluid">
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10 ">
            <!-- Contents -->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <?php $this->load->view('includes/crclm_tab'); ?>
                    <!--content goes here-->	                      
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Curriculum Delivery Method List
                        </div>
                    </div>	

                    <div>
                        <label>
                            Curriculum:<font color="red"> * </font>
                            <select size="1" id="crclm_id" name="crclm_id" autofocus = "autofocus" aria-controls="example" onChange="dm_table_generate();">
                                <option value="" selected> Select Curriculum </option>
                                <?php foreach ($results['result_curriculum_list'] as $list_item) { ?>
                                    <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                <?php } ?>
                            </select>

                        </label>
                    </div>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid"  >
                        <table class="table table-bordered table-hover dataTable" id="example" aria-describedby="example_info" style="font-size: 12;">
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:50px;">Sl No.</th>										
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Curriculum Delivery Methods</th>											
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Description </th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Bloom's Level</th>
                                    <!--<th>Bloom's Level</th>-->
                                    <th class="header crclm_getDeliveryMethods" role="columnheader" tabindex="0" style="width:40px"  aria-controls="example">Edit
                                    </th>
                                    <th class="header" role="columnheader" style="width:50px"  tabindex="0" aria-controls="example">Delete
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <br><br><br><br>	
                    </div>

                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Delivery Method 
                        </div>
                    </div>	

                    <form class="form-horizontal"  id="delivery_method_add_form" name="delivery_method_add_form" >
                        <input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $crclm_id; ?>" />																			


                        <div class="span5">	
                            <label class="control-label" for="delivery_method_name"> 
                                Delivery Method Name : <font color="red"> * </font>
                            </label>

                            <div class="controls">

                                <input type="text" id="delivery_method_name" name="delivery_method_name" class="required"/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="bloom_level_1">
                                Bloom's Level :
                            </label>

                            <select  class="input-medium bloom_level" name="bloom_level_1[]" id="bloom_level_1" multiple="multiple">
                                <?php foreach ($bloom_level_data as $bloom_level) { ?>											
                                    <option value="<?php echo $bloom_level['bloom_id']; ?>" title="<?php echo $bloom_level['description'].' - ['.$bloom_level['bloom_actionverbs'].']' ?>"><?php echo $bloom_level['level']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="span3">	
                            <label class="control-label" for="delivery_method_description"> 
                                Delivery Method Description :
                            </label>

                            <div class="controls">

                                <textarea id="delivery_method_description" name="delivery_method_description" style="width: 565px;"></textarea>
                            </div>
                        </div>

                        <!-- bloom's level dropdown -->


                        <div class="pull-right"><br>
                            <button class="delivery_method_add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                            <button class="clear_all btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                        </div><br><br>
                        </div>



                    </form>


                    <!-- Modal do confirm before deleting -->


                    <div id="edit" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_exists" data-backdrop="static" data-keyboard="false">
                        </br>

                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" >
                                    Edit Curriculum Delivery Method  
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="comments">

                            <form class="form-horizontal"  id="delivery_method_edit_form" name="delivery_method_edit_form" action= "">


                                <div class="control-group">
                                    <label class="control-label" for="delivery_method_id">Delivery Method Name:<font color="red"> * </font>
                                    </label>
                                    <div class="controls">

                                        <input type="hidden" id="crclm_id_edit" name="crclm_id_edit"/>

                                        <input type="hidden" id="delivery_method_id_edit" name="delivery_method_id_edit"/>
                                    </div>
                                    <div class="controls">

                                        <input type="text" required id="delivery_method_name_edit" name="delivery_method_name_edit"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="delivery_mtd_description"> Delivery Method Description : </label>
                                    <div class="controls">

                                        <textarea id="delivery_method_description_edit" name="delivery_method_description_edit" ></textarea>
                                    </div>
                                </div>		


                                <div class="control-group">
                                    <label class="control-label" for="bloom_level_2"> Bloom's Level  : </label>
                                    <div class="controls">
                                        <div class="span12">
                                            <div class="span4">
                                                <select  class="example-getting-started bloom_level" name="bloom_level_2[]" id="bloom_level_2" multiple="multiple">
                                                    <?php foreach ($bloom_level_data as $bloom_level) { ?>
                                                        <option value="<?php echo $bloom_level['bloom_id']; ?>" title="<?php echo $bloom_level['description'].' - ['.$bloom_level['bloom_actionverbs'].']' ?>"><?php echo $bloom_level['level']; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>									
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button class='delivery_method_edit_form_submit btn btn-primary'><i class='icon-file icon-white'></i> Update</button>

                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>

                    </div>
                    <br><br>


                    </form>
                </div>
                <div id="myModaldelete" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModaldelete" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to Delete?</p>
                    </div>

                    <div class="modal-footer">
                        <button class="delete_dm btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>

                <!-- Warning modal - if delivery method is already assigned in any topic -->
                <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="cant_delete" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning 
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You cannot delete this Delivery Method, as it has been assigned to a <?php echo $this->lang->line('entity_topic'); ?> . </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                    </div>
                </div>

                <!--Checkbox Modal-->
                <div id="myModal_edit_view" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_edit_view" data-backdrop="static" data-keyboard="false">
                    </br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" id="comments">
                        <p >This Delivery Method Name already exists.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>

                <!-- warning modal message - same delivery name -->
                <div id="myModal_exists" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_exists" data-backdrop="static" data-keyboard="false">
                    </br>
                   
                        <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                    
                    </div>
                    <div class="modal-body" id="comments">
                        <p >This Delivery Method Name already exists.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                    </div>
                </div>
                <!--Do not place contents below this line-->	
            </section>	
        </div>
    </div>


    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <style media="all" type="text/css">
        td.alignRight { text-align: right; }
    </style>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_delivery_method.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />

    <!-- End of file curriculum_delivery_method_list_vw.php 
                            Location: .curriculum/curriculum_delivery_method/curriculum_delivery_method_list_vw.php -->
