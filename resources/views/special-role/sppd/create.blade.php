@extends('bsbmtemplate.admin-template')
@section('spt-sppd-active','active')
@section('sppd-active','active')
@section('content')
		<div class="container-fluid">
            <div class="block-header">
                <h2>
                    Data SPPD
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Tambah SPPD</h2>
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" action="{{route('pegawai.sppd.insert')}}">
                            	{{csrf_field()}}
                                <div class="form-group form-float">
                                    <select class="form-control show-tick" name="spt_id" required="" data-live-search="true">
                                        <option value="">Pilih SPT</option>
                                        @foreach($sptEmployee as $spt)
                                        <option value="{{$spt->spt_id}}" {{old('spt_id') == $spt->spt_id ? 'selected=""' : '' }}>{{$spt->list->no_spt}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('spt_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('spt_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <select class="form-control show-tick pengikut" name="pengikut[]" required="" data-live-search="true" multiple=""></select>
                                    @if ($errors->has('pengikut'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('pengikut') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="no_sppd" required value="{{old('no_sppd')}}">
                                        <label class="form-label">No SPPD</label>
                                    </div>
                                    @if ($errors->has('no_sppd'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('no_sppd') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label>Tanggal</label>
                                        <input type="text" class="datepicker form-control" name="tanggal" placeholder="Tanggal" required="" value="{{old('tanggal')}}">
                                    </div>
                                    @if ($errors->has('tanggal'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tanggal') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label>Kegiatan</label>
                                        <input type="text" class="form-control" value="{{auth()->user()->employee->isPptk->nama}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <select class="form-control show-tick" name="transportation_id" required="" data-live-search="true">
                                        <option value="">Pilih Transportasi</option>
                                        @foreach($transportations as $transport)
                                        <option value="{{$transport->id}}" {{old('transportation_id') == $transport->id ? 'selected=""' : '' }}>{{$transport->nama}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('transportation_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('transportation_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="asal" required value="{{old('asal')}}">
                                        <label class="form-label">Tempat Berangkat</label>
                                    </div>
                                    @if ($errors->has('asal'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('asal') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tujuan" required value="{{old('tujuan')}}">
                                        <label class="form-label">Tempat Tujuan</label>
                                    </div>
                                    @if ($errors->has('tujuan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tujuan') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="uang_harian" required value="{{old('uang_harian')}}">
                                        <label class="form-label">Uang Harian</label>
                                    </div>
                                    @if ($errors->has('uang_harian'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('uang_harian') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="transport" required value="{{old('transport')}}">
                                        <label class="form-label">Transport</label>
                                    </div>
                                    @if ($errors->has('transport'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('transport') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="penginapan" required value="{{old('penginapan')}}">
                                        <label class="form-label">Penginapan</label>
                                    </div>
                                    @if ($errors->has('penginapan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('penginapan') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                    <input type="text" class="form-control" name="representatif" required value="{{old('representatif')}}">
                                        <label class="form-label">Representatif</label>
                                    </div>
                                    @if ($errors->has('representatif'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('representatif') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                                <a class="btn btn-warning waves-effect" href="{{url()->previous()}}">KEMBALI</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('script')
<!-- Select Plugin Js -->
<script src="{{asset('template/bsbm/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<!-- Moment Plugin Js -->
<script src="{{asset('template/bsbm/plugins/momentjs/moment.js')}}"></script>
<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{asset('template/bsbm/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script type="text/javascript">
$('input[name=tanggal]').bootstrapMaterialDatePicker({
    clearButton: true,
    weekStart: 1,
    time: false
});
$('select[name=spt_id]').change(() => {
    var id = $('select[name=spt_id]').val()
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url:"{{route('pegawai.sppd.get-employees')}}",
        data:{id:id},
        success:function(data){
            data.forEach(value => {
                var option = "<option value='"+value.employee_id+"'>" + value.employee.nama + "</option>"
                $('select.pengikut').append(option)
            })
            $("select.pengikut").selectpicker("refresh");
        }
    });
});

</script>
@endsection