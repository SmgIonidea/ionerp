<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_table_detail($table_name) {

        $table_details[$table_name] = $this->__get_table_detail($table_name);
        $table_keys = array_column($table_details[$table_name], 'Key');

        //check foreign keys are there or not
        if (in_array('MUL', $table_keys)) {

            $foreign_keys = $this->__get_foreign_keys($table_name);

            foreach ($foreign_keys as $keys => $vals) {
                $table_details[$vals['REFERENCED_TABLE_NAME']] = $this->__get_table_detail($vals['REFERENCED_TABLE_NAME']);
            }
        }
        return $table_details;
    }

    private function __get_table_detail($table_name) {
        $data = array();
        $data = $this->db->query("SHOW FULL COLUMNS FROM $table_name")->result_array();
        return $data;
    }

    private function __get_foreign_keys($table_name) {
        $foreign_keys = $this->db->query(
                 "SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                 FROM
                 INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                 WHERE
                 TABLE_NAME = '$table_name' AND CONSTRAINT_NAME != 'PRIMARY'")->result_array();
        return $foreign_keys;
    }
    public function get_table_list() {
        $tables = $this->db->list_tables();        
        $table_values = array_values($tables);
        return array_combine($tables, $table_values);
    }
}
