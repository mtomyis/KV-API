<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Laporan Penjualan Rojo Cafe</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!-- <link href="font-awesome/css/font-awesome.css" rel="stylesheet" /> -->

    <!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />

    <link rel="stylesheet" href="css/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-tags-input/jquery.tagsinput.css" />

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

</head>

<body>

<section id="container" >
    <!--main content start-->
        <!-- page start-->
        <div class="container-fluid">
        
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label col-md-12">Periode Transaksi</label>
                        <div class="col-md-4">
                        <!-- <form method="POST" action="#" class="needs-validation" novalidate=""> -->
                            <div class="input-group input-large" data-date="2021/12/22" data-date-format="yyyy/mm/dd">
                                <input id="tgl_awal" readonly type="text" class="form-control dpd1" name="from">
                                <span class="input-group-addon">Sampai</span>
                                <input id="tgl_akhir" readonly type="text" class="form-control dpd2" name="to">
                            </div>
                            <!-- <span class="help-block">Select date range</span> -->
                            <br>
                            <input type="button" class="btn btn-primary btn-block" onclick="tampilkanPeriode()" value="Tampilkan"></input>
                        <!-- </form> -->
                        </div>
                    </div>
                    </div>
                </section>
            </div>
        </div>

        <div id="div_content">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Laporan Penjualan Transaksi Periode <?= $data_periode; ?>
                        </header>
                        <div class="panel-body">
                        <div class="adv-table">
                        <table  class="display table table-bordered table-striped" id="table1">
                        <thead>
                        <tr>
                            <th class="hidden-phone sorting_asc">No.</th>
                            <th class="hidden-phone">No. Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Total belum diskon</th>
                            <th>Diskon</th>
                            <th>PPN</th>
                            <th>Service</th>
                            <th>Total sudah diskon</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                            $total_tagihan=0;
                            $total_tagihan_sudah=0;
                            $t_total_tagihan_sudah=0;
                            $jenis_diskon=0;
                            $diskon=0; $t_diskon=0;
                            $ppn=0; $t_ppn=0;
                            $service=0; $t_service=0;
                            $voidgak="";

                            foreach ($data_penjualan as $value) { 
                                // 1 persen
                                // 2 Rupiah
                                if ($value['jenis_diskon']=="1") {
                                    $jenis_diskon="".$value['diskon']." %";
                                    $diskon = ($value['diskon']/100*$value['total_tagihan']);
                                    $ppn = (($value['total_tagihan']-$diskon)*10/100);
                                    $service = (($value['total_tagihan']-$diskon)*$value['service']/100);
                                }elseif ($value['jenis_diskon']=="2") {
                                    $jenis_diskon="Rp. ".$value['diskon']."";
                                    $diskon = ($value['total_tagihan']-$value['diskon']);
                                    $ppn = (($value['total_tagihan']-$diskon)*10/100);
                                    $service = (($value['total_tagihan']-$diskon)*$value['service']/100);
                                }else{
                                    $diskon=0;
                                    $jenis_diskon="-";
                                    $ppn = (($value['total_tagihan'])*10/100);
                                    $service = (($value['total_tagihan'])*$value['service']/100);
                                }

                                if ($value['status']=="1") {
                                    $voidgak = "".$value['id'];
                                    $tagihan = $value['total_tagihan'];
                                    $total_tagihan+=$tagihan;

                                    $total_tagihan_sudah=(($value['total_tagihan'])-$diskon) + (($value['total_tagihan']-$diskon)*10/100)+(($value['total_tagihan']-$diskon)*$value['service']/100);
                                    $t_total_tagihan_sudah+=$total_tagihan_sudah;

                                    $t_diskon+=$diskon;
                                    $t_ppn+=$ppn;
                                    $t_service+=$service;
                                }else{
                                    $voidgak = "".$value['id']." (Void)";
                                }

                        ?>
                        <tr class="gradeU">
                            <td class=""><?= $no ?></td>
                            <td class=""><?= $voidgak; ?></td>
                            <td><?= $value['pelanggan'] ?></td>
                            <td class="">Rp. <?= number_format($value['total_tagihan'], 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($diskon, 0,',','.'); ?><?= " (".$jenis_diskon.")"; ?></td>
                            <td class="">Rp. <?= number_format($ppn, 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($service, 0,',','.'); ?><?= " (".$value['service']." %)"; ?></td>
                            <td class="">Rp. <?= number_format($total_tagihan_sudah, 0,',','.'); ?></td>
                        </tr>
                        <?php $no++; } ?>

                        </tbody>
                        <tfoot>
                        <tr class="gradeU">
                            <td colspan="3">Total Terjual</td>
                            <td class="">Rp. <?= number_format($total_tagihan, 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($t_diskon, 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($t_ppn, 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($t_service, 0,',','.'); ?></td>
                            <td class="">Rp. <?= number_format($t_total_tagihan_sudah, 0,',','.'); ?></td>
                        </tr>
                        </tfoot>
                        </table>
                        </div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Laporan Metode Transaksi <?= $data_periode; ?>
                        </header>
                        <div class="panel-body">
                        <div class="adv-table">
                        <table class="display table table-bordered table-striped" >
                        <thead>
                        <tr>
                            <th class="hidden-phone">No</th>
                            <th class="hidden-phone">Metode Pembayaran</th>
                            <th>Jumlah</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                            $total=0;
                            foreach ($data_metode as $value) { 
                                // dd($data_penjualan_menu);
                            $total+=$value['total'];
                        ?>
                        <tr class="gradeU">
                            <td class=""><?= $no ?></td>
                            <td><?= $value['nama'] ?></td>
                            <td>Rp. <?= number_format($value['total'], 0,',','.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                        </tbody>
                        <tfoot>
                        <tr class="gradeU">
                            <td colspan="2">Total</td>
                            <td class="">Rp. <?= number_format($total, 0,',','.'); ?></td>
                        </tr>
                        </tfoot>
                        </table>
                        </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Laporan Penjualan Menu Periode <?= $data_periode; ?>
                        </header>
                        <div class="panel-body">
                        <div class="adv-table">
                        <table class="display table table-bordered table-striped" id="table2">
                        <thead>
                        <tr>
                            <th class="hidden-phone">No</th>
                            <th>Nama Menu</th>
                            <th class="hidden-phone">Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                            $total_penjualan=0;
                            foreach ($data_penjualan_menu as $value) { 
                                // dd($data_penjualan_menu);
                            $total_penjualan+=$value['jumlah'];
                        ?>
                        <tr class="gradeU">
                            <td class=""><?= $no ?></td>
                            <td><?= $value['nama_menu'] ?></td>
                            <td class=""><?= $value['nama_kategori'] ?></td>
                            <td><?= $value['jumlah'] ?></td>
                        </tr>
                        <?php $no++; } ?>
                        </tbody>
                        <tfoot>
                        <tr class="gradeU">
                            <td colspan="3">Total Terjual Menu</td>
                            <td class=""><?= $total_penjualan ?></td>
                        </tr>
                        </tfoot>
                        </table>
                        </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        </div>
        <!-- page end-->
    <!--main content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<script src="js/toggle-init.js"></script>
<script src="js/advanced-form.js"></script>
<!--dynamic table initialization -->
<script>
    $(document).ready(function() {

    $('#table1').dataTable( {
    } );
    $('#table2').dataTable( {
    } );
} );

</script>
<script src="js/dynamic_table_init.js"></script>

<script>                    
function tampilkanPeriode() {
    let tgl_awal = $("#tgl_awal").val();
    let tgl_akhir = $("#tgl_akhir").val();

    $.ajax({
        url: "<?= base_url('Home/periode') ?>",
        type: 'post',
        data: {
            "_token": "<?= csrf_token() ?>",
            "tgl_awal": tgl_awal,
            "tgl_akhir": tgl_akhir
        },             
        success: function(data) {               
        $('#div_content').html(data);
        document.getElementById('div_content').scrollIntoView();
        // console.log(data);
        }
    });
}
</script>

</body>
</html>
