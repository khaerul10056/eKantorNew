@extends('bsbmtemplate.admin-template')
@section('kepegawaian-active','active')
@section('group-active','active')
@section('content')
		<div class="container-fluid">
            <div class="block-header">
                <h2>
                    Data Sub Group ({{$group->nama}})
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                        	<div class="pull-left">
                            	<h2>Edit Data Sub Group</h2>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('reference.group.show',$group->id)}}" class="btn btn-warning waves-effect">
                                    <i class="material-icons">arrow_back</i> 
                                    <span>KEMBALI</span>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" action="{{route('reference.group.sub.update',$group->id)}}">
                            	{{csrf_field()}}
                            	<input type="hidden" name="_method" value="PUT">
                            	<input type="hidden" name="id" value="{{$model->id}}">
                            	<input type="hidden" name="group_id" value="{{$group->id}}">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama" required value="{{old('nama') ? old('nama') : $model->nama}}">
                                        <label class="form-label">Nama</label>
                                    </div>
                                    @if ($errors->has('nama'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nama') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group form-float">
                                    <select class="form-control show-tick" name="kepala_id" required="" data-live-search="true">
                                        <option value="">Pilih Pimpinan</option>
                                        {{'',$old_kepala_id = old('kepala_id') ? old('kepala_id') : $model->kepala_id}}
                                        @foreach($employees as $employee)
                                        <option value="{{$employee->id}}" {{$old_kepala_id == $employee->id ? 'selected=""' : '' }}>{{$employee->nama}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('kepala_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('kepala_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
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
@endsection