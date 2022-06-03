<?php

    require "../../config/controller.php";

    $response = array();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $userid         = $_POST['userid']; 
        $tokenakses     = $_POST['token_akses_dashboard'];
        $switchmonth    = $_POST['this_month'];
        $switchyear     = $_POST['init_year'];

        # Mengambil Token Akses Dari Database
        $GET_TOKEN_INDB = "SELECT kode_user, token_akses FROM $table_user WHERE id_user = $userid;";
        $EXECUTE_QUERY_GETTOKEN = mysqli_query($connecting, $GET_TOKEN_INDB);
        $row_data = mysqli_fetch_row($EXECUTE_QUERY_GETTOKEN);
        
        # Query List Data Income / Pemasukan
        $QUERY_LIST_INCOME = "SELECT $table_income.id_income, $table_user.username, $table_user.nama_user, $table_user.gender, DAYNAME($table_pemasukan_mkk.tanggal_tambah) AS hari_tambah, DATE_FORMAT($table_pemasukan_mkk.tanggal_tambah, '%d %M %Y') AS tgl_tambah_income, DATE_FORMAT($table_pemasukan_mkk.tanggal_tambah, '%T') AS waktu_tambah, $table_pemasukan_mkk.kategori_inc, $table_income.keterangan, $table_income.jumlah_income FROM $table_income JOIN $table_pemasukan_mkk ON $table_pemasukan_mkk.id_pemasukan=$table_income.id_pemasukan JOIN $table_user ON $table_user.id_user=$table_pemasukan_mkk.id_user WHERE MONTHNAME($table_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR($table_pemasukan_mkk.tanggal_tambah) = $switchyear;";

        # Eksekusi Query List Data Income
        $EXECUTE_LIST_INCOME = mysqli_query($connecting, $QUERY_LIST_INCOME);
        
        # Query Cek Jumlah Data Income / Pemasukan
        $QUERY_JML_INCOME = "SELECT COUNT(*) AS 'jumlah_data' FROM $table_income JOIN $table_pemasukan_mkk ON $table_pemasukan_mkk.id_pemasukan=$table_income.id_pemasukan JOIN $table_user ON $table_user.id_user=$table_pemasukan_mkk.id_user WHERE MONTHNAME($table_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR($table_pemasukan_mkk.tanggal_tambah) = $switchyear;";

        # Eksekusi Query List Data Income
        $EXECUTE_QUERY_JML_INCOME = mysqli_query($connecting, $QUERY_JML_INCOME);
        $jml_income_available = mysqli_fetch_row($EXECUTE_QUERY_JML_INCOME);

        # Query Get Jumlah Saldo Bulan Ini
        $QUERY_GET_TOTAL_SALDO_THIS_MONTH = mysqli_query($connecting, "SELECT SUM($table_income.jumlah_income) AS 'jumlah_saldo' FROM $table_pemasukan_mkk JOIN $table_income ON $table_income.id_pemasukan=$table_pemasukan_mkk.id_pemasukan WHERE MONTHNAME($table_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR($table_pemasukan_mkk.tanggal_tambah) = $switchyear;");
        $EXECUTE_GET_TOTAL_SALDO = mysqli_fetch_row($QUERY_GET_TOTAL_SALDO_THIS_MONTH);

        $result = array();

        if ($row_data[1] != $tokenakses) {
            $response['code'] = 500;
            $response['status'] = false;
            $response['message'] = 'Token Anda tidak Valid!';
        } else if ($jml_income_available[0] == 0) {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Data pemasukan tidak ada di periode bulan ' . $switchmonth . " " . $switchyear . " ini, tambahkan data pemasukan yuk sekarang 🤩";
        } else {
            $response['code'] = 200;
            $response['status'] = true;
            $response['periode'] = $switchmonth . ' ' . $switchyear;
            $response['message'] = 'Data dashboard berhasil di ambil';
            $response['uid'] = $row_data[0];
            $response['informasi_saldo'] = [
                "jml_saldo" => (int)$EXECUTE_GET_TOTAL_SALDO[0],
                "jml_pengeluaran" => 0,
                "jml_tabungan" => 0,
                "jml_budget_sedekah" => 0
            ];

            while($row = mysqli_fetch_array($EXECUTE_LIST_INCOME)){
                array_push($result, 
                array(
                    'id_income'=>(int)$row[0], 
                    'username'=>$row[1], 
                    'nama_user'=>$row[2], 
                    'gender'=>$row[3],
                    'hari_tambah'=>$row[4],
                    'tgl_tambah_income'=>$row[5],
                    'waktu_tambah'=>$row[6],
                    'kategori_inc'=>$row[7],
                    'keterangan'=>$row[8],
                    'jumlah_income'=>(int)$row[9]
                ));
            }
            $response['data_income'] = $result;
        }
        
        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>