<?php
namespace CB;

require_once 'init.php';

// untuk filter solr

/**
 * Creted By : Usva Dhiar P.
 * Date : 2017-12-21
 * Description : Fungsi ini berguna untuk mendapatkan 1 data berdasarkan input parameter name,pid, sort desc by date
**/
if($_GET['url_uploader'])
{
    $url_solr_get_files = $_POST['url_solr_get_files'];
    $name = $_POST['name'];
    $pid = $_POST['pid'];
    
    // http://192.168.1.169:8983/solr/cb_chanthel/select?q=*%3A*&fq=pid%3A205&fq=name%3Anewversion.png&wt=json&indent=true
    $url = $url_solr_get_files.$pid."&fq=name%3A".$name."&wt=json&indent=true&sort=date%20desc";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,    
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "postman-token: 2354ddb2-84b9-982f-63ba-7d9612178ceb"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) 
    {
        echo "cURL Error #:" . $err;
    } 
    else 
    {
        echo $response;
    }
}
else if($_GET['url_upload_pdf'])
{
    $arr_image_type = array(
        'JPEG',
        'jpeg',
        'JPG',
        'jpg',
        'JFIF',
        'jfif',
        'BMP',
        'bmp',
        'PNG',
        'png',
        'TIFF',
        'tiff',
        'GIF',
        'gif'
    );
    
    $msg = '';
    $ar_success = array();
    $ar_target_file  = array();    
    
    foreach ($_FILES as $k => $v) 
    {

        if(isset($v['name']) && $v['name'] != "" && $v['error'] != "4")
        {            
            $target_file = tempnam(sys_get_temp_dir(), 'reimburse-').basename($v["name"]); // modif            
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            if (file_exists($target_file)) 
            {
                $msg = "Sorry, file already exists.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
            }
            else if (!in_array($imageFileType, $arr_image_type)) 
            {
                $msg = "Sorry, can't upload file with extension $imageFileType.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
                
            }
            // Check file size
            else if ($v["size"] > 500000) 
            {
                $msg = "Sorry, your file is too large.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
            }
            else 
            {
                if (move_uploaded_file($v["tmp_name"], $target_file)) 
                {
                    chmod($target_file,0777);
                    $msg = "The file ". basename($v["name"]). " has been uploaded.";
                    
                    // $target_file --> /tmp/asdas.png
                    // menjalankan curl
                    
                    array_push($ar_success, 1);
                    array_push($ar_target_file, $target_file);
                } 
                else 
                {
                    $msg = "Sorry, there was an error uploading your file.".filesize($target_file).$v["name"];
                    array_push($ar_success, 0);
                    array_push($ar_target_file, '');
                }
            }
        }
        else
        {
            array_push($ar_success, 0);
            array_push($ar_target_file, '');
        }
    }
    
    echo '{count:'. count($_FILES).',success:'. json_encode($ar_success).',target_file:'.  json_encode($ar_target_file).'}';    
}
else if($_GET['url_upload_bukti'])
{
    $arr_image_type = array(
        'JPEG',
        'jpeg',
        'JPG',
        'jpg',
        'JFIF',
        'jfif',
        'BMP',
        'bmp',
        'PNG',
        'png',
        'TIFF',
        'tiff',
        'GIF',
        'gif'
    );
    
    $msg = '';
    $ar_success = array();
    $ar_target_file  = array();    
    
    foreach ($_FILES as $k => $v) 
    {

        if(isset($v['name']) && $v['name'] != "" && $v['error'] != "4")
        {            
            $target_file = tempnam(sys_get_temp_dir(), $_GET['url_upload_bukti'].'-').basename($v["name"]); // modif            
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

            if (file_exists($target_file)) 
            {
                $msg = "Sorry, file already exists.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
            }
            else if (!in_array($imageFileType, $arr_image_type)) 
            {
                $msg = "Sorry, can't upload file with extension $imageFileType.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
                
            }
            // Check file size
            else if ($v["size"] > 500000) 
            {
                $msg = "Sorry, your file is too large.";
                array_push($ar_success, 0);
                array_push($ar_target_file, '');
            }
            else 
            {
                if (move_uploaded_file($v["tmp_name"], $target_file)) 
                {
                    chmod($target_file,0777);
                    $msg = "The file ". basename($v["name"]). " has been uploaded.";
                    
                    // $target_file --> /tmp/asdas.png
                    // menjalankan curl
                    
                    array_push($ar_success, 1);
                    array_push($ar_target_file, $target_file);
                } 
                else 
                {
                    $msg = "Sorry, there was an error uploading your file.".filesize($target_file).$v["name"];
                    array_push($ar_success, 0);
                    array_push($ar_target_file, '');
                }
            }
        }
        else
        {
            array_push($ar_success, 0);
            array_push($ar_target_file, '');
        }
    }
    
    echo '{count:'. count($_FILES).',success:'. json_encode($ar_success).',target_file:'.  json_encode($ar_target_file).'}';    
}
else if($_GET['url_upload_chanthel'])
{
    // $url_api_upload =  "http://training-00.labs247.com/chanthelAPI/index.php";
    $url_api_upload =  $_POST["url_api_upload"];
    // $iddir = "260";
    $id_folder = $_POST['id_folder'];
    $targetFile = $_POST['targetFile'];
    $nameFile = $_POST['nameFile'];
    $usr = $_POST['usr'];
    $pswd = $_POST['pswd'];
    
//      url_chanthel:url_chanthel,
//                                        targetFile:targetFile,
//                                        nameFile:nameFile,
//                                        usr:usr,
//                                        pswd:pswd
    
//    $targetFile = $_POST['targetFile'];
//    echo '$targetFile='.$targetFile;
//    echo '$nameFile='.$nameFile;
//    echo '__$id_folder='.$id_folder;
//    echo '__url='.$url_api_upload;
    
//    $result = "";
//    $result = shell_exec("php ajaxUpload.php $targetFile");
    $result = exec("php ajaxUpload.php  "
                . "$usr "
                . "$pswd "
                . "$url_api_upload "
                . "$nameFile "
                . "$id_folder "
                . "$targetFile");
    echo $result;
}

