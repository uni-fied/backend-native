<?php

    $switch_server = "LOCAL";

    $versionapp_api                       = "V1.0 BETA";
    $build_version_api                    = "Build 07062022.0935.1";
 
    switch ($switch_server) {
        case "LOCAL":
            $server_db                 = "Lokal Database Komputer";
            $host_db                   = "host_db";
            $username_db               = "username_db";
            $key_db                    = "password_db";
            $db_name                   = "database_name";
            break;
        case "HOSTING":
            $server_db                 = "Server Hosting Database";
            $host_db                   = "host_db";
            $username_db               = "username_db";
            $key_db                    = "password_db";
            $db_name                   = "database_name";
            break;  
    }

    $connecting = mysqli_connect($host_db, $username_db, $key_db, $db_name);

    if ($connecting) {
        $response['code']           = 200;
        $response['status']         = true;
        $response['server']         = $server_db;
        $response['message']        = 'Berhasil terhubung dengan server Database '.$host_db;
    } else {
        $response['code']           = 500;
        $response['status']         = false;
        $response['server']         = $server_db;
        $response['message']        = 'Gagal menghubungkan dengan server Database '.$host_db;
    }

    # echo json_encode($response);
?>