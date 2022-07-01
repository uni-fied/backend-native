<?php

    require "../../config/controller.php";

    $response = array();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $userid         = $_POST['userid']; 
        $tokenakses     = $_POST['token_akses_dashboard'];
        $switchmonth    = $_POST['this_month'];
        $switchyear     = $_POST['init_year'];

        # Mengambil Token Akses Dari Database
        $GET_TOKEN_INDB = "SELECT kode_user, token_akses FROM tbl_user WHERE id_user = $userid;";
        $EXECUTE_QUERY_GETTOKEN = mysqli_query($connecting, $GET_TOKEN_INDB);
        $row_data = mysqli_fetch_row($EXECUTE_QUERY_GETTOKEN);
        
        # Query List Data Income / Pemasukan (Tampilkan data terbaru paling atas)
        $QUERY_LIST_INCOME = "SELECT tbl_data_income.id_income, tbl_user.username, tbl_user.nama_user, tbl_user.gender, DAYNAME(tbl_pemasukan_mkk.tanggal_tambah) AS hari_tambah, DATE_FORMAT(tbl_pemasukan_mkk.tanggal_tambah, '%d %M %Y') AS tgl_tambah_income, DATE_FORMAT(tbl_pemasukan_mkk.tanggal_tambah, '%T') AS waktu_tambah, tbl_pemasukan_mkk.kategori_inc, tbl_data_income.keterangan, tbl_data_income.jumlah_income, tbl_data_income.jml_sisa_income AS 'sisa_pemasukan' FROM tbl_data_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan JOIN tbl_user ON tbl_user.id_user=tbl_pemasukan_mkk.id_user WHERE MONTHNAME(tbl_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR(tbl_pemasukan_mkk.tanggal_tambah) = $switchyear ORDER BY tbl_pemasukan_mkk.tanggal_tambah DESC;";

        # Eksekusi Query List Data Income
        $EXECUTE_LIST_INCOME = mysqli_query($connecting, $QUERY_LIST_INCOME);
        
        # Query Cek Jumlah Data Income / Pemasukan
        $QUERY_JML_INCOME = "SELECT COUNT(*) AS 'jumlah_data' FROM tbl_data_income JOIN tbl_pemasukan_mkk ON tbl_pemasukan_mkk.id_pemasukan=tbl_data_income.id_pemasukan JOIN tbl_user ON tbl_user.id_user=tbl_pemasukan_mkk.id_user WHERE MONTHNAME(tbl_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR(tbl_pemasukan_mkk.tanggal_tambah) = $switchyear;";

        # Eksekusi Query List Data Income
        $EXECUTE_QUERY_JML_INCOME = mysqli_query($connecting, $QUERY_JML_INCOME);
        $jml_income_available = mysqli_fetch_row($EXECUTE_QUERY_JML_INCOME);

        # Query Get Jumlah Saldo Bulan Ini
        $QUERY_GET_TOTAL_SALDO_THIS_MONTH = mysqli_query($connecting, "SELECT SUM(tbl_data_income.jumlah_income) AS 'jumlah_saldo' FROM tbl_pemasukan_mkk JOIN tbl_data_income ON tbl_data_income.id_pemasukan=tbl_pemasukan_mkk.id_pemasukan WHERE MONTHNAME(tbl_pemasukan_mkk.tanggal_tambah) = '$switchmonth' AND YEAR(tbl_pemasukan_mkk.tanggal_tambah) = $switchyear;");
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
            $response['message'] = 'Data dashboard berhasil di load.';
            $response['uid'] = $row_data[0];
            $response['informasi_saldo'] = [
                "jml_saldo" => (int)$EXECUTE_GET_TOTAL_SALDO[0],
                "jml_pengeluaran" => 0, // not use in apps
                "jml_tabungan" => 0,  // not use in apps
                "jml_budget_sedekah" => 0 // not use in apps
            ];

            while($row = mysqli_fetch_array($EXECUTE_LIST_INCOME)){
                
                # Query Get Jumlah Item Pengeluaran
                $QUERY_GET_COUNT_ITEM_OUT = mysqli_query($connecting, "SELECT COUNT(*) as 'jml_item_out' FROM tbl_data_income JOIN tbl_pengeluaran_mkk ON tbl_data_income.id_income=tbl_pengeluaran_mkk.id_income WHERE tbl_pengeluaran_mkk.id_income = $row[0];");
                $EXECUTE_GET_COUNT_ITEM_OUT = mysqli_fetch_row($QUERY_GET_COUNT_ITEM_OUT);

                if ($EXECUTE_GET_COUNT_ITEM_OUT[0] == 0) {
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
                        'jumlah_income'=>(int)$row[9],
                        'jml_sisa_income'=>(int)$row[10],
                        'jml_item_out'=>0
                    ));
                } else {
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
                        'jumlah_income'=>(int)$row[9],
                        'jml_sisa_income'=>(int)$row[10],
                        'jml_item_out'=>$EXECUTE_GET_COUNT_ITEM_OUT[0]
                    ));
                }
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