<?php 
    $DB_Y = new SafeMySQL(array(
        'host'      => 'localhost',
        'user'      => JLIFE_DB_Y_USERNAME,
        'pass'      => '1800',
        'db'        => JLIFE_DB_Y_NAME,
        'port'      => NULL,
        'socket'    => NULL,
        'pconnect'  => FALSE,
        'charset'   => 'utf8',
        'errmode'   => 'error', //or exception
        'exception' => 'Exception', //Exception class name
    ));
?>