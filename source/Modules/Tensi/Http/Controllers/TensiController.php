<?php

namespace Modules\Tensi\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\HariPerawatan\Entities\HariPerawatan;
use Modules\RawatInap\Entities\RawatInap;
use Modules\Tensi\Entities\TensiMalam;
use Modules\Tensi\Entities\TensiPagi;
use Modules\Tensi\Entities\TensiSiang;
use Modules\Tensi\Entities\TensiSore;
use Jenssegers\Agent\Agent;

class TensiController extends Controller
{
    use ValidatesRequests;

    public $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function showAllTensi($id_ranap)
    {
        $ranap = RawatInap::where('id', '=', $id_ranap)->first();

        $hari_perawatans = HariPerawatan::with('konsumsi_obat')->where('id_ranap', '=', $id_ranap)->orderBy('tanggal', 'desc')->get();

        foreach($hari_perawatans as $hari_perawatan)
        {
            $table = \Lava::DataTable();
            $table->addStringColumn('Waktu')
                ->addNumberColumn('Tensi Atas')
                ->addNumberColumn('Tensi Bawah')
                ->addNumberColumn('Suhu');

            if(!empty($hari_perawatan->tensi_pagi->tensi_atas) && !empty($hari_perawatan->tensi_pagi->tensi_bawah))
                $table->addRow(['Pagi', $hari_perawatan->tensi_pagi->tensi_atas, $hari_perawatan->tensi_pagi->tensi_bawah, $hari_perawatan->tensi_pagi->temperatur]);

            if(!empty($hari_perawatan->tensi_siang->tensi_atas) && !empty($hari_perawatan->tensi_siang->tensi_bawah))
                $table->addRow(['Siang', $hari_perawatan->tensi_siang->tensi_atas, $hari_perawatan->tensi_siang->tensi_bawah, $hari_perawatan->tensi_siang->temperatur]);

            if(!empty($hari_perawatan->tensi_sore->tensi_atas) && !empty($hari_perawatan->tensi_sore->tensi_bawah))
                $table->addRow(['Sore', $hari_perawatan->tensi_sore->tensi_atas, $hari_perawatan->tensi_sore->tensi_bawah, $hari_perawatan->tensi_sore->temperatur]);

            if(!empty($hari_perawatan->tensi_malam->tensi_atas) && !empty($hari_perawatan->tensi_pagi->tensi_bawah))
                $table->addRow(['Malam', $hari_perawatan->tensi_malam->tensi_atas, $hari_perawatan->tensi_malam->tensi_bawah, $hari_perawatan->tensi_malam->temperatur]);

            \Lava::LineChart($hari_perawatan->id.'_tensi', $table, [
                'hAxis' => ['Pagi', 'Siang', 'Sore', 'Malam']
            ]);
        }

        if($hari_perawatans->isEmpty())
        {
            $hari_perawatan_ke = 1;
        }
        else
        {
            $hari_perawatan_terakhir = HariPerawatan::where('id_ranap', '=', $id_ranap)->select('hari_perawatan')->orderBy('created_at', 'desc')->first()->toArray();
            foreach ($hari_perawatan_terakhir as $hari)
                $hari_perawatan_terakhir = $hari;

            $hari_perawatan_ke = $hari_perawatan_terakhir + 1;
        }

        if($this->agent->isMobile() || $this->agent->isTablet())
        {
            return view('tensi::mobile.index')
                ->with('ranap', $ranap)
                ->with('haris', $hari_perawatans)
                ->with('hari_perawatan_ke', $hari_perawatan_ke);
        }

        return view('tensi::index')
            ->with('ranap', $ranap)
            ->with('haris', $hari_perawatans)
            ->with('hari_perawatan_ke', $hari_perawatan_ke);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('tensi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeTensiPasien(Request $request)
    {
        $this->validate($request, [
            'id_hari_perawatan' => 'required',
            'tensi_atas' => 'required',
            'tensi_bawah' => 'required',
            'temperatur' => 'required',
            'waktu' => 'required'
        ]);

        $waktu = $request->get('waktu');

        if($waktu == 'pagi')
        {
            $pagi = new TensiPagi();
            $pagi->id_hari_perawatan = $request->get('id_hari_perawatan');
            $pagi->tensi_atas = $request->get('tensi_atas');
            $pagi->tensi_bawah = $request->get('tensi_bawah');
            $pagi->temperatur = $request->get('temperatur');
            $pagi->id_petugas = Auth::id();
            $pagi->save();
        }
        if($waktu == 'siang')
        {
            $siang = new TensiSiang();
            $siang->id_hari_perawatan = $request->get('id_hari_perawatan');
            $siang->tensi_atas = $request->get('tensi_atas');
            $siang->tensi_bawah = $request->get('tensi_bawah');
            $siang->temperatur = $request->get('temperatur');
            $siang->id_petugas = Auth::id();
            $siang->save();
        }
        if($waktu == 'sore')
        {
            $sore = new TensiSore();
            $sore->id_hari_perawatan = $request->get('id_hari_perawatan');
            $sore->tensi_atas = $request->get('tensi_atas');
            $sore->tensi_bawah = $request->get('tensi_bawah');
            $sore->temperatur = $request->get('temperatur');
            $sore->id_petugas = Auth::id();
            $sore->save();
        }
        if($waktu == 'malam')
        {
            $malam = new TensiMalam();
            $malam->id_hari_perawatan = $request->get('id_hari_perawatan');
            $malam->tensi_atas = $request->get('tensi_atas');
            $malam->tensi_bawah = $request->get('tensi_bawah');
            $malam->temperatur = $request->get('temperatur');
            $malam->id_petugas = Auth::id();
            $malam->save();
        }

        Session::flash('message', 'Catatan tensi dan temperatur pasien berhasil disimpan.');

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('tensi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('tensi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
