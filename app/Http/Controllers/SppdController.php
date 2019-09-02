<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Setting;
use App\Model\Reference\Transportation;
use App\Model\Surat\{SppdList, SptList, SptEmployee, SppdEmployee, SppdMaskapai};

class SppdController extends Controller
{

    public function __construct()
    {
        $this->model = new SppdList;
        $this->transportation = Transportation::get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('special-role.sppd.index',[
            'sppd' => $this->model->where('employee_id', auth()->user()->employee->id)->get()
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $sptEmployee = SptEmployee::where('employee_id',auth()->user()->employee->id)->get();
        return view('special-role.sppd.create',[
            'sptEmployee' => $sptEmployee,
            'transportations' => $this->transportation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'spt_id' => 'required',
            'no_sppd' => 'required|unique:sppd_lists',
            'tanggal' => 'required',
            'asal' => 'required',
            'tujuan' => 'required',
            'pengikut' => 'required'
        ]);

        $sppd = $this->model->create([
            'spt_id' => $request->spt_id,
            'no_sppd' => $request->no_sppd,
            'tanggal' => $request->tanggal,
            'kegiatan_id' => auth()->user()->employee->isPptk->id,
            'transportation_id' => $request->transportation_id,
            'uang_harian' => '',
            'transport' => '',
            'penginapan' => '',
            'representatif' => '',
            'asal' => $request->asal,
            'tujuan' => $request->tujuan,
            'employee_id' => auth()->user()->employee->id,
        ]);

        foreach($request->pengikut as $pengikut)
        {
            $model = new SppdEmployee;
            $model->create([
                'sppd_id' => $sppd->id,
                'employee_id' => $pengikut,
                'no_urut' => 0
            ]);
        }

        return redirect()->route('pegawai.sppd.index')->with(['success'=>'Data berhasil disimpan']);;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SppdList $sppd)
    {
        //
        $sptEmployee = SptEmployee::where('employee_id',auth()->user()->employee->id)->get();
        $allSptEmployee = SptEmployee::where('spt_id',$sppd->spt_id)->get();
        $employees = [];
        foreach($allSptEmployee as $employee)
            $employees[] = $employee->employee_id;
        return view('special-role.sppd.edit',[
            'sptEmployee' => $sptEmployee,
            'employees' => $employees,
            'sppd' => $sppd,
            'transportations' => $this->transportation
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate($request,[
            'spt_id' => 'required',
            'no_sppd' => 'required|unique:sppd_lists,no_sppd,'.$request->id.',id',
            'tanggal' => 'required',
            'asal' => 'required',
            'tujuan' => 'required',
            'pengikut' => 'required'
        ]);

        $sppd = $this->model->find($request->id)->update([
            'spt_id' => $request->spt_id,
            'no_sppd' => $request->no_sppd,
            'tanggal' => $request->tanggal,
            'kegiatan_id' => auth()->user()->employee->isPptk->id,
            'transportation_id' => $request->transportation_id,
            'uang_harian' => '',
            'transport' => '',
            'penginapan' => '',
            'representatif' => '',
            'asal' => $request->asal,
            'tujuan' => $request->tujuan,
        ]);

        SppdEmployee::where('sppd_id',$sppd->id)->delete();
        foreach($request->pengikut as $pengikut)
        {
            $model = new SppdEmployee;
            $model->create([
                'sppd_id' => $sppd->id,
                'employee_id' => $pengikut,
                'no_urut' => 0
            ]);
        }

        return redirect()->route('pegawai.sppd.index')->with(['success'=>'Data berhasil diupdate']);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->model->find($request->id)->delete();
        return redirect()->route('pegawai.sppd.index')->with(['success'=>'Data berhasil dihapus']);;
    }

    public function getEmployees(Request $request)
    {
        $employees = SptEmployee::where('spt_id',$request->id)->get();
        foreach($employees as $employee)
            $employee->employee;

        return response()->json($employees);
    }

    public function setUrutan(Request $request)
    {
        SppdEmployee::find($request->id)->update([
            'no_urut' => $request->urutan
        ]);

        return 1;
    }

    public function cetak(SppdList $sppd)
    {
        $setting = Setting::first();
        return view('special-role.sppd.cetak',[
            'sppd' => $sppd,
            'setting' => $setting
        ]);
    }

    public function detailBiaya(SppdList $sppd)
    {
        return view('special-role.sppd.detail-biaya',[
            'sppd' => $sppd,
        ]);
    }

    public function setBiaya(Request $request)
    {
        $this->validate($request,[
            'id' => 'required',
            'uang_harian' => 'required|integer',
            'transport' => 'required|integer',
            'penginapan' => 'required|integer',
            'representatif' => 'required|integer',
        ]);

        $model = SppdEmployee::find($request->id);
        $model->update([
            'uang_harian' => $request->uang_harian,
            'transport' => $request->transport,
            'penginapan' => $request->penginapan,
            'representatif' => $request->representatif,
        ]);

        return redirect()->route('pegawai.sppd.detail-biaya',$model->sppd_id)->with(['success'=>'Data berhasil dihapus']);;
    }

    public function setMaskapai(Request $request)
    {
        $arr = [
            'nama_maskapai' => $request->nama_maskapai,
            'no_tiket' => $request->no_tiket,
            'id_booking' => $request->id_booking,
            'tanggal_checkin' => $request->tanggal_checkin,
            'harga_tiket' => $request->harga_tiket,
            'sppd_id' => $request->sppd_id,
            'status_keberangkatan' => $request->status,
        ];
        $model = SppdMaskapai::where('sppd_id',$request->id)->where('status_keberangkatan',$request->status)->first();
        if(empty($model))
        {
            SppdMaskapai::create($arr);
        }
        else
        {
            $model->update($arr);
        }

        return redirect()->route('pegawai.sppd.index')->with(['success'=>'Data berhasil disimpan']);;
    }
}
