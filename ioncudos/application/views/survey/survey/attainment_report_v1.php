<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($res);
$peoId = "";
$prevPeoId = "";
$i = 1;
$totalGraph = "";
$j = 0;
$k = 1;
$last = count($res);
$p = 1;
$f = 0;
$g = 0;
$h = 1;
$arrPeoScore = $res;
$graphValPer = array();
$colspan = array();

$collumns = array_keys($arrPeoScore[0]);
//print_r($collumns);
foreach($arrPeoScore as $key => $val){
    $count = "";
    $respVal = "";
    $peoId = $val[$collumns[0]];
    for($x=5;$x<count($collumns);$x++){
    $arr[$collumns[$x]] = explode('*',$val[$collumns[$x]]);
    $count+=  $arr[$collumns[$x]][0];
    if(isset($arr[$collumns[$x]][1])){
        $respVal+= $arr[$collumns[$x]][0]*$arr[$collumns[$x]][1];
    }else{
        $respVal+= $arr[$collumns[$x]][0];
    }
    
    }

    $maxRating = $val['maxrating']*$count;
    if($maxRating == '0'){
        $maxRating = 1;
    }
    $graphVal = number_format(($respVal*100)/$maxRating,2);
    
  if($prevPeoId != $peoId){
        $finalGraphVal = number_format($totalGraph/$i,2);
        $graphValPer[$f] = $finalGraphVal;
        $colspan[$f] = $g;
        $prevPeoId = $peoId;
        $i = 0;
        $totalGraph = "";
       // $totalGraph = $graphVal;
        $f++;
		$g = 0;
    }
    if($prevPeoId == $peoId){
        $prevPeoId = $peoId;
        $totalGraph+= $graphVal;
        $i++;
        if($p==$last){
            $finalGraphVal = $totalGraph/$i;
            $graphValPer[$f] = $finalGraphVal;
            $colspan[$f] = $g+1;
        }
        $g++;
    }
    $p++;
}

$i = 1;
$prevPeoId = "";
$totalGraph = "";
?>
<br /><br />
<table border="1">
    <tr>
        <th align='center'>Survey Question </th>
        <?php
        for($x=5;$x<count($collumns);$x++){
            echo "<th align='center'>".$collumns[$x]."</th>";
        }
        ?>
        <th align='center'>Qn Score</th>
         <th align='center'><?php echo strtoupper($collumns[0]); ?> Score(%)</th> 
    </tr>
<?php
$peoListStmt = '';
foreach ($res as $key=>$val){
    echo "<tr>";
    $count = "";
    $respVal = "";
    $peoId = $val[$collumns[0]];
    //echo $peoId;
    for($x=5;$x<count($collumns);$x++){
    $arr[$collumns[$x]] = explode('*',$val[$collumns[$x]]);
    $count+=  $arr[$collumns[$x]][0];
    if(isset($arr[$collumns[$x]][1])){
        $respVal+= $arr[$collumns[$x]][0]*$arr[$collumns[$x]][1];
    }else{
        $respVal+= $arr[$collumns[$x]][0];
    }
    
    }
    $maxRating = $val['maxrating']*$count;
    if($maxRating == '0'){
        $maxRating = 1;
    }
    $graphVal = number_format(($respVal*100)/$maxRating,2);
    if($prevPeoId != $peoId){
        
        echo "<td colspan='8' style='background:#ccc;color:#0088cc;'><b>".strtoupper($collumns[0])."".$h.": </b>".$val[$collumns[1]]."</td></tr><tr>";
        $peoListStmt.=$val[$collumns[1]]."#*";
        echo "<td align='left' style='color:#0088cc;padding-left: 10px;'>".$val['question']."</td>";
        for($x=5;$x<count($collumns);$x++){
            echo "<td align='center'>".$arr[$collumns[$x]][0]."</td>";
        }
        echo "<td align='center'>".$graphVal."</td>";
        echo "<td rowspan='".$colspan[$h]."' align='center'>".number_format($graphValPer[$h],2)."</td></tr>";
        $finalGraphVal = number_format($totalGraph/$i,2);
        if($j!=0){
        //echo "<input type='hidden' name='attainment' class='attainment' id='attainment_".$j."' value='$finalGraphVal' />";
        }
        $prevPeoId = $peoId;
        $i = 1;
        $totalGraph = "";
        $totalGraph = $graphVal;
        $j++;
        $h++;
        
    }
   else if($prevPeoId == $peoId){
        echo "<td align='left' style='color:#0088cc;padding-left: 10px;'>".$val['question']."</td>";
         for($x=5;$x<count($collumns);$x++){
            echo "<td align='center'>".$arr[$collumns[$x]][0]."</td>";
        }
    echo "<td align='center'>".$graphVal."</td>";
        $prevPeoId = $peoId;
        $totalGraph+= $graphVal;
        $i++;
        if($k==$last){
            $finalGraphVal = $totalGraph/$i;
            //echo "<td rowspan='".$colspan[$h]."'>".$graphValPer[$h]."</td></tr>";
            //echo "<input type='hidden' name='attainment' class='attainment' id='attainment_".$j."' value='$finalGraphVal' />";
        }else{
            echo "</tr>";
        }
    }
    
    ?>
<?php
    $k++;
}
echo "<input type='hidden' name='peoListStmt' id='peoListStmt' value='$peoListStmt' />";
foreach($graphValPer as $key1 => $val1){
    if($key1!=0){
        echo "<input type='hidden' name='attainment' class='attainment' id='attainment_".$key."' value='".number_format($val1,2)."' />";
    } 
}
?>
</table>