<?php

namespace Modules\AlatKesehatan\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\AlatKesehatan\Entities\AlatKesehatan;

class AlatKesehatanController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('checkRole:1');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function showAllAlatKesehatan()
    {
        $alkes = AlatKesehatan::all();

        return view('alatkesehatan::index')->with('alkess', $alkes);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createNewAlatKesehatan()
    {
        return view('alatkesehatan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function saveNewAlatKesehatan(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'harga' => 'required'
        ]);

        $input = $request->all();

        AlatKesehatan::create($input);

        Session::flash('message', 'Alat kesehatan berhasil disimpan');

        return redirect()->route('alat_kesehatan.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function showDetailAlatKesehatan($id)
    {
        $alkes = AlatKesehatan::findOrFail($id);

        return view('alatkesehatan::show')->with('alkes', $alkes);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function editAlatKesehatan($id)
    {
        $alkes = AlatKesehatan::findOrFail($id);

        return view('alatkesehatan::edit')->with('alkes', $alkes);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateAlatKesehatan(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'harga' => 'required'
        ]);

        $input = $request->all();

        $alkes = AlatKesehatan::findOrFail($id);

        $alkes->fill($input)->save();

        Session::flash('message', 'Perubahan berhasil dilakukan');

        return redirect()->route('alat_kesehatan.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}