<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Model\Surat\{Disposisi, SuratMasuk, SuratKeluar, HistoriSuratMasuk, SptList, SppdList};
use App\Model\Reference\Employee;
use App\Model\{Notification, Agenda, Avatar};
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->level == "admin")
        {
            $suratMasuk = SuratMasuk::count();
            $suratKeluar = SuratKeluar::count();
            $spt = SptList::count();
            $sppd = SppdList::count();
            $agenda = Agenda::count();
        }
        else
        {
            $suratMasuk = auth()->user()->employee->surat_masuks()->count();
            $suratKeluar = auth()->user()->employee->surat_keluars()->count();
            $spt = auth()->user()->employee->sptLists()->count();
            $sppd = auth()->user()->employee->sppdLists()->count();
            $agenda = auth()->user()->employee->agendas()->count();
        }
        return view('home',[
            "suratMasuk" => $suratMasuk,
            "suratKeluar" => $suratKeluar,
            "spt" => $spt,
            "sppd" => $sppd,
            "agenda" => $agenda
        ]);
    }

    public function disposisi()
    {
        // set disposisi as read
        $disposisi = Disposisi::where('pegawai_id',auth()->user()->employee->id)->whereNull('status')->orderby('id','desc')->get();
        if(!empty($disposisi) && count($disposisi) > 0)
            Disposisi::where('pegawai_id',auth()->user()->employee->id)->whereNull('status')->update(['status' => 1]);

        $disposisi = Disposisi::where('pegawai_id',auth()->user()->employee->id)->orderby('id','desc')->get();
        $employees = [];
        if(auth()->user()->employee->kepala_group)
        {
            $group = auth()->user()->employee->kepala_group;
            foreach($group->subGroups as $subGroup)
            {
                $employees[] = $subGroup->kepala;
                foreach($subGroup->subGroupStaffs as $staff)
                    $employees[] = $staff->employee;
            }
        }
        if(auth()->user()->employee->kepala_sub_group)
        {
            $subGroup = auth()->user()->employee->kepala_sub_group;
            foreach($subGroup->subGroupStaffs as $staff)
                    $employees[] = $staff->employee;
        }
        return view('disposisi',[
            'disposisis' => $disposisi,
            'employees' => $employees
        ]);
    }

    public function setDisposisi(Request $request)
    {
        $id = $request->surat_id;
        $nama = "";
        foreach($request->pegawai as $pegawai)
        {
            $disposisi = new Disposisi;
            $disposisi->pegawai_id = $pegawai;
            $disposisi->surat_masuk_id = $id;
            $disposisi->catatan = $request->catatan;
            $disposisi->save();

            $employee = Employee::find($pegawai);

            $surat = SuratMasuk::find($id);

            $notification = new Notification;
            $notification->user_id = $pegawai;
            $notification->status = 0;
            $notification->url_to = route('detail-surat-masuk',$surat->id);
            $notification->deskripsi = "Dispoisisi - ".$surat->sifat_surat.' - '.$surat->sumber_surat;
            $notification->save();

            $nama .= $employee->nama.' ('.$employee->nama.'), ';
        }

        $nama = rtrim($nama, ', ');

        HistoriSuratMasuk::create([
            'surat_masuk_id' => $id,
            'status' => 'Surat sudah di disposisikan oleh '.auth()->user()->employee->nama.' ('.auth()->user()->employee->jabatan.')'.' ke '.$nama
        ]);

        return redirect()->route('disposisi')->with(['success'=>'Surat telah di Disposisikan']);
    }

    public function detailSuratMasuk(SuratMasuk $surat)
    {

        // check is sekretaris
        if(auth()->user()->employee->kepala_group_special_role())
        {
            $status = 'Surat sudah dibaca oleh Sekretaris';
            $histori = HistoriSuratMasuk::where('surat_masuk_id',$surat->id)->where('status',$status)->first();
            if(!$histori)
            {
                HistoriSuratMasuk::create([
                    'status' => $status,
                    'surat_masuk_id' => $surat->id
                ]);
            }
        }

        if(auth()->user()->employee->isPimpinan())
        {
            $status = 'Surat sudah dibaca oleh Pimpinan';
            $histori = HistoriSuratMasuk::where('surat_masuk_id',$surat->id)->where('status',$status)->first();
            if(!$histori)
            {
                HistoriSuratMasuk::create([
                    'status' => $status,
                    'surat_masuk_id' => $surat->id
                ]);
            }
        }

        return view('surat-detail',[
            'surat' => $surat,
            'employees' => Employee::get()
        ]);
    }

    public function fileViewer()
    {
        $storage_url = $_GET['url'];
        $file = Storage::url($storage_url);
        $pathinfo = pathinfo($file);
        if($pathinfo['extension'] == "pdf")
        {
            return redirect($file);
        }
    }

    public function notificationRedirector(Notification $notification)
    {
        if(auth()->user()->employee->id == $notification->user_id)
        {
            $notification->status = 1;
            $notification->save();
            return redirect($notification->url_to);
        }

        return abort(404);

    }

    public function profil()
    {
        return view('profil');
    }

    public function editProfil()
    {
        return view('edit-profil');
    }

    public function updateProfil(Request $request)
    {
        $employee = Employee::find($request->id);
        //
        $this->validate($request,[
            'NIP' => 'required|unique:employees,NIP,'.$request->id.',id,NIP,'.$request->NIP,
            'nama' => 'required',
        ]);

        $user = User::find($employee->user_id)->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        if(!empty($request->password))
        {
            User::find($employee->user_id)->update([
                'password' => bcrypt($request->password)
            ]);
        }

        $employee->update([
            'NIP' => $request->NIP,
            'nama' => $request->nama,
        ]);

        return redirect()->route('profil')->with(['success'=>'Profil berhasil diupdate']);;
    }

    public function updateAvatar(Request $request)
    {
        $uploadedFile = $request->file('avatar');
        $path = $uploadedFile->store('public/avatar');
        $ava = Avatar::where('user_id',auth()->user()->id)->first();
        if(empty($ava))
        {
            $ava = new Avatar;
            $ava->create([
                'user_id' => auth()->user()->id,
                'avatar_url' => $path
            ]);
        }
        else
        {
            $ava->update([
                'avatar_url' => $path
            ]);
        }

        return redirect()->route('profil')->with(['success'=>'Avatar berhasil di update']);
    }

    public function agenda()
    {
        $events = [];

        $events[] = \Calendar::event(
            'Event One', //event title
            false, //full day event?
            '2019-02-11T0800', //start time (you can also use Carbon instead of DateTime)
            '2019-02-12T0800', //end time (you can also use Carbon instead of DateTime)
            0 //optionally, you can specify an event ID
        );

        $events[] = \Calendar::event(
            "Valentine's Day", //event title
            true, //full day event?
            new \DateTime('2019-02-14'), //start time (you can also use Carbon instead of DateTime)
            new 	\DateTime('2019-02-14'), //end time (you can also use Carbon instead of DateTime)
            'stringEventId' //optionally, you can specify an event ID
        );

        $calendar = \Calendar::addEvents($events) //add an array with addEvents
            ->setOptions([ //set fullcalendar options
                'firstDay' => 1,
            ])
            ->setCallbacks([
                'eventClick' => 'function(event) { alert(event.title)}',
            ]);

        return view('agenda', [
            'calendar' => $calendar
        ]);
    }
}
