<?php
    
    $servername = "localhost";
    $username   = "devuser";
    $password   = "mmoa!@2019PHM";
    $dbname     = "macmeanoffer_custom_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $conn->query("SET GLOBAL sql_mode='', SESSION sql_mode=''");
    
    $date = date('d-m-y h:i:s');
	//file_put_contents(APPPATH.'third_party/hook/clonfile/sqlmodefile_'.$date.'.txt',print_r($date,TRUE));

?>