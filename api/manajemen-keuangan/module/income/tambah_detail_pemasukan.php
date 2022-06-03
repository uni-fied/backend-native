<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id_income          = $_POST['id_pemasukan'];
        $keterangan         = $_POST['keterangan'];
        $jumlah_income      = $_POST['jumlah_income'];
        
        if(!empty($id_income) && !empty($keterangan) && !empty($jumlah_income)) {
            # Query Tambah Data Pemasukan
            $QUERY_TAMBAH_DATA_INCOME = "INSERT INTO $table_income (id_pemasukan, keterangan, jumlah_income) VALUE ($id_income, '$keterangan', $jumlah_income);";
            $EXEC_TAMBAH_DATA_PEMASUKAN = mysqli_query($connecting, $QUERY_TAMBAH_DATA_INCOME);

            # Query Ambil Data Pemasukan yang Baru di Tambahkan
            $QUERY_GET_NEW_ADDED = "SELECT * FROM  $table_income ORDER BY id_income DESC LIMIT 1;";
            $EXEC_GET_DATA_NEW_ADDED = mysqli_query($connecting, $QUERY_GET_NEW_ADDED);

            $result = array();

            # Show Data Pemasukan
            while($row = mysqli_fetch_array($EXEC_GET_DATA_NEW_ADDED)) {
                array_push(
                    $result, array (
                        "id_income"=>(int)$row['id_income'],
                        "id_pemasukan"=>(int)$row['id_pemasukan'],
                        "keterangan"=>$row['keterangan'],
                        "jumlah_income"=>(int)$row['jumlah_income']
                    ));
            }

            $response['code'] = 201;
            $response['status'] = true;
            $response['message'] = 'Yay!, Data income berhasil di tambahkan';
            $response['data_pemasukan'] = $result;
            
        } else {
            $response['code'] = 500;
            $response['status'] = false;
            $response['message'] = 'Mohon maaf!, Proses simpan data income gagal!';
        }

        echo json_encode($response);
    } else {
        $response['code'] = 404;
        $response['status'] = false;
        $response['message'] = 'Request API ini pakai Method POST, sesuaikan lebih dahulu';
        echo json_encode($response);
    }

?>