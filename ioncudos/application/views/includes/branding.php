
<?php
/**
* Description	:	Generic Branding View file of the application contains Application Title & descriptions.
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
---------------------------------------------------------------------------------------------------------------
*/
$val =  $this->ion_auth->user()->row();
$org_name = $val->org_name->org_name;
$org_type = $val->org_name->org_type;
$oe_pi_flag = $val->org_name->oe_pi_flag;
$theory_iso_code = $val->org_name->theory_iso_code;
?>
<header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62); padding-top:2px; padding-bottom:2px; ">
<!--<div class="container">-->
<div class="container-fluid">
	<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 200px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 1px;">
	<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" class="img-circle" style="float:right;"/>
	<center><b style="text-shadow: 2px 2px black; color: white; font-size: 15px; margin-top: 10px;"> <?php echo $org_name ; if ($org_type == 'TIER-I') { echo ' (T1)'; } else { echo ' (T2)'; }?></b> <br>
	<b style="text: 2px 2px black; color: white; font-size: 12px;">Department of <?php echo $this->ion_auth->user()->row()->dept_name.".";?></b></center>
	
</div>
</header>