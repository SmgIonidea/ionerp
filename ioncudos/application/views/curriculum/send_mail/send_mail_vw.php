<?php
/**
 * Description          :	View for Send Mail Module.
 * Created              :	09-03-2017 
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                     Modified By		         Description
  --------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<?php
$val = $this->ion_auth->user()->row();
$signature = "--\r\nRegards,";
$signature .= "\r\n" . $org_name = $val->first_name . ' ' . $val->last_name;
$signature .="\r\n" . $val->dept_name;
$signature .="\r\n" . $val->org_name->org_name;
?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
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
                            Send Mail
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid" style="width:90%;">
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            <p>Role(s) :
                                                <select size="1" id="roles" name="roles" class="input-medium" autofocus = "autofocus" aria-controls="example" onChange = "fetch_program();">
                                                    <option value="" selected> Select Role</option>
                                                    <?php foreach ($roles_list as $role): ?>
                                                        <option value="<?php echo $role['id']; ?>"> <?php echo $role['name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </p>
                                        </td>
                                        <td>
                                            <p>Program :
                                                <select size="1" id="program" class="input-medium" name="program" autofocus = "autofocus" aria-controls="example" onChange = "">
                                                    <option value="" selected> Select Program </option>
                                                </select>
                                            </p>
                                        </td>
                                        <td id="crclm_co" style="display:none">
                                            <p>Curriculum :
                                                <select size="1" id="crclm" name="crclm" multiple="multiple" autofocus = "autofocus" aria-controls="example" onChange = "fetch_term();">
                                                </select>
                                            </p>
                                        </td>
                                        <td id="term_co" style="display:none">
                                            <p>Term :
                                                <select size="1" id="term" name="term" multiple="multiple" autofocus = "autofocus" aria-controls="example" onChange = "fetch_co_mail_id();">
                                                </select>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <form  class="form-horizontal" method="POST"  id="add_form" name="add_form" action="" enctype="multipart/form-data">
                        <div class="control-group">
                            <p class="control-label">To :<font color="red"> * </font></p>
                            <div class="controls">
                                <input type="text" name="to" id="to" class="input-large required" style="width:80%" value=""><span id="mail_to"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Cc :</p>
                            <div class="controls">
                                <input type="text" name="cc" id="cc" class="input-large" style="width:80%" value=""><span id="mail_cc"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Subject :<font color="red"> * </font></p>
                            <div class="controls"> 
                                <input type="text" name="subject" id="subject" class="input-large required" style="width:80%" value="">                              
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Email Body :<font color="red"> * </font></p>
                            <div class="controls"> 
                                <textarea name="body" id="body" class="input-large required" rows="5" style="width:80%"></textarea>                              
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Signature :</p>
                            <div class="controls"> 
                                <div class="span6">
                                <textarea name="signature" id="signature" class="input-large" rows="3" style="width:50%"><?php echo $signature; ?></textarea>                              
                                </div>
                                <div class="span6">
                                    <div class=""  id="div_attachments"> 
                                    
                                    <a href="#" name="add_attachments" id="" class="add_attachment">Attachments</a>
                                    </div> 
                                    <div id="attachment_validate">
                                    <b>Note :</b> Files allowed are .doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf.
                                    <br>Maximum file size allowed is <b><?php echo $attachment_size_limit; ?>MB.</b>
                                </div>
                                </div>
                            </div>
                             
                            
                        </div><br/>

                        <div class="pull-right">       
                            <button id="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save & Send</button>
                            <button class="btn btn-info" id="reset" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                        </div>
                    </form>
                    <br/><br/>
                    <div id="mail_list" style="width:100%;overflow:auto;">
                    </div>
                    <br/><br/>
                    <div class="modal hide fade" id="view_mail_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="width:60%;left:40%;display:block;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Email Details
                                </div>
                            </div>
                        </div>
                        <div class="modal-body mail_details" >

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger pull-right" type="button" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <br/>
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
<script type="text/javascript">
    var attachment_cnt_limit = <?php echo $attachment_cnt_limit; ?>;
    var attachment_size_limit = <?php echo $attachment_size_limit; ?>;
</script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/send_mail.js'); ?>" type="text/javascript"></script>
<!-- End of file send_mail_vw.php 
                        Location: .curriculum/send_mail/send_mail_vw.php  -->
