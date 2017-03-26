<?php

class Utilities
{
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    function filterTableToArray($data, $col)
    {
        $arr = array();
        $i=0;
        foreach($data as $val)
        {
            $val_ = (array)$val;
            $arr[$i] = $val_[$col];
            $i++;
        }
        return $arr;
    }
    
    function getImageAsBase64($filename)
    {
        $file = file_get_contents( "image_upload/marlyn.png" );
        if($file )
        {
            $file = base64_encode( $file );
        }
        return $file;
    }
}

?>