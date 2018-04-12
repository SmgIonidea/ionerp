<?php

function search_multdim_array($array, $field, $value, $column = null) {
    foreach ($array as $key => $array) {
        if ($array[$field] == $value) {
            return ($column) ? $array[$column] : $key;
        }
    }
    return false;
}

function test(){
    echo 'achived';
}