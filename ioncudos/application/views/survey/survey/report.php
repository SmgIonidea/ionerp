<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    $('#accordion .panel-heading').click(function () {
    if (!$(this).hasClass('active'))
    {
      // the element clicked was not active, but now is - 
      $('.panel-heading').removeClass('active').addClass('inactive');
      $(this).addClass('active').removeClass('inactive'); 
      setIconOpened(this);
    }
    else
    {    
      // active panel was reclicked
      if ($(this).find('b').hasClass('opened'))
      {
        setIconOpened(null);
      }
      else
      {
        setIconOpened(this);
      }
    }
});

// create a function to set the open icon for the given panel
// clearing out all the rest (activePanel CAN be null if nothing is open)
function setIconOpened(activePanel) {
  $('.panel-heading').find('b').addClass('closed').removeClass('opened'); 

  if (activePanel)
  {
    $(activePanel).find('b').addClass('opened').removeClass('closed'); 
  }
}
$('#accordion').on('show.bs.collapse', function () {
    $('#accordion .in').collapse('hide');
});
</script>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php
$statment = '';
$preStatment = '';
$i = 1;
$outcome_type = '';
$j = 1;
foreach ($q as $key => $val){
    if(isset($val['peo'])){
        $outcome_type = "PEO ";
    }
    if(isset($val['po'])){
        $outcome_type = "PO ";
    }
    if(isset($val['co'])){
        $outcome_type = "CO ";
    }
    $statment = $val['statment'];
    if($statment!=$preStatment){
        //echo "NOT";
        if($i!=1){
            echo "</div></div>";
        }
        ?>
    
    <div class="bs-docs-example panel panel-default">
       <div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne">
                  <b class="closed"></b> <?php echo $outcome_type."".$j; $j++; ?>: <?php echo $statment;?>
                </a>
            </h4>
        </div> 
        <div id="collapse<?php echo $i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
<?php
    }
    ?>
        
            
    <table  style="width:100%;border-collapse: collapse">
        <tbody>
            <tr style="background-color:#d9e7f0;">
                <td colspan="2"><b><?php echo $i;?> . <?php echo $val['question'];?></b></td>
                <td valign="bottom" align="center" style="width:10%;"><center><b>Responses</b></center></td>
                <td valign="bottom" align="center" style="width:10%;"><center><b>Percent</b></center></td>
            </tr>
            <tr>
                    <td>&nbsp;</td>
            </tr>
            <?php 
                foreach ($r[$i-1] as $rkey => $rval){
            ?>
            <tr>
                <td style="width:20%;"><div id="opt_<?php echo $i; ?>"><?php echo $rkey+1; ?>.<?php echo $rval['opt'];?>:</div></td>
                <td style="width:50%;">
                        <div class="dd"><progress id="progressbar_<?php echo $i; ?>" value="<?php echo $rval['PercentageResponse'];?>" max="100" style="height:20px;width:100%;background:white;color:#60AFFE;"></progress></div>
                </td>
                <td style="width:10%;"><center><?php echo $rval['Response'];?></center></td>
                <td style="width:10%;"><center><?php echo number_format($rval['PercentageResponse'],2);?>%</center></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
            
        
        
<?php
    if($statment!=$preStatment){
        echo "</div>";
        $preStatment = $statment;
    }
    $i++;
}
?>

</div>