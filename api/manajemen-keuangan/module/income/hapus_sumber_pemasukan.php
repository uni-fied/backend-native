<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id_pemasukan = $_POST['id_pemasukan'];

        # Query cek ketersediaan data
        $QUERY_CEK_ID_PEMASUKAN = mysqli_query($connecting, "SELECT COUNT(*) AS 'ketersediaan_data' FROM $table_pemasukan_mkk WHERE id_pemasukan = 5;");
        $available_data = mysqli_fetch_row($QUERY_CEK_ID_PEMASUKAN);

        # Pengecekan jika data ada maka hapus, dan sebaliknya
        if ($available_data[0] != 0) {
            # Cek Jumlah Data Source di Database
            $QUERY_CEK_JML_DATA = mysqli_query($connecting, "SELECT COUNT(*) AS 'jml_data_source' FROM $table_pemasukan_mkk;");
            $jml_data = mysqli_fetch_row($QUERY_CEK_JML_DATA);

            # Hapus data source pemasukan
            $QUERY_HAPUS_DATA = mysqli_query($connecting, "DELETE FROM $table_pemasukan_mkk WHERE (id_pemasukan = $id_pemasukan);");

            # Set Increment by Jumlah data - 1
            $set_increment = $jml_data[0] - 1;
            $QUERY_ROLLBACK = mysqli_query($connecting, "ALTER TABLE tbl_pemasukan_mkk AUTO_INCREMENT=$set_increment;");
            
            if ($QUERY_HAPUS_DATA) {
                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Anda berhasil membatalkan penambahan data.';
            }
        } else {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Gagal hapus data source income, data tidak tersedia!';
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>