else if($_GET['url_pdfManagerMonitorAnggaran'])
{
   
    
    
    
$tahun  = $_GET['tahun'];
$kegiatan  = $_GET['kegiatan'];
if(isset($_GET['tahun'])){
    $rencana = " AND  rencana.tgl_kegiatan like '%$tahun%' ";
    $realisasi = " AND  realisasi.tgl_kegiatan like '%$tahun%'";
}
if(isset($_GET['kegiatan'])){
    $rencana = " AND  rencana.keterangan_kegiatan like '%$kegiatan%' ";
    $realisasi = " AND  realisasi.keterangan_kegiatan like '%$kegiatan%'";
}
//    
// $th = $_GET['url_pdfManagerMonitorAnggaran'];   
    
$anggaran_kode = "anggaran.kode AS anggaran_id";    
    $anggaran_nilai = "anggaran.nilai_anggaran AS nilai_anggaran";
    $id_ls_rencana = "rencana.id AS id_rencana";
    $nominal_rencana = "rencana.nominal_anggaran AS nominal_rencana";
    $selisih_pengajuan_rencana = "rencana.selisih_pengajuan AS selisih_pengajuan_rencana";
    $ket_kegiatan_rencana = "rencana.keterangan_kegiatan AS ket_kegiatan_rencana";    
    $koord_output_rencana = "rencana.koord_output AS koord_output_rencana";
    $tgl_kegiatan_rencana = "rencana.tgl_kegiatan AS tgl_kegiatan_rencana";
    
    $id_realisasi = "realisasi.id AS id_realisasi";
    $no_memo_rencana = "rencana.no_memo AS no_memo_rencana";        
    $realisasi_id_rencana = "realisasi.id_rencana AS realisasi_id_rencana";
    $ket_kegiatan_realisasi = "realisasi.keterangan_kegiatan AS ket_kegiatan_realisasi";    
    $no_memo_realisasi = "realisasi.no_memo AS no_memo_realisasi";    
    $nominal_realisasi = "realisasi.nominal_anggaran AS nominal_realisasi";
    $ppn_realisasi = "realisasi.ppn AS ppn_realisasi";
    $pph_realisasi = "realisasi.pph AS pph_realisasi";
    $type_realisasi = "realisasi.type AS type_realisasi";
    $koord_output_realisasi = "realisasi.koord_output AS koord_output_realisasi";
    $tgl_kegiatan_realisasi = "realisasi.tgl_kegiatan AS tgl_kegiatan_realisasi";
    
    $select_count_anggaran = "SELECT COUNT(`anggaran_id`) AS count_anggaran_id, anggaran_id";
    $join_anggaran_rencana = "dii_dm_anggaran anggaran ON rencana.anggaran_id = anggaran.id";
    $join_anggaran_realisasi = "dii_dm_anggaran anggaran ON realisasi.anggaran_id = anggaran.id";
    $q_select_rencana = " SELECT $anggaran_kode,"
                        . "$anggaran_nilai,"
                        . "$id_ls_rencana,"
                        . "$no_memo_rencana,"
                        . "$nominal_rencana,"
                        . "$selisih_pengajuan_rencana,"
                        . "$koord_output_rencana,"
                        . "$tgl_kegiatan_rencana,"
                        . "$ket_kegiatan_rencana ";
                    
                    $q_select_realisasi = " SELECT "
                        . "$anggaran_kode,"
                        . "$anggaran_nilai,"
                        . "$id_realisasi,"
                        . "$realisasi_id_rencana,"
                        . "$no_memo_realisasi,"                                                
                        . "$nominal_realisasi,"
                        . "$ppn_realisasi,"
                        . "$pph_realisasi,"
                        . "$type_realisasi,"
                        . "$koord_output_realisasi,"
                        . "$tgl_kegiatan_realisasi,"
                        . "$ket_kegiatan_realisasi "; 
$q_select = " SELECT ".$anggaran_kode.","
                        . "$anggaran_nilai,"
                        . "$id_ls_rencana,"
                        . "$no_memo_rencana,"
                        . "$nominal_rencana,"
                        . "$selisih_pengajuan_rencana,"
                        . "$ket_kegiatan_rencana,"
                        . "$koord_output_rencana,"
                        . "$tgl_kegiatan_rencana,"
                        . "$id_realisasi,"
                        . "$realisasi_id_rencana,"
                        . "$no_memo_realisasi,"                                                
                        . "$nominal_realisasi,"
                        . "$ppn_realisasi,"
                        . "$pph_realisasi,"
                        . "$type_realisasi,"
                        . "$koord_output_realisasi,"
                        . "$tgl_kegiatan_realisasi,"
                        . "$ket_kegiatan_realisasi ";
$limit = '0,10';                                                                                                              
    $where_not_null = " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) ";
    
    $q_total = DB\dbQuery(
    "SELECT COUNT(`anggaran_id`) AS count_anggaran_id FROM (".$q_select
        . ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
    . "RIGHT JOIN  dii_dm_anggaran anggaran "
        . "ON rencana.anggaran_id = anggaran.id " .
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
    . "LEFT JOIN dii_realisasi_anggaran realisasi "
        . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
    . "GROUP by anggaran_id) count_dii_dm_anggaran"
    );
    
    // SELECT anggaran.id AS anggaran_id,rencana.no_memo AS no_memo_rencana,rencana.nominal_anggaran AS nominal_rencana,rencana.selisih_pengajuan AS selisih_pengajuan_rencana,rencana.keterangan_kegiatan AS ket_kegiatan_rencana, realisasi.no_memo AS no_memo_realisasi,realisasi.keterangan_kegiatan AS ket_kegiatan_realisasi FROM dii_ls_rencana rencana RIGHT JOIN  dii_dm_anggaran anggaran ON rencana.anggaran_id = anggaran.id LEFT JOIN dii_realisasi_anggaran realisasi ON realisasi.anggaran_id = anggaran.id GROUP by anggaran_id;

    $total = $q_total->fetch_assoc()['count_anggaran_id'];

    
    $q_group =  DB\dbQuery(
    "SELECT anggaran_id FROM (".$q_select
        . ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
    . "RIGHT JOIN  dii_dm_anggaran anggaran "
        . "ON rencana.anggaran_id = anggaran.id " .
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
    . "LEFT JOIN dii_realisasi_anggaran realisasi "
        . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
    . "GROUP by anggaran_id) count_dii_dm_anggaran ORDER BY anggaran_id ASC "." LIMIT ".$limit
    );                              
    $data = array();

    while($r = $q_group->fetch_assoc()) 
    {       
        $anggaran_id = $r['anggaran_id'];
        $q_data_rencana =  DB\dbQuery(
            $q_select_rencana. ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
            . "JOIN  dii_dm_anggaran anggaran "
                . "ON rencana.anggaran_id = anggaran.id " .
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
            . " WHERE NOT (rencana.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'  AND rencana.is_rampung = '0' ".$rencana
        );

        $q_data_realisasi =  DB\dbQuery(
            $q_select_realisasi. ",realisasi.id_status AS id_status_realisasi, state.name AS name_status FROM dii_realisasi_anggaran realisasi "
            . "JOIN  dii_dm_anggaran anggaran "
                . "ON realisasi.anggaran_id = anggaran.id "
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON realisasi.id_status = state.id "
            . " WHERE NOT (realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."' ".$realisasi
        );

        $tot_rencana_realisasi = 0;

        while($r_data = $q_data_rencana->fetch_assoc()) 
        {                            
            $nominal_rencana = ($r_data["nominal_rencana"] != null ? $r_data["nominal_rencana"] : 0);
            $tot_rencana_realisasi += $nominal_rencana;  
            $sls = ($r_data["selisih_pengajuan_rencana"] != null ? $r_data["selisih_pengajuan_rencana"] : "0");
            
            array_push($data, array(
                'anggaran_id' => $r_data["anggaran_id"],
                'name_status' => $r_data["name_status"],
                'id_rencana' => ($r_data["id_rencana"] != null ? $r_data["id_rencana"] : "-"),
                'no_memo_rencana' => ($r_data["no_memo_rencana"] != null ? $r_data["no_memo_rencana"] : "-"),
                'nominal_rencana' => "Rp. ".number_format($nominal_rencana,0,",",".").",00",
                'selisih_pengajuan_rencana' => "Rp. ".number_format($sls,0,",",".").",00",
                'ket_kegiatan_rencana' => ($r_data["ket_kegiatan_rencana"] != null ? $r_data["ket_kegiatan_rencana"] : "-"),
                'koord_output_rencana' => ($r_data["koord_output_rencana"] != null ? $r_data["koord_output_rencana"] : "-"),
                'tgl_kegiatan_rencana' => ($r_data["tgl_kegiatan_rencana"] != null ? $r_data["tgl_kegiatan_rencana"] : "-"),
                'id_realisasi' => "-",
                'realisasi_id_rencana'=> "-",
                'no_memo_realisasi' => "-",
                'nominal_realisasi' => 0,
                'ppn_realisasi' => 0,
                'pph_realisasi' => 0,
                'ket_kegiatan_realisasi' => "-",
                'type_realisasi' => "-",
                'koord_output_realisasi' => "-",
                'tgl_kegiatan_realisasi' => "-",                                        
                "sisa_anggaran" => 0
            ));                                                                                         
        }

        while($r_data = $q_data_realisasi->fetch_assoc()) 
        {                            
            $nominal_realisasi = ($r_data["nominal_realisasi"] != null ? $r_data["nominal_realisasi"] : 0);
            $tot_rencana_realisasi += $nominal_realisasi;                                                                        

            array_push($data, array(
                'anggaran_id' => $r_data["anggaran_id"],
                'id_rencana' => "-",
                'name_status' => $r_data["name_status"],
                'no_memo_rencana' => "-",
                'nominal_rencana' => 0,
                'selisih_pengajuan_rencana' => 0,
                'ket_kegiatan_rencana' => "-",
                'koord_output_rencana' => "-",
                'tgl_kegiatan_rencana' => "-",                                                                                
                'id_realisasi' => ($r_data["id_realisasi"] != null ? $r_data["id_realisasi"] : "-"),
                'realisasi_id_rencana'=> ($r_data["realisasi_id_rencana"] != null ? $r_data["realisasi_id_rencana"] : "-"),
                'no_memo_realisasi' => ($r_data["no_memo_realisasi"] != null ? $r_data["no_memo_realisasi"] : "-"),
                'nominal_realisasi' => "Rp. ".number_format($nominal_realisasi,0,",",".").",00",
                'ppn_realisasi' => ($r_data["ppn_realisasi"] != null ? $r_data["ppn_realisasi"] : 0),
                'pph_realisasi' => ($r_data["pph_realisasi"] != null ? $r_data["pph_realisasi"] : 0),
                'ket_kegiatan_realisasi' => ($r_data["ket_kegiatan_realisasi"] != null ? $r_data["ket_kegiatan_realisasi"] : "-"),
                'type_realisasi' => ($r_data["type_realisasi"] != null ? $r_data["type_realisasi"] : "-"),
                'koord_output_realisasi' => ($r_data["koord_output_realisasi"] != null ? $r_data["koord_output_realisasi"] : "-"),
                'tgl_kegiatan_realisasi' => ($r_data["tgl_kegiatan_realisasi"] != null ? $r_data["tgl_kegiatan_realisasi"] : "-"),
                "sisa_anggaran" => 0
            ));                                                                                
        }

        $q_data =  DB\dbQuery(
            $q_select. ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
            . "RIGHT JOIN  dii_dm_anggaran anggaran "
                . "ON rencana.anggaran_id = anggaran.id " .
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
            . "LEFT JOIN dii_realisasi_anggaran realisasi "
                . "ON realisasi.anggaran_id = anggaran.id "
            . " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'"
        );
        $r_data = $q_data->fetch_assoc();
        $r_nilai = (int)$r_data['nilai_anggaran'];

        // id_rencana id_realisasi                              
        array_push($data, array(
            'anggaran_id' => $r_data["anggaran_id"],
            'id_rencana' => "",
            'name_status' => "",
            'no_memo_rencana' => "",
            'nominal_rencana' => "",
            'selisih_pengajuan_rencana' => "",
            'ket_kegiatan_rencana' => "",
            'koord_output_rencana' => "",
            'tgl_kegiatan_rencana' => "",                                                                        
            'id_realisasi' => "",
            'realisasi_id_rencana'=> "",
            'no_memo_realisasi' => "",
            'nominal_realisasi' => "",
            'ppn_realisasi' => "",
            'pph_realisasi' => "",
            'ket_kegiatan_realisasi' =>"",
            'type_realisasi' => "",
            'koord_output_realisasi' => "",
            'tgl_kegiatan_realisasi' => "",                                    
            "sisa_anggaran" => "Rp. ".number_format(($r_nilai - $tot_rencana_realisasi),0,",",".").",00"
        ));
    }                                     

//    $data = array(
//        "success"=> true,
//        "total"=> $total,
//        "data" => $data
//    );
//
//    echo json_encode($data);      
    
//    exit();   
 
 
 
//    
$pdf = new MYPDFANGGARAN(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Riset Dikti');
$pdf->SetTitle('Monitor Anggaran '.date("l, Y-m-d").'.pdf');
$pdf->SetSubject('Monitor Anggaran');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//$pdf->AddPage('L', 'A4 LANDSCAPE');
//$pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 100, "", "");
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
//$pdf->SetFont('dejavusans', '', 9, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();
$pdf->AddPage('L', 'A4 LANDSCAPE');
// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		$pdf->SetFont('helvetica', '', 6);
// Set some content to print
                

         
         
$html = <<<EOD
    <h3>&nbsp;Rencana (LS) & Realisasi Anggaran (R) </h3>
        <br>&nbsp;
<table cellspacing="0" cellpadding="5" border="1" style="border-color: black">
    <tr style="background-color: #f5f5f5; color:black;">
        <td width="70px" align="center" >No. Memo (LS)</td>
        <td width="70px" align="center" >Status</td>
        <td width="70px" align="center" >Nominal (LS)</td>
        <td width="70px" align="center" >Selisih Pengajuan (LS)</td>
        <td width="70px" align="center" >Ket.Kegiatan (LS)</td>
        <td width="70px" align="center" >Koord. (LS)</td>
        <td width="70px" align="center" >Tgl.Kegiatan (LS)</td>
        <td width="70px" align="center" >No.Memo (R)</td>
        <td width="70px" align="center" >Nominal (R)</td>
        <td width="70px" align="center" >Ket.Kegiatan (R)</td>
        <td width="50px" align="center" >Type (R)</td>
        <td width="70px" align="center" >Koord. (R)</td>
        <td width="70px" align="center" >Tgl.Kegiatan (R)</td>
        <td width="70px" align="center" >Sisa</td>
    </tr>
    
        
EOD;
$newrec = 0;
$idrec = "";
foreach ($data as $key => $value){
    //
    if($key==0){
           $newrec==0; 
    }else{
        if($idrec != $data[$key]["anggaran_id"]){
            $newrec=0; 
        }
    }
    $idrec = $data[$key]["anggaran_id"];

    
//    else{
        $rencana1 = $data[$key]["rencana1"];
        $nominal_rencana = $data[$key]["nominal_rencana"];
        $selisih_pengajuan_rencana = $data[$key]["selisih_pengajuan_rencana"];
        $ket_kegiatan_rencana = $data[$key]["ket_kegiatan_rencana"];
        $koord_output_rencana = $data[$key]["koord_output_rencana"];
        $tgl_kegiatan_rencana = $data[$key]["tgl_kegiatan_rencana"];
        $no_memo_realisasi = $data[$key]["no_memo_realisasi"];
        $nominal_realisasi = $data[$key]["nominal_realisasi"];
        $ket_kegiatan_realisasi = $data[$key]["ket_kegiatan_realisasi"];
        $type_realisasi = $data[$key]["type_realisasi"];
        $koord_output_realisasi = $data[$key]["koord_output_realisasi"];
        $tgl_kegiatan_realisasi = $data[$key]["tgl_kegiatan_realisasi"];
        $sisa_anggaran = $data[$key]["sisa_anggaran"];
        $name_status = $data[$key]["name_status"];
        if($newrec==0){
            if($type_realisasi!=""){
                $newrec = 1;
        
$html .= <<<EOD
    <tr style="background-color: #f9f7ed; color:#3764a0;">
        <td colspan="14">Anggaran ID : $idrec</td>
    </tr>
EOD;
            }
        }
if($newrec == 1)
    if($key % 2 ==0){
        if($type_realisasi==""){
                
            $html .= <<<EOD

            <tr>
                <td width="70px">$rencana1</td>
                <td width="70px">$name_status</td>
                <td width="70px">$nominal_rencana</td>
                <td width="70px">$selisih_pengajuan_rencana</td>
                <td width="70px">$ket_kegiatan_rencana</td>
                <td width="70px">$koord_output_rencana</td>
                <td width="70px">$tgl_kegiatan_rencana</td>
                <td width="70px">$no_memo_realisasi</td>
                <td width="70px">$nominal_realisasi</td>
                <td width="70px">$ket_kegiatan_realisasi</td>
                <td width="50px">$type_realisasi</td>
                <td width="70px">$koord_output_realisasi</td>
                <td width="70px">$tgl_kegiatan_realisasi</td>
                <td width="70px" style="background-color: #99ff99" >$sisa_anggaran</td>
            </tr>
EOD;
            }else{
                
            $html .= <<<EOD

            <tr>
                <td width="70px">$rencana1</td>
                <td width="70px">$name_status</td>
                <td width="70px">$nominal_rencana</td>
                <td width="70px">$selisih_pengajuan_rencana</td>
                <td width="70px">$ket_kegiatan_rencana</td>
                <td width="70px">$koord_output_rencana</td>
                <td width="70px">$tgl_kegiatan_rencana</td>
                <td width="70px">$no_memo_realisasi</td>
                <td width="70px">$nominal_realisasi</td>
                <td width="70px">$ket_kegiatan_realisasi</td>
                <td width="50px">$type_realisasi</td>
                <td width="70px">$koord_output_realisasi</td>
                <td width="70px">$tgl_kegiatan_realisasi</td>
                <td width="70px">$sisa_anggaran</td>
            </tr>
EOD;
            }
        }else{
            if($type_realisasi==""){
                $html .= <<<EOD

            <tr style="background-color: #fafafa;">
                <td width="70px">$rencana1</td>
                <td width="70px">$name_status</td>
                <td width="70px">$nominal_rencana</td>
                <td width="70px">$selisih_pengajuan_rencana</td>
                <td width="70px">$ket_kegiatan_rencana</td>
                <td width="70px">$koord_output_rencana</td>
                <td width="70px">$tgl_kegiatan_rencana</td>
                <td width="70px">$no_memo_realisasi</td>
                <td width="70px">$nominal_realisasi</td>
                <td width="70px">$ket_kegiatan_realisasi]</td>
                <td width="50px">$type_realisasi</td>
                <td width="70px">$koord_output_realisasi</td>
                <td width="70px">$tgl_kegiatan_realisasi</td>
                <td width="70px" style="background-color: #99ff99">$sisa_anggaran</td>
            </tr>
EOD;
            }else{
                $html .= <<<EOD

            <tr style="background-color: #fafafa;">
                <td width="70px">$rencana1</td>
                <td width="70px">$name_status</td>
                <td width="70px">$nominal_rencana</td>
                <td width="70px">$selisih_pengajuan_rencana</td>
                <td width="70px">$ket_kegiatan_rencana</td>
                <td width="70px">$koord_output_rencana</td>
                <td width="70px">$tgl_kegiatan_rencana</td>
                <td width="70px">$no_memo_realisasi</td>
                <td width="70px">$nominal_realisasi</td>
                <td width="70px">$ket_kegiatan_realisasi</td>
                <td width="50px">$type_realisasi</td>
                <td width="70px">$koord_output_realisasi</td>
                <td width="70px">$tgl_kegiatan_realisasi</td>
                <td width="70px">$sisa_anggaran</td>
            </tr>
EOD;
            }
            
        }

//    }
    
}


//            <tr>
//                <td colspan="12"></td>
//                <td width="70px" style="background-color: #99ff99;">5727.001.051.A</td>
//            </tr>



$html .= <<<EOD
    </table>
EOD;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'L', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
//$pdf->Output('Monitor Anggaran '.date("l, Y-m-d").'.pdf', 'I');
$pdf->Output('Monitor Anggaran '.date("l, Y-m-d").'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+      
}
// url_api_upload_pdf_reimburse
else if($_GET['id_templates'])
{
    // Description : menghasilkan data dari table cb_chanthel.templates
    $id = $_GET['id_templates'];
    $rez_templates = DB\dbQuery(
        'SELECT id,name FROM templates WHERE id='.$id
    );
    
    $r = $rez_templates->fetch_assoc();
    
    echo json_encode($r);
}
else if($_GET['url_api_upload_pdf_reimburse'])
{        
    $datareimburse  = json_decode($_GET['datareimburse']); // parse_str($argv[1]);
    //                "data": {
//                    "No": 123,
//                    "Name": "usva",
//                    "Patiens": "patiens1",
//                    "FamilyRelationship": 1173,
//                    "Date of visit": "2017-12-12T00:00:00Z",
//                    "Certificate of illness": 1171,
//                    "Diagnose": "diagnose1",
//                    "Description": [{
//                        "no": 1,
//                        "desc": "Biaya Konsultasi/Dokter Umum/Gigi/Spesialis : .......",
//                        "rp": "10",
//                        "us": "",
//                        "id": "extModel1064-1"
//                    }, {
//                        "no": 2,
//                        "desc": "Biaya Lab / Check up sebagai tindak lanjut pemeriksaan",
//                        "rp": "20",
//                        "us": "",
//                        "id": "extModel1064-2"
//                    }, {
//                        "no": 3,
//                        "desc": "Biaya Obat-obatan (Resep Dokter)",
//                        "rp": "30",
//                        "us": "",
//                        "id": "extModel1064-3"
//                    }, {
//                        "no": 4,
//                        "desc": "Lain-lain: ......",
//                        "rp": "40",
//                        "us": "",
//                        "id": "extModel1064-4"
//                    }],
//                    "Attachment": ["/tmp/phppxB4rk/bibit.png"],
//                    "Employee": "employee1",
//                    "HRD": "hrd1",
//                    "Finance": "finance1",
//                    "Received By": "received1",
//                    "Date of received": "2017-12-26T00:00:00Z",
//                    "Assigned": "50,49",
//                    "sys_data": []
//                },
    
    $no = "  ..................................";
    if(isset($datareimburse->No) && $datareimburse->No != "")
    {
        $no = $datareimburse->No; 
    }
    $_SESSION['no_reimburse'] = $no;
    $_SESSION['date_reimburse'] = date("l, Y-m-d");
    
    $name = ($datareimburse->Name != "" ? $datareimburse->Name : "-");
    $patiens = ($datareimburse->Patiens != "" ? $datareimburse->Patiens : "-");
    
    // cari pada table 'objects' by id
    // sample query : /var/www/chanthel_125_ho/httpsdocs/classes/CB/DataModel/Files.php
    $family = ($datareimburse->FamilyRelationship != "" ? $datareimburse->FamilyRelationship : "-"); // ex:1173
    $certificate = ($datareimburse->CertificateOfIllness != "" ? $datareimburse->CertificateOfIllness : "-"); // ex:1171
    
    // SELECT id,data,sys_data FROM objects WHERE id=1171;
    $res_family = DB\dbQuery(
        'SELECT id,data,sys_data FROM objects WHERE id='.$family
    );
    $res_certificate = DB\dbQuery(
        'SELECT id,data,sys_data FROM objects WHERE id='.$certificate
    );
    // -------------------------------------------------------------------
    
    $family_spouse = "false";
    $family_child = "false";
    if ($r = $res_family->fetch_assoc()) 
    {
        $obj = json_decode($r['data']);
        $family = $obj->en;
    }
    if($family == 'spouse')
    {
        $family_spouse = "true"; // untuk checked pada checkbox
    }
    else if($family == "child")
    {
        $family_child = "true";
    }
    
    $certificate_checked = "true";
    if ($r = $res_certificate->fetch_assoc()) 
    {
        $obj = json_decode($r['data']);
        $certificate = $obj->en;
    }    
    if($certificate == "no")
    {
        $certificate_checked = "false";
    }
    
    $date_of_visit_iso = ($datareimburse->DateOfVisit != "" ? $datareimburse->DateOfVisit : "-");
    $date = new \DateTime( $date_of_visit_iso, new \DateTimeZone( 'UTC' ) );
    // $date_of_visit = $date->format('l, Y-m-d H:i:s');
    $date_of_visit = $date->format('l, Y-m-d');
    $diagnose = ($datareimburse->Diagnose != "" ? $datareimburse->Diagnose : "-");
    
    $employee = ($datareimburse->Employee != "" ? $datareimburse->Employee : "-");
    $hrd = ($datareimburse->HRD != "" ? $datareimburse->HRD : "-");
    $finance = ($datareimburse->Finance != "" ? $datareimburse->Finance : "-");
    $receivedby = ($datareimburse->ReceivedBy != "" ? $datareimburse->ReceivedBy : "....................................................");
    
    $dateofreceived = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($datareimburse->DateOfReceived != "" && $datareimburse->DateOfReceived != null)
    {
        $dateofreceived_iso = new \DateTime( $datareimburse->DateOfReceived, new \DateTimeZone( 'UTC' ) );
        $dateofreceived =  $dateofreceived_iso->format('l, Y-m-d');
    }
    $assigned = ($datareimburse->Assigned != "" ? $datareimburse->Assigned : "-");
    
    /**
     * Mengubah spasi menjadi array (fungsi explode), kemudian mengubah array menjadi string & dihubungkan dengan tanda "_"
     */
    $fileName       = implode("_", explode(" ", $_GET['fileName']));
    $url_api_upload_pdf_reimburse = $_GET['url_api_upload_pdf_reimburse'];
    $usr            = $_GET['usr'];
    $pswd           = $_GET['pswd'];
    $iddir          = $_GET['idDir'];
    
    // create new PDF document
    // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // set core font
    $pdf->SetFont('helvetica', '', 9);
    // set document information
    // set default header data
    // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Medical Claim Form : ".$name, "http://www.solusi247.com/");
    /// $pdf->setFooterData(array(0,64,0), array(0,64,128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
    }

    // add a page
    $pdf->AddPage();
    $pdf->setPage(1);
    
    $h_tbl_identity = "height:25px";
    $text_center = "text-align:center;";
    $text_right = "text-align:right;";
    
    $left_none = "border-left-style: none;";
    
    $right_solid = "border-right-style:solid; border-width:0.1px;";
    $top_solid = "border-top-style:solid; border-width:0.1px;";
    $bottom_solid = "border-bottom-style:solid; border-width:0.1px;";
    $left_solid = "border-left-style:solid; border-width:0.1px;";
    
// print a block of text using Write()
$tbl = <<<EOD
<br><br><br><br>
<table border="0">
  <tr>
    <td style="width: 120px;">NAME</td>
    <td style="width: 20px;">&nbsp;&nbsp;: &nbsp;&nbsp;</td>
    <td style="width: 260px;">$name</td>
    <td style="width: 260px;"></td>
  </tr>
  <tr>
    <td style="$h_tbl_identity"><i>(Nama Karyawan)</i></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>PATIEN'S NAME</td>
    <td>&nbsp;&nbsp;: &nbsp;&nbsp;</td>
    <td>$patiens</td>
    <td>( CHILD <input type="checkbox" name="reimburse1" value="1" checked="$family_child"  readonly="true"/> / SPOUSE <input type="checkbox" name="reimburse2" value="1" checked="$family_spouse"  readonly="true"/> )</td>
  </tr>
  <tr>
    <td style="$h_tbl_identity"><i>(Nama Pasien)</i></td>
    <td></td>
    <td></td>
    <td><i>(Anak / Istri)</i></td>
  </tr>
  <tr>
    <td>DATE OF VISIT</td>
    <td>&nbsp;&nbsp;: &nbsp;&nbsp;</td>
    <td>$date_of_visit</td>
    <td>CERTIFICATE OF ILLNESS <input type="checkbox" name="reimburse3" value="1" checked="$certificate_checked"  readonly="true"/> Check</td>
  </tr>
  <tr>
    <td style="$h_tbl_identity"><i>(Tanggal Berobat)</i></td>
    <td></td>
    <td></td>
    <td><i>(Lampiran / Surat Keterangan Sakit)   (Centang)</i></td>
  </tr>
  <tr>
    <td>DIAGNOSE</td>
    <td>&nbsp;&nbsp;: &nbsp;&nbsp;</td>
    <td>$diagnose</td>
    <td></td>
  </tr>
  <tr>
    <td><i>(diagnosa)</i></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
<br><br>
<table cellpadding="2">
  <tr>
    <td rowspan="2" style="width:25px; $text_center $top_solid $right_solid">&nbsp;<br><i>No.</i></td>
    <td rowspan="2" style="width:390px; $text_center $top_solid $right_solid">&nbsp;<br><i>Description</i><br><i>(Jenis Pengobatan)<br>&nbsp;</i></td>
    <td colspan="2" style="width:230px; $text_center $top_solid">Amount<br><i>(Jumlah)</i></td>
  </tr>
  <tr style="padding:100px;">
    <td style="$text_center $top_solid $right_solid padding-top:100px;margin-top:100px;"><i>Rp.</i></td>
    <td style="$text_center $top_solid"><i>US$</i></td>
  </tr>
EOD;

$total_rp = 0;
$val_rp = 0;
$total_us = 0;
$val_us = 0;

$loop_description = 1;
$description = json_decode($datareimburse->Description);

    if( !empty($description) && $description != NULL)
    {
        foreach ($description as $descript => $obj_descript) 
        {
            if($obj_descript->rp != "")
            {
                $total_rp += (int)$obj_descript->rp;
                $val_rp = number_format((int)$obj_descript->rp,0,",",".");
            }
            else
            {
                $total_rp += 0;
                $val_rp = 0;
            }
            
            if($obj_descript->us != "")
            {
                $total_us += (int)$obj_descript->us;
                $val_us =  $val_desc = number_format((int)$obj_descript->us,0,",",".");
            }
            else
            {
                $total_us += 0;
                $val_us = 0;
            }
            
$tbl .= <<<EOD
<tr>
    <td style="$text_center  $top_solid $right_solid"><i>$loop_description</i></td>
    <td style="$top_solid $right_solid"><i> $obj_descript->desc</i></td>
    <td style="$text_right $top_solid $right_solid">$val_rp</td>
    <td style="$text_right $top_solid">$val_us</td>
</tr>
EOD;
            $loop_description++;
        }
    }

    $total_rp = number_format($total_rp,0,",",".");
    $total_us = number_format($total_us,0,",",".");
    
$tbl .= <<<EOD
  <tr>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid"></td>
  </tr>
  <tr>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid $right_solid"></td>
    <td style="$top_solid "></td>
  </tr>
  <tr>
    <td colspan="2" style="$text_center $top_solid $right_solid"><i><b>TOTAL</b></i></td>
    <td style="$text_right $top_solid $right_solid $bottom_solid">$total_rp</td>
    <td style="$text_right $top_solid $bottom_solid">$total_us</td>
  </tr>
</table>

<br><br><br>
<table cellpadding="2">
        <tr>
            <td style="width:140px; $text_center $top_solid $left_solid $right_solid">PREPARED BY<br><i>(Employee)</i></td>
            <td style="width:140px; $text_center $top_solid $right_solid">CHECKED BY<br><i>(HRD)</i></td>
            <td style="width:140px; $text_center $top_solid $right_solid">APPROVED BY<br><i>(Finance)</i></td>
            <td rowspan="2" style="width:225px; $text_center">
                RECEIVED BY
                <br><br><br><br><br><br>
                ( $receivedby )
                <br>
                Date : $dateofreceived
            </td>
        </tr>
        <tr>
            <td style="$text_center $top_solid $left_solid $right_solid $bottom_solid"><br><br><br><br><br>$employee</td>
            <td style="$text_center $top_solid $right_solid $bottom_solid"><br><br><br><br><br>$hrd</td>
            <td style="$text_center $top_solid $right_solid $bottom_solid"><br><br><br><br><br>$finance</td>
        </tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, 0, true, 0);

