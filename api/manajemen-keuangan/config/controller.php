<?php

    $switch_server = "HOSTING";

    # Deploy to New Hosting @ 01 Juni 2022 09.11 WIB - V1.0 BETA

    # Version API
    $versionapp_api                       = "V1.0 BETA";
    $build_version_api                    = "Build 07062022.0935.1";

    # 1. Build 30042022.110910
    # 2. Build 07062022.0935.1
 
    switch ($switch_server) {
        case "LOCAL":
            # Server Local Komputer
            $server_db                 = "Lokal Komputer";
            $host_db                   = "127.0.0.1";
            $username_db               = "root";
            $key_db                    = "";
            $db_name                   = "lts_db_managerial_dev";
            break;
        case "HOSTING":
            # Server 000webhost
            // $server_db                 = "Server Hosting";
            // $host_db                   = "localhost";
            // $username_db               = "id18790144_academydev";
            // $key_db                    = "453p714D1!@1234";
            // $db_name                   = "id18790144_lts_db_academydev";
            
            # Server InfinityFree
            # New User @ 01 Juli 2022
            $server_db                 = "Server Hosting";
            $host_db                   = "fdb32.awardspace.net";
            $username_db               = "4130798_softask";
            $key_db                    = "453p714d1";
            $db_name                   = "4130798_softask";
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