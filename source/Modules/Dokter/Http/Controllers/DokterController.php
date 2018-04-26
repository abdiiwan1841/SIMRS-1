<?php

namespace Modules\Dokter\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\User\Entities\User;

class DokterController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('checkRole:1')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $dokter = User::where('jabatan_id', '=', '4')->get();

        return view('dokter::index')
            ->with('dokters', $dokter);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('dokter::dokter.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_user' => 'required|unique:users',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|numeric',
        ]);

        $user = new User();
        $user->id_user = $request->id_user;
        $user->nama = $request->nama;
        $user->alamat = $request->alamat;
        $user->telepon = $request->telepon;
        $user->password = bcrypt($request->telepon);
        $user->jabatan_id = '4';
        $user->save();

        Session::flash('message', 'Data dokter berhasil disimpan');

        return redirect()->route('dokter.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $dokter = User::findorFail($id);

        return view('dokter::dokter.show')->with('dokter', $dokter);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $dokter = User::findorFail($id);

        return view('dokter::dokter.edit')
            ->with('dokter', $dokter);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_user' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|numeric',
        ]);

        $input = $request->all();

        $dokter = User::findorFail($id);

        $dokter->fill($input)->save();

        Session::flash('message', 'Perubahan berhasil disimpan');

        return redirect()->route('dokter.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function cari(Request $request)
    {
        $query = $request->get('query');

        $results = DB::table('dokter')->select('*')->where('id_dokter', 'like', '%'.$query.'%')->
        orWhere('nama', 'like', '%'.$query.'%')->
        orWhere('alamat', 'like', '%'.$query.'%')->
        orWhere('telepon', 'like', '%'.$query.'%')->get();

        return view('dokter::dokter.hasil_cari')->with('dokters', $results)->with('query', $query);
    }
}
