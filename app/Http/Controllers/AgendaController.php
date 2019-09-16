<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Agenda,Setting,Notification};

class AgendaController extends Controller
{
    //

    function __construct()
    {
        $this->model = new Agenda;
    }

    function index()
    {
        $agendas = $this->model->where('employee_id',auth()->user()->employee->id)->get();
        if(auth()->user()->employee->inSpecialRoleUser() && !auth()->user()->employee->kepala_group_special_role())
            $agendas = $this->model->get();
        return view('agenda.index',[
            'agendas' => $agendas
        ]);
    }
    
    function create()
    {
        return view('agenda.create');
    }

    function store(Request $request)
    {
        $this->validate($request,[
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'kegiatan' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
        ]);

        $path = "";
        if(!empty($request->file('file_url')))
        {
            $uploadedFile = $request->file('file_url');
            $path = $uploadedFile->store('public/file_agenda');
        }

        $status = auth()->user()->employee->kepala_group || auth()->user()->employee->kepala_group_special_role() ? 1 : 0;

        $model = $this->model->create([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'waktu_mulai' => $request->waktu_mulai ? $request->waktu_mulai : '',
            'waktu_selesai' => $request->waktu_selesai ? $request->waktu_selesai : '',
            'kegiatan' => $request->kegiatan,
            'tempat' => $request->tempat,
            'keterangan' => $request->keterangan,
            'file_url' => $path,
            'status' => $status
        ]);

        if(!$status)
        {
            $kepala_id = [];
            $setting = Setting::find(1);
            $kepala_id[] = $setting->special->group->kepala_id;
            if(auth()->user()->employee->kepala_sub_group)
                $kepala_id[] = auth()->user()->employee->kepala_sub_group->group->kepala_id;
            else
                $kepala_id[] = auth()->user()->employee->staffGroup->subGroups->group->kepala_id;
            foreach($kepala_id as $k_id)
            {
                $notification = new Notification;
                $notification->user_id = $id;
                $notification->status = 0;
                $notification->url_to = route('agenda.show',$model->id);
                $notification->deskripsi = "Agenda oleh ".auth()->user()->employee->nama;
                $notification->save();
            }
        }

        return redirect()->route('agenda.index')->with(['success'=>'Data berhasil disimpan']);
    }

    function edit(Agenda $agenda)
    {
        return view('agenda.edit',[
            'agenda' => $agenda
        ]);
    }

    function show(Agenda $agenda)
    {
        return view('agenda.show',[
            'agenda' => $agenda
        ]);
    }

    function acc(Agenda $agenda)
    {
        $agenda->update([
            'status' => 1
        ]);

        return redirect()->route('agenda.index')->with(['success'=>'Agenda disetujui']);
    }

    function tolak(Agenda $agenda)
    {
        $agenda->update([
            'status' => 2
        ]);

        return redirect()->route('agenda.index')->with(['success'=>'Agenda ditolak']);
    }

    function update(Request $request)
    {
        $this->validate($request,[
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'kegiatan' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
        ]);

        $status = auth()->user()->employee->kepala_group || auth()->user()->employee->kepala_group_special_role ? 1 : 0;

        if(!$status)
        {
            $kepala_id = [];
            $setting = Setting::find(1);
            $kepala_id[] = $setting->special->group->kepala_id;
            if(auth()->user()->employee->kepala_sub_group)
                $kepala_id[] = auth()->user()->employee->kepala_sub_group->group->kepala_id;
            else
                $kepala_id[] = auth()->user()->employee->staffGroup->subGroups->group->kepala_id;
            foreach($kepala_id as $k_id)
            {
                $notification = new Notification;
                $notification->user_id = $id;
                $notification->status = 0;
                $notification->url_to = route('agenda.show',$request->id);
                $notification->deskripsi = "Update Agenda oleh ".auth()->user()->employee->nama;
                $notification->save();
            }
        }

        $this->model->find($request->id)->update([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'waktu_mulai' => $request->waktu_mulai ? $request->waktu_mulai : '',
            'waktu_selesai' => $request->waktu_selesai ? $request->waktu_selesai : '',
            'kegiatan' => $request->kegiatan,
            'tempat' => $request->tempat,
            'keterangan' => $request->keterangan,
            'status' => $status
        ]);

        $path = "";
        if(!empty($request->file('file_url')))
        {
            $uploadedFile = $request->file('file_url');
            $path = $uploadedFile->store('public/file_agenda');

            $this->model->find($request->id)->update([
                'file_url' => $path,
            ]);
        }

        return redirect()->route('agenda.index')->with(['success'=>'Data berhasil diupdate']);
    }

    function destroy(Request $request)
    {
        $this->model->find($request->id)->delete();
        return redirect()->route('agenda.index')->with(['success'=>'Data berhasil dihapus']);
    }
}
