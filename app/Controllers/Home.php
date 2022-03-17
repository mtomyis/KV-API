<?php

namespace App\Controllers;
use App\Models\M_penjualan;
use App\Models\M_order_menu;
use App\Models\M_pembayaran;

class Home extends BaseController
{
	public function index()
	{
        // $this->output->delete_cache();
        $db = db_connect();
		// get tgl akhir
		$builder = $db->table('penjualan');
        $builder->select('created_at');
        $builder->where('status', '1');
		$builder->orderBy('id', 'desc');
		$builder->limit(1);
        $query_tgl = $builder->get()->getRow();

    	$now = date("Y-m-d", strtotime($query_tgl->created_at));
		$view_periode = date("d F Y", strtotime($now));

		// $hasil[]=array();
        // $data = $db->query('SELECT penjualan.*, meja.nama as nama_meja FROM penjualan 
		// join meja on meja.id = penjualan.id_meja 
		// WHERE status=1 AND penjualan.created_at <= now() AND penjualan.created_at >= "'.$now.'" order by penjualan.id ASC');
        // $query = $data->getResult();
        // echo $now;
        // echo date('d F Y');

        $builder = $db->table('penjualan');
        $builder->select('meja.nama as nama_meja, penjualan.*');
        $builder->join('meja', 'meja.id = penjualan.id_meja');
        $builder->havingIn('status', [1,2]);
        // $builder->having('status', 2);
		$builder->like('penjualan.created_at', $now);
        $builder->orderBy('penjualan.id', 'ASC');
        $query = $builder->get()->getResult();
        // dd($query);

        foreach ($query as $row)
        {
			// dd($row->id);
            $builder2 = $db->table('menu');
            $builder2->select('SUM(menu.harga_jual * order_menu.jumlah) as total_tagihan');
            $builder2->join('order_menu', 'order_menu.id_menu = menu.id');
            $builder2->where('order_menu.id_penjualan', $row->id);
            $query2 = $builder2->get()->getRow();

            $hasil[] = array(
                "id"=>$row->id, 
                "id_meja"=>$row->id_meja, 
                "nama_meja"=>$row->nama_meja,
                "id_admin"=>$row->id_user, 
                "pelanggan"=>$row->pelanggan, 
                "jenis_transaksi"=>$row->jenis_transaksi, 
                "status"=>$row->status,
                "diskon"=>$row->diskon, 
                "service"=>$row->service, 
                "jenis_diskon"=>$row->jenis_diskon, 
                "created_at"=>$row->created_at, 
                "total_tagihan"=>$query2->total_tagihan
            );
        }

		$builder3 = $db->table('order_menu');
        $builder3->select('id_menu');
		$builder3->groupBy('order_menu.id_menu');
		$builder3->like('order_menu.created_at', $now);
        $builder3->orderBy('order_menu.id', 'ASC');
        $query3 = $builder3->get()->getResult();
		foreach ($query3 as $row2)
        {
            $builder4 = $db->table('order_menu');
            $builder4->select('SUM(order_menu.jumlah) as jumlah, menu.nama as nama_menu, kategori.nama as nama_kategori');
			$builder4->join('menu', 'menu.id = order_menu.id_menu');
			$builder4->join('kategori', 'menu.id_kategori = kategori.id');
			$builder4->groupBy('order_menu.id_menu');
            $builder4->where('order_menu.id_menu', $row2->id_menu);
			$builder4->like('order_menu.created_at', $now);
			$builder4->orderBy('order_menu.id', 'ASC');
            $query4 = $builder4->get()->getRow();

            $hasil2[] = array(
                "jumlah"=>$query4->jumlah, 
                "nama_menu"=>$query4->nama_menu, 
                "nama_kategori"=>$query4->nama_kategori,
            );
        }

        // mtd transaksi
        $builder_mtd = $db->table('metode_pembayaran');
        $builder_mtd->select('kategori, nama');
        $builder_mtd->orderBy('id', 'ASC');
        $query_mtd = $builder_mtd->get()->getResult();
        // dd($query_mtd);

        foreach ($query_mtd as $row)
        {
			// dd($row->id);
            $builder2_mtd = $db->table('pembayaran');
            $builder2_mtd->select('SUM(total) as total');
            $builder2_mtd->join('penjualan', 'penjualan.id = pembayaran.id_penjualan');
            $builder2_mtd->where('metode_pembayaran', $row->kategori);
            $builder2_mtd->where('penjualan.status', 1);
			$builder2_mtd->like('penjualan.created_at', $now);
            $query2_mtd = $builder2_mtd->get()->getRow();

            $hasil_mtd[] = array(
                "nama"=>$row->nama, 
                "total"=>$query2_mtd->total
            );
        }
		// foreach ($hasil as $value) {
		// 	dd($value['id']);
		// }
		// dd($hasil2);

		$data['data_penjualan'] = $hasil;
		$data['data_metode'] = $hasil_mtd;
		$data['data_penjualan_menu'] = $hasil2;
		$data['data_periode'] = $view_periode;

		return view('welcome_message', $data);
	}
	public function periode()
	{
        $tgl_awal = $this->request->getVar('tgl_awal');
        $tgl_akhir = $this->request->getVar('tgl_akhir');
        // dd($input);
		$old_awal = $tgl_awal;
    	$new_awal = date("Y-m-d", strtotime($old_awal));
		$old_akhir = $tgl_akhir;
    	$new_akhir = date("Y-m-d", strtotime($old_akhir));
        
		$view_periode_awal = date("d F Y", strtotime($new_awal));
		$view_periode_akhir = date("d F Y", strtotime($new_akhir));

		// echo "tgl aw: ".$new_awal;
		// echo "tgl ak: ".$new_akhir;
		$db = db_connect();
        
        $builder = $db->table('penjualan');
        $builder->select('meja.nama as nama_meja, penjualan.*');
        $builder->join('meja', 'meja.id = penjualan.id_meja');
        $builder->where('status', '1');
		$builder->where('penjualan.created_at >=', $new_awal);
		$builder->where('penjualan.created_at <=', $new_akhir);
        $builder->orderBy('penjualan.id', 'ASC');
        $query = $builder->get()->getResult();
		// dd($query);
		// echo "periode";
        foreach ($query as $row)
        {
			// dd($row->id);
            $builder2 = $db->table('menu');
            $builder2->select('SUM(menu.harga_jual * order_menu.jumlah) as total_tagihan');
            $builder2->join('order_menu', 'order_menu.id_menu = menu.id');
            $builder2->where('order_menu.id_penjualan', $row->id);
            $query2 = $builder2->get()->getRow();

            $hasil[] = array(
                "id"=>$row->id, 
                "id_meja"=>$row->id_meja, 
                "nama_meja"=>$row->nama_meja,
                "id_admin"=>$row->id_user, 
                "pelanggan"=>$row->pelanggan, 
                "jenis_transaksi"=>$row->jenis_transaksi, 
                "status"=>$row->status,
                "diskon"=>$row->diskon, 
                "service"=>$row->service, 
                "jenis_diskon"=>$row->jenis_diskon, 
                "created_at"=>$row->created_at, 
                "total_tagihan"=>$query2->total_tagihan
            );
        }

		$builder3 = $db->table('order_menu');
        $builder3->select('id_menu');
		$builder3->groupBy('order_menu.id_menu');
        $builder->where('order_menu.created_at >=', $new_awal);
		$builder->where('order_menu.created_at <=', $new_akhir);
        $builder3->orderBy('order_menu.id', 'ASC');
        $query3 = $builder3->get()->getResult();
		foreach ($query3 as $row2)
        {
            $builder4 = $db->table('order_menu');
            $builder4->select('SUM(order_menu.jumlah) as jumlah, menu.nama as nama_menu, kategori.nama as nama_kategori');
			$builder4->join('menu', 'menu.id = order_menu.id_menu');
			$builder4->join('kategori', 'menu.id_kategori = kategori.id');
			$builder4->groupBy('order_menu.id_menu');
            $builder4->where('order_menu.id_menu', $row2->id_menu);
            $builder->where('order_menu.created_at >=', $new_awal);
		    $builder->where('order_menu.created_at <=', $new_akhir);
			$builder4->orderBy('order_menu.id', 'ASC');
            $query4 = $builder4->get()->getRow();

            $hasil2[] = array(
                "jumlah"=>$query4->jumlah, 
                "nama_menu"=>$query4->nama_menu, 
                "nama_kategori"=>$query4->nama_kategori,
            );
        }

        // dd($hasil2);
        $a="
        <div class=\"row\">
            <div class=\"col-sm-12\">
                <section class=\"panel\">
                    <header class=\"panel-heading\">
                        Laporan Penjualan Transaksi Periode ".$view_periode_awal." - ".$view_periode_akhir."
                    </header>
                    <div class=\"panel-body\">
                    <div class=\"adv-table\">
                    <table  class=\"display table table-bordered table-striped\" id=\"table1\">
                    <thead>
                    <tr>
                        <th class=\"hidden-phone sorting_asc\">No.</th>
                        <th class=\"hidden-phone\">No. Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Total belum diskon</th>
                        <th>Diskon</th>
                        <th>PPN</th>
                        <th>Service</th>
                        <th>Total sudah diskon</th>
                    </tr>
                    </thead>
                    <tbody>";

                    $no = 1;
                    $total_tagihan=0;
                    $total_tagihan_sudah=0;
                    $t_total_tagihan_sudah=0;
                    $jenis_diskon=0;
                    $diskon=0; $t_diskon=0;
                    $ppn=0; $t_ppn=0;
                    $service=0; $t_service=0;
                    
                    foreach ($hasil as $value) { 
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
                    $tagihan = $value['total_tagihan'];
                    $total_tagihan+=$tagihan;

                    $total_tagihan_sudah=(($value['total_tagihan'])-$diskon) + (($value['total_tagihan']-$diskon)*10/100)+(($value['total_tagihan']-$diskon)*$value['service']/100);
                    $t_total_tagihan_sudah+=$total_tagihan_sudah;

                    $t_diskon+=$diskon;
                    $t_ppn+=$ppn;
                    $t_service+=$service;
                    
                    $a.="
                    <tr class=\"gradeU\">
                        <td class=\"\">".$no."</td>
                        <td class=\"\">".$value['id']."</td>
                        <td>". $value['pelanggan'] ."</td>
                        <td class=\"\">Rp. ". number_format($value['total_tagihan'], 0,',','.') ."</td>
                        <td class=\"\">Rp. ". number_format($diskon, 0,',','.') ." "." (".$jenis_diskon.")" ."</td>
                        <td class=\"\">Rp. ". number_format($ppn, 0,',','.') ."</td>
                        <td class=\"\">Rp. ". number_format($service, 0,',','.') ." "." (".$value['service']." %)"."</td>
                        <td class=\"\">Rp. ". number_format($total_tagihan_sudah, 0,',','.') ."</td>
                    </tr>
                    ";
                    $no++;
                    }

                    $a.="
                    </tbody>
                        <tfoot>
                        <tr class=\"gradeU\">
                            <td colspan=\"2\">Total Terjual</td>
                            <td class=\"\">Rp. ". number_format($total_tagihan, 0,',','.') ."</td>
                            <td class=\"\">Rp. ". number_format($t_diskon, 0,',','.') ."</td>
                            <td class=\"\">Rp. ". number_format($t_ppn, 0,',','.') ."</td>
                            <td class=\"\">Rp. ". number_format($t_service, 0,',','.') ."</td>
                            <td class=\"\">Rp. ". number_format($t_total_tagihan_sudah, 0,',','.') ."</td>
                        </tr>
                        </tfoot>
                        </table>
                        </div>
                        </div>
                    </section>
                </div>
            </div>
            ";

        $a.='
        <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Laporan Penjualan Menu Periode '.$view_periode_awal.' - '.$view_periode_akhir.'
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
        ';
            $no = 1;
            $total_penjualan=0;
            foreach ($hasil2 as $value) { 
                // dd($data_penjualan_menu);
            $total_penjualan+=$value['jumlah'];

            $a.='
                        <tr class="gradeU">
                            <td class="">'. $no .'</td>
                            <td>'. $value['nama_menu'] .'</td>
                            <td class="">'. $value['nama_kategori'] .'</td>
                            <td>'. $value['jumlah'] .'</td>
                        </tr>
            ';
            $no++;
            }

            $a.='
                    </tbody>
                        <tfoot>
                        <tr class="gradeU">
                            <td colspan="3">Total Terjual Menu</td>
                            <td class="">'. $total_penjualan .'</td>
                        </tr>
                        </tfoot>
                        </table>
                        </div>
                        </div>
                    </section>
                </div>
            </div>
            ';

        $a.="
        <script>
            $(document).ready(function() {

            $('#table1').dataTable( {
            } );
            $('#table2').dataTable( {
            } );
        } );

        </script>
        ";

        
        echo $a;
	}
}
