<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id_income = $_POST['id_income'];

        # 1 Cari Id Income dan Data Pemasukan
        $QUERY_CEK_ID_INCOME = mysqli_query($connecting, "SELECT tbl_pemasukan_mkk.id_pemasukan, tbl_data_income.id_income FROM tbl_pemasukan_mkk JOIN tbl_data_income ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE tbl_data_income.id_income = $id_income;");
        $result_checkid = mysqli_fetch_row($QUERY_CEK_ID_INCOME);

        if (isset($result_checkid[0]) && isset($result_checkid[1])) {

            # 2.1 Hapus data Pemasukan
            $QUERY_HAPUS_DATA_PEMASUKAN = mysqli_query($connecting, "DELETE FROM tbl_pemasukan_mkk WHERE id_pemasukan = $result_checkid[1];");

            # 2.2 Hapus data Income
            $QUERY_HAPUS_DATA_INCOME = mysqli_query($connecting, "DELETE FROM tbl_data_income WHERE id_income = $result_checkid[0];");

            # 3 Cek jumlah data di pemasukan dan income keseluruhan data
            $QUERY_CEK_COUNT_JML_EVALABLE = mysqli_query($connecting, "SELECT COUNT(*) AS 'jml_available' FROM tbl_pemasukan_mkk JOIN tbl_data_income ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan;");
            $result_available = mysqli_fetch_row($QUERY_CEK_COUNT_JML_EVALABLE);

            # 4 Setup Count Increment data
            $QUERY_COUNT_INCR_PMS = mysqli_query($connecting, "ALTER TABLE tbl_pemasukan_mkk AUTO_INCREMENT = $result_available[0];");
            $QUERY_COUNT_INCR_INC = mysqli_query($connecting, "ALTER TABLE tbl_data_income AUTO_INCREMENT = $result_available[0];");

            $response['code'] = 200;
            $response['status'] = true;
            $response['message'] = 'Berhasil menghapus data pemasukan';
    
        } else {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Gagal melakukan penghapusan data pemasukan! silahkan menghubungi developer';
        }

        echo json_encode($response);
        
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>