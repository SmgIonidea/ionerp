<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Help Content view Page.	  
 * Modification History:
 * Date							Modified By								Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  15-05-2014 					Jevi V G								Added Wysiwyg editor from v3.x to v4.x
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>

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
<?php
$this->load->view('includes/js');
?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents
            ================================================== -->
            <!--<section id="contents">-->
            <div class="bs-docs-example fixed-height" >
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Add Guidelines
                    </div>
                </div>	
                <div><font color="red"><?php echo validation_errors(); ?></font></div>
                <form class="bs-docs-example form-horizontal" id="form1" name="form1" action="<?php echo base_url('configuration/help_content/insert_into_db'); ?>" method="POST">
                    <div class="control-group controls">
                        <p class="control-label inline " for="pagename"> Guidelines for: <font color="red"> * </font></p>
                        <div class="controls">
                            <select name="page_name" id="page_name" autofocus = "autofocus" class="required" onchange="page_name();">
                                <option value="">Select Guidelines</option>
                                <?php foreach ($alias_entity as $entity_name): ?>
                                    <option value="<?php echo $entity_name['entity_id']; ?>"><?php echo $entity_name['alias_entity_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="entity_name" id="entity_name" value=""/>
                            </br>
                        </div>
                    </div>
                    <div class="control-group">
                        <div>
                            <?php echo form_textarea($text_content); ?>
                        </div>
                        <br/>
                        <div class="pull-right">
                            <button type="submit" id="update" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                            <a href="<?php echo base_url('configuration/help_content'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</a>
                        </div>
                    </div>
                </form>
                </br>
                <div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <?php $this->load->view('includes/footer'); ?>	
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/help_content.js'); ?>"></script>