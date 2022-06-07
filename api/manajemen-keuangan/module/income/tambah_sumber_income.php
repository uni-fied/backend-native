<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userid         = $_POST['userid'];
        $kategori       = $_POST['kategori_income'];
        
        if(!empty($userid) && !empty($kategori)) {
            # Query Tambah Pemasukan
            $QUERY_TAMBAH_PEMASUKAN = "INSERT INTO $table_pemasukan_mkk (id_user, kategori_inc) VALUE ($userid, '$kategori');";
            $EXEC_TAMBAH_PEMASUKAN = mysqli_query($connecting, $QUERY_TAMBAH_PEMASUKAN);

            # Query Ambil Data Pemasukan yang Baru di Tambahkan
            $QUERY_GET_NEW_ADDED = "SELECT * FROM tbl_pemasukan_mkk ORDER BY id_pemasukan DESC LIMIT 1;";
            $EXEC_GET_DATA_NEW_ADDED = mysqli_query($connecting, $QUERY_GET_NEW_ADDED);

            # Fetching with Row
            $fetch_row_pemasukan = mysqli_fetch_row($EXEC_GET_DATA_NEW_ADDED);

            $response['code'] = 201;
            $response['status'] = true;
            $response['message'] = 'Yay!, Sumber pemasukan berhasil di tambahkan, Berikut isi data detail pemasukan Anda.';
            $response['data_pemasukan'] = [
                "id_pemasukan"=>$fetch_row_pemasukan[0],
                "id_user"=>$fetch_row_pemasukan[1],
                "tanggal_tambah"=>$fetch_row_pemasukan[2],
                "kategori_income"=>$fetch_row_pemasukan[3]
            ];
            
        } else {
            $response['code'] = 500;
            $response['status'] = false;
            $response['message'] = 'Mohon maaf!, Proses simpan gagal!';
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>