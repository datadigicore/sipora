<?php
include 'config/application.php';

switch ($link[3]) {
  case 'table':
    $table = "rabview";
    $key   = "id";
    $dataArray['url_rewrite'] = $url_rewrite; 
    $dataArray['direktorat'] = $direk; 
    $tahun = $_POST['tahun'];
    $direktorat = $_POST['direktorat'];
    $kdoutput = $_POST['kdoutput'];
    $kdsoutput = $_POST['kdsoutput'];
    $kdkmpnen = $_POST['kdkmpnen'];
    $kdskmpnen = $_POST['kdskmpnen'];
    $column = array(
      array( 'db' => 'id',      'dt' => 0 ),
      array( 'db' => 'kdprogram',  'dt' => 1, 'formatter' => function($d,$row, $dataArray){ 
          return '<table><tr><td>Program</td><td> :&nbsp;</td><td>'.$d.'</td></tr>'.
                 '<tr><td>Output</td><td> :&nbsp;</td><td>'.$row[9].'</td></tr>'.
                 '<tr><td>Sub Output</td><td> :&nbsp;</td><td>'.$row[10].'</td></tr>'.
                 '<tr><td>Komponen</td><td> :&nbsp;</td><td>'.$row[11].'</td></tr>'.
                 '<tr><td>Sub Komponen</td><td> :&nbsp;</td><td>'.$row[12].'</td></tr></table>';
      }),
      array( 'db' => 'kdgiat',  'dt' => 2, 'formatter' => function($d, $row, $dataArray){
        return $dataArray['direktorat'][$d];
      }),
      array( 'db' => 'deskripsi',  'dt' => 3),
      array( 'db' => 'tanggal',  'dt' => 4, 'formatter' => function( $d, $row ) {
        $arrbulan = array(
                '01'=>"Januari",
                '02'=>"Februari",
                '03'=>"Maret",
                '04'=>"April",
                '05'=>"Mei",
                '06'=>"Juni",
                '07'=>"Juli",
                '08'=>"Agustus",
                '09'=>"September",
                '10'=>"Oktober",
                '11'=>"November",
                '12'=>"Desember",
        );
        $pecahtgl1 = explode("-", $d);
        $tglawal = $pecahtgl1[2].' '.$arrbulan[$pecahtgl1[1]].' '.$pecahtgl1[0];
        $pecahtgl2 = explode("-", $row[15]);
        $tglakhir = $pecahtgl2[2].' '.$arrbulan[$pecahtgl2[1]].' '.$pecahtgl2[0];
        return $tglawal.' - '.$tglakhir;
      }),
      array( 'db' => 'lokasi',  'dt' => 5, 'formatter' => function($d,$row){
        return $row[14].', '.$d;
      }),
      array( 'db' => '(SELECT SUM(rabfull.value) from rabfull where rabfull.rabview_id = rabview.id group by rabfull.rabview_id)','dt' => 6, 'formatter' => function($d,$row){
        return 'Rp '.number_format($d,2,',','.');
      }),
      array( 'db' => 'status', 'dt' => 7, 'formatter' => function($d,$row){ 
        if($d==0){
          return '<i>Belum Diajukan</i>';
        }
        elseif($d==1){
          return '<i>Telah Diajukan</i>';
        }
        elseif($d==2){
          return '<i>Telah Disahkan</i>';
        }
        elseif($d==3){
          return '<i>Revisi</i>';
        }
        elseif($d==4){
          return '<i>Close</i>';
        }
        elseif($d==5){
          return '<i>Adendum</i>';
        }
        elseif($d==6){
          return '<i>Close Adendum</i>';
        }
        elseif($d==7){
          return '<i>Penutupan Anggaran</i>';
        }
      }),
      array( 'db' => 'status',  'dt' => 8, 'formatter' => function($d,$row, $dataArray){ 
        $button = '<div class="btn-group">';
        if($d==0 && $_SESSION['level'] != 0){
          $button .= '<a style="margin:0 2px;" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm" ><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          if ($_SESSION['level'] == 2) {
            $button .= '<a style="margin:0 2px;" id="btn-aju" href="#ajuan" class="btn btn-flat btn-success btn-sm col-sm" data-toggle="modal"><i class="fa fa-check"></i>&nbsp; Ajukan</a>';
          }
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rab/edit/'.$row[0].'" class="btn btn-flat btn-warning btn-sm" ><i class="fa fa-pencil"></i>&nbsp; Edit</a>';
          $button .= '<a style="margin:0 2px;" id="btn-del" href="#delete" class="btn btn-flat btn-danger btn-sm" data-toggle="modal"><i class="fa fa-close"></i>&nbsp; Delete</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/pengajuan_UMK/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Pengajuan UMK</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/rincian_kebutuhan_dana/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Rincian Keb. Dana</a>';
        }
        elseif ($d==0 && $_SESSION['level'] == 0) {
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/pengajuan_UMK/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Pengajuan UMK</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/rincian_kebutuhan_dana/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Rincian Keb. Dana</a>';
        }
        elseif($d==1  && $_SESSION['level'] != 0){
          $button .= '<a style="margin:0 2px;" class="btn btn-flat btn-primary btn-sm" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" ><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/pengajuan_UMK/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Pengajuan UMK</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/rincian_kebutuhan_dana/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Rincian Keb. Dana</a>';        
        }

        elseif ($d==1  && $_SESSION['level'] == 0) {
          $button .= '<a style="margin:0 2px;" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          $button .= '<a style="margin:0 2px;" id="btn-sah" href="#sahkan" class="btn btn-flat btn-success btn-sm" data-toggle="modal"><i class="fa fa-check"></i>&nbsp; Sahkan</a>';
          $button .= '<a style="margin:0 2px;" id="btn-rev" href="#revisi" class="btn btn-flat btn-warning btn-sm" data-toggle="modal"><i class="fa fa-edit"></i>&nbsp; Revisi</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/pengajuan_UMK/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Pengajuan UMK</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/rincian_kebutuhan_dana/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Rincian Keb. Dana</a>';
        }
        elseif($d==3 && $_SESSION['level'] != 0){
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm" ><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          if ($_SESSION['level'] == 2) {
            $button .= '<a style="margin:0 2px;" id="btn-aju" href="#ajuan" class="btn btn-flat btn-success btn-sm" data-toggle="modal"><i class="fa fa-check"></i>&nbsp; Ajukan Revisi</a>';
          }
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rab/edit/'.$row[0].'" class="btn btn-flat btn-warning btn-sm" ><i class="fa fa-pencil"></i>&nbsp; Edit</a>';
          if ($row[13] != "") {
            $button .= '<a style="margin:0 2px;" id="btn-pesan" href="#pesanrevisi" class="btn btn-flat btn-danger btn-sm" data-toggle="modal"><i class="fa fa-envelope"></i>&nbsp; Pesan </a>';
          }
        }
        elseif ($d==3 && $_SESSION['level'] == 0) {
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          if ($row[13] != "") {
            $button .= '<a style="margin:0 2px;" id="btn-pesan" href="#pesanrevisi" class="btn btn-flat btn-danger btn-sm" data-toggle="modal"><i class="fa fa-envelope"></i>&nbsp; Pesan </a>';
          }
        }
        elseif($d==6 && $_SESSION['level'] != 0){
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm" ><i class="fa fa-list"></i>&nbsp; Rincian</a>';
        }
        elseif ($d==6 && $_SESSION['level'] == 0) {
          $button .= '<a style="margin:0 2px;" id="btn-aju" href="#tutup" class="btn btn-flat btn-success btn-sm" data-toggle="modal"><i class="fa fa-check"></i>&nbsp; Penutupan Anggaran</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-list"></i>&nbsp; Rincian</a>';
        }
        else{
          $button .= '<a style="margin:0 2px;" href="'.$dataArray['url_rewrite'].'content/rabdetail/'.$row[0].'" class="btn btn-flat btn-primary btn-sm" ><i class="fa fa-list"></i>&nbsp; Rincian</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/pengajuan_UMK/'.$row[0].'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Pengajuan UMK</a>';
          $button .= '<a style="margin:0 2px;" id="btn-trans" href="'.$dataArray['url_rewrite'].'process/report/rincian_kebutuhan_dana/'.$row[0].'/1'.'" class="btn btn-flat btn-default btn-sm" ><i class="fa fa-file-text-o"></i>&nbsp; Cetak Daftar PJ UMK</a>';
        }
        
        $button .= '</div>';
        return $button;
      }),
      array( 'db' => 'kdoutput',  'dt' => 9),
      array( 'db' => 'kdsoutput',  'dt' => 10),
      array( 'db' => 'kdkmpnen',  'dt' => 11),
      array( 'db' => 'kdskmpnen',  'dt' => 12),
      array( 'db' => 'pesan',  'dt' => 13),
      array( 'db' => 'tempat',  'dt' => 14),
      array( 'db' => 'tanggal_akhir',  'dt' => 15),
    );
    $where="";
    if ($tahun != "") {
      $where = 'thang = "'.$tahun.'"';
    }
    if ($direktorat != "") {
      if ($where == "") {
        $where .= 'kdgiat = "'.$direktorat.'"';
      }else{
        $where .= 'AND kdgiat = "'.$direktorat.'"';
      }
    }
    if ($kdoutput != "") {
      if ($where == "") {
        $where .= 'kdoutput = "'.$kdoutput.'"';
      }else{
        $where .= 'AND kdoutput = "'.$kdoutput.'"';
      }
    }
    if ($kdsoutput != "") {
      if ($where == "") {
        $where .= 'kdsoutput = "'.$kdsoutput.'"';
      }else{
        $where .= 'AND kdsoutput = "'.$kdsoutput.'"';
      }
    }
    if ($kdkmpnen != "") {
      if ($where == "") {
        $where .= 'kdkmpnen = "'.$kdkmpnen.'"';
      }else{
        $where .= 'AND kdkmpnen = "'.$kdkmpnen.'"';
      }
    }
    if ($kdskmpnen != "") {
      if ($where == "") {
        $where .= 'kdskmpnen = "'.$kdskmpnen.'"';
      }else{
        $where .= 'AND kdskmpnen = "'.$kdskmpnen.'"';
      }
    }
    $group='';
    $datatable->get_table_group($table, $key, $column,$where,$group,$dataArray);
    break;
  case 'table-rkakl':
    $dataArray['url_rewrite'] = $url_rewrite;
    $query="SELECT `id`, `thang`, `kdprogram`, `kdgiat`, `kdoutput`, `kdsoutput`, `kdkmpnen`, `kdskmpnen`, `deskripsi`, `tanggal`, `tanggal_akhir`, `tempat`, `lokasi`, `volume`, `satuan`, `jumlah`, `status`, `created_at`, `created_by`, `idtriwulan` FROM `rabview` " ;
    $result=$db->_fetch_array($query,1);
    $rabview =array();
    $key_stack=array();

    if (!is_null($result)) {
      foreach ($result as $key => $value) {
        $id=$value['id'];
        $thang=$value['thang'];
        $kdprogram=$value['kdprogram'];
        $kdgiat=$value['kdgiat'];
        $kdoutput=$value['kdoutput'];
        $kdsoutput=$value['kdsoutput'];
        $kdkmpnen=$value['kdkmpnen'];
        $kdskmpnen=$value['kdskmpnen'];
        $jumlah=$value['jumlah'];
        $volume=$value['volume'];
        $key="$kdprogram-$kdgiat-$kdoutput-$kdsoutput-$kdkmpnen-$kdskmpnen";
        $realisasi["$kdprogram-$kdgiat-$kdoutput-$kdsoutput-$kdkmpnen-$kdskmpnen"]['key']=$key;
        $realisasi["$kdprogram-$kdgiat-$kdoutput-$kdsoutput-$kdkmpnen-$kdskmpnen"]['jumlah'] += $jumlah;
        $realisasi["$kdprogram-$kdgiat-$kdoutput-$kdsoutput-$kdkmpnen-$kdskmpnen"]['volume'] += $volume;
      }
      $dataArray = $realisasi;
    }
    
    $query = "SELECT * from triwulan where status = '1' ORDER BY id DESC LIMIT 1";
    $result=$db->_fetch_array($query,1);
    if (!is_null($result)) {
      foreach ($result as $key => $value) {
        $dataArray['prog_low'] = $value['prog_low'];
        $dataArray['prog_med'] = $value['prog_med'];
        $dataArray['prog_high'] = $value['prog_high'];
      }
    }else{
      $dataArray['prog_low'] = -1;
      $dataArray['prog_med'] = -1;
      $dataArray['prog_high'] = -1;
    }

    $swhere = "";
    if ($_SESSION['direktorat'] != "") {
      $swhere="WHERE KDGIAT = '".$_SESSION['direktorat']."' ";
    }

    $tableKey   = "rkakl_full";
    $primaryKey = "idrkakl";
    $columns    = array('IDRKAKL',
                        'KDGIAT',
                        'CONCAT(KDOUTPUT," - ",NMOUTPUT)',
                        'CONCAT(KDSOUTPUT," - ",NMSOUTPUT)',
                        'CONCAT(KDKMPNEN," - ",NMKMPNEN)',
                        'CONCAT(KDSKMPNEN," - ",NMSKMPNEN)',
                        'SUM(JUMLAH)',
                        'CONCAT(KDPROGRAM,"-",KDGIAT,"-",KDOUTPUT,"-",KDSOUTPUT,"-",KDKMPNEN,"-",KDSKMPNEN)',
                        'SUM(JUMLAH)',
                        'SUM(VOLREAL)',
                        'SUM(VOLKEG)',
                        'SUM(JUMLAH)',
                        'IDRKAKL',
                        );
    $formatter  = array(
      '6' => array('formatter' => function($d,$row,$data){ 
        return number_format($d,2,",",".");
      }),
      '7' => array('formatter' => function($d,$row,$data){ 
        if (isset($data[$d]['jumlah'])) {
          return number_format($data[$d]['jumlah'],2,",",".");
        }else{
          return 0;
        }
      }),
      '8' => array('formatter' => function($d,$row,$data){ 
        if (isset($data[$row[7]]['jumlah'])) {
          $persen = ($data[$row[7]]['jumlah'] / $d) *100;

          if ($data['prog_low'] != "-1") {
            if ($persen <= $data['prog_low']) {
              $status = 'danger';
            }elseif ($persen <= $data['prog_med']) {
              $status = 'warning';
            }else{
              $status = 'success';
            }
          }else{
            $status = 'default';
          }
          return '<div class="pull-right">&nbsp;<span class="label label-'.$status.'">'.number_format($persen,2).'%</span></div>
                  <div class="progress progress-sm active">
                    <div class="progress-bar progress-bar-'.$status.' progress-bar-striped" role="progressbar" aria-valuenow="'.number_format($persen,2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.number_format($persen,2).'%">
                      <span class="sr-only">'.number_format($persen,2).'% Complete</span>
                    </div>
                  </div>';
        }else{
          return '<div class="pull-right">&nbsp;<span class="label label-danger">'.number_format(0,2).'%</span></div>
                  <div class="progress progress-sm active">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="'.number_format(0,2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.number_format(0,2).'%">
                      <span class="sr-only">'.number_format(0,2).'% Complete</span>
                    </div>
                  </div>';
        }
      }),
      '9' => array('formatter' => function($d,$row,$data){ 
        return number_format($d).' / '.number_format($row[10],0,",",".");
      }),
      '10' => array('formatter' => function($d,$row,$data){ 
        if (isset($d) || $d != 0 || $d != "") {
          $persenvol = ($row[9] / $d) * 100;
          if ($data['prog_low'] != "-1") {
            if ($persenvol <= $data['prog_low']) {
              $status = 'danger';
            }elseif ($persenvol <= $data['prog_med']) {
              $status = 'warning';
            }else{
              $status = 'success';
            }
          }else{
            $status = 'default';
          }
          return '<div class="pull-right">&nbsp;<span class="label label-'.$status.'">'.number_format($persenvol,2).'%</span></div>
                  <div class="progress progress-sm active">
                    <div class="progress-bar progress-bar-'.$status.' progress-bar-striped" role="progressbar" aria-valuenow="'.number_format($persenvol,2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.number_format($persenvol,2).'%">
                      <span class="sr-only">'.number_format($persenvol,2).'% Complete</span>
                    </div>
                  </div>';
        }else{
          return '<div class="pull-right">&nbsp;<span class="label label-danger">'.number_format(0,2).'%</span></div>
                  <div class="progress progress-sm active">
                    <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="'.number_format(0,2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.number_format(0,2).'%">
                      <span class="sr-only">'.number_format(0,2).'% Complete</span>
                    </div>
                  </div>';
        }
      }),
      '11' => array('formatter' => function($d,$row,$data){ 
        if (isset($data[$row[7]]['jumlah'])) {
          $sisa = ($d - $data[$row[7]]['jumlah']);
          return number_format($sisa,2,",",".");
        }else{
          return 0;
        }
      }),
      '12' => array('formatter' => function($d,$row,$data){ 
        $button = '<div class="col-md-12">';
        $button .= '<a href="'.$data['url_rewrite'].'kegiatan-rinci/'.$d.'" class="btn btn-flat btn-primary btn-sm col-md-12" ><i class="fa fa-plus"></i>&nbsp; Tambah Kegiatan</a>';
        $button .= '<a id="btn-vol" href="#mdl-vol" class="btn btn-flat btn-warning btn-sm col-md-12" data-toggle="modal"><i class="fa fa-pencil"></i>&nbsp; Edit Volume</a>';
        $button .='</div>';
        return $button; 
      }),
      );
    $query      =  "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $columns)."
                    FROM rkakl_full
                    ".$swhere."
                    GROUP BY KDPROGRAM, KDGIAT, KDOUTPUT, KDSOUTPUT, KDKMPNEN, KDSKMPNEN";
    $datatable->get_table($tableKey, $primaryKey, $columns, $query, $formatter, $dataArray);

    break;
  case 'table-kegiatan':
    $dataArray['url_rewrite'] = $url_rewrite;
    $dataArray['idrkakl'] = $_POST['idrkakl'];
    $tableKey   = "rabview";
    $primaryKey = "id";
    $columns    = array('id',
                        'deskripsi',
                        'tanggal',
                        'lokasi',
                        'jumlah',
                        'status',
                        'status',
                        'tanggal_akhir',
                        'tempat',
                        );
    $formatter  = array(
        '2' => array('formatter' => function($d,$row,$data){ 
          return date("d M Y", strtotime($d)) . ' - ' . date("d M Y", strtotime($row[7]));
        }),
        '3' => array('formatter' => function($d,$row,$data){ 
          return $row[8] . ', ' . $d;
        }),
        '4' => array('formatter' => function($d,$row,$data){ 
          return number_format($d,2,",",".");
        }),
        '5' => array('formatter' => function($d,$row,$data){ 
          if ($d == "1") {
            return "<i>Aktif</i>";
          }elseif ($d == "0") {
            return "<i>Close</i>";
          }elseif ($d == "4") {
            return "<i>Revisi</i>";
          }
        }),
        '6' => array('formatter' => function($d,$row,$data){ 
          $button = '<div class="col-md-12">';
          if($_SESSION['level'] != 0 && ($d == 1 || $d == 4)){
            $button .= '<a id="btn-trans" href="'.$data['url_rewrite'].'content/kegiatan-edit/'.$row[0].'/'.$data['idrkakl'].'" class="btn btn-flat btn-warning btn-sm col-md-6" ><i class="fa fa-pencil"></i>&nbsp; Edit</a>';
            $button .= '<a id="btn-del" href="#delete" class="btn btn-flat btn-danger btn-sm col-md-6" data-toggle="modal"><i class="fa fa-close"></i>&nbsp; Delete</a>';
          }
          elseif($_SESSION['level'] != 0 && $d == 0){
            $button .= '<a style="margin:1px 2px;" class="btn btn-flat btn-sm btn-default col-md-12"><i class="fa fa-warning"></i> No available</a>';
          }
          elseif($_SESSION['level'] == 0 && $d == 0){
            $button .= '<a id="btn-unlock" href="#unlock" class="btn btn-flat btn-danger btn-sm col-md-6" data-toggle="modal"><i class="fa fa-check"></i>&nbsp; UNLOCK</a>';
          }
          elseif($_SESSION['level'] == 0 && $d == 4){
            $button .= '<a id="btn-lock" href="#lock" class="btn btn-flat btn-danger btn-sm col-md-6" data-toggle="modal"><i class="fa fa-close"></i>&nbsp; LOCK</a>';
          }
          $button .= '</div>';
          return $button;
        }),
      );
    $tahun = $_POST['tahun'];
    $direktorat = $_POST['direktorat'];
    $kdoutput = $_POST['kdoutput'];
    $kdsoutput = $_POST['kdsoutput'];
    $kdkmpnen = $_POST['kdkmpnen'];
    $kdskmpnen = $_POST['kdskmpnen'];
    $where="";
    if ($tahun != "") {
      $where = 'thang = "'.$tahun.'" ';
    }
    if ($direktorat != "") {
      if ($where == "") {
        $where .= 'kdgiat = "'.$direktorat.'" ';
      }else{
        $where .= 'AND kdgiat = "'.$direktorat.'" ';
      }
    }
    if ($kdoutput != "") {
      if ($where == "") {
        $where .= 'kdoutput = "'.$kdoutput.'" ';
      }else{
        $where .= 'AND kdoutput = "'.$kdoutput.'" ';
      }
    }
    if ($kdsoutput != "") {
      if ($where == "") {
        $where .= 'kdsoutput = "'.$kdsoutput.'" ';
      }else{
        $where .= 'AND kdsoutput = "'.$kdsoutput.'" ';
      }
    }
    if ($kdkmpnen != "") {
      if ($where == "") {
        $where .= 'kdkmpnen = "'.$kdkmpnen.'" ';
      }else{
        $where .= 'AND kdkmpnen = "'.$kdkmpnen.'" ';
      }
    }
    if ($kdskmpnen != "") {
      if ($where == "") {
        $where .= 'kdskmpnen = "'.$kdskmpnen.'" ';
      }else{
        $where .= 'AND kdskmpnen = "'.$kdskmpnen.'" ';
      }
    }
    $query      =  "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $columns)."
                    FROM rabview
                    WHERE ".$where."";
    $datatable->get_table($tableKey, $primaryKey, $columns, $query, $formatter, $dataArray);

    break;
  case 'getnpwp':
    $jenis = $data[3];
    $npwp = $mdl_rab->getnpwp($jenis);
    echo json_encode($npwp);
    break;
  case 'getyear':
    $query  = "SELECT THANG FROM rkakl_full where (THANG != '' OR THANG != '-') and STATUS = 1 group by THANG";
    $result=$db->_fetch_array($query,1);
    echo json_encode($result);
    break;
  case 'getkode':
    $getrkakl = "SELECT THANG,KDPROGRAM,NMPROGRAM,KDGIAT,NMGIAT,KDOUTPUT,NMOUTPUT,KDSOUTPUT,NMSOUTPUT,KDKMPNEN,NMKMPNEN,KDSKMPNEN,NMSKMPNEN
                        FROM rkakl_full 
                            WHERE idrkakl = '".$_POST['idrkakl']."'
                            AND THANG = '".$_POST['tahun']."'
                            AND KDGIAT = '".$_POST['kdgiat']."'
                            LIMIT 1
                ";  
    $result=$db->_fetch_array($getrkakl,1);
    echo json_encode($result);
    break;
  case 'gettriwulan':
    $query = "SELECT id, nama, status FROM triwulan WHERE status = 1";
    $result=$db->_fetch_array($query,1);
    echo json_encode($result);
    break;
  case 'getsout':
    $soutput = $mdl_rab->getsout($_POST['prog'],$_POST['output'],$_POST['tahun'],$_POST['direktorat']);
    echo json_encode($soutput);
    break;
  case 'getkomp':
    $komp = $mdl_rab->getkomp($_POST['prog'],$_POST['output'],$_POST['soutput'],$_POST['tahun'],$_POST['direktorat']);
    echo json_encode($komp);
    break;
  case 'getskomp':
    $skomp = $mdl_rab->getskomp($_POST['prog'],$_POST['output'],$_POST['soutput'],$_POST['komp'],$_POST['tahun'],$_POST['direktorat']);
    echo json_encode($skomp);
    break;
  case 'save':
    $idrkakl = $_POST['idrkakl'];
    $cek = $rabview->cekpagu($idrkakl,$_POST['jumlah'],$_POST['idtriwulan']);
      
    if ($cek == 'error') {
      $flash  = array(
            'category' => "warning",
            'messages' => "Data Kegiatan gagal dilanjutkan karena realisasi melebihi PAGU Anggaran."
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }elseif ($cek == 'berhasil') {
      $rabview->insertRabview($_POST);
      $flash  = array(
            'category' => "success",
            'messages' => "Data Kegiatan berhasil ditambahkan !"
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }else{
      $flash  = array(
            'category' => "warning",
            'messages' => "Data Kegiatan gagal dilanjutkan. Silahkan dicoba kembali."
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }
    break;
  case 'edit':
    $idrkakl = $_POST['idrkakl'];
    $cek = $rabview->cekpagu($idrkakl,$_POST['jumlah'],$_POST['idtriwulan'], $_POST['id']);
    if ($cek == 'error') {
      $flash  = array(
            'category' => "warning",
            'messages' => "Data Kegiatan gagal dilanjutkan karena realisasi melebihi PAGU Anggaran."
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }elseif ($cek == 'berhasil') {
      $rabview->updateRabview($_POST);
      $flash  = array(
            'category' => "success",
            'messages' => "Data Kegiatan berhasil diubah !"
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }else{
      $flash  = array(
            'category' => "warning",
            'messages' => "Data Kegiatan gagal dilanjutkan. Silahkan dicoba kembali."
          );
      $utility->location("content/kegiatan-rinci/".$idrkakl,$flash);
    }
    break;
  case 'ajukan':
    $id_rabview = $_POST['id_rab_aju'];
    $akun = $mdl_rab->getakun($id_rabview);
    $error = false;
    for ($i=0; $i < count($akun); $i++) { 
      if ($akun[$i]->kdakun == "") {  //kode akun kosong
        $error = '2';
        $kderror[$i] = $akun[$i]->kdakun;
      }
    }
    if (!$error) {
      $status = '1';
      $mdl_rab->chstatus($id_rabview, $status);
      $utility->load("content/rab","success","Data RAB telah diajukan ke Bendahara Pengeluaran");
    }else{
      $kodeError = implode(", ", $kderror);
      if ($error == 1) {
        $utility->load("content/rab","warning","Proses tidak dilanjutkan. Kode Akun ".$kodeError." melebihi Pagu");
      }else{
        $utility->load("content/rab","error","Proses tidak dilanjutkan. Terdapat data yang kosong");
      }
    }
    break;
  case 'sahkan':
    $id_rabview = $_POST['id_rab_sah'];
    $akun = $mdl_rab->getakun($id_rabview);
    for ($i=0; $i < count($akun); $i++) { 
      if ($akun[$i]->kdakun == 521211) {  //belanja bahan
        $rab = $mdl_rab->getRabItem($akun[$i]);
        for ($j=0; $j < count($rab); $j++) { 
          $jum_rkakl = $mdl_rab->getJumRkakl($akun[$i], $rab[$j]);
          $realisasi = $jum_rkakl->realisasi;
          $usulan = $jum_rkakl->usulan;
          $total = $realisasi + $usulan;
          $item = $rab[$j]->noitem;
          $mdl_rab->moveRealisasi($akun[$i], $item, $total);
        }
      }elseif($akun[$i]->kdakun != ""){  // bukan belanja bahan
        $jum_rkakl = $mdl_rab->getJumRkakl($akun[$i]);
        $item = $jum_rkakl->noitem;
        $pecah_item = explode(",", $item);
        $banyakitem = count($pecah_item);

        for ($x=0; $x < $banyakitem; $x++) { 
          $nilai = $mdl_rab->getRealUsul($akun[$i], $pecah_item[$x]);
          $total = $nilai->realisasi + $nilai->usulan;
          $mdl_rab->moveRealisasi($akun[$i], $pecah_item[$x], $total);
        }
      }
    }
    $status = '2';
    $mdl_rab->chstatus($id_rabview, $status);
    $utility->load("content/rab","success","Data RAB telah disahkan");
    break;
  case 'revisi':
    $id_rabview = $_POST['id_rab_rev'];
    $status = '3';
    $pesan = $_POST['pesan'];
    $mdl_rab->chstatus($id_rabview, $status);
    $mdl_rab->pesanrevisi($id_rabview, $pesan);
    $utility->load("content/rab","success","Data RAB direvisi");
    break;
  case 'delete':
    $idrkakl = $_POST['idrkakl'];
    $jumlah = 0;
    $id = $_POST['id'];
    $query = "SELECT idtriwulan FROM rabview WHERE id = '$id'";
    $result = $db->_fetch_array($query,1);
    $idtriwulan = $result[0]['idtriwulan'];
    $cek = $rabview->cekpagu($idrkakl,$jumlah,$idtriwulan,$id);
    $rabview->deleteRabview($_POST);
    $flash  = array(
          'category' => "success",
          'messages' => "Data Kegiatan telah dihapus"
        );
    $utility->location("content/kegiatan-rinci/".$_POST['idrkakl'],$flash);
    break;
  case 'lock':
    print_r($_POST);die;
    $query = "SELECT idtriwulan FROM rabview WHERE id = '$id'";
    $result = $db->_fetch_array($query,1);
    break;
  default:
    $utility->location_goto(".");
  break;

  function cekpagu(){
    return 'tes';
  }
}
?>
