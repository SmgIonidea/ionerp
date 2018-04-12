<?php
/**
 * Description          :	View for Mail details.
 * Created              :	10-03-2017 
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                     Modified By		         Description
  --------------------------------------------------------------------------------
 */
?>
<style>
    .send_mail_header td:first-child{
        text-align:right;
    }
    .send_mail_header td{ 
        border:none !important;
    }
    .send_mail_header{
        background-color: rgb(245, 243, 243);
    }
</style>
<div class="div_border span12" style="padding:0px;">
    <table class="send_mail_header table span9 div_border" style="width:100%;overflow:auto;padding:0px;">
        <tr>
            <td>
                To :
            </td>
            <td>
                <?php echo $mail_details->to ?>
            </td>
        </tr>
        <?php if ($mail_details->cc) { ?>
            <tr> 
                <td>
                    Cc :
                </td>
                <td>
                    <?php echo $mail_details->cc ?>
                </td>
            </tr>
        <?php } ?>
        <tr> 
            <td>
                Subject :
            </td>
            <td>
                <?php echo $mail_details->subject ?>
            </td>
        </tr>
    </table>
    <div class="span11" style="width:100%;overflow:auto;">
        <div style="border: 1px solid #dddddd;margin:4%;">
            <div style="border-bottom: 1px solid #dddddd;background-color: rgb(245, 243, 243);height:40px;">
                <div style="padding:6px;text-align:center;font-size:14px;"><b>Ion<span style="color:red">CUDOS</span></b></div>
            </div>
            <div style="margin:3%;">
                <?php echo $mail_details->email_body ?>
                <br/><br/>
                <?php echo $mail_details->signature ?>
            </div>
            <div style="background-color: rgb(245, 243, 243);margin:2%;">
                <p style="margin:1%;font-size:9pt;color:#b0b0b0"><hr style="border:1px solid black;"/>This is an auto-generated mail, please do not reply back.Incase of any discrepancy please email to ion.cudos@ionidea.com If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited. 
                </p>
            </div>
        </div>
    </div>
</div>
<!-- End of file sent_mail_details_vw.php 
                        Location: .curriculum/send_mail/sent_mail_details_vw.php  -->
