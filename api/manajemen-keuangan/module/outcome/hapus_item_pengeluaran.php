<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id_outcome          = $_POST['id_outcome'];
        
        # 1 Cara Data Pengeluaran berdasarkan Id Outcomenya
        $QUERY_CARI_DATA_OUTCOME = "SELECT tbl_data_outcome.jumlah_outcome, tbl_pengeluaran_mkk.id_income FROM tbl_data_outcome JOIN tbl_pengeluaran_mkk ON tbl_data_outcome.id_pengeluaran=tbl_pengeluaran_mkk.id_pengeluaran WHERE id_outcome = $id_outcome;";
        $EXEC_CARI_DATA_OUTCOME = mysqli_query($connecting, $QUERY_CARI_DATA_OUTCOME);

        # Cek ketersediaan data
        $QUERY_AVAILABLE_DATA = "SELECT COUNT(*) AS 'available_data' FROM tbl_data_outcome JOIN tbl_pengeluaran_mkk ON tbl_data_outcome.id_pengeluaran=tbl_pengeluaran_mkk.id_pengeluaran WHERE id_outcome = $id_outcome;";
        $EXEC_AVAILABLE_DATA = mysqli_query($connecting, $QUERY_AVAILABLE_DATA);
        $item_pengeluaran = mysqli_fetch_row($EXEC_AVAILABLE_DATA);

        if ($item_pengeluaran[0] == 0) {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Woaps!, data item pengeluaran tidak ada didalam database!';
        } else {
            # 1.1 Mengambil data kandidat yang ingin di hapus berdasarkan
            $QUERY_FETCH_DATA_OUT = "SELECT tbl_data_outcome.jumlah_outcome, tbl_pengeluaran_mkk.id_income FROM tbl_data_outcome JOIN tbl_pengeluaran_mkk ON tbl_data_outcome.id_pengeluaran=tbl_pengeluaran_mkk.id_pengeluaran WHERE id_outcome = $id_outcome;";
            $EXEC_GET_CANDIDATE_DATA = mysqli_query($connecting, $QUERY_FETCH_DATA_OUT);
            $candidate_data_out = mysqli_fetch_row($EXEC_GET_CANDIDATE_DATA);

            # Keterangan : Object Candidate data
            # 0 'jumlah_outcome' => $candidate_data_out[0]
            # 1 'id_income' => $candidate_data_out[1]

            # Cek ketersediaan data
            $QUERY_AVAILABLE_FETCH = "SELECT COUNT(*) AS 'item_fetch_tersedia' FROM tbl_data_outcome JOIN tbl_pengeluaran_mkk ON tbl_data_outcome.id_pengeluaran=tbl_pengeluaran_mkk.id_pengeluaran WHERE id_outcome = $id_outcome;";
            $EXEC_AVAILABLE_FETCH = mysqli_query($connecting, $QUERY_AVAILABLE_FETCH);
            $availbale_candidate = mysqli_fetch_row($EXEC_AVAILABLE_FETCH);

            # 1.2 Rollback sisa pemasukan yang sebelumnya dipotong
            $QUERY_ROLLBACK_INCOME_SISA = "UPDATE tbl_data_income SET jml_sisa_income = jml_sisa_income + $candidate_data_out[0] WHERE id_income = $candidate_data_out[1];";
            $EXEC_ROLLBACK_INCOME_SISA = mysqli_query($connecting, $QUERY_ROLLBACK_INCOME_SISA);

            # 1.3 Hapus data outcome 
            $QUERY_HAPUS_OUTCOME = "DELETE FROM tbl_data_outcome WHERE id_outcome = $id_outcome;";
            $EXEC_HAPUS_OUTCOME = mysqli_query($connecting, $QUERY_HAPUS_OUTCOME);
            
            # 1.4 Hapus data detail pengeluaran berdasarkan Id kandidat
            $QUERY_HAPUS_DETAIL_OUT = "DELETE FROM tbl_pengeluaran_mkk WHERE id_pengeluaran = $id_outcome;";
            $EXEC_HAPUS_DETAIL_OUT = mysqli_query($connecting, $QUERY_HAPUS_DETAIL_OUT);

            # 1.5 Cek count data pada table outcome
            $QUERY_CEK_COUNT_OUT = "SELECT COUNT(*) FROM tbl_pengeluaran_mkk;";
            $EXEC_CEK_COUNT_OUT = mysqli_query($connecting, $QUERY_CEK_COUNT_OUT);
            $count_outcome = mysqli_fetch_row($EXEC_CEK_COUNT_OUT);

            # 1.6 Cek count data pada table detail pengeluaran
            $QUERY_CEK_COUNT_DTLOUT = "SELECT COUNT(*) FROM tbl_data_outcome;";
            $EXEC_CEK_COUNT_DTLOUT = mysqli_query($connecting, $QUERY_CEK_COUNT_DTLOUT);
            $count_detail_out = mysqli_fetch_row($EXEC_CEK_COUNT_DTLOUT);

            if ($count_outcome[0] == $count_detail_out[0]) {

                # 1.7 Rollback Increment Pengeluaran
                $QUERY_ALTER_DETAIL_OUT = "ALTER TABLE tbl_pengeluaran_mkk AUTO_INCREMENT=$count_detail_out[0];";
                $EXEC_ALTER_DETAIL_OUT = mysqli_query($connecting, $QUERY_ALTER_DETAIL_OUT);

                # 1.8 Rollback Increment Outcome
                $QUERY_ALTER_OUT = "ALTER TABLE tbl_data_outcome AUTO_INCREMENT=$count_outcome[0];";
                $EXEC_ALTER_OUT = mysqli_query($connecting, $QUERY_ALTER_OUT);

                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Yay!, Hapus data & rollback berhasil';
            } else {
                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Woaps!, Hapus data & rollback gagal dilakukan harap hubungi tim developer';
            }
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>