//$pdf->AddPage();
//$pdf->setPage(2);

$n = 1; // jumlah attachment
foreach ($datareimburse->Attachment as $k=> $v)
{
    if($v != "")
    {
//$tbl = <<<EOD
//<br><br><br><br><br>
//<table border="0.1" cellpadding="2">
//    <tr>
//        <td style="width:645px; $text_center">Lampiran $n</td>
//    </tr>
//    <tr>
//        <td style="$text_center"><img src="/var/www/chanthel/httpsdocs/$v"></td>
//    </tr>
//</table>
//EOD;
        // modif
        $tbl = <<<EOD
<br><br><br><br><br>
<table border="0.1" cellpadding="2">
    <tr>
        <td style="width:645px; $text_center">Lampiran $n</td>
    </tr>
    <tr>
        <td style="$text_center"><img src="$v"></td>
    </tr>
</table>
EOD;
        
        $n++;        
        $pdf->AddPage();
        $pdf->setPage($n);
        $pdf->writeHTML($tbl, true, 0, true, true);
    }
}

//<br>&nbsp;Attachment $n : $path_attachment <br>
//&nbsp;<img src="/var/www/chanthel/httpsdocs/$v"> <br>


    // output the HTML content
//    $pdf->writeHTML($tbl, true, 0, true, true);
    // $pdf->writeHTML($tbl, true, false, false, false, ''); // ini error.
    $pdf->Ln();
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.

    // $path_filename = '/tmp/'.$fileName.'.pdf'; // ori
    $path_filename = tempnam(sys_get_temp_dir(), 'workflow-').$fileName;
    $pdf->Output($path_filename,'F');        
        
    // $fileName = explode('/tmp/', $path_filename)[1]; // sample : workflow-1h94MJd_2018-01-31_06:19:48.reimburse.pdf
    
    if(
        $usr != "" && $pswd != "" && $url_api_upload_pdf_reimburse != "" && $datareimburse  != ""
        && $iddir != "" && $_GET['fileName'] != ""
    )
    {
        // $result = shell_exec("php ajaxUploadPdf.php $usr $pswd $url_api_upload_pdf_reimburse $datareimburse $iddir $fileName");
        $result = shell_exec("php ajax_upload_pdf_reimburse.php  "
                . "$usr "
                . "$pswd "
                . "$url_api_upload_pdf_reimburse "
                . "$fileName "
                . "$iddir "
                . "$path_filename");
        echo "success|".$fileName;
    }
    else
    {
        echo "error|".$fileName;
    }
}

// dhiar 2
else if($_GET['url_api_upload_pdf_overtime'])
{
    $text_center = "text-align:center;";
    
    $dataovertime  = json_decode($_GET['dataovertime']);
    $no_form = ($dataovertime->no_form != "" ? $dataovertime->no_form : "-");
    $nama = ($dataovertime->nama != "" ? $dataovertime->nama : "-");
    
    $tipe_assignment = ($dataovertime->tipe_assignment != "" ? $dataovertime->tipe_assignment : "-");
    $res_tipe_assignment = DB\dbQuery(
        'SELECT id,data,sys_data FROM objects WHERE id='.$tipe_assignment
    );
    if ($r = $res_tipe_assignment->fetch_assoc()) 
    {
        $obj = json_decode($r['data']);
        $tipe_assignment = $obj->en;
    }
    
    $aplikasi = ($dataovertime->aplikasi != "" ? $dataovertime->aplikasi : "-");
    
    $nama_cr = ($dataovertime->nama_cr != "" ? $dataovertime->nama_cr : "-");
    $tanggal = ($dataovertime->tanggal != "" ? $dataovertime->tanggal : "-");
    
    $tanggal = "-";
    if($dataovertime->tanggal != "")
    {
        $tanggal = $dataovertime->tanggal;
        $tanggal = new \DateTime( $tanggal, new \DateTimeZone( 'UTC' ) );
        $tanggal = $tanggal->format('l, Y-m-d');
    }
    
    $waktu_mulai = ($dataovertime->waktu_mulai != "" ? $dataovertime->waktu_mulai : "-");
    $waktu_sampai = ($dataovertime->waktu_sampai != "" ? $dataovertime->waktu_sampai : "-");
    $description = ($dataovertime->description != "" ? $dataovertime->description : "-");
    
    $sys_analyst = ($dataovertime->sys_analyst != "" ? $dataovertime->sys_analyst : "-");
    $project_manager = ($dataovertime->project_manager != "" ? $dataovertime->project_manager : "-");
    
    $assignment = ($dataovertime->assignment != "" ? $dataovertime->assignment : "-");
    
    $date_approval = "-";
    if($dataovertime->date_approval != "")
    {
        $date_approval = $dataovertime->date_approval;
        $date_approval = new \DateTime( $date_approval, new \DateTimeZone( 'UTC' ) );
        $date_approval = $date_approval->format('l, Y-m-d');
    }
    
    $fileName       = implode("_", explode(" ", $_GET['fileName']));
    $url_api_upload_pdf_overtime = $_GET['url_api_upload_pdf_overtime'];
    $usr            = $_GET['usr'];
    $pswd           = $_GET['pswd'];
    $iddir          = $_GET['idDir'];
    
//    echo 'fileName='.$fileName.'<br>';
//    echo 'iddir='.$iddir.'<br>';
//    echo 'description='.$description.'<br>';
//    exit();
    
    // create new PDF document
    $pdf = new MYPDFOVERTIME(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // set core font
    $pdf->SetFont('helvetica', '', 9);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
    {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

//    $tbl = "";
    // add a page
    $pdf->AddPage();
//$tbl .= <<<EOD
//    fileName=$fileName
//    iddir=$iddir
//EOD;
    
$tbl = <<<EOD
<h3>&nbsp;FORM PENGAJUAN OVERTIME </h3>
<br>&nbsp;
<table cellspacing="0" cellpadding="5" border="0.1" style="border-color: black">
    <tr style="background-color: black; color:white;">
        <td width="650px" align="center" colspan="2" >Detail</td>
    </tr>
    <tr>
        <td width="150px">No. Form</td>
        <td width="500px">$no_form</td>
    </tr>
    <tr>
        <td width="150px">Nama </td>
        <td width="500px">$nama</td>
    </tr>
    <tr>
        <td width="150px">Tipe assignment </td>
        <td width="500px">$tipe_assignment</td>
    </tr>
    <tr>
        <td width="150px">Aplikasi  </td>
        <td width="500px">$aplikasi</td>
    </tr>    
    <tr>
        <td width="150px">Nama CR </td>
        <td width="500px">$nama_cr</td>
    </tr>
    <tr>
        <td width="150px">Tanggal </td>
        <td width="500px">$tanggal</td>
    </tr>
    <tr>
        <td width="150px">Waktu (mulai - sampai) </td>
        <td width="500px">$waktu_mulai s/d $waktu_sampai</td>
    </tr>
    <tr>
        <td width="150px">Detail pekerjaan yang harus diselesaikan</td>
        <td width="500px">$description</td>
    </tr>
    </table>
        <br>
        <br>  
    <table cellspacing="0" cellpadding="5" border="1" style="border-color: black;float:left">
    <tr style="background-color: black; color:white;">
        <td width="650px" align="center" colspan="2" >Approval</td>
    </tr>
    <tr>
        <td width="325px" style="font-size:11px;height: 100px;vertical-align:top; $text_center"><i>Date : &nbsp;$date_approval </i> <br><br><br><br><br><br>$sys_analyst</td>
        <td width="325px" style="$text_center"><br><br><br><br><br><br> $project_manager</td>
    </tr>
    <tr>
        <td width="325px" style="text-align:center">System Analyst </td>
        <td width="325px" style="text-align:center">Project Manager</td>
    </tr>
    </table>
EOD;

    $pdf->writeHTML($tbl, true, 0, true, 0);
    $pdf->Ln();
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.

    // $path_filename = '/tmp/'.$fileName.'.pdf'; // ori
    $path_filename = tempnam(sys_get_temp_dir(), 'workflow-').$fileName;
    $pdf->Output($path_filename,'F');
    
    // $fileName = explode('/tmp/', $path_filename)[1]; // sample : workflow-1h94MJd_2018-01-31_06:19:48.overtime.pdf
    
    if(
        $usr != "" && $pswd != "" && $url_api_upload_pdf_overtime != "" && $dataovertime  != ""
        && $iddir != "" && $_GET['fileName'] != ""
    )
    {
        $result = shell_exec("php ajax_upload_pdf_overtime.php  "
                . "$usr "
                . "$pswd "
                . "$url_api_upload_pdf_overtime "
                . "$fileName "
                . "$iddir "
                . "$path_filename");
        echo "success|".$fileName;
    }
    else
    {
        echo "error|".$fileName;
    }
}

else if($_GET['url_api_upload_pdf_dynamic_form'])
{
    $tbl = "";
    $h_tbl_identity = "height:25px";
    $text_center = "text-align:center;";
    $text_right = "text-align:right;";    
    $left_none = "border-left-style: none;";
    
    $right_solid = "border-right-style:solid; border-width:0.1px;";
    $top_solid = "border-top-style:solid; border-width:0.1px;";
    $bottom_solid = "border-bottom-style:solid; border-width:0.1px;";
    $left_solid = "border-left-style:solid; border-width:0.1px;";
    
    $loop_description = 1;
    $datadynamicform  = json_decode($_GET['datadynamicform']);        
    $assignment = ($datadynamicform->assignment != "" ? $datadynamicform->assignment : "-");
    
    $fileName       = implode("_", explode(" ", $_GET['fileName']));
    $url_api_upload_pdf_dynamic_form = $_GET['url_api_upload_pdf_dynamic_form'];
    $usr            = $_GET['usr'];
    $pswd           = $_GET['pswd'];
    $iddir          = $_GET['idDir'];
    // create new PDF document
    $pdf = new MYPDFOVERTIME(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // set core font
    $pdf->SetFont('helvetica', '', 9);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
    {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

//    $tbl = "";
    // add a page
    $pdf->AddPage();
$tbl = <<<EOD
<h3>&nbsp;DYNAMIC FORM </h3>
<br>&nbsp;
<table cellspacing="0" cellpadding="5" border="0.1" style="border-color: black">
    <tr style="background-color: black; color:white;">
        <td width="30px" align="center">No</td>
        <td width="150px" align="center">Label</td>
        <td width="450px" align="center">Value</td>
    </tr>
EOD;
    
    // echo 'datadynamicform=';
    // print_r($datadynamicform);    
    $dynamic_form = json_decode($datadynamicform->dynamic_form);    
    // echo 'dynamic_form=';
    // print_r($dynamic_form);  
    $val_value = "";
    if( !empty($dynamic_form) && $datadynamicform != NULL)
    {        
        foreach ($dynamic_form as $key => $val) 
        {                        
            if($val->label != "" )
            {
                $val_value = $val->value;
                if($val_value == "")
                {
                    $val_value = "-";
                }
                
                $tbl .= <<<EOD
<tr>
    <td style="$text_center $top_solid $right_solid">$loop_description</td>
    <td style="$top_solid $right_solid">$val->label</td>
    <td style="$top_solid $right_solid">$val_value</td>
</tr>
EOD;
            $loop_description++;
            }
            

        }
    }    

$tbl .= <<<EOD
</table>
EOD;

    $pdf->writeHTML($tbl, true, 0, true, 0);
    $pdf->Ln();
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.

    // $path_filename = '/tmp/'.$fileName.'.pdf'; // ori
    $path_filename = tempnam(sys_get_temp_dir(), 'workflow-').$fileName;
    $pdf->Output($path_filename,'F');
    
    // $fileName = explode('/tmp/', $path_filename)[1]; // sample : workflow-1h94MJd_2018-01-31_06:19:48.overtime.pdf
    
    if(
        $usr != "" && $pswd != "" && $url_api_upload_pdf_dynamic_form != "" && $datadynamicform  != ""
        && $iddir != "" && $_GET['fileName'] != ""
    )
    {
        $res_tree = DB\dbQuery(
            'SELECT id,pid,name,did,ddate from tree WHERE pid='.$iddir.' ORDER BY id ASC'
        );
        
        // get url solr from config.ini
        $config = parse_ini_file("/var/www/chanthel/httpsdocs/config.ini");
        $solr_host = $config['solr_host']; // "yava-228.solusi247.com"
        $solr_port = $config['solr_port']; ///solr_port = ""
        $solr_core = "cb_chanthel";
        $url = "http://".$solr_host.":".$solr_port."/solr/".$solr_core."/update?wt=json";        
        
        while ($r = $res_tree->fetch_assoc()) 
        {
            DB\dbQuery(
                "UPDATE tree SET did=1,dstatus=1,ddate='".$ddate."' WHERE id=".$r['id']
            );            
            
            $data = '{"add":{ "doc":{
              "id":"'.$r['id'].'",
              "dstatus":{"set":"1"}
            },"boost" :1.0,"overwrite":true,"commitWithin":1000}}';
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $data,  
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) 
            {
                // print_r("respon err : " .  $err );
                // echo "cURL Error #:" . $err;
            } 
            else 
            {
                $response_obj = json_decode($response);
                $status = $response_obj->responseHeader->status; // jika 0 maka success, jika 400 maka error
                // echo 'status='.$status;
            }            
        }
        
        echo "iddir".$iddir;
        
//        echo "usr=$usr "
//                . "p=$pswd "
//                . "url=$url_api_upload_pdf_dynamic_form "
//                . "file=$fileName "
//                . "file=$iddir "
//                . "path=$path_filename";
        
        $result = shell_exec("php ajax_upload_pdf.php  "
                . "$usr "
                . "$pswd "
                . "$url_api_upload_pdf_dynamic_form "
                . "$fileName "
                . "$iddir "
                . "$path_filename");
        echo "success|".$fileName;
    }
    else
    {
        echo "error|".$fileName;
    }
}
// Description : untuk upload file .wfc arief
else if($_GET['url_api_upload'])
{        
//    echo $_GET['theData'];
//    echo '<br>';
//    echo $_GET['fileName'];
//    echo '<br>';
//    echo $_GET['idDir'];
//    exit();die();
    $iddir          = $_GET['idDir'];
    $datamydiagram  = $_GET['datamydiagram'];
    $fileName       = $_GET['fileName'];
    $url_api_upload = $_GET['url_api_upload'];
    $usr            = $_GET['usr'];
    $pswd           = $_GET['pswd'];
//    shell_exec("php ajaxUpload.php $iddir $theData $fileName $url_api_upload_2 $usr $pswd");
    // shell_exec("php ajaxUpload.php '".$iddir."' '".$pswd."'");
    
    if(
        $usr != "" && $pswd != "" && $url_api_upload != "" && $datamydiagram  != ""
        && $iddir != "" && trim($fileName) != ".wfc"
    )
    {
        $result = shell_exec("php ajaxUpload.php $usr $pswd $url_api_upload $datamydiagram $iddir $fileName");
        echo "success|".$fileName;
    }
    else
    {
        echo "error|".$fileName;
    }
}
else if($_GET['url_chanthel_api'])
{
    $name = $_SESSION['user']['name'];        
    $rez_user = DB\dbQuery(
        "SELECT name,password FROM users_groups WHERE name='$name'"
        // 'SELECT name,password FROM users_groups WHERE name='.$name
    );
    
//    $rez = $rez_user->fetch_assoc();
    $p = "";
    while ($r = $rez_user->fetch_assoc()) 
    {   
        // echo 'user=';
        $p = $r['password'];
    }    
    
    $act = $_POST['act'];
    $fid = $_POST['fid'];    
    
    $url_digital_signature = $_GET['url_chanthel_api']."?act=".$act."&u=".$name."&p=".$p."&fid=".$fid;   
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url_digital_signature,    
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "postman-token: 2354ddb2-84b9-982f-63ba-7d9612178ceb"
        ),
    ));
 
    $rez = "";

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) 
    {
	$rez = array('error_code'=> 1, 'message'=>"cURL Error #:" . $err);
        echo json_encode($rez);
    } 
    else 
    {
        echo $response;
    }
}
else if($_GET['url_userlogin'])
{
    // Added By : Usva Dhiar P.
    // Description : Tambah info user apa yang sedang digunakan untuk login saat ini.
    // Terhubung dengan file : 
    // 1 . httpsdocs/js/ViewPort.js
    // 2 . httpsdocs/js/browser/ViewContainer.js
    
    $GroupIds = UsersGroups::getGroupIdsForUser();
    $rez = array();
    
    foreach ($GroupIds as $key => $val) 
    {
        
        $res = DB\dbQuery(
            'SELECT name
            FROM users_groups
            WHERE id = '.$val
        );

        while ($r = $res->fetch_assoc()) {
            $rez[] = $r['name'];
        }
        
    }
    echo json_encode([
        'name' => $_SESSION['user']['name'],
        'display_name' => User::getDisplayName(),
        'id_user' => User::getId(),
        'group_names' => $rez
    ]);
}

