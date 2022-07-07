<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $username         = $_POST['username'];
        $pwd              = $_POST['password'];
        $accesstime       = $_POST['waktu_akses'];
        $versiapp         = $_POST['versi_apps'];
        $buildver         = $_POST['build_version'];
        $action           = $_POST['aksi'];

        # Pengecekan Inputan
        if (!empty($username) && !empty($pwd) && !empty($versiapp) && !empty($buildver) && !empty($action)) {

            # Pengecekan Versi & Build Version
            if ($versiapp == $versionapp_api && $buildver == $build_version_api) {

                # 1 Cek user exist pada database berdasarkan username & password
                $LOGIN_USER = "SELECT COUNT(*) 'user_terdaftar' FROM tbl_user WHERE username = '$username' AND kunci = MD5('$pwd');";
        
                # Eksekusi Query Cari user
                $EXECUTE_LOGIN = mysqli_query($connecting, $LOGIN_USER);
    
                # Ambil Hasil data dari Respon MySQL
                $row = mysqli_fetch_row($EXECUTE_LOGIN);
    
                switch ($row[0]) {
                    case 1:
                        
                        # 2 Ambil Data User Login
                        $GET_USER_DETAILS = "SELECT * FROM tbl_user WHERE username = '$username' AND kunci = MD5('$pwd');";
                        $EXECUTE_QUERY_DETAILS = mysqli_query($connecting, $GET_USER_DETAILS);
    
                        # 3 Mengambil Id User untuk Log History
                        $EXECUTE_QUERY_GETROW = mysqli_query($connecting, $GET_USER_DETAILS);
                        $row_data = mysqli_fetch_row($EXECUTE_QUERY_GETROW);
    
                        # 4 Pengecekan Lisensi Akun
                        $QUERY_CHECK_LISENSI = "SELECT IF(lisensi_akun IS NULL OR lisensi_akun = '', 'empty', lisensi_akun) AS lisensi_akun FROM tbl_user WHERE id_user = $row_data[0];";
                        $EXECUTE_CHECK_LISENSI = mysqli_query($connecting, $QUERY_CHECK_LISENSI);

                        $row_licence = mysqli_fetch_row($EXECUTE_CHECK_LISENSI);

                        switch ($row_licence[0]) {
                            case "empty":
                                $response['code'] = 404;
                                $response['status'] = false;
                                $response['message'] = 'Anda tidak memiliki lisensi dari Akun ini, Harap hubungi pengembang.';
                                break;
                            default:
                                # 5 Generate Token for Login md5
                                $random_token = md5(uniqid($row_data[0], true));
            
                                # 6 Proses Input Data log History
                                $LOG_HISTORY_SET = "INSERT INTO tbl_log_history_mkk (id_user, waktu_akses, versi_apps, build_version, token_akses_log, aksi) VALUES ($row_data[0], '$accesstime', '$versiapp', '$buildver', '$random_token', '$action');";
                                $EXECUTE_QUERY_LOGING = mysqli_query($connecting, $LOG_HISTORY_SET);
            
                                # 7 Proses Input Token Akses Generate
                                $UPDATE_TOKEN_TBL_USER = "UPDATE tbl_user SET token_akses = '$random_token', last_access = '$accesstime' WHERE id_user = $row_data[0];";
                                $EXECUTE_UPDATE_TOKEN = mysqli_query($connecting, $UPDATE_TOKEN_TBL_USER);    
            
                                # 8 Get Data User for Show in Response
                                $GET_USER = "SELECT tbl_user.id_user, tbl_user.kode_user, tbl_user.id_group, tbl_group_user.deskripsi_group, tbl_user.nama_user, tbl_user.gender, tbl_user.alias, tbl_user.username, tbl_user.kunci, tbl_user.email, tbl_user.tanggal_terdaftar, tbl_user.last_access, tbl_user.token_akses, tbl_user.lisensi_akun FROM tbl_user JOIN tbl_group_user ON tbl_group_user.id_group=tbl_user.id_group WHERE username = '$username' AND kunci = md5('$pwd');";
                                $EXECUTE_QUERY_GET_USER = mysqli_query($connecting, $GET_USER);
            
                                $result = array();
            
                                while($row = mysqli_fetch_array($EXECUTE_QUERY_GET_USER)) {
                                    array_push(
                                        $result, array (
                                            "id_user"=>$row['id_user'],
                                            "kode_user"=>$row['kode_user'],
                                            "id_group"=>$row['id_group'],
                                            "deskripsi_group"=>$row['deskripsi_group'],
                                            "nama_user"=>$row['nama_user'],
                                            "gender"=>$row['gender'],
                                            "alias"=>$row['alias'],
                                            "username"=>$row['username'],
                                            "kunci"=>$row['kunci'],
                                            "email"=>$row['email'],
                                            "tanggal_terdaftar"=>$row['tanggal_terdaftar'],
                                            "token_akses_login"=>$row['token_akses'],
                                        ));
                                }
            
                                $response['code'] = 200;
                                $response['status'] = true;
                                $response['message'] = 'Akun Anda terdaftar didalam sistem, Harap tunggu, Sedang mengarahkan ke halaman Dashboard...';
                                $response['data_akun'] = $result;
                                break;
                        }
                        break;
                    case 0:
                        $response['code'] = 500;
                        $response['status'] = false;
                        $response['message'] = 'Data User belum terdaftar di dalam sistem, Silahkan untuk melakukan Register akun.';
                        break;
                }
            } else {
                $response['code'] = 500;
                $response['status'] = false;
                $response['message'] = 'App / Build Version Tidak Sesuai!, Harap hubungi developer atau update versi aplikasi anda.';
            }

        } else {
            $response['code'] = 500;
            $response['status'] = false;
            $response['message'] = 'Jangan biarkan parameter inputan login kosong!';
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }
?>