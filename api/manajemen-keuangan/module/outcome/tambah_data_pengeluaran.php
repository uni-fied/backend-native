<?php

    require "../../config/controller.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id_income          = $_POST['id_income'];
        $id_user            = $_POST['id_user'];
        $headline_out       = $_POST['kategori_out'];
        $detail_pengeluaran = $_POST['detail_out'];
        $jml_pengeluaran    = $_POST['jml_out'];
        
        # 1 Tambah Data Pengeluaran
        $QUERY_TAMBAH_DATA_OUTCOME = "INSERT INTO tbl_pengeluaran_mkk (id_income, id_user, kategori_out) VALUES ($id_income, $id_user, '$headline_out');";
        $EXEC_TAMBAH_DATA_OUTCOME = mysqli_query($connecting, $QUERY_TAMBAH_DATA_OUTCOME);

        # 1.1 Mengambil data terakhir yang ditambahkan
        $QUERY_GET_NEW_DATA = "SELECT * FROM tbl_pengeluaran_mkk ORDER BY id_pengeluaran DESC LIMIT 1;";
        $EXEC_GET_NEW_DATA = mysqli_query($connecting, $QUERY_GET_NEW_DATA);
        $new_data_outcome = mysqli_fetch_row($EXEC_GET_NEW_DATA);

        # Object New Data
        # 0 'id_pengelualran' => $new_data_outcome[0]
        # 1 'id_income' => $new_data_outcome[1]
        # 2 'id_user' => $new_data_outcome[2]
        # 3 'tanggal_pengeluaran' => $new_data_outcome[3]
        # 4 'headline_out' => $new_data_outcome[4]

        # Cek berdasarkan id_pengeluaran yang didapat dari result pengambilan data baru
        $QUERY_COUNT_EXIST = "SELECT COUNT(*) AS 'item_baru_exist' FROM tbl_pengeluaran_mkk WHERE id_pengeluaran = $new_data_outcome[0];";
        $EXEC_COUNT_EXIST = mysqli_query($connecting, $QUERY_COUNT_EXIST);
        $count_exist = mysqli_fetch_row($EXEC_COUNT_EXIST);

        if ($count_exist[0] == 0) {
            $response['code'] = 404;
            $response['status'] = false;
            $response['message'] = 'Woaps!, data item pengeluaran gagal diinputkan!';
        } else {
            # 1.2 Tambah Detail Pengeluaran
            $QUERY_ADD_DETAIL_OUT = "INSERT INTO tbl_data_outcome (id_pengeluaran, keterangan, jumlah_outcome) VALUES ($new_data_outcome[0], '$detail_pengeluaran', $jml_pengeluaran);";
            $EXEC_ADD_DETAIL_OUT = mysqli_query($connecting, $QUERY_ADD_DETAIL_OUT);

            # 1.3 Mengambil data terakhir yang ditambahkan detail pengeluaran
            $QUERY_GET_NEW_DATA_OUT = "SELECT * FROM tbl_data_outcome ORDER BY id_outcome DESC LIMIT 1;";
            $EXEC_GET_NEW_DATA_OUT = mysqli_query($connecting, $QUERY_GET_NEW_DATA_OUT);
            $new_data_outcome_detail = mysqli_fetch_row($EXEC_GET_NEW_DATA_OUT);

            # Cek berdasarkan id_income yang didapat dari result pengambilan data baru
            $QUERY_COUNT_EXIST_INC = "SELECT COUNT(*) AS 'item_baru_exist' FROM tbl_data_outcome WHERE id_outcome = $new_data_outcome[0];";
            $EXEC_COUNT_EXIST_INC = mysqli_query($connecting, $QUERY_COUNT_EXIST_INC);
            $count_exist_income = mysqli_fetch_row($EXEC_COUNT_EXIST_INC);

            if ($count_exist_income[0] == 0) {
                $response['code'] = 404;
                $response['status'] = false;
                $response['message'] = 'Woaps!, data item income gagal diinputkan!';
            } else {
                # 1.4 Update sisa jumlah income pada id income tujuan (memotong jumlah dari pengeluaran)
                $QUERY_UPDATE_SISA_INCOME = "UPDATE tbl_data_income SET jml_sisa_income = jml_sisa_income - $jml_pengeluaran WHERE id_income = $new_data_outcome[1];";
                $EXEC_UPDATE_SISA_INCOME = mysqli_query($connecting, $QUERY_UPDATE_SISA_INCOME);

                $response['code'] = 200;
                $response['status'] = true;
                $response['message'] = 'Yay!, Anda berhasil menambahkan pengeluaran.';
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