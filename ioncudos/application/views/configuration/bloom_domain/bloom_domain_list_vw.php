<?php
/**
 * Description          :	List View for bloom's domain Module.
 * Created		:	31-05-2016 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
  --------------------------------------------------------------------------------
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
            <!-- Contents  -->
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Bloom's Domain List
                        </div>
                    </div>
                    <div class="row pull-right">  
                        <?php if ($count >= 3) { ?> 
                            <a data-toggle="modal" href="#cantAdd"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>                           
                            </a> 
                        <?php } else { ?>
                            <a href="<?php echo base_url('configuration/bloom_domain/bloom_domain_add_record'); ?>"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                            </a> 
                        <?php } ?>
                    </div><br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 50px;" tabindex="0" aria-controls="example" aria-sort="ascending">Sl No.</th>
                                    <th class="header headerSortDown" style="width: 70px;" tabindex="0" aria-controls="example" aria-sort="ascending" >Bloom's Domain</th>
                                    <th class="header headerSortDown" style="width: 110px;" tabindex="0" aria-controls="example" aria-sort="ascending" >Bloom's Domain Acronym</th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" >Description</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 40px;" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 50px;" tabindex="0" aria-controls="example" align="center" >Delete</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 50px;" tabindex="0" aria-controls="example" align="center" >Status</th>
                            </tr>
                            </thead>
                            <tbody aria-live="polite" aria-relevant="all">
                                <?php $slNo = 0; ?>
                                <?php foreach ($bloom_domain as $records): ?>
                                    <tr>
                                        <td class="sorting_1 span1" style="text-align:right;"><?php echo ++$slNo ?></td>
                                        <td class="sorting_1 table-left-align"><?php echo $records['bld_name']; ?></td>
                                        <td class="sorting_1 table-left-align span1"><?php echo $records['bld_acronym']; ?></td> 
                                        <td class="sorting_1 table-left-align"><?php echo $records['bld_description']; ?></td>
                                        <td><center><a title="Edit" class="" href="<?php echo base_url('configuration/bloom_domain/bloom_domain_edit_record') . '/' . $records['bld_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center>
                                </td>
                                <td><center><a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal" onclick="javascript:storeId(<?php echo $records['bld_id']; ?>);" >
                                        <i class=" get_id icon-remove"> </i></a></a></center>
                                </td>
                                <?php if ($records['status'] == 0) { ?>
                                    <td><center>
                                        <a data-toggle="modal" href="#myModalenable" class="get_id"   id="<?php echo $records['bld_id']; ?>"
                                           rel="tooltip" title="Enable" role="button"><i class="icon-ok-circle"></i> </a></center></td>
                                    <?php } else { ?>
                                    <td>
                                    <center><a data-toggle="modal" href="#" rel="tooltip" title="Disable" onclick="javascript:store_disable_id(<?php echo $records['bld_id']; ?>);"    id="<?php echo $records['bld_id']; ?>"><i class="icon-ban-circle"></i></a>
                                    </center>
                                    </td>
                                <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br/><br/><br/>
                        <div class="row pull-right">   
                            <?php if ($count >= 3) { ?> 
                                <a data-toggle="modal" href="#cantAdd"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>                           
                                </a> 
                            <?php } else { ?>
                                <a href="<?php echo base_url('configuration/bloom_domain/bloom_domain_add_record'); ?>"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                </a> 
                            <?php } ?>
                        </div><br/><br/><br/>
                        <!-- modal to confirm before deleting a Bloom's Domain -->
                        <div id="delete_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
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
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:deleteRecord();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>
                        <!-- modal to confirm before enabling a Bloom's Domain -->
                        <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Enable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to enable?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary enable_bloom_domain btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>
                        <!-- modal to confirm before disabling a Bloom's Domain -->
                        <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Disable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to disable?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary disable_bloom_domain btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>
                        <!-- modal to can not disabling a Bloom's Domain -->
                        <div id="cantDisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You can't disable this Bloom's Domain as it is assigned to a Course. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <div id="cantAdd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You can't add more than three Bloom's Domain. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <div id="cantDelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                            <div class="modal-body">
                                <!--<p>You can't delete this Bloom's Domain as Bloom's Level are associated with it. </p>-->
                                <p>You can't delete this Bloom's Domain as it is assigned to a Course. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <input type="hidden" name="delete_id" id="delete_id">
                        <input type="hidden" name="enable_id" id="enable_id">
                        <input type="hidden" name="disable_id" id="disable_id">
                        <!--Do not place contents below this line-->	
                    </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bloom_domain.js'); ?>" type="text/javascript"></script>

<!-- End of file bloom_domain_list_vw.php 
                      Location: .configuration/bloom_domain/bloom_domain_list_vw.php -->