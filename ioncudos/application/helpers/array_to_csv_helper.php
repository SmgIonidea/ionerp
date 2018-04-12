<?php

if (!function_exists('array_to_csv')) {

    /**
     * @method: array_to_csv()
     * @param:     array 
     * @desc : convert an array to css format string
     */
    function array_to_csv(array $fields, $delimiter = ',', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $outputString = "";
        foreach ($fields as $tempFields) {
            $output = array();
            if(is_array($tempFields)){
                foreach ($tempFields as $field) {
                    if ($field === null && $nullToMysqlNull) {
                        $output[] = 'NULL';
                        continue;
                    }

                    // Enclose fields containing $delimiter, $enclosure or whitespace
                    if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
                        $field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
                    }
                    $output[] = $field;// . " ";
                }
            }else{
                $output[] = $tempFields;
            }
            $outputString .= implode($delimiter, $output) . PHP_EOL;
        }
        return $outputString;
    }
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( ! isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( ! isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
