<?php 
if($res){

$tbl='<table aria-describedby="rubrics_info" id="rubrics" class="table table-bordered table-hover dataTable" style="width: 1062px;">
        <thead>
            <tr>';            
            foreach($res['column'] as $name):
                $tbl.="<th><center>$name</center></th>";
            endforeach;            
     $tbl.='</tr>
        </thead>
        <tbody role="alert" aria-live="polite" aria-relevant="all">';
        
        foreach($res['data'] as $data):
            $edit=$delete='';
            $tbl.="<tr>";
            foreach($data as $key=>$rec):
                    if($key=='edit'){
                        $edit=$rec;
                    }else if($key=='delete'){
                        $delete=$rec;
                    }else{
                        $tbl.="<td><center>".$rec."</center></td>";
                    }
                    
            endforeach;
            if($edit)
                $tbl.="<td><center>".$edit."</center></td>";
            if($delete)
                $tbl.="<td><center>".$delete."</center></td>";
            $tbl.="</tr>";
        endforeach;
  $tbl.='</tbody>
    </table>';  
$tbl.='<form name="export_rubrics_form" target="_blank" method="post" action="'.base_url("/Extra_curricular_activities/Extra_curricular_activities/export_to_pdf").'" ><div class="col-sm-5 pull-right" style="margin-right:0px; padding-right:0px;">'
        . '<input type="text" id="export_rubrics_form_activity_id" name="export_rubrics_form_activity_id" value="">'        
    . '<button id="rubrics_export" class="btn btn-success" name="rubrics_export" type="submit"> <i class="icon-file icon-white"></i> Export PDF</button>';
$tbl.='</form>&nbsp;<button class="btn btn-success" name="rubrics_finalize" id="rubrics_finalize" type="button">Rubrics Finalize</button></div>';  
}else{
    $tbl= "<div class='error'>Rubrics not defined</div>";
}
echo $tbl;

