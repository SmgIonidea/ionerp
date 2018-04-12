<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description         : Help Management view Page.	  
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/jscripts/tiny_mce/tiny_mce.js'); ?>"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options

        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,save,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        // Example content CSS (should be your site CSS)
        content_css: "../twitterbootstrap/tinymce/css/content.css",
        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "../twitterbootstrap/tinymce/js/template_list.js",
        external_link_list_url: "../twitterbootstrap/tinymce/js/link_list.js",
        external_image_list_url: "../twitterbootstrap/tinymce/js/image_list.js",
        media_external_list_url: "../twitterbootstrap/tinymce/js/media_list.js",
        // Style formats
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });
</script>
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
                        Edit Guidelines
                    </div>
                </div>	
                <?php foreach ($result_data as $result): ?>
                    <form class="bs-docs-example form-horizontal" id="form1" name="form1" action="<?php echo base_url('configuration/help_mgmt/update_help_data') . '/' . $result['serial_no']; ?>" method="POST">
                        <div class="control-group">
                            <div>
                                <input type="hidden" name="help" value="<?php echo $result['serial_no']; ?>"/>
                                <textarea name="text_content" cols="40" type="text"  rows="10" id="text_content"><?php echo $result['help_desc']; ?></textarea>
                            <?php endforeach; ?>
                        </div>
                        <br />
                        <div class="pull-right">
                            <button href="#myModal" type="submit" id="update" class="btn btn-primary" data-toggle="modal"><i class="icon-file icon-white"></i> Update </button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                            <a href="http://localhost/ionbvbcd/configuration/help_content" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Save Confirmation 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Data has been saved successfully. </p>
                        </div>
                        <div class="modal-footer">
                            <button onclick="javascript:fun_submit();" class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>
                    <div class="accordion" id="accordion2">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                    <h4> Guidelines Preview </h4>
                                </a>
                            </div>
                            <div id="collapseOne" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <div>
                                        <table class="table table-bordered table-hover" id="peoList" aria-describedby="example_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="header span3"> Page Names </th>
                                                    <th class="header"> Guidelines </th>
                                                </tr>
                                            </thead>
                                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                <?php foreach ($result_data as $data_value): ?>
                                                    <tr class="gradeU even">
                                                        <td class="sorting_1"><?php echo $data_value['entity_data']; ?></td>
                                                        <td><?php echo $data_value['help_desc']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>				
                                            </tbody>
                                        </table>
                                    </div>
                                    </br></br>
                                </div>
                            </div>
                        </div>
                    </div>	
                </form>
                </br>
            </div>
        </div>
    </div>
</div>
<hr>
<? $this->load->view('includes/footer'); ?>	
<script type="text/javascript">
    var data_val;
    function gethelpid(id)
    {
        data_val = id;
    }

    function display_help()
    {
        var data_val = document.getElementById('help').value;
        var post_data = {
            'page_name': data_val
        }
        $.ajax(
                {
                    type: "POST",
                    url: "<?php echo base_url('configuration/help_mgmt/update_content'); ?>",
                    data: post_data,
                    datatype: "JSON",
                    success: function (msg) {
                        tinyMCE.activeEditor.setContent(msg);
                    }
                }
        );
    }
    function fun_submit()
    {
        document.forms["form1"].submit();
    }
</script>

<!-- /TinyMCE -->
<script type="text/javascript">
    if (document.location.protocol == 'file:') {
        alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
    }
</script>
