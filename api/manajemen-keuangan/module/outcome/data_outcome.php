<?php

    require "../../config/controller.php";

    $response = array();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $switchmonth    = $_POST['this_month'];
        $switchyear     = $_POST['init_year'];
        
        # Query List Data Outcome / Pengeluaran
        $QUERY_LIST_OUTCOME = "SELECT tbl_data_outcome.id_outcome, tbl_pengeluaran_mkk.id_pengeluaran, tbl_pengeluaran_mkk.kategori_out, tbl_pengeluaran_mkk.id_income AS 'id_sumber', tbl_pemasukan_mkk.kategori_inc AS 'source_income', tbl_pengeluaran_mkk.id_user, tbl_user.nama_user AS 'dikeluarkan_oleh', tbl_user.gender AS 'jenis_kelamin', DATE_FORMAT(tbl_pengeluaran_mkk.tanggal_pengeluaran, '%d %M %Y') AS 'tanggal_pengeluaran', DATE_FORMAT(tbl_pengeluaran_mkk.tanggal_pengeluaran, '%T') AS 'waktu_pengeluaran', tbl_data_outcome.keterangan, tbl_data_outcome.jumlah_outcome FROM tbl_pengeluaran_mkk JOIN tbl_data_outcome ON tbl_pengeluaran_mkk.id_pengeluaran=tbl_data_outcome.id_pengeluaran JOIN tbl_user ON tbl_pengeluaran_mkk.id_user=tbl_user.id_user JOIN tbl_data_income ON tbl_pengeluaran_mkk.id_income=tbl_data_income.id_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE MONTHNAME(tbl_pengeluaran_mkk.tanggal_pengeluaran) = '$switchmonth' AND YEAR(tbl_pengeluaran_mkk.tanggal_pengeluaran) = $switchyear;";

        # Eksekusi Query List Data Income
        $EXECUTE_LIST_OUTCOME = mysqli_query($connecting, $QUERY_LIST_OUTCOME);

        # Query Menghitung Jumlah item data Pengeluaran
        $QUERY_JUMLAH_DATA_OUTCOME = "SELECT COUNT(*) AS 'jumlah_data' FROM tbl_pengeluaran_mkk JOIN tbl_data_outcome ON tbl_pengeluaran_mkk.id_pengeluaran=tbl_data_outcome.id_pengeluaran JOIN tbl_user ON tbl_pengeluaran_mkk.id_user=tbl_user.id_user JOIN tbl_data_income ON tbl_pengeluaran_mkk.id_income=tbl_data_income.id_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE MONTHNAME(tbl_pengeluaran_mkk.tanggal_pengeluaran) = '$switchmonth' AND YEAR(tbl_pengeluaran_mkk.tanggal_pengeluaran) = $switchyear;";

        # Eksekusi Query Jumlah data Pengeluaran
        $EXECUTE_QUERY_JUMLAH_DATA_OUTCOME = mysqli_query($connecting, $QUERY_JUMLAH_DATA_OUTCOME);

        # Fetching data array 0
        $jumlah_data = mysqli_fetch_row($EXECUTE_QUERY_JUMLAH_DATA_OUTCOME);

        # Query Menghitung Jumlah Pengeluaran bulan ini
        $QUERY_JUMLAH_RUPIAH_OUT = "SELECT SUM(tbl_data_outcome.jumlah_outcome) AS 'jumlah_pengeluaran' FROM tbl_pengeluaran_mkk JOIN tbl_data_outcome ON tbl_pengeluaran_mkk.id_pengeluaran=tbl_data_outcome.id_pengeluaran JOIN tbl_user ON tbl_pengeluaran_mkk.id_user=tbl_user.id_user JOIN tbl_data_income ON tbl_pengeluaran_mkk.id_income=tbl_data_income.id_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan WHERE MONTHNAME(tbl_pengeluaran_mkk.tanggal_pengeluaran) = '$switchmonth' AND YEAR(tbl_pengeluaran_mkk.tanggal_pengeluaran) = $switchyear;";

        # Eksekusi Query jumlah pengeluaran
        $EXECUTE_QUERY_JUMLAH_RUPIAH_OUT = mysqli_query($connecting, $QUERY_JUMLAH_RUPIAH_OUT);

        # Fetching data array 0
        $total_pengeluaran = mysqli_fetch_row($EXECUTE_QUERY_JUMLAH_RUPIAH_OUT);

        $result = array();

        if ($jumlah_data[0] != 0) {
            $response['code'] = 200;
            $response['status'] = true;
            $response['jml_item_outcome'] = $jumlah_data[0] . ' Item';
            $response['jumlah_pengeluaran'] = (int)$total_pengeluaran[0];
            $response['message'] = 'Data pengeluaran bulan ' . $switchmonth . " " . $switchyear . ' tersedia!';

            while($row = mysqli_fetch_array($EXECUTE_LIST_OUTCOME)){
                array_push($result, 
                array(
                    'id_outcome'=>(int)$row[0], 
                    'id_pengeluaran'=>(int)$row[1], 
                    'kategori_out'=>$row[2], 
                    'id_sumber'=>(int)$row[3],
                    'source_income'=>$row[4],
                    'id_user'=>(int)$row[5],
                    'dikeluarkan_oleh'=>$row[6],
                    'gender'=>$row[7],
                    'tanggal_pengeluaran'=>$row[8],
                    'jam_pengeluaran'=>$row[9],
                    'keterangan'=>$row[10],
                    'jumlah_outcome'=>(int)$row[11]
                ));
            }
        
            $response['data_outcome'] = $result;
        } else {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Data pengeluaran bulan ' . $switchmonth . " " . $switchyear . ' belum ada.';
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>