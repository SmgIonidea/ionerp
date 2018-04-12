<?php
/**
 * Description          :	View for Bulk Email Module.
 * Created		:	23-06-2016 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
 * 03-07-2017		Jyoti			Added attachments with mail functionality
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
                            Send Bulk Emails to Staff
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid" style="width:70%;">
                                <table style="width:100%">
                                    <tr>
                                        <td class="span2">
                                        </td>
                                        <td>
                                            <p>Department :
                                                <select size="1" id="department" name="department" autofocus = "autofocus" aria-controls="example" onChange = "fetch_roles();">
                                                    <option value="" selected> Select Department </option>
                                                    <?php foreach ($department as $list_item): ?>
                                                        <option value="<?php echo $list_item['dept_id']; ?>"> <?php echo $list_item['dept_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </p>
                                        </td>
                                        <td>
                                            <p>Role(s) :
                                                <select size="1" id="roles" name="roles" autofocus = "autofocus" multiple="multiple" aria-controls="example" onChange = "fetch_email_id();">

                                                </select>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <form  class="form-horizontal" method="POST"  id="add_form" name="add_form" action="<?php echo base_url('configuration/bloom_domain/insert_bloom_domain'); ?>">
                        <div class="control-group">
                            <p class="control-label">To :<font color="red"> * </font></p>
                            <div class="controls">
                                <input type="text" name="to" id="to" class="input-large required" style="width:80%"><br/><span id="mail_to"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Subject :<font color="red"> * </font></p>
                            <div class="controls"> 
                                <input type="text" name="subject" id="subject" class="input-large required" style="width:80%">                              
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Email Body :</p>
                            <div class="controls"> 
                                <textarea name="body" id="body" class="input-large" rows="5" style="width:80%"></textarea>                              
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Signature :</p>
                            <div class="controls"> 
                                <div class="span6">
                                <textarea name="signature" id="signature" class="input-large" rows="3" style="width:50%"></textarea>                              
                                </div>
                                <div class="span6">
                                    <div class=""  id="div_attachments"> 
                                    
                                    <a href="#" name="add_attachments" id="" class="add_attachment">Attachments</a>
                                    </div> 
                                    <div id="attachment_validate">
                                    <br><b>Note :</b> Files allowed are .doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf.
                                    <br>Maximum file size allowed is <b><?php echo $attachment_size_limit; ?>MB.</b>
                                </div>
                                </div>
                            </div>
                             
                            
                        </div><br/>
                        <div class="pull-right">       
                            <button id="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save & Send</button>
                            <button class="btn btn-info" id="reset" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                        </div>
                        <br/>
                        <!--Modal to display the valid types of files as attachments with email-->
                        <div class="modal hide fade" id="invalid_file_upload" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Invalid upload
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" >
                                Please upload files having extensions:.doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf. 
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <br/>
                        <!--Modal to display the message for limit of size of attachments with email-->
                        <div class="modal hide fade" id="file_upload_maximum_size" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        File upload size exceeds limit
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" >
                                Maximum upload file size is <b><?php echo $attachment_size_limit; ?>MB</b> .
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <!--Modal to display the message for limit of attachments with email-->
                        <div class="modal hide fade" id="file_upload_maximum_count" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        File upload exceeds limit
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" >
                                Maximum number of files to be uploaded is restricted to <?php echo $attachment_cnt_limit; ?>  .
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                    </section>
                </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?>
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bulk_email.js'); ?>" type="text/javascript"></script>
    <script>
        $('#roles').multiselect({
            maxHeight: 200,
            buttonWidth: 160,
            numberDisplayed: 5,
            nSelectedText: 'selected',
            nonSelectedText: "Select Role(s)"
        });
        var attachment_cnt_limit = <?php echo $attachment_cnt_limit; ?>;
        var attachment_size_limit = <?php echo $attachment_size_limit; ?>;
    </script>