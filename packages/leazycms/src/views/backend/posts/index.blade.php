@extends('cms::backend.layout.app',['title'=>get_post_type('title_crud')])
@section('content')
<div class="row">
<div class="col-lg-12 mb-3">
  <h3 style="font-weight:normal;float:left;" ><i class="fa {{get_module_info('icon')}}" aria-hidden="true"></i> {{get_post_type('title_crud')}}
</h3>
<div class="pull-right">
    <div class="btn-group">
    @if(Route::has(get_post_type().'.create'))
    <a href="{{route(get_post_type().'.create')}}" class="btn btn-primary btn-sm"> <i class="fa fa-plus" aria-hidden></i> Tambah</a>
    @endif
    @if(Route::has(get_post_type().'.category') && Auth::user()->isAdmin()) <a href="{{route(get_post_type().'.category')}}" class="btn btn-dark btn-sm"> <i class="fa fa-tags" aria-hidden></i> Kategori</a> @endif
</div>
</div>

</div>
<div class="col-lg-12">
    @include('cms::backend.layout.error')
@if(!in_array(get_post_type(),['surat-keluar','surat-masuk']))
<table class="display table table-hover table-bordered datatable" style="background:#f7f7f7;width:100%">
<thead style="text-transform:uppercase;color:#444">
  <tr>

    <th style="width:10px;vertical-align: middle">NO</th>
    @if(current_module()->form->thumbnail)
    <th style="width:55px;vertical-align: middle" >Gambar</th>
    @endif
    <th style="vertical-align: middle">{{current_module()->datatable->data_title}}</th>

    @if($parent = current_module()->form->post_parent)
    <th style="vertical-align: middle" >{{$parent[0]}}</th>
    @endif
    @if($custom = current_module()->datatable->custom_column)
    <th style="vertical-align: middle">{{$custom}}</th>
    @endif
    <th style="width:60px;vertical-align: middle">Dibuat</th>

    @if(get_post_type()!='media')<th style="width:60px;vertical-align: middle">Diubah</th>@endif
    @if(current_module()->web->detail)
    <th  style="width:30px;vertical-align: middle">Hits</th>
    @endif
    <th style="width:40px;vertical-align: middle">Aksi</th>
  </tr>
</thead>

<tbody style="background:#fff">

</tbody>


</table>

</div>
</div>
@include('cms::backend.posts.datatable')

@endif

@if(get_post_type()=='surat-keluar')
@include('cms::backend.posts.surat-keluar')
@endif
@push('styles')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

@endpush
@push('scripts')
<script type="text/javascript" src="{{secure_asset('backend/js/plugins/jquery.dataTables.min.js')}}"></script>
     <script type="text/javascript" src="{{secure_asset('backend/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
     <script type="text/javascript">$('#sampleTable').DataTable();</script>
     @include('cms::backend.layout.js')
@endpush

@endsection
