<div class="row-fluid">
    <div class="row-fluid">
        <div class="checkbox">
            <label for="check_all">
                <input type="checkbox" name="check_all" id="check_all">Select all
            </label>
        </div>
    </div>
    <div id="error_msg" class="text-red"></div>
    <div class="table-responsive">
        <form action="<?= base_url('/rbac/rbac_permissions/create') ?>" name="permission_form" id="permission_form" method="post">
            <table class="table table-sm table-bordered responsive table-striped">
                <tr>
                    <th>Modules</th>
                    <?php
                    foreach ($action_list as $action):
                        echo '<th>' . $action['name'] . '</th>';
                    endforeach;

                    foreach ($module_list as $module):
                        echo '<tr>';
                        echo '<th>' . str_replace('_', ' ', ucfirst($module['name'])) . '</th>';
                        foreach ($action_list as $actions):
                            $checked = '';
                            if (isset($permission_list[$module['module_id']])) {
                                $assigned_actions = $permission_list[$module['module_id']]['action_list'];
                                $arr_assigned_actions = explode(',', $assigned_actions);
                                if (in_array($actions['action_id'], $arr_assigned_actions)) {
                                    $checked = 'checked="checked"';
                                }
                            }
                            echo '<td><input type="checkbox" class="check_item" ' . $checked . ' name="' . $module['module_id'] . '[]" value="' . $actions['action_id'] . '"></td>';
                        endforeach;
                        echo '</tr>';
                    endforeach;
                    ?>
                </tr>
            </table>
            <div style="float:right;">
                <input class="btn btn-primary" type="submit" name="save_rbac_permission" id="save_rbac_permission" value="Save">
            </div>
        </form>
    </div>
    <script>
        (function ($) {
            $('#check_all').on('click', function () {
                $(':checkbox.check_item').prop('checked', this.checked);
            });
        })(jQuery);
    </script>