// Created By : Usva Dhiar P.
// File : httpsdocs/js/CB.UsersGroups.js
// Description : Untuk mendapatkan data dari table berdasarkan id atau pid.
else if($_GET['act'])
{
    $anggaran_kode = "anggaran.kode AS anggaran_id";
    $anggaran_kegiatan = "anggaran.kegiatan AS anggaran_kegiatan";
    $anggaran_tahun = "anggaran.tahun AS anggaran_tahun";
    $anggaran_nilai = "anggaran.nilai_anggaran AS nilai_anggaran";
    $id_ls_rencana = "rencana.id AS id_rencana";
    $nominal_rencana = "rencana.nominal_anggaran AS nominal_rencana";
    $selisih_pengajuan_rencana = "rencana.selisih_pengajuan AS selisih_pengajuan_rencana";
    $ket_kegiatan_rencana = "rencana.keterangan_kegiatan AS ket_kegiatan_rencana";    
    $koord_output_rencana = "rencana.koord_output AS koord_output_rencana";
    $tgl_kegiatan_rencana = "rencana.tgl_kegiatan AS tgl_kegiatan_rencana";
    
    $id_realisasi = "realisasi.id AS id_realisasi";
    $no_memo_rencana = "rencana.no_memo AS no_memo_rencana";        
    $realisasi_id_rencana = "realisasi.id_rencana AS realisasi_id_rencana";
    $ket_kegiatan_realisasi = "realisasi.keterangan_kegiatan AS ket_kegiatan_realisasi";    
    $no_memo_realisasi = "realisasi.no_memo AS no_memo_realisasi";    
    $nominal_realisasi = "realisasi.nominal_anggaran AS nominal_realisasi";
    $ppn_realisasi = "realisasi.ppn AS ppn_realisasi";
    $pph_realisasi = "realisasi.pph AS pph_realisasi";
    $type_realisasi = "realisasi.type AS type_realisasi";
    $koord_output_realisasi = "realisasi.koord_output AS koord_output_realisasi";
    $tgl_kegiatan_realisasi = "realisasi.tgl_kegiatan AS tgl_kegiatan_realisasi";
    $bukti_setor_pajak = "realisasi.bukti_setor_pajak AS bukti_setor_pajak";
    
    
    $select_count_anggaran = "SELECT COUNT(`anggaran_id`) AS count_anggaran_id, anggaran_id";
    $join_anggaran_rencana = "dii_dm_anggaran anggaran ON rencana.anggaran_id = anggaran.id";
    $join_anggaran_realisasi = "dii_dm_anggaran anggaran ON realisasi.anggaran_id = anggaran.id";
    
    if($_GET['act'] == 'get_data_pagination')
    {
        $data = array();        
        
        if($_GET['table'] != "" &&  $_GET['start'] != "" && $_GET['limit'] != "")
//        if($_GET['table'] != "" )
        {
            $table = $_GET['table'];            

            if($table == "dii_dm_anggaran" || $table == "dii_dm_state" || $table == "dii_dm_workflow" || $table == "dii_realisasi_anggaran" || $table == "dii_ls_rencana" || $table == "dii_monitor_anggaran")
            {
                if(isset($_POST['id']) && $_POST['id'] != "0")
                {
                    $rez = DB\dbQuery(
                        'SELECT * FROM '.$table.' WHERE id='.$_POST['id'].' ORDER BY id ASC '
                    );

                    $rez_total = DB\dbQuery(
                        'SELECT COUNT(*) as total FROM '.$table.' WHERE id='.$_POST['id'].' ORDER BY id ASC '
                    );
                }
                else
                {  
//                    echo "monitor";
                    
                    $q_select = " SELECT ".$anggaran_kode.","
                        . "$anggaran_kegiatan,"
                        . "$anggaran_tahun,"
                        . "$anggaran_nilai,"
                        . "$id_ls_rencana,"
                        . "$no_memo_rencana,"
                        . "$nominal_rencana,"
                        . "$selisih_pengajuan_rencana,"
                        . "$ket_kegiatan_rencana,"
                        . "$koord_output_rencana,"
                        . "$tgl_kegiatan_rencana,"
                        . "$id_realisasi,"
                        . "$realisasi_id_rencana,"
                        . "$no_memo_realisasi,"                                                
                        . "$nominal_realisasi,"
                        . "$ppn_realisasi,"
                        . "$pph_realisasi,"
                        . "$type_realisasi,"
                        . "$koord_output_realisasi,"
                        . "$tgl_kegiatan_realisasi,"
                        . "$ket_kegiatan_realisasi,"
                        . "$bukti_setor_pajak ";
                    
                    
                    $q_select_rencana = " SELECT $anggaran_kode,"
                        . "$anggaran_kegiatan,"
                        . "$anggaran_tahun,"
                        . "$anggaran_nilai,"
                        . "$id_ls_rencana,"
                        . "$no_memo_rencana,"
                        . "$nominal_rencana,"
                        . "$selisih_pengajuan_rencana,"
                        . "$koord_output_rencana,"
                        . "$tgl_kegiatan_rencana,"
                        . "$ket_kegiatan_rencana ";
                    
                    $q_select_realisasi = " SELECT "
                        . "$anggaran_kode,"
                        . "$anggaran_kegiatan,"
                        . "$anggaran_tahun,"
                        . "$anggaran_nilai,"
                        . "$id_realisasi,"
                        . "$realisasi_id_rencana,"
                        . "$no_memo_realisasi,"                                                
                        . "$nominal_realisasi,"
                        . "$ppn_realisasi,"
                        . "$pph_realisasi,"
                        . "$type_realisasi,"
                        . "$koord_output_realisasi,"
                        . "$tgl_kegiatan_realisasi,"
                        . "$ket_kegiatan_realisasi, "
                        . "$bukti_setor_pajak ";
                    
                    
                    if($_GET['search'])
                    {
                        $limit = $_GET['start'].','.$_GET['limit'];
                        
                        if($_GET['search'] != "")
                        {
                            // kegiatan, tahun, no memo.
                            $condition = "";
                            $search = $_GET['search'];
                            
                            if($table == 'dii_dm_state')
                            {
                                $condition = "(`name` LIKE '%".$search."%')";                                
                            }
                            else if($table == 'dii_dm_anggaran')
                            {
                                // kode kegiatan tahun koord_output
                                $condition = "(`kode` LIKE '%".$search."%')"
                                            . " OR (`kegiatan` LIKE '%".$search."%')"
                                            . " OR (`tahun` LIKE '%".$search."%')"   
                                            . " OR (`koord_output` LIKE '%".$search."%')";   
                            }
                            else if($table == 'dii_dm_workflow')
                            {
                                $condition = "(`name` LIKE '%".$search."%')"
                                            . " OR (`state_flow` LIKE '%".$search."%')";   
                            }
                            else if($table == 'dii_monitor_anggaran')
                            {
                                $condition = " (`anggaran_id` LIKE '%".$search."%') "
                                            . " OR (`ket_kegiatan_rencana` LIKE '%".$search."%') "
                                            . " OR (`no_memo_rencana` LIKE '%".$search."%') "                                                                                                                          
                                            . " OR (`ket_kegiatan_realisasi` LIKE '%".$search."%') "
                                            . " OR (`no_memo_realisasi` LIKE '%".$search."%') ";                                
                                
                                $where_not_null = " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) ";
                                // dii_dm_state
                                $q_total = DB\dbQuery(
                                "SELECT COUNT(`anggaran_id`) AS count_anggaran_id FROM (".$q_select
                                    . ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                                . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                    . "ON rencana.anggaran_id = anggaran.id " .
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                    . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
                                       
                                . "GROUP by anggaran_id) count_dii_dm_anggaran WHERE ".$condition
                                );
                                // SELECT anggaran.id AS anggaran_id,rencana.no_memo AS no_memo_rencana,rencana.nominal_anggaran AS nominal_rencana,rencana.selisih_pengajuan AS selisih_pengajuan_rencana,rencana.keterangan_kegiatan AS ket_kegiatan_rencana, realisasi.no_memo AS no_memo_realisasi,realisasi.keterangan_kegiatan AS ket_kegiatan_realisasi FROM dii_ls_rencana rencana RIGHT JOIN  dii_dm_anggaran anggaran ON rencana.anggaran_id = anggaran.id LEFT JOIN dii_realisasi_anggaran realisasi ON realisasi.anggaran_id = anggaran.id GROUP by anggaran_id;
                                
                                $total = $q_total->fetch_assoc()['count_anggaran_id'];
                                
                                $q_group =  DB\dbQuery(
                                "SELECT anggaran_id FROM (".$q_select
                                    . ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                                . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                    . "ON rencana.anggaran_id = anggaran.id " 
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                    . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
                                . "GROUP by anggaran_id) count_dii_dm_anggaran WHERE ".$condition." ORDER BY anggaran_id ASC "." LIMIT ".$limit
                                );                              
                                $data = array();
                                
                                while($r = $q_group->fetch_assoc()) 
                                {                                                                        
                                    $anggaran_id = $r['anggaran_id'];
                                    $q_data_rencana =  DB\dbQuery(
                                        $q_select_rencana. ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                                        . "JOIN  dii_dm_anggaran anggaran "
                                            . "ON rencana.anggaran_id = anggaran.id "
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                        . " WHERE NOT (rencana.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."' AND rencana.is_rampung = '0'" 
                                    );
                                    
                                    $q_data_realisasi =  DB\dbQuery(
                                        $q_select_realisasi. ",realisasi.id_status AS id_status_realisasi, state.name AS name_status FROM dii_realisasi_anggaran realisasi "
                                        . "JOIN  dii_dm_anggaran anggaran "
                                            . "ON realisasi.anggaran_id = anggaran.id " 
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON realisasi.id_status = state.id "
                                        . " WHERE NOT (realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'"
                                    );
                                    
                                    $tot_rencana_realisasi = 0;
                                    
                                    while($r_data = $q_data_rencana->fetch_assoc()) 
                                    {                      
                                        $nominal_rencana = ($r_data["nominal_rencana"] != null ? $r_data["nominal_rencana"] : 0);
                                        $tot_rencana_realisasi += $nominal_rencana;                                                                                
                                                                                
                                        
                                        array_push($data, array(                                            
                                            'anggaran_id' => $r_data["anggaran_id"],
                                            'id_status' => $r_data["id_status"],
                                            'name_status' => $r_data["name_status"],
                                            'anggaran_kegiatan' => $r_data["anggaran_kegiatan"],
                                            'anggaran_tahun' => $r_data["anggaran_tahun"],
                                            'id_rencana' => ($r_data["id_rencana"] != null ? $r_data["id_rencana"] : "-"),
                                            'no_memo_rencana' => ($r_data["no_memo_rencana"] != null ? $r_data["no_memo_rencana"] : "-"),
                                            'nominal_rencana' => $nominal_rencana,
                                            'selisih_pengajuan_rencana' => ($r_data["selisih_pengajuan_rencana"] != null ? $r_data["selisih_pengajuan_rencana"] : "0"),
                                            'ket_kegiatan_rencana' => ($r_data["ket_kegiatan_rencana"] != null ? $r_data["ket_kegiatan_rencana"] : "-"),
                                            'koord_output_rencana' => ($r_data["koord_output_rencana"] != null ? $r_data["koord_output_rencana"] : "-"),
                                            'tgl_kegiatan_rencana' => ($r_data["tgl_kegiatan_rencana"] != null ? $r_data["tgl_kegiatan_rencana"] : "-"),
                                            
                                            'id_realisasi' => "-",
                                            'realisasi_id_rencana' => "-",
                                            'no_memo_realisasi' => "-",
                                            'nominal_realisasi' => 0,
                                            'ppn_realisasi' => 0,
                                            'pph_realisasi' => 0,
                                            'ket_kegiatan_realisasi' => "-",
                                            'type_realisasi' => "-",
                                            'koord_output_realisasi' => "-",
                                            'tgl_kegiatan_realisasi' => "-",   
                                            'bukti_setor_pajak' => "-",
                                            "sisa_anggaran" => 0
                                        ));                                                                                
                                    }
                                    
                                    while($r_data = $q_data_realisasi->fetch_assoc()) 
                                    {                            
                                        $nominal_realisasi = ($r_data["nominal_realisasi"] != null ? $r_data["nominal_realisasi"] : 0);
                                        $tot_rencana_realisasi += $nominal_realisasi;
                                        
                                        // id_rencana id_realisasi                                                                         
                                        array_push($data, array(
                                            'anggaran_id' => $r_data["anggaran_id"],
                                            'id_status' => $r_data["id_status"],
                                            'name_status' => $r_data["name_status"],
                                            'anggaran_kegiatan' => $r_data["anggaran_kegiatan"],   
                                            'anggaran_tahun' => $r_data["anggaran_tahun"],
                                            'id_rencana' => "-",
                                            'no_memo_rencana' => "-",
                                            'nominal_rencana' => 0,
                                            'selisih_pengajuan_rencana' => 0,
                                            'ket_kegiatan_rencana' => "-",
                                            'koord_output_rencana' => "-",
                                            'tgl_kegiatan_rencana' => "-",                                            
                                            'id_realisasi' => ($r_data["id_realisasi"] != null ? $r_data["id_realisasi"] : "-"),
                                            'realisasi_id_rencana' => ($r_data["realisasi_id_rencana"] != null ? $r_data["realisasi_id_rencana"] : "-"),
                                            'no_memo_realisasi' => ($r_data["no_memo_realisasi"] != null ? $r_data["no_memo_realisasi"] : "-"),
                                            'nominal_realisasi' => $nominal_realisasi,
                                            'ppn_realisasi' => ($r_data["ppn_realisasi"] != null ? $r_data["ppn_realisasi"] : 0),
                                            'pph_realisasi' => ($r_data["pph_realisasi"] != null ? $r_data["pph_realisasi"] : 0),
                                            'ket_kegiatan_realisasi' => ($r_data["ket_kegiatan_realisasi"] != null ? $r_data["ket_kegiatan_realisasi"] : "-"),
                                            'type_realisasi' => ($r_data["type_realisasi"] != null ? $r_data["type_realisasi"] : "-"),
                                            'koord_output_realisasi' => ($r_data["koord_output_realisasi"] != null ? $r_data["koord_output_realisasi"] : "-"),
                                            'tgl_kegiatan_realisasi' => ($r_data["tgl_kegiatan_realisasi"] != null ? $r_data["tgl_kegiatan_realisasi"] : "-"),                                                                                                                                    
                                            'bukti_setor_pajak' => ($r_data["bukti_setor_pajak"] != null ? $r_data["bukti_setor_pajak"] : "-"),
                                            "sisa_anggaran" => 0
                                        ));                                                                                
                                    }
                                    
                                    $q_data =  DB\dbQuery(
                                        $q_select. ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                                        . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                            . "ON rencana.anggaran_id = anggaran.id "
                                        . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                            . "ON realisasi.anggaran_id = anggaran.id ".
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                        . " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'"
                                    );
                                    $r_data = $q_data->fetch_assoc();
                                    $r_nilai = (int)$r_data['nilai_anggaran'];                                                                                                                                                     
                                    
                                    array_unshift($data, array(
                                        'anggaran_id' => $r_data["anggaran_id"],
                                        'id_status' => $r_data["id_status"],
                                        'name_status' => $r_data["name_status"],
                                        'anggaran_kegiatan' => $r_data["anggaran_kegiatan"],    
                                        'anggaran_tahun' => $r_data["anggaran_tahun"],
                                        'id_rencana' => "",
                                        'no_memo_rencana' => "",
                                        'nominal_rencana' => "",
                                        'selisih_pengajuan_rencana' => "",
                                        'ket_kegiatan_rencana' => "",
                                        'koord_output_rencana' => "",
                                        'tgl_kegiatan_rencana' => "",                                        
                                        'id_realisasi' => "",
                                        'realisasi_id_rencana'=> "",
                                        'no_memo_realisasi' => "",
                                        'nominal_realisasi' => "",
                                        'ppn_realisasi' => "",
                                        'pph_realisasi' => "",
                                        'ket_kegiatan_realisasi' =>"",
                                        'type_realisasi' => "",
                                        'koord_output_realisasi' => "",
                                        'tgl_kegiatan_realisasi' => "",   
                                        'bukti_setor_pajak' => "",
                                        "sisa_anggaran" => ($r_nilai - $tot_rencana_realisasi)
                                    ));
                                }                               
                                
                                $data = array(
                                    "success"=> true,
                                    "total"=> $total,
                                    "data" => $data
                                );
                                                                                                
                                echo json_encode($data);                               
                                exit();
                            }
                            
                            $q = 'SELECT * FROM '.$table." WHERE ".$condition.' LIMIT '.$limit;                                
                            $q_total = 'SELECT COUNT(*) as total  FROM '.$table." WHERE ".$condition;
                        }
                        else
                        {                                                        
                            $q = 'SELECT * FROM '.$table.' LIMIT '.$limit;
                            $q_total = 'SELECT COUNT(*) as total FROM '.$table;
                        }
                    }
                    else
                    {                                                
                        $limit = $_GET['start'].','.$_GET['limit'];                        
                        if($table == "dii_monitor_anggaran")
                        {                                                                                                               
                            $where_not_null = " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) ";
                          
                            $q_total = DB\dbQuery(
                            "SELECT COUNT(`anggaran_id`) AS count_anggaran_id FROM (".$q_select
                                . ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                            . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                . "ON rencana.anggaran_id = anggaran.id "."LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                            . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                        
                                . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
                            . "GROUP by anggaran_id) count_dii_dm_anggaran"
                            );
                            // SELECT anggaran.id AS anggaran_id,rencana.no_memo AS no_memo_rencana,rencana.nominal_anggaran AS nominal_rencana,rencana.selisih_pengajuan AS selisih_pengajuan_rencana,rencana.keterangan_kegiatan AS ket_kegiatan_rencana, realisasi.no_memo AS no_memo_realisasi,realisasi.keterangan_kegiatan AS ket_kegiatan_realisasi FROM dii_ls_rencana rencana RIGHT JOIN  dii_dm_anggaran anggaran ON rencana.anggaran_id = anggaran.id LEFT JOIN dii_realisasi_anggaran realisasi ON realisasi.anggaran_id = anggaran.id GROUP by anggaran_id;

                            $total = $q_total->fetch_assoc()['count_anggaran_id'];
                           
                            $q_group =  DB\dbQuery(
                            "SELECT anggaran_id FROM (".$q_select
                                . ",rencana.id_status AS id_status, state.name AS name_status  FROM dii_ls_rencana rencana "
                            . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                . "ON rencana.anggaran_id = anggaran.id "
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                            . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                . "ON realisasi.anggaran_id = anggaran.id ".$where_not_null
                            . "GROUP by anggaran_id) count_dii_dm_anggaran ORDER BY anggaran_id ASC "." LIMIT ".$limit
                            );    
                            $data = array();
                            while($r = $q_group->fetch_assoc()) 
                            {
                                $anggaran_id = $r['anggaran_id'];
                                $q_data_rencana =  DB\dbQuery(
                                    $q_select_rencana. ",rencana.id_status AS id_status, state.name AS name_status  FROM dii_ls_rencana rencana "
                                    . "JOIN  dii_dm_anggaran anggaran "
                                        . "ON rencana.anggaran_id = anggaran.id "
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                    . " WHERE NOT (rencana.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'  AND rencana.is_rampung = '0'"
                                );

                                $q_data_realisasi =  DB\dbQuery(
                                    $q_select_realisasi. ",realisasi.id_status AS id_status_realisasi, state.name AS name_status FROM dii_realisasi_anggaran realisasi "
                                    . "JOIN  dii_dm_anggaran anggaran "
                                        . "ON realisasi.anggaran_id = anggaran.id "
                                        ."LEFT JOIN dii_dm_state state "
                                    . "ON realisasi.id_status = state.id "
                                    . " WHERE NOT (realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'"
                                );

                                $tot_rencana_realisasi = 0;

                                while($r_data = $q_data_rencana->fetch_assoc()) 
                                {                            
                                    $nominal_rencana = ($r_data["nominal_rencana"] != null ? $r_data["nominal_rencana"] : 0);
                                    $tot_rencana_realisasi += $nominal_rencana;                                                                                                                                                
                                    
                                    array_push($data, array(
                                        'anggaran_id' => $r_data["anggaran_id"],
                                        'id_status' => $r_data["id_status"],
                                        'name_status' => $r_data["name_status"],
                                        'anggaran_kegiatan' => $r_data["anggaran_kegiatan"], 
                                        'anggaran_tahun' => $r_data["anggaran_tahun"],
                                        'id_rencana' => ($r_data["id_rencana"] != null ? $r_data["id_rencana"] : "-"),
                                        'no_memo_rencana' => ($r_data["no_memo_rencana"] != null ? $r_data["no_memo_rencana"] : "-"),
                                        'nominal_rencana' => $nominal_rencana,
                                        'selisih_pengajuan_rencana' => ($r_data["selisih_pengajuan_rencana"] != null ? $r_data["selisih_pengajuan_rencana"] : "0"),
                                        'ket_kegiatan_rencana' => ($r_data["ket_kegiatan_rencana"] != null ? $r_data["ket_kegiatan_rencana"] : "-"),
                                        'koord_output_rencana' => ($r_data["koord_output_rencana"] != null ? $r_data["koord_output_rencana"] : "-"),
                                        'tgl_kegiatan_rencana' => ($r_data["tgl_kegiatan_rencana"] != null ? $r_data["tgl_kegiatan_rencana"] : "-"),
                                        'id_realisasi' => "-",
                                        'realisasi_id_rencana'=> "-",
                                        'no_memo_realisasi' => "-",
                                        'nominal_realisasi' => 0,
                                        'ppn_realisasi' => 0,
                                        'pph_realisasi' => 0,
                                        'ket_kegiatan_realisasi' => "-",
                                        'type_realisasi' => "-",
                                        'koord_output_realisasi' => "-",
                                        'tgl_kegiatan_realisasi' => "-",        
                                        'bukti_setor_pajak' => "-",
                                        "sisa_anggaran" => 0
                                    ));                                                                                         
                                }

                                while($r_data = $q_data_realisasi->fetch_assoc()) 
                                {                            
                                    $nominal_realisasi = ($r_data["nominal_realisasi"] != null ? $r_data["nominal_realisasi"] : 0);
                                    $tot_rencana_realisasi += $nominal_realisasi;                                                                        
                                    
                                    array_push($data, array(
                                        'anggaran_id' => $r_data["anggaran_id"],
                                        'id_status' => $r_data["id_status"],
                                        'name_status' => $r_data["name_status"],
                                        'anggaran_kegiatan' => $r_data["anggaran_kegiatan"],               
                                        'anggaran_tahun' => $r_data["anggaran_tahun"],
                                        'id_rencana' => "-",
                                        'no_memo_rencana' => "-",
                                        'nominal_rencana' => 0,
                                        'selisih_pengajuan_rencana' => 0,
                                        'ket_kegiatan_rencana' => "-",
                                        'koord_output_rencana' => "-",
                                        'tgl_kegiatan_rencana' => "-",                                                                                
                                        'id_realisasi' => ($r_data["id_realisasi"] != null ? $r_data["id_realisasi"] : "-"),
                                        'realisasi_id_rencana'=> ($r_data["realisasi_id_rencana"] != null ? $r_data["realisasi_id_rencana"] : "-"),
                                        'no_memo_realisasi' => ($r_data["no_memo_realisasi"] != null ? $r_data["no_memo_realisasi"] : "-"),
                                        'nominal_realisasi' => $nominal_realisasi,
                                        'ppn_realisasi' => ($r_data["ppn_realisasi"] != null ? $r_data["ppn_realisasi"] : 0),
                                        'pph_realisasi' => ($r_data["pph_realisasi"] != null ? $r_data["pph_realisasi"] : 0),
                                        'ket_kegiatan_realisasi' => ($r_data["ket_kegiatan_realisasi"] != null ? $r_data["ket_kegiatan_realisasi"] : "-"),
                                        'type_realisasi' => ($r_data["type_realisasi"] != null ? $r_data["type_realisasi"] : "-"),
                                        'koord_output_realisasi' => ($r_data["koord_output_realisasi"] != null ? $r_data["koord_output_realisasi"] : "-"),
                                        'tgl_kegiatan_realisasi' => ($r_data["tgl_kegiatan_realisasi"] != null ? $r_data["tgl_kegiatan_realisasi"] : "-"),                                                                                
                                        'bukti_setor_pajak' => ($r_data["bukti_setor_pajak"] != null ? $r_data["bukti_setor_pajak"] : "-"),
                                        "sisa_anggaran" => 0
                                    ));                                                                                
                                }
                                $q_data =  DB\dbQuery(
                                    $q_select. ",rencana.id_status AS id_status, state.name AS name_status FROM dii_ls_rencana rencana "
                                    . "RIGHT JOIN  dii_dm_anggaran anggaran "
                                        . "ON rencana.anggaran_id = anggaran.id "
                                    . "LEFT JOIN dii_realisasi_anggaran realisasi "
                                        . "ON realisasi.anggaran_id = anggaran.id ".
                                        "LEFT JOIN dii_dm_state state "
                                    . "ON rencana.id_status = state.id "
                                    . " WHERE NOT (rencana.no_memo IS NULL AND realisasi.no_memo IS NULL) AND anggaran.kode="."'".$anggaran_id."'"
                                );
                                $r_data = $q_data->fetch_assoc();
                                $r_nilai = (int)$r_data['nilai_anggaran'];
                                                                
                                array_unshift($data, array(
                                    'anggaran_id' => $r_data["anggaran_id"],
                                    'id_status' => "",
                                    'name_status' => "",
                                    'anggaran_kegiatan' => $r_data["anggaran_kegiatan"],
                                    'anggaran_tahun' => $r_data["anggaran_tahun"],
                                    'id_rencana' => "",
                                    'no_memo_rencana' => "",
                                    'nominal_rencana' => "",
                                    'selisih_pengajuan_rencana' => "",
                                    'ket_kegiatan_rencana' => "",
                                    'koord_output_rencana' => "",
                                    'tgl_kegiatan_rencana' => "",                                                                        
                                    'id_realisasi' => "",
                                    'realisasi_id_rencana'=> "",
                                    'no_memo_realisasi' => "",
                                    'nominal_realisasi' => "",
                                    'ppn_realisasi' => "",
                                    'pph_realisasi' => "",
                                    'ket_kegiatan_realisasi' =>"",
                                    'type_realisasi' => "",
                                    'koord_output_realisasi' => "",
                                    'tgl_kegiatan_realisasi' => "",
                                    'bukti_setor_pajak' => "",
                                    "sisa_anggaran" => ($r_nilai - $tot_rencana_realisasi)
                                ));
                            }                                     

                            $data = array(
                                "success"=> true,
                                "total"=> $total,
                                "data" => $data
                            );

                            echo json_encode($data);                               
                            exit();                    
                        }
                        else
                        {
                            $q = 'SELECT * FROM '.$table.' LIMIT '.$limit;
                            $q_total = 'SELECT COUNT(*) as total FROM '.$table;
                        }
                    }     
                    
                    $rez = DB\dbQuery($q);
                    $rez_total = DB\dbQuery($q_total);
                }

                $rez_column = DB\dbQuery(
                    "SELECT `COLUMN_NAME`  FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='cb_chanthel' AND `TABLE_NAME`='".$table."'"
                );

                $column = array();

                while($r = $rez_column->fetch_assoc()) 
                {
                    array_push($column, $r["COLUMN_NAME"]);
                }

                while($r = $rez->fetch_assoc()) 
                {
                    $data_column = array();
                    foreach ($column as $k => $v) 
                    {
                        $data_column[$v] = $r[$v];
                    }
                    array_push($data, $data_column);
                }
            } 

            $total = $rez_total->fetch_assoc()['total'];
        }    
                  
        $data = array(
            "success"=> true,
            "total"=> $total,
            "data"=> $data
        );

        echo json_encode($data);
    }    
        
    if($_GET['act'] == 'get_data_table' && isset($_POST['table']) )
    {
        $data = array();
        $table = $_POST['table'];
        
        if($table == "users_groups")
        {
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];                        
                $rez = DB\dbQuery(
                    'SELECT id,name,position FROM '.$table.' WHERE id='.$id.' ORDER BY id ASC '
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('id'=> $r['id'],'name'=> $r['name'],'position'=> $r['position']));
                }
            }
        }
        else if($table == "tree")
        {
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];                        
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' WHERE id='.$id.' ORDER BY id ASC '
                );
                while($r = $rez->fetch_assoc()) 
                { // id  | name  | category | tag  | others 
                    array_push($data, 
                        array(
                            'id'=> $r['id'],
                            'name'=> $r['name'],
                            'category'=> $r['category'],
                            'tag'=> $r['tag'],
                            'others'=> $r['others']
                        ));
                }
            }
        }
        else if($table == "dii_dm_anggaran" || $table == "dii_dm_state" || $table == "dii_dm_workflow" || $table == "dii_realisasi_anggaran" || $table == "dii_ls_rencana" || $table == "dii_monitor_anggaran")
        {
            if(isset($_POST['id']) && $_POST['id'] != "0")
            {
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' WHERE id='.$_POST['id'].' ORDER BY id ASC '
                );
            }
            else if(isset($_POST['unique']) && $_POST['unique'] != "" && isset($_POST['unique_table']) && $_POST['unique_table'] != "")
            {
                $unique_table = $_POST['unique_table'];
                
                $table_id = $table.'.id';
                $unique_table_id = $unique_table.'.id_rencana';
                
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table
                    .' WHERE NOT EXISTS (select id FROM '.$unique_table
                    . ' WHERE '.$unique_table_id.' = '.$table_id.' )'
                    . 'ORDER BY id ASC '
                );
            }
            else
            {
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC '
                );
            }
            
            $rez_column = DB\dbQuery(
                "SELECT `COLUMN_NAME`  FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='cb_chanthel' AND `TABLE_NAME`='".$table."'"
            );
                       
            $column = array();
            
            while($r = $rez_column->fetch_assoc()) 
            {
                array_push($column, $r["COLUMN_NAME"]);
            }
            // SELECT `COLUMN_NAME`  FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='cb_chanthel' AND `TABLE_NAME`='dii_dm_anggaran';
            
            while($r = $rez->fetch_assoc()) 
            {
                $data_column = array();
                foreach ($column as $k => $v) 
                {
                    $data_column[$v] = $r[$v];
                }
                
                array_push($data, $data_column);
            }
        }        
        else
        {
            // table level. position, role_position
            if(isset($_POST['pid']))
            {
                $pid = $_POST['pid'];
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' WHERE pid='.$pid.' ORDER BY id ASC '
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('id'=> $r['id'], 'name'=> $r['name']));
                }      
            }
            else if(isset($_POST['id']))
            {
                $id = $_POST['id'];                        
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' WHERE id='.$id.' ORDER BY id ASC '
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('id'=> $r['id'],'pid'=> $r['pid'],'name'=> $r['name']));
                }
            }
            else
            {
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC '
                );
                while($r = $rez->fetch_assoc()) 
                {
                    if($table == "level")
                    {
                        array_push($data, array('id'=> $r['id'], 'name'=> $r['name']));
                    }
                    else if($table == "position")
                    {
                        array_push($data, array('id'=> $r['id'], 'name'=> $r['name'], 'pid'=> $r['pid']));
                    }
                    else if($table == "role_position")
                    {
                        array_push($data, array('id'=> $r['id'], 'id_position'=> $r['id_position'], 'pid_position'=> $r['pid_position']));
                    }                                
                }                        
            }
        }
        
        echo json_encode($data);
    }        
    
    if($_GET['act'] == 'join_data_table' && isset($_POST['table1'])  && isset($_POST['table2']) && !isset($_POST['table3']))
    {
        $data = array();
        $table1 = $_POST['table1']; // users_groups
        $table2 = $_POST['table2']; // position
        
        if($table1 == "users_groups" && $table2 == "position")
        {
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];                        
                $rez = DB\dbQuery(
                    "SELECT ug.id AS id,ug.name AS name,pos.name AS position FROM ".$table1." ug LEFT JOIN ".$table2." pos ON pos.id = ug.position WHERE ug.id=".$id
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('id'=> $r['id'],'name'=> $r['name'],'position'=> $r['position']));
                }
            }
        }
        else if($table1 == "files" && $table2 == "files_content")
        {
            $config = '/var/www/chanthel/httpsdocs/config.ini';
            
            
            $name = $_POST['name']; 
            $rez = DB\dbQuery(
                "select f.id,f.content_id,f.name,fc.path from ".$table1." f LEFT JOIN ".$table2." fc ON fc.id=f.content_id WHERE f.name='".$name."'"
            );
            while($r = $rez->fetch_assoc()) 
            {
                $result = array();
            if (file_exists($config)) 
            {
                $result = parse_ini_file($config);
            } 
            else 
            {
                throw new \Exception('Can\t load config file: ' . $config, 1);
            }
            $url = $result['datanode'].'/webhdfs/v1/chanthel-whdfs/var/www/chanthel/data/chanthel-data/files/chanthel/' . $r['path'] . "/" . $r['content_id'].'?op=OPEN&namenoderpcaddress='.$result['namenoderpcaddress'].'&offset=0';
            
            // http://training-00.labs247.com:50075/webhdfs/v1/chanthel-whdfs/var/www/chanthel/data/chanthel-data/files/chanthel/2018/08/28/16?op=OPEN&namenoderpcaddress=training-00.labs247.com&offset=0            
            // http://training-00.labs247.com:50070/webhdfs/v1/chanthel-whdfs/var/www/chanthel/data/chanthel-data/files/chanthel/2018/08/28/16?op=OPEN
    
                //echo '$url='.$url;
            
                array_push($data, array('id'=> $r['id'],'name'=> $r['name'],'path'=> $r['path'], 'url'=>$url));
            
            }
//            exit();
        }
        
        echo json_encode($data);
    }
    
    if($_GET['act'] == 'join_data_table' && isset($_POST['table1'])  && isset($_POST['table2']) && isset($_POST['table3']))
    {
        $data = array();
        $table1 = $_POST['table1']; // users_groups
        $table2 = $_POST['table2']; // position
        $table3 = $_POST['table3']; // level
        
        if($table1 == "users_groups" && $table2 == "position" && $table3 == "level")
        {
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];                        
                $rez = DB\dbQuery(
                    "SELECT  ug.name AS uname ,lev.name AS levname ,pos.name AS posname, ug.did AS did, lev.id AS lev_id ,ug.type AS type FROM  ".$table1." ug LEFT JOIN ".$table2." pos ON pos.id = ug.position LEFT JOIN ".$table3." lev ON pos.pid = lev.id WHERE did IS NULL AND type=2 ORDER BY lev_id ASC"
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('uname'=> $r['uname'],'levname'=> $r['levname'],'posname'=> $r['posname']));
                }
            }
            else
            {
                $rez = DB\dbQuery(
                    "SELECT  ug.name AS uname ,lev.name AS levname ,pos.name AS posname, ug.did AS did, lev.id AS lev_id ,ug.type AS type FROM  ".$table1." ug LEFT JOIN ".$table2." pos ON pos.id = ug.position LEFT JOIN ".$table3." lev ON pos.pid = lev.id  WHERE did IS NULL AND type=2 ORDER BY lev_id ASC"
                );
                while($r = $rez->fetch_assoc()) 
                {
                    array_push($data, array('uname'=> $r['uname'],'levname'=> $r['levname'],'posname'=> $r['posname']));
                }
            }
        }
        
        echo json_encode($data);
    }
    
    if($_GET['act'] == 'insert_data_table' && isset($_POST['table']) )
    {                       
        $table = $_POST['table'];
   
        $temp_id_table = array();
        
        if($table == "level")
        {
            $temp_id_grid = array();
            if(isset($_POST['data']))
            {
                $data_grid = $_POST['data'];         
                
                DB\startTransaction();
                // select from level
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC '
                );            

                while($r = $rez->fetch_assoc()) 
                {
                    array_push($temp_id_table,$r['id']);
                }            

                // data grid 1 s/d 10
                // data level 1 s/d 5

                foreach ($data_grid as $k => $v) 
                {
                    if(!empty($v['grid_level']))
                    {
                        // jika id is_exist, lakukan Update dengan grid_level                
                        // in_array("Glenn", $people))
                        if( in_array($v['grid_no'], $temp_id_table))
                        {
                            DB\dbQuery("UPDATE level SET name='".$v['grid_level']."' WHERE id=".$v['grid_no']);
                        }
                        else
                        {
                            DB\dbQuery("INSERT INTO level (`name`) VALUES ('".$v['grid_level']."')");
                            // insert tanpa id
                        }
                        array_push($temp_id_grid,$v['grid_no']);
                    }                
                }


                // data grid 1 s/d 5
                // data level 1 s/d 10
                foreach ($temp_id_table as $k => $v) 
                {
                    if( !in_array($v, $temp_id_grid))
                    {
                        DB\dbQuery("DELETE FROM level WHERE id=".$v);
                    }
                }

                DB\commitTransaction();       

                if(DB\dbAffectedRows() >= 0)
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                }
            }
        }        
        else if($table == "position")
        {
            $temp_id_grid = array();
            if(isset($_POST['data']))
            {
                // "grid_no":"41","grid_position":"PENGOLAH DATA TU 3","grid_pid_position":"5"
                $data_grid = $_POST['data'];

                DB\startTransaction();
                // select from level
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC '
                );            

                while($r = $rez->fetch_assoc()) 
                {
                    array_push($temp_id_table,$r['id']);
                }     
                
                // data grid 1 s/d 10
                // data level 1 s/d 5

                foreach ($data_grid as $k => $v) 
                {
                    if(!empty($v['grid_position']) && !empty($v['grid_pid_position']))
                    {
                        if($v['grid_pid_position'] != "0")
                        {
                            // jika id is_exist, lakukan Update dengan grid_level                
                            // in_array("Glenn", $people))
                            if( in_array($v['grid_no'], $temp_id_table))
                            {
                                DB\dbQuery("UPDATE position SET name='".$v['grid_position']."' , pid = ".$v['grid_pid_position']." WHERE id=".$v['grid_no']);
                            }
                            else
                            {
                                // insert tanpa id
                                DB\dbQuery("INSERT INTO position (`name`,`pid`) VALUES ('".$v['grid_position']."' , ".$v['grid_pid_position']." )");                                
                            }
                            array_push($temp_id_grid,$v['grid_no']);
                        }
                    }                
                }
                
                foreach ($temp_id_table as $k => $v) 
                {
                    if( !in_array($v, $temp_id_grid))
                    {
                        DB\dbQuery("DELETE FROM position WHERE id=".$v);
                    }
                }
                
                DB\commitTransaction();                      
                
                if(DB\dbAffectedRows() >= 0)
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                }
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
            }
        }
        else if($table == "role_position")
        {
            $temp_id_grid = array();
            if(isset($_POST['data']))
            {
                $data_grid = $_POST['data'];                         
                DB\startTransaction();
                // select from level
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC '
                );            

                while($r = $rez->fetch_assoc()) 
                {
                    array_push($temp_id_table,$r['id']);
                }        
                
                // data grid 1 s/d 10
                // data role_position 1 s/d 5

                foreach ($data_grid as $k => $v) 
                {
                    // "grid_no":"1","grid_id_position":"2","grid_pid_position":"1"
                    if(!empty($v['grid_id_position']) && !empty($v['grid_pid_position']))
                    {
                        if($v['grid_id_position'] != "0" && $v['grid_pid_position'] != "0")
                        {
                            // jika id is_exist, lakukan Update dengan grid_level                
                            // in_array("Glenn", $people))
                            if( in_array($v['grid_no'], $temp_id_table))
                            {                                                                
                                DB\dbQuery("UPDATE role_position SET id_position='".$v['grid_id_position']."' , pid_position = ".$v['grid_pid_position']." WHERE id=".$v['grid_no']);                                     
                            }
                            else
                            {
                                // insert tanpa id
                                DB\dbQuery("INSERT INTO role_position (`id_position`,`pid_position`) VALUES ('".$v['grid_id_position']."' , ".$v['grid_pid_position']." )");                                                             
                            }
                            array_push($temp_id_grid,$v['grid_no']);
                        }
                    }                
                }
                
                foreach ($temp_id_table as $k => $v) 
                {
                    if( !in_array($v, $temp_id_grid))
                    {
                        DB\dbQuery("DELETE FROM role_position WHERE id=".$v);
                    }
                }
                
                DB\commitTransaction();  
                
                if(DB\dbAffectedRows() >= 0)
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". id_position must be unique"));
                }                
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". id_position must be unique"));
            }
        }
        else if($table == "tree")
        {
            if(isset($_POST['id']))
            {                
                // insert data ke db, jika berhasil, insert ke solr.
                DB\startTransaction();
                
                $id = (isset($_POST['id']) ? $_POST['id'] : "");                     
                $category = (isset($_POST['category']) ? $_POST['category'] : "");                
                $tag = (isset($_POST['tag']) ? $_POST['tag'] : ""); 
                $others = (isset($_POST['others']) ? $_POST['others'] : ""); 
           
                DB\dbQuery("UPDATE $table SET category='".$category."' , tag = '".$tag."' , others = '".$others."' WHERE id=".$id);                
                DB\commitTransaction();  
                
                if(DB\dbAffectedRows() >= 0)
                {
                    // echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                                    
                    $config = parse_ini_file("/var/www/chanthel/httpsdocs/config.ini");
                    $solr_host = $config['solr_host']; // "yava-228.solusi247.com"
                    $solr_port = $config['solr_port']; ///solr_port = ""
                    $solr_core = "cb_chanthel";
                    $url = "http://".$solr_host.":".$solr_port."/solr/".$solr_core."/update?wt=json";                                                       
                    
                    $data = '{"add":{ "doc":{
                        "id":"'.$id.'",
                        "category":{"set":"'.$category.'"},
                        "tag":{"set":"'.$tag.'"},
                        "others":{"set":"'.$$others.'"},
                      },"boost" :1.0,"overwrite":true,"commitWithin":1000}}';
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => $data,  
                      CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json"
                      ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);
                    if ($err) 
                    {
                      // print_r("respon err : " .  $err );
                      // echo "cURL Error #:" . $err;
                      echo json_encode(array("error_code"=>1, "message"=>"cURL Error #:" . $err));
                    } 
                    else 
                    {
                        $response_obj = json_decode($response);
                        $status = $response_obj->responseHeader->status; // jika 0 maka success, jika 400 maka error
                        echo json_encode(array("error_code"=>0, "message"=>"success add keyword. code : $status"));
                    }
                }
                else
                {
                    echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                }
            }
        }
        else if($table == "dii_dm_anggaran" )
        {            
            date_default_timezone_set('Asia/Jakarta');
            $temp_id_grid = array();
            if(isset($_POST['data']) && isset($_POST['currentData']))
            {
                $data_grid = $_POST['data'];
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }

                DB\startTransaction();
                
                if(count($data_grid) > 0)
                {                    
                    $rez = DB\dbQuery(
                         'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                    );   

                    while($r = $rez->fetch_assoc()) 
                    {
                        array_push($temp_id_table,$r['id']);
                    }                      
                    foreach ($data_grid as $k => $v) 
                    {
                        if($v['id'] != "" && $v['kode'] != "" && $v['nilai_anggaran'] != "" && $v['koord_output'] != "")
                        {                                   
                            $tahun = date("Y", strtotime($v['tahun']));
                            if( in_array($v['id'], $temp_id_table))
                            {                                                            
                                DB\dbQuery("UPDATE ".$table." SET kode='".$v['kode']."' , kegiatan = '".$v['kegiatan']."' , nilai_anggaran='".$v['nilai_anggaran']."' , tahun='".$tahun."' , koord_output='".$v['koord_output']."'  WHERE id='".$v['id']."'");
                            }
                            else
                            {
                                
                                DB\dbQuery("INSERT INTO ".$table." (`kode`,`kegiatan`,`nilai_anggaran`,`tahun`,`koord_output`) VALUES ('".$v['kode']."' , '".$v['kegiatan']."' , '".$v['nilai_anggaran']."' , '".$tahun."' , '".$v['koord_output']."' )");
                            }
                            array_push($temp_id_grid,$v['id']);
                        }                    
                    }
                    
                    $is_delete = "0";
                
                    foreach ($temp_id_table as $k => $v) 
                    {
                        $is_delete = "1";
                        if( !in_array($v, $temp_id_grid))
                        {                                                                                    
                            DB\dbQuery("DELETE FROM $table WHERE id=".$v);
                        }
                    }
                }
                
                DB\commitTransaction();
                
                if($is_delete == "1")
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    if(DB\dbAffectedRows() >= 0)
                    {
                        echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                    }
                    else
                    {
                        echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                    } 
                }
            }
            else if(!isset($_POST['data']) && isset($_POST['currentData']))
            {
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }
                
                $temp_id_table = array();
                
                DB\startTransaction();
                
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                );   

                while($r = $rez->fetch_assoc()) 
                {
                    DB\dbQuery("DELETE FROM $table WHERE id=".$r['id']);                    
                }
                echo json_encode(array("error_code"=>0, "message"=>"Success delete data ".$table));
                
                DB\commitTransaction();
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". data can't be empty"));
            }                                       
        }
        else if($table == "dii_dm_state" )
        {
            $temp_id_grid = array();
            if(isset($_POST['data']) && isset($_POST['currentData']))
            {
                $data_grid = $_POST['data'];
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }
                
//                echo '$limit_from='.$limit_from.'__';
//                 echo '$limit_to='.$limit_to.'__';                                           
//                $currentData=Array
//                (
//                    [total] => 15
//                    [currentPage] => 2
//                    [pageCount] => 2
//                    [fromRecord] => 11
//                    [toRecord] => 15
//                )                
//                exit();
                
                DB\startTransaction();
                
                if(count($data_grid) > 0)
                {                    
                    $rez = DB\dbQuery(
                         'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                    );   

                    while($r = $rez->fetch_assoc()) 
                    {
                        array_push($temp_id_table,$r['id']);
                    }  
                    
                    foreach ($data_grid as $k => $v) 
                    {
                        if($v['id'] != "" && $v['name'] != "")
                        {       
                            if( in_array($v['id'], $temp_id_table))
                            {                                                            
                                DB\dbQuery("UPDATE ".$table." SET name='".$v['name']."'  WHERE id='".$v['id']."'");
                            }
                            else
                            {
                                
                                DB\dbQuery("INSERT INTO ".$table." (`name`) VALUES ('".$v['name']."')");
                            }
                            array_push($temp_id_grid,$v['id']);
                        }                    
                    }
                    
                    $is_delete = "0";
                
                    foreach ($temp_id_table as $k => $v) 
                    {
                        $is_delete = "1";
                        if( !in_array($v, $temp_id_grid))
                        {                                                                                    
                            DB\dbQuery("DELETE FROM $table WHERE id=".$v);
                        }
                    }
                }
                
                DB\commitTransaction();
                
                if($is_delete == "1")
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    if(DB\dbAffectedRows() >= 0)
                    {
                        echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                    }
                    else
                    {
                        echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                    } 
                }
            }
            else if(!isset($_POST['data']) && isset($_POST['currentData']))
            {
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }
                
                $temp_id_table = array();
                
                DB\startTransaction();
                
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                );   

                while($r = $rez->fetch_assoc()) 
                {
                    DB\dbQuery("DELETE FROM $table WHERE id=".$r['id']);                    
                }
                echo json_encode(array("error_code"=>0, "message"=>"Success delete data ".$table));
                
                DB\commitTransaction();
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". data can't be empty"));
            }
        }
        else if($table == "dii_dm_workflow" )
        {
            $temp_id_grid = array();
            if(isset($_POST['data']) && isset($_POST['currentData']))
            {
                $data_grid = $_POST['data'];
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }
                
//                echo '$limit_from='.$limit_from.'__';
//                 echo '$limit_to='.$limit_to.'__';                                           
//                $currentData=Array
//                (
//                    [total] => 15
//                    [currentPage] => 2
//                    [pageCount] => 2
//                    [fromRecord] => 11
//                    [toRecord] => 15
//                )                
//                exit();
                
                DB\startTransaction();
                
                if(count($data_grid) > 0)
                {                    
                    $rez = DB\dbQuery(
                         'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                    );   

                    while($r = $rez->fetch_assoc()) 
                    {
                        array_push($temp_id_table,$r['id']);
                    }  
                    
                    foreach ($data_grid as $k => $v) 
                    {
                        if($v['id'] != "" && $v['name'] != "" && $v['state_flow'] != "")
                        {       
                            if( in_array($v['id'], $temp_id_table))
                            {                                                            
                                DB\dbQuery("UPDATE ".$table." SET name='".$v['name']."',state_flow='".$v['state_flow']."'  WHERE id='".$v['id']."'");
                            }
                            else
                            {                                
                                DB\dbQuery("INSERT INTO ".$table." (`name`, `state_flow`) VALUES ('".$v['name']."', '".$v['state_flow']."')");
                            }
                            array_push($temp_id_grid,$v['id']);
                        }                    
                    }
                    
                    $is_delete = "0";
                
                    foreach ($temp_id_table as $k => $v) 
                    {
                        $is_delete = "1";
                        if( !in_array($v, $temp_id_grid))
                        {                                                                                    
                            DB\dbQuery("DELETE FROM $table WHERE id=".$v);
                        }
                    }
                }
                
                DB\commitTransaction();
                
                if($is_delete == "1")
                {
                    echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                }
                else
                {
                    if(DB\dbAffectedRows() >= 0)
                    {
                        echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));
                    }
                    else
                    {
                        echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table));
                    } 
                }
            }
            else if(!isset($_POST['data']) && isset($_POST['currentData']))
            {
                $currentData = $_POST['currentData'];
                
                if($currentData['toRecord'] > 0)
                {
                    $limit_from = $currentData['fromRecord']-1;
                    $limit_to = ($currentData['toRecord'] - $limit_from);
                }
                else
                {
                    $limit_from = 0;
                    $limit_to = 0;
                }
                
                $temp_id_table = array();
                
                DB\startTransaction();
                
                $rez = DB\dbQuery(
                    'SELECT * FROM '.$table.' ORDER BY id ASC LIMIT '.$limit_from.','.$limit_to
                );   

                while($r = $rez->fetch_assoc()) 
                {
                    DB\dbQuery("DELETE FROM $table WHERE id=".$r['id']);                    
                }
                echo json_encode(array("error_code"=>0, "message"=>"Success delete data ".$table));
                
                DB\commitTransaction();
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". data can't be empty"));
            }
            
        }
        else if($table == "dii_realisasi_anggaran" )
        {
//            date_default_timezone_set('Asia/Jakarta');
            if(isset($_POST['data']))
            {
                DB\startTransaction();
                $data = $_POST['data'];
//                Array
//                (
//                    [no_memo] => sdf
//                    [anggaran_id] => 11
//                    [tgl_kegiatan] => Tue Jul 24 2018 00:00:00 GMT+0700 (WIB)
//                    [penerima] => 
//                    [keterangan_kegiatan] => 
//                    [uraian] => 
//                    [koord_output] => 
//                    [nominal_anggaran] => 
//                    [ppn] => 
//                    [pph] => 
//                    [id_status] => extModel488-1
//                    [tgl_status] => Tue Jul 24 2018 00:00:00 GMT+0700 (WIB)
//                    [bukti_setor_pajak] => 
//                )
                 
                $str_data = "";
                $type = $data["type"];
                $id_rencana = (($data["id_rencana"] != null && $data["id_rencana"] != "") ? $data["id_rencana"] : null);
                foreach ($data as $k => $v) 
                {
                    
//                    echo $k."<br>";
                    if($k == "no_memo")
                    {
                        $str_data = "'".$v."'";
                    }
                    else if($k == "tgl_kegiatan")
                    {
                        $newDate = date("Y-m-d H:i:s", strtotime($v));                       
                        $str_data .= ",'".$newDate."'";
                    }
                    else if($k == "tgl_status")
                    {                        
                        $str_data .= ",'".date("Y-m-d H:i:s")."'";
                    }
                    else if($k == "anggaran_id" || $k == "id_status" ||  $k == "pengelola" ||  $k == "type" || $k == "id_rencana")
                    {
                        if($v == null) 
                        {
                            echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". data '".$k."' can't be empty"));
                            exit();
                        }
                        else
                        {
                            $str_data .= ",'".$v."'";
                        }
                    }
                    else
                    {
                        $str_data .= ",'".$v."'";
                    }
                }
                
                $str_data= $str_data.",'".User::getId()."'";
                
                if($type == "Rampung")
                {                    
                    // set field dii_ls_rencana.is_rampung = 1
                    DB\dbQuery("UPDATE dii_ls_rencana "
                        ."SET is_rampung='1' "
                        . "WHERE id='".$id_rencana."'"
                    );
                }
                
//                exit();
                // jika status Rampung atau jika "LS Rencana"
                
//                 {'id':"Rampung"},
//                {'id':"LS Rencana"}
                
//                echo "INSERT INTO ".$table." (`no_memo`, `anggaran_id`, `tgl_kegiatan`,`penerima`,`keterangan_kegiatan`,`uraian`,`koord_output`,`nominal_anggaran`,`ppn`,`pph`,`id_status`,`tgl_status`,`bukti_setor_pajak`,`pengelola`,`type`,`id_rencana`,`updated_by`) VALUES (".$str_data.")";
                
//                exit();
                
                DB\dbQuery("INSERT INTO ".$table." (`no_memo`, `anggaran_id`, `tgl_kegiatan`,`penerima`,`keterangan_kegiatan`,`uraian`,`koord_output`,`nominal_anggaran`,`ppn`,`pph`,`id_status`,`tgl_status`,`bukti_setor_pajak`,`pengelola`,`type`,`id_rencana`,`updated_by`) VALUES (".$str_data.")");
                DB\commitTransaction();
                echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));                 
            }
        }
        else if($table == "dii_ls_rencana" )
        {
            // date_default_timezone_set('Asia/Jakarta');
            if(isset($_POST['data']))
            {                
                $data = $_POST['data'];
                DB\startTransaction();
                
//                Array
//                (
//                    [no_memo] => asd
//                    [anggaran_id] => 12
//                    [tgl_kegiatan] => Tue Jul 24 2018 00:00:00 GMT+0700 (WIB)
//                    [penerima] => 
//                    [keterangan_kegiatan] => 
//                    [uraian] => 
//                    [koord_output] => 
//                    [nominal_anggaran] => 12
//                    [id_status] => 6
//                    [tgl_status] => Tue Jul 24 2018 00:00:00 GMT+0700 (WIB)
//                    [status_cair] => 0
//                    [status_berkas] => 0
//                    [selisih_pengajuan] => 123
//                )
                $str_data = "";
                foreach ($data as $k => $v) 
                {
                    if($k == "no_memo")
                    {
                        $str_data = "'".$v."'";
                    }
                    else if($k == "tgl_kegiatan")
                    {
                        $newDate = date("Y-m-d H:i:s", strtotime($v));                       
                        $str_data .= ",'".$newDate."'";
                    }
                    else if($k == "tgl_status")
                    {                        
                        $str_data .= ",'".date("Y-m-d H:i:s")."'";
                    }
                    else if($k == "anggaran_id" || $k == "id_status")
                    {
                        if($v == null)
                        {
                            echo json_encode(array("error_code"=>1, "message"=>"Failed update data ".$table. ". data '".$k."' can't be empty"));
                            exit();
                        }
                        else
                        {
                            $str_data .= ",'".$v."'";
                        }
                    }
                    else if($k == "status_cair" || $k == "status_berkas")
                    {
                        $bit_val = "b'$v'";
                        $str_data .= ",".$bit_val;
                    }
                    else
                    {
                        $str_data .= ",'".$v."'";
                    }
                }
                
                $str_data = $str_data.",'".User::getId()."'";                
                DB\dbQuery("INSERT INTO ".$table." (`no_memo`, `anggaran_id`, `tgl_kegiatan`, `penerima`, `keterangan_kegiatan`, `uraian`, `koord_output`, `nominal_anggaran`, `id_status`, `tgl_status`, `status_cair`, `status_berkas`, `selisih_pengajuan`,`updated_by`) VALUES (".$str_data.")");
                
                DB\commitTransaction();
                echo json_encode(array("error_code"=>0, "message"=>"Success update data ".$table));               
            }
        }
        else if($table == "dii_monitor_anggaran" )
        {
            $temp_id_grid = array();            
            
            if(isset($_POST['data']))
            {
                $data = $_POST['data'];                
                DB\startTransaction();
                foreach ($data as $k => $v) 
                {                    
                        if($v["id_rencana"] != "" && $v["id_rencana"] != "-")
                        {
                            // update where id_rencana
                            DB\dbQuery("UPDATE dii_ls_rencana "
                                ."SET no_memo='".$v['no_memo_rencana']."'"
                                .",nominal_anggaran='".$v['nominal_rencana']."' "
                                .",selisih_pengajuan='".$v['selisih_pengajuan_rencana']."' "
                                .",keterangan_kegiatan='".$v['ket_kegiatan_rencana']."' "
                                . "WHERE id='".$v['id_rencana']."'"
                            );
                        }
                        else if($v["id_realisasi"] != "" && $v["id_realisasi"] != "-")
                        {
                            DB\dbQuery("UPDATE dii_realisasi_anggaran "
                                ."SET no_memo='".$v['no_memo_realisasi']."'"
                                .",nominal_anggaran='".$v['nominal_realisasi']."' "
//                                .",ppn='".$v['ppn_realisasi']."' "
//                                .",pph='".$v['pph_realisasi']."' "
                                .",keterangan_kegiatan='".$v['ket_kegiatan_realisasi']."' "
                                .",type='".$v['type_realisasi']."' "
                                . "WHERE id='".$v['id_realisasi']."'"
                            );
                            
                            $realisasi_id_rencana = (($v['realisasi_id_rencana'] != "-" && $v['realisasi_id_rencana'] != "") ? $v['realisasi_id_rencana'] : NULL );
                            if($v['type_realisasi'] == "Rampung")
                            {                                 
                                DB\dbQuery("UPDATE dii_ls_rencana "
                                    ."SET is_rampung='1' "
                                    . "WHERE id='".$realisasi_id_rencana."'"
                                );
                            }
                            else
                            {
                                DB\dbQuery("UPDATE dii_ls_rencana "
                                    ."SET is_rampung='0' "
                                    . "WHERE id='".$realisasi_id_rencana."'"
                                );
                            }
                        }
                }
//                exit();                
                DB\commitTransaction();
                echo json_encode(array("error_code"=>0, "message"=>"Success update data rencana & realisasi."));
                exit();
            }
            else
            {
                echo json_encode(array("error_code"=>1, "message"=>"Error update. Data is required."));
            }
        }        
    }
    
    if($_GET['act'] == 'delete_data_table' && isset($_POST['table']) && isset($_POST['id']))
    {
        $table = $_POST['table'];
        $id = $_POST['id'];
        DB\startTransaction();
        if($table == "dii_ls_rencana" || $table == "dii_realisasi_anggaran")
        {
            if(isset($_POST['realisasi_id_rencana']))
            {
                $realisasi_id_rencana = $_POST['realisasi_id_rencana'];                
                DB\dbQuery("DELETE FROM ".$table." WHERE id=".$id);
                // DB\dbQuery("DELETE FROM dii_ls_rencana WHERE id=".$realisasi_id_rencana);
            }
            else
            {
                DB\dbQuery("DELETE FROM ".$table." WHERE id=".$id);
            }            
        }
        DB\commitTransaction();
        echo json_encode(array("error_code"=>0, "message"=>"Success delete data in table ".$table."."));
        exit();
    }else
        if($_GET['act'] == 'next_status_rencana' && isset($_POST['table']) && isset($_POST['id']))
    {
        $table = $_POST['table'];
        $id = $_POST['id'];
        $idstatus_next = $_POST['idstatus_next'];
        DB\startTransaction();
        if($table == "dii_ls_rencana" || $table == "dii_realisasi_anggaran")
        {           
            DB\dbQuery("UPDATE ".$table." SET id_status =".$idstatus_next."  WHERE id=".$id);
            // DB\dbQuery("DELETE FROM dii_ls_rencana WHERE id=".$realisasi_id_rencana);
        }
        DB\commitTransaction();
        echo json_encode(array("error_code"=>0, "message"=>"Success update data in table ".$table."."));
        exit();
    }else
        if($_GET['act'] == 'next_status_anggaran' && isset($_POST['table']) && isset($_POST['id']))
    {
        $table = $_POST['table'];
        $id = $_POST['id'];
        $idstatus_next = $_POST['idstatus_next'];
        DB\startTransaction();
        if($table == "dii_ls_rencana" || $table == "dii_realisasi_anggaran")
        {              
            DB\dbQuery("UPDATE ".$table." SET id_status =".$idstatus_next."  WHERE id=".$id);
            // DB\dbQuery("DELETE FROM dii_ls_rencana WHERE id=".$realisasi_id_rencana);
        }
        DB\commitTransaction();
        echo json_encode(array("error_code"=>0, "message"=>"Success update data in table ".$table."."));
        exit();
    }
}
else
{
    DataModel\GUID::checkTableExistance();

    $coreName = Config::get('core_name');

    $coreUrl = Config::get('core_url');

    // Added By : Usva Dhiar P.
    // Date : 2017-01-22
    $urlCoreUrl = $coreUrl;
    $urlArr = explode(":",$urlCoreUrl);
    $urlCoreIP = $urlArr[0].':'.$urlArr[1];
    
    //remove last slash
    $coreUrl = substr($coreUrl, 0, strlen($coreUrl) -1);

    $debugSuffix = IS_DEBUG_HOST ? '-debug' : '';

    $debugQueryParam = IS_DEBUG_HOST ? '&debug=1' : '';

    $rtl = Config::get('rtl') ? '-rtl' : '';

    $theme = empty($_SESSION['user']['cfg']['theme'])
        ? 'classic'
        : $_SESSION['user']['cfg']['theme'];

    // modified by : Usva Dhiar P
    if (empty($_SESSION['user'])) {
        exit(header('Location: ' . $coreUrl . '/login/'));
    }// ori
    else{
	// description : berfungsi untuk temporary directory upload workflow
	// NOTE : dir chanthel/httpsdocs harus di-set 0777
$dir_tmp_wf = 'tmp/';
$dir_tmp_pdf_wf = 'tmp/pdf/';
$owner = 'root';
$group = 'root';

if(!file_exists($dir_tmp_wf))
{
    // mkdir($dir_tmp_wf, 0777);
    // chown($dir_tmp_wf, 'root');
    // chmod($dir_tmp_wf, 0777);    
    // chown($dir_tmp_wf, 'root');

    // shell_exec("chown ".$owner.":".$group." ".$dir_tmp_wf);    

    //if(!file_exists($dir_tmp_pdf_wf))
    //{
    //    mkdir($dir_tmp_pdf_wf, 0777);
    //    chmod($dir_tmp_pdf_wf, 0777);        
    //    shell_exec("chown ".$owner.":".$group." ".$dir_tmp_pdf_wf);
    //}
}
    }	
    require_once(LIB_DIR . 'MinifyCache.php');

    $projectTitle = Config::getProjectName();

    loadMinifyUris();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <meta name="author" content="KETSE">
        <meta name="description" content="Casebox">
        <meta name="robots" content="noindex">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="/chanthel.ico" type="image/x-icon">

    <?php

    echo '<link rel="stylesheet" type="text/css" href="/libx/ext/packages/ext-theme-' . $theme . '/build/resources/ext-theme-' . $theme . '-all' . $rtl . '.css" />
        <link rel="stylesheet" type="text/css" href="/libx/extjs-ace/styles.css" />
        <link rel="stylesheet" type="text/css" href="' . $coreUrl . getMinifyGroupUrl('css') . '" />' . "\n";

    // Custom CSS for the core
    $css = Config::getCssList();
    if (!empty($css)) {
        echo '<link rel="stylesheet" type="text/css" href="' . $coreUrl . getMinifyGroupUrl($coreName . '_css') . '" />' . "\n";
    }

    echo '<title>' . $projectTitle . '</title>' . "\n";
    $colors = Users::getColors();
    $rez = array();
    foreach ($colors as $id => $c) {
        $rez[] = '.user-color-' . $id . "{background-color: $c}";
    }
    $rez = implode("\n", $rez);
    echo "<style>$rez</style>";
    ?>
    <style>
    #loading {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: #fff;
    }

    #loading, #stb {
    background-color: #f5f5f5;
    }

    .cmsg {
    margin: 1em;
    }

    .msg {
        margin-top: 150px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 5px;
        color: #000
    }

    .lpb {
        text-align: center;
        width: 320px;
        border: 1px solid #999;
        padding: 1px;
        height: 8px;
        margin-right: auto;
        margin-left: auto;
    }

    @-webkit-keyframes pb { 0% { background-position:0 0; } 100% { background-position:-16px 0; } }

    #lpt {
    width: 0;
    height: 100%;
    background-color: #6188f5;
    background-repeat: repeat-x;
    background-position: 0 0;
    background-size: 16px 8px;
    background-image: -webkit-linear-gradient(315deg,transparent,transparent 33%,rgba(0,0,0,0.12) 33%,rgba(0,0,0,0.12) 66%,transparent 66%,transparent);
    background-image: -moz-linear-gradient(315deg,transparent,transparent 33%,rgba(0,0,0,0.12) 33%,rgba(0,0,0,0.12) 66%,transparent 66%,transparent);
    background-image: -o-linear-gradient(315deg,transparent,transparent 33%,rgba(0,0,0,0.12) 33%,rgba(0,0,0,0.12) 66%,transparent 66%,transparent);
    background-image: linear-gradient(315deg,transparent,transparent 33%,rgba(0,0,0,0.12) 33%,rgba(0,0,0,0.12) 66%,transparent 66%,transparent);
    -webkit-animation: pb 0.8s linear 0 infinite;
    }

    .msgb {
        position: absolute;
        right: 0;
        font-size: 11px;
        font-weight: normal;
        color: #555;
        background: #fff;
        padding: 20px;
    }

    .msgb a {
        color: #777;
    }
    </style>

    <?php
    if (Config::get('geoMapping', false)) {
      echo '<link rel="stylesheet" href="/libx/leaflet/leaflet.css" />
        <script src="/libx/leaflet/leaflet.js"></script>';
    }
    echo '<script src="/libx/underscore-amd/underscore.js"></script>';
