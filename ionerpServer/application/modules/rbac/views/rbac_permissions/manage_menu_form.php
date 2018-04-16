<?php

$tbl = '';
if ($permission):
    $count_actions = count($permission);
    $data = array_chunk($permission, 3);
    foreach ($data as $row) {
        $tbl.='<div class="row-fluid" >';
        foreach ($row as $col) {
            $selected = "";
            $tbl.='<div class="col-sm-4" style="padding-left:0px;">
                    <div class="box box-info">                
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Default Name</label>
                                <div class="col-sm-8">
                                    <div class="form-control">' . $col['action_name'] . '</div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Alias Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="permission_' . $col['permission_id'] . '[]" value="' . $col['menu_name'] . '"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Menu Class</label>
                                <div class="col-sm-8">
                                    <input type="text" name="permission_' . $col['permission_id'] . '[]" value="' . $col['menu_class'] . '"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Parent</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="permission_' . $col['permission_id'] . '[]">
                                        <option value="">Select parent</option>';
            foreach ($action_lists as $per_id => $action_name) {
                if ($col['permission_id'] != $per_id) {
                    $selected = ($per_id == $col['parent']) ? 'selected="selected"' : "";
                    $tbl.='<option value="' . $per_id . '" ' . $selected . '>' . $action_name . '</option>';
                }
            }
            $tbl.='</select>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Order</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="permission_' . $col['permission_id'] . '[]">
                                        <option value="">Select order</option>';
            for ($len = 1; $len <= $count_actions; $len++) {
                $selected = (($col['order'] == $len) ? 'selected="selected"' : "");
                $tbl.='<option value="' . $len . '" ' . $selected . '>' . $len . '</option>';
            }
            $tbl.='</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >URL</label>
                                <div class="col-sm-8">
                                    <input type="text" name="permission_' . $col['permission_id'] . '[]" value="' . $col['url'] . '"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Header Name</label>
                                <div class="col-sm-8">
                                    <input type="text" name="permission_' . $col['permission_id'] . '[]" value="' . $col['menu_header'] . '"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Header Class</label>
                                <div class="col-sm-8">
                                    <input type="text" name="permission_' . $col['permission_id'] . '[]" value="' . $col['header_class'] . '"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >Menu type</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="permission_' . $col['permission_id'] . '[]">';
            $type = array('l' => 'Left', 'r' => 'Right', 't' => 'Top', 'o' => 'Other');
            foreach ($type as $t => $val) {
                $selected = (($t == $col['menu_type']) ? 'selected="selected"' : "");
                $tbl.='<option value="' . $t . '" ' . $selected . '>' . $val . '</option>';
            }
            $tbl.='</select>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>';
        }
        $tbl.='</div><br>';
    }
else:
    $tbl = 'No permission assigned to the module';
endif;
echo $tbl;
