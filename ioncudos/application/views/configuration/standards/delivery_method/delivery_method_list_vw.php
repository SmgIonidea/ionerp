<?php
/**
 * Description          :	List View for Delivery Method Module.
 * Created		:	23-03-2015. 
 * Author		:	Jyoti
 * Modification History:
 *   Date                   Modified By                			Description
 * 15-05-2015		   Arihant Prasad		Code clean up, variable naming, addition of bloom's level
  --------------------------------------------------------------------------------------------------------
 */
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height"">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delivery Method List
                        </div>
                    </div>	

                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/delivery_method/delivery_method_add_record'); ?>" align="right">
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
                                </i> Add</button>
                        </a>
                    </div>
                    <br><br>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style ="width: 200px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Delivery Methods</th>
                                    <!--<th class="header headerSortDown" style ="width: 185px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Bloom's Level & Action Verbs</th>-->
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Guidelines </th>
                                    <th class="header" style ="width: 40px;" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                    <th class="header" style ="width: 50px;" role="columnheader" tabindex="0" aria-controls="example">Delete</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">

                                <?php foreach ($records as $records) { ?>
                                    <tr>
                                        <td class="sorting_1 table-left-align">
                                            <?php echo $records['delivery_mtd_name']; ?>
                                        </td> 

                                        <!--<td class="sorting_1 table-left-align">
                                            <?php echo $records['level'] . " - " . $records['bloom_actionverbs']; ?>
                                        </td>-->

                                        <td class="sorting_1 table-left-align">
                                            <?php echo $records['delivery_mtd_desc']; ?>
                                        </td>

                                        <td>
                                <center><a class="" href="<?php echo base_url('configuration/delivery_method/delivery_method_edit_record') . '/' . $records['delivery_mtd_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i>
                                    </a></center>
                                </td>

                                <?php if ($records['is_dm'] == 0) { ?>
                                    <td>
                                        <div id="hint">
                                            <center>
                                                <a href = "#myModaldelete" id="<?php echo $records['delivery_mtd_id']; ?>" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
                                                   title="Delete" 
                                                   value="<?php echo $records['delivery_mtd_id']; ?>">
                                                </a>
                                            </center>
                                        </div>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <div id="hint">
                                            <center>
                                                <a href = "#cant_delete" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete">
                                                </a>
                                            </center>
                                        </div>
                                    </td>
                                <?php } ?>
                                </tr>
                            <?php } ?>	
                            </tbody>
                        </table>
                        <br><br><br>

                        <div class="pull-right">   
                            <a href="<?php echo base_url('configuration/delivery_method/delivery_method_add_record'); ?>" >
                                <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
                                    </i> Add </button>
                            </a>
                        </div><br>	
                    </div><br><br>

                    <!-- Modal do confirm before deleting -->
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>
                        </div>		
                        <div class="modal-body">
                            <p>Are you sure you want to Delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_dm btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>

                    <!-- Warning modal - if delivery method is already assigned in any topic -->
                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
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
                    <!--Do not place contents below this line-->	
            </section>	
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/delivery_method.js'); ?>" type="text/javascript"></script>