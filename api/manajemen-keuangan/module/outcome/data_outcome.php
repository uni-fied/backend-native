<?php

    require "../../config/controller.php";

    $response = array();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $switchmonth    = $_POST['this_month'];
        $switchyear     = $_POST['init_year'];
        
        # Query List Data Outcome / Pengeluaran
        $QUERY_LIST_OUTCOME = "SELECT tbl_data_outcome.id_outcome, tbl_pengeluaran_mkk.id_pengeluaran, tbl_pengeluaran_mkk.kategori_out, tbl_pengeluaran_mkk.id_income AS 'id_sumber', tbl_pemasukan_mkk.kategori_inc AS 'source_income', tbl_pengeluaran_mkk.id_user, tbl_user.nama_user AS 'dikeluarkan_oleh', tbl_pengeluaran_mkk.tanggal_pengeluaran, tbl_data_outcome.keterangan, tbl_data_outcome.jumlah_outcome FROM tbl_pengeluaran_mkk JOIN tbl_data_outcome ON tbl_pengeluaran_mkk.id_pengeluaran=tbl_data_outcome.id_pengeluaran JOIN tbl_user ON tbl_pengeluaran_mkk.id_user=tbl_user.id_user JOIN tbl_data_income ON tbl_pengeluaran_mkk.id_income=tbl_data_income.id_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE MONTHNAME(tbl_pengeluaran_mkk.tanggal_pengeluaran) = '$switchmonth' AND YEAR(tbl_pengeluaran_mkk.tanggal_pengeluaran) = $switchyear;";

        # Eksekusi Query List Data Income
        $EXECUTE_LIST_OUTCOME = mysqli_query($connecting, $QUERY_LIST_OUTCOME);

        $QUERY_JUMLAH_DATA_OUTCOME = "SELECT COUNT(*) AS 'jumlah_data' FROM tbl_pengeluaran_mkk JOIN tbl_data_outcome ON tbl_pengeluaran_mkk.id_pengeluaran=tbl_data_outcome.id_pengeluaran JOIN tbl_user ON tbl_pengeluaran_mkk.id_user=tbl_user.id_user JOIN tbl_data_income ON tbl_pengeluaran_mkk.id_income=tbl_data_income.id_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE MONTHNAME(tbl_pengeluaran_mkk.tanggal_pengeluaran) = '$switchmonth' AND YEAR(tbl_pengeluaran_mkk.tanggal_pengeluaran) = $switchyear;";

        $EXECUTE_QUERY_JUMLAH_DATA_OUTCOME = mysqli_query($connecting, $QUERY_JUMLAH_DATA_OUTCOME);

        $jumlah_data = mysqli_fetch_row($EXECUTE_QUERY_JUMLAH_DATA_OUTCOME);

        $result = array();

        if ($jumlah_data[0] != 0) {
            $response['code'] = 200;
            $response['status'] = true;
            $response['message'] = 'Data pengeluaran bulan ' . $switchmonth . " " . $switchyear . ' tersedia!';

            while($row = mysqli_fetch_array($EXECUTE_LIST_OUTCOME)){
                array_push($result, 
                array(
                    'id_outcome'=>(int)$row[0], 
                    'id_pengeluaran'=>$row[1], 
                    'kategori_out'=>$row[2], 
                    'id_sumber'=>$row[3],
                    'source_income'=>$row[4],
                    'id_user'=>$row[5],
                    'dikeluarkan_oleh'=>$row[6],
                    'tanggal_pengeluaran'=>$row[7],
                    'keterangan'=>$row[8],
                    'jumlah_outcome'=>(int)$row[9]
                ));
            }
        
            $response['data_outcome'] = $result;
        } else {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Data pengeluaran bulan ' . $switchmonth . " " . $switchyear . ' tidak tersedia!';
        }

        
        
        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>