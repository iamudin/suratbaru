@extends('cms::backend.layout.app', ['title' => 'File Manager'])
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3">
            <h3 style="font-weight:normal;float:left"><i class="fa fa-comments"></i> Tanggapan
            </h3>
            <div class="pull-right btn-group">

                <a href="{{route('panel.dashboard')}}" class="btn btn-danger btn-sm"> <i class="fa fa-undo" aria-hidden></i> Kembali</a>
            </div>
        </div>
        <div class="col-lg-12">
            {{ flc_comment() }}
        </div>
    </div>
@include('cms::backend.layout.js')
@endsection
