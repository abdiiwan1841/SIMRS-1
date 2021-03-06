<?php

namespace Modules\KonsumsiObat\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\HariPerawatan\Entities\HariPerawatan;
use Modules\KonsumsiObat\Entities\KonsumsiObat;
use Modules\KonsumsiObat\Entities\KonsumsiObatMalam;
use Modules\KonsumsiObat\Entities\KonsumsiObatPagi;
use Modules\KonsumsiObat\Entities\KonsumsiObatSiang;
use Modules\KonsumsiObat\Entities\KonsumsiObatSore;
use Modules\Obat\Entities\Obat;
use Modules\RawatInap\Entities\RawatInap;
use Jenssegers\Agent\Agent;

class KonsumsiObatController extends Controller
{
    use ValidatesRequests;

    public $agent;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('checkRole:3')->except(['showAllKonsumsiObat', 'ubahKeteranganObat']);

        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function showAllKonsumsiObat($id_ranap)
    {
        $ranap = RawatInap::where('id', '=', $id_ranap)->first();

        $hari_perawatan = HariPerawatan::with('konsumsi_obat')->with('konsumsi_obat_luar')->where('id_ranap', '=', $id_ranap)->orderBy('tanggal', 'desc')->get();

        $daftar_obat = Obat::all();

        if($hari_perawatan->isEmpty())
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
            return view('konsumsiobat::mobile.index')
                ->with('ranap', $ranap)
                ->with('haris', $hari_perawatan)
                ->with('daftars', $daftar_obat)
                ->with('hari_perawatan_ke', $hari_perawatan_ke);
        }

        return view('konsumsiobat::index')
            ->with('ranap', $ranap)
            ->with('haris', $hari_perawatan)
            ->with('daftars', $daftar_obat)
            ->with('hari_perawatan_ke', $hari_perawatan_ke);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createNewKonsumsiObat($id_ranap)
    {
        $ranap = RawatInap::where('id', '=', $id_ranap)->first();

        $obat = Obat::all();

        return view('konsumsiobat::create')
            ->with('ranap', $ranap)
            ->with('obats', $obat);
    }

    public function storeRincianKonsumsiObat(Request $request)
    {
        $waktu = $request->waktu;

        if($waktu == 'pagi')
        {
            $obat = new KonsumsiObatPagi();
            $obat->id_konsumsi_obat = $request->get('id_obat');
            $obat->sudah = true;
            $obat->id_petugas = Auth::id();
            $obat->save();
        }
        elseif ($waktu == 'siang')
        {
            $obat = new KonsumsiObatSiang();
            $obat->id_konsumsi_obat = $request->get('id_obat');
            $obat->sudah = true;
            $obat->id_petugas = Auth::id();
            $obat->save();
        }
        elseif ($waktu == 'sore')
        {
            $obat = new KonsumsiObatSore();
            $obat->id_konsumsi_obat = $request->get('id_obat');
            $obat->sudah = true;
            $obat->id_petugas = Auth::id();
            $obat->save();
        }
        elseif ($waktu == 'malam')
        {
            $obat = new KonsumsiObatMalam();
            $obat->id_konsumsi_obat = $request->get('id_obat');
            $obat->sudah = true;
            $obat->id_petugas = Auth::id();
            $obat->save();
        }

        Session::flash('message', 'Rincian konsumsi obat berhasil disimpan.');

        return redirect()->route('konsumsi_obat.index', $request->get('id_ranap'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeKonsumsiObat(Request $request)
    {
        $this->validate($request, [
            'id_ranap' => 'required',
            'id_hari_perawatan' => 'required',
            'nama_obat' => 'required',
            'dosis' => 'required',
        ]);

        $konsumsi_obat = new KonsumsiObat();
        $konsumsi_obat->id_ranap = $request->get('id_ranap');
        $konsumsi_obat->id_hari_perawatan = $request->get('id_hari_perawatan');
        $konsumsi_obat->nama_obat = $request->get('nama_obat');
        $konsumsi_obat->dosis = $request->get('dosis');
        $konsumsi_obat->keterangan = $request->get('keterangan');
        $konsumsi_obat->id_petugas = Auth::id();
        $konsumsi_obat->save();

        Session::flash('message', 'Konsumsi obat berhasil disimpan.');

        return redirect()->route('konsumsi_obat.index', $request->get('id_ranap'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function ubahKeteranganObat(Request $request)
    {
        $this->validate($request, [
            'id_konsumsi_obat' => 'required',
            'keterangan' => 'required'
        ]);

        $konsumsi_obat = KonsumsiObat::findorFail($request->get('id_konsumsi_obat'));
        $konsumsi_obat->keterangan = $request->get('keterangan');
        $konsumsi_obat->save();

        $keterangan_obat_dikonsumsi = KonsumsiObat::where('id_ranap', '=', $request->get('id_ranap'))->where('nama_obat', '=', $request->get('nama_obat'))->get();

        Session::flash('message', 'Keterangan berhasil disimpan.');

        return redirect()->route('konsumsi_obat.index', $request->get('id_ranap'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