echo '<script src="/libx/backbone-amd/backbone.js"></script>';
//echo '<script src="/libx/backbone-amd/jquery.min.js"></script>';
echo '<script  src="/libx/requirejs-amd/require.js"></script>';
    ?>
    
    <script type="text/javascript">
        require.config({
    baseUrl: "/libx/", // tidak terpengaruh oleh posisi application.js
    paths:
    {
        'underscore':'underscore-amd/underscore', 
        'backbone':'backbone-amd/backbone',
//        'jquery':'backbone-amd/jquery.min',
//        'models':'models', // directory
    }
});
        myDiagram = {};
        myDiagram["coreUrl"]='<?php echo htmlspecialchars($urlCoreUrl);?>';
        myDiagram["coreUrlIP"]='<?php echo htmlspecialchars($urlCoreIP);?>';
        myDiagram["name"]='<?php echo htmlspecialchars($_SESSION["user"]["name"]);?>';
        myDiagram["key"]='<?php echo htmlspecialchars($_SESSION["key"]);?>';
        myDiagram["id"]='<?php echo htmlspecialchars($_SESSION["user"]["id"]);?>';
    </script>
    
    <script type="text/javascript">
        window.name = '<?php
            echo substr(str_shuffle(MD5(tempnam(sys_get_temp_dir(), 'pre') . microtime())), 0, rand(15, 50));
        ?>';

        function setProgress(label, percentage)
        {
            document.getElementById('loading-msg').innerHTML = label + 'â€¦';
            document.getElementById('lpt').style.width = percentage;
        }
    </script>
    </head>

    <body>

    <div style="font-size:0px;color:white;z-index:-9;position:absolute;left:-999px">
    </div>

    <div id="loading">
        <div class="cmsg">
            <div class="msg" id="loading-msg">
                Loading Casebox (<?php echo $projectTitle ?>)â€¦
            </div>
            <div class="lpb">
                <div id="lpt" style="width: 50%;"></div>
            </div>
        </div>

        <div id="stb" class="msgb" style="bottom:10px">
            <a href="http://www.solusi247.com" target="_blank">www.solusi247.com</a> <span style="color: #AAA; padding-left: 2px; padding-right: 5px">&bull;</span>  <a href="#">Support forum</a>
        </div>

        <div id="loadingError" class="cmsg" style="clear:left;display:none">
            <p style="font-size:larger;margin:40px 0">
            This is taking longer than usual.
            <a href="https://core.casebox.org"><b>Try reloading the page</b></a>.
            </p>

            <div>
            ...
            </div>
        </div>
    </div>

    <script type="text/javascript">setProgress('<?php echo L\get('Loading_ExtJS_Core')?>', '30%')</script>
    <script type="text/javascript" src="<?php echo EXT_PATH . '/ext-all' . $rtl . $debugSuffix; ?>.js"></script>
    <script type="text/javascript" src="<?php echo EXT_PATH . '/packages/ext-charts/build/ext-charts' . $debugSuffix; ?>.js"></script>
    <script type="text/javascript" src="<?php echo EXT_PATH . '/packages/ext-theme-' . $theme . '/build/ext-theme-' . $theme . $debugSuffix; ?>.js"></script>

    <script type="text/javascript">
        bravojs = {
            url: window.location.protocol + "//" + window.location.host + "/libx/extjs-ace/Component.js"
        };
        document.write('<script type="text/javascript" src="' + bravojs.url + '"><' + '/script>');

        //move liflet object to LL, because we assign our translations in L object below
        if (typeof(L) !== 'undefined') {
          LL = L;
          delete L;
        }
    </script>

    <?php

    if (!empty($_SESSION['user']['language']) && ($_SESSION['user']['language'] != 'en')) {

        // ExtJS locale
        if (file_exists(DOC_ROOT.EXT_PATH.'/packages/ext-locale/build/ext-locale-' . $_SESSION['user']['language'] . '.js')) {
            echo '<script type="text/javascript" src="' . EXT_PATH . '/packages/ext-locale/build/ext-locale-' . $_SESSION['user']['language'] . '.js"></script>';
        }

        // Casebox locale
        echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl('lang-' . $_SESSION['user']['language']) . '"></script>';
    } else {
        // default Casebox locale
        echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl('lang-en') . '"></script>';
    }

    ?>
    
    <script type="text/javascript" src="/libx/cores/jquery.min.js"></script>
    
    <script type="text/javascript" src="/libx/highlight/highlight.pack.js"></script>

    <script type="text/javascript">setProgress('<?php echo L\get('Loading_ExtJS_UI')?>', '60%')</script>

    <?php
    echo '<script type="text/javascript" src="' . $coreUrl . '/remote/api.php"></script>';

    echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl('js') . $debugQueryParam . '"></script>';
    echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl('jsdev') . $debugQueryParam . '"></script>';
    echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl('jsoverrides') . $debugQueryParam . '"></script>';

    $js = Config::getJsList();
    if (!empty($js)) {
        echo '<script type="text/javascript" src="' . $coreUrl . getMinifyGroupUrl($coreName.'_js') . $debugQueryParam . '"></script>';
    }
    $prc = Config::getPluginsRemoteConfig();
    if (!empty($prc)) {
        echo '<script type="text/javascript">CB.plugin.config = '.Util\jsonEncode($prc).';</script>';
    }

    echo '<script type="text/javascript" src="' . $coreUrl . '/js/CB.DB.php"></script>';
    ?>

    <script type="text/javascript">setProgress('<?php echo L\get('Initialization')?>', '100%')</script>

    <!--
    Modif By : Usva Dhiar P.
    Description : change layput

    <!-- bootstrap, d3, c3-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/libx/cores/bootstrap.min.css" />-->

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/libx/cores/c3.css" />-->

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
    <script type="text/javascript" src="/libx/cores/jquery.min.js"></script>

    <!--
    Created By : Usva Dhiar P.
    Date : 2018-05-28
    Terhubung dengan file : httpsdocs/js/browser/ViewContainer.js
    -->
    <input type="hidden" id="temp_list_task">
    
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
    <!--<script type="text/javascript" src="/libx/cores/bootstrap.min.js"></script>-->

    <!--<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>-->
    <!--<script type="text/javascript" src="/libx/cores/d3.v3.min.js"></script>-->

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.js" charset="utf-8"></script>-->
    <!--<script type="text/javascript" src="/libx/cores/c3.js"></script>-->
    
   <script>
        function addTagCatogory(id)
        {
            var url = window.location.href;
            var arr = url.split("/");
            var url_chanthel = arr[0] + "//" + arr[2] +"/"+ arr[3] + "/";
            
            var tag_metadata = $("#tag_metadata").val();
            var category_metadata = $("#category_metadata").val();
            
            // kirim ke db & solr            
            $.ajax({
                type: 'POST',
                url : url_chanthel+"?act="+"insert_data_table",
                data : 
                {
                    table:'tree',
                    id:id,
                    tag:tag_metadata,
                    category:category_metadata
                },
                dataType: 'json',
                async: false,
                success: function(data, textStatus, jqXHR) 
                {   
                    // {"error_code":0,"message":"success add keyword. code : 500"}
                    if(data.error_code == "0")
                    {
                        Ext.Msg.alert('Success', data.message);
                    }
                    else
                    {
                        Ext.Msg.alert('Error', data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    Ext.Msg.alert('Error', 'Failed add keyword in this document');
                }
            }); 
        }
    </script>
     
    </body>
    </html>

    <?php

    saveMinifyUris();
}
