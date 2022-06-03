<?php

    $switch_server = "LOCAL";

    # Version API
    $versionapp_api                       = "V1.0";
    $build_version_api                    = "Build 30042022.110910";

    switch ($switch_server) {
        case "LOCAL":
            # Server Local Komputer
            $server_db                 = "Lokal Komputer";
            $host_db                   = "127.0.0.1";
            $username_db               = "root";
            $key_db                    = "453p714d1";
            $db_name                   = "lts_db_managerial";

            # Table Variable
            $table_user                = "tbl_user";
            $table_log_history         = "tbl_log_history_mkk";
            $table_income              = "tbl_data_income";
            $table_pemasukan_mkk       = "tbl_pemasukan_mkk";
            break;
        case "HOSTING":
            # Server 000webhost
            $server_db                 = "Server Hosting";
            $host_db                   = "localhost";
            $username_db               = "id18790144_academydev";
            $key_db                    = "453p714D1!@1234";
            $db_name                   = "id18790144_lts_db_academydev";

            # Table Variable
            $table_user                = "tbl_user";
            $table_log_history         = "tbl_log_history_mkk";
            $table_income              = "tbl_data_income";
            $table_pemasukan_mkk       = "tbl_pemasukan_mkk";
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