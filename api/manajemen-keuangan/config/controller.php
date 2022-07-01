<?php

    $switch_server = "HOSTING";

    # Version API
    $versionapp_api                       = "V1.0 BETA";
    $build_version_api                    = "Build 07062022.0935.1";
 
    switch ($switch_server) {
        case "LOCAL":
            # Server Local Komputer
            $server_db                 = "Lokal Komputer";
            $host_db                   = "localservermu";
            $username_db               = "rootmu";
            $key_db                    = "passwordmu";
            $db_name                   = "dbmu";
            break;
        case "HOSTING":
            $server_db                 = "Server Hosting";
            $host_db                   = "localservermu";
            $username_db               = "rootmu";
            $key_db                    = "passwordmu";
            $db_name                   = "dbmu";
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