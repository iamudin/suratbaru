@extends('cms::backend.layout.app',['title'=>'Dashboard'])
@section('content')
<!-- <link href="https://coderthemes.com/adminox/layouts/vertical/assets/css/icons.min.css" rel="stylesheet" type="text/css" /> -->
<div class="row">
<div class="col-lg-12 mb-3">

    @if(!Auth::user()->isAdmin())
    <div class="alert alert-info">
        <h3> Selamat Datang di E-Surat Kabupaten Bengkalis v.2 (beta)</h3>  Anda login sebagai : <strong> <i class="fa fa-user"></i> {{ str(Auth::user()->level)->headline() }} | {{ Auth::user()->unit->title }} {{ Auth::user()->unit->parent ? ' | '.Auth::user()->unit->parent->title : '' }}</strong>
        <hr>
        @if(Auth::user()->isAdminKantor())

        @endif
        @if(Auth::user()->isOperator())
        <p class="text-danger">
            <strong>Diinformasikan bahwa data surat yang ada sebelumnya akan di pulihkan kembali dalam waktu 3x24 jam. Silahkan tetap menginput data surat keluar saat ini.</strong><br><br>
            Catatan :<br>
            - Data surat masuk hanya bisa dinput oleh Admin Kantor / Instansi .
        </p>
        @endif
    </div>
    @endif

    <h3 style="font-weight:normal;"> <i class="fa fa-line-chart"></i> Dashboard / Statistik Data </h3>

</div>
    <div class="col-lg-12">
  <div class="row">
    @foreach($type as $row)
          <div title="Klik untuk selengkapnya" class="pointer col-md-6 col-lg-3" onclick="location.href='{{route($row->name)}}'">
            <div class="widget-small danger coloured-icon"><i class="icon fa {{$row->icon}} fa-3x"></i>
              <div class="info pl-3">
                <p class="mt-2 text-muted">{{$row->title}}</p>
                <h2><b>{{$posts->where('type',$row->name)->count()}}</b></h2>
              </div>
            </div>
          </div>
          @endforeach
          @if(Auth::user()->isAdmin())
          <div title="Klik untuk selengkapnya" class="pointer col-md-6 col-lg-3" onclick="location.href='{{route('user')}}'">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-users fa-3x"></i>
              <div class="info pl-3">
                <p class="mt-2 text-muted">Pengguna</p>
                <h2><b>{{\Leazycms\Web\Models\User::whereNotIn('level',['admin'])->count()}}</b></h2>
              </div>
            </div>
          </div>
          @endif

        </div>
</div>
<div class="col-lg-12 mb-3">
  <div class="card" style="padding:15px">
  <h4 for="" style="margin-bottom:20px"><i class="fa fa-history" aria-hidden="true"></i> 5 Transaksi Data Surat Terbaru</h4>
  <div class="table-responsive">
    <table class="table" style="font-size:small">
  <thead><tr>
    <th width="150px">Waktu</th>
    <th>Jenis Data</th>
    <th>Nomor Surat</th>
    <th>Hal</th>
    <th>Pembuat</th>
    <th  width="50px">Status</th>
  </tr></thead>
  <tbody>
    @foreach($latest as $row)
    <tr>
        <td><code>{{ $row->created_at->diffForHumans() }}</code></td>
        <td>{{ str($row->type)->headline() }}</td>
        <td><span class="text-primary">{{$row->title }}</span></td>
        <td>{{ $row->field->perihal }}</td>
        <td><small>{{ $row->user->name }} | {{ str($row->user->level)->headline()}} {{ $row->user->unit->title }} {{ $row->user->unit->parent ? ' | '.$row->user->unit->parent->title : '' }}</small></td>
        <td>{!! $row->status == 'draft' ? '<badge class="badge badge-warning">Draft</badge>' : '<badge class="badge badge-success">Publish</badge>' !!}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
  </div>
</div>
</div>

@if(Auth::user()->isAdmin())
    <div class="col-lg-12 mb-3">
        <div class="card" style="padding:15px">
        <h4 for="" style="margin-bottom:20px"><i class="fa fa-history" aria-hidden="true"></i> 5 User Login Terbaru</h4>
        <div class="table-responsive">
          <table class="table" style="font-size:small">
        <thead><tr>
          <th width="150px">Waktu</th>
          <th>IP</th>
          <th>Unit</th>
          <th>Nama User</th>
        </tr>
    </thead>
        <tbody>
          @foreach(\Leazycms\Web\Models\User::with('unit.parent')->whereIn('level',['AdminKantor','operator'])->latest('last_login_at')->limit('5')->get() as $row)
          <tr>
              <td><code>{{ $row->last_login_at}}</code></td>
              <td><code>{{ $row->last_login_ip}}</code></td>
              <td><span class="text-primary">{{$row->unit->title}} {{ $row->unit->parent ? ' - '.$row->unit->parent->title : '' }}</span></td>
              <td><code>{{ $row->name}}</code></td>

          </tr>
          @endforeach
        </tbody>
        </table>
        </div>
      </div>
      </div>
@endif
<script type="text/javascript">
          window.addEventListener('DOMContentLoaded', function() {
      /*  var sort_col = $('.datatable').find("th:contains('Time')")[0].cellIndex;*/
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
                method: "POST",
                url: "{{ route('visitor.data') }}",
                data: function (d){
                 d._token = "{{csrf_token()}}";
                 d.timevisit = $("#timevisit").val();
            }
          },
        columns: [

            {data: 'created_at', name: 'created_at', orderable: true},
            {data: 'page', name: 'page'},
            {data: 'reference', name: 'reference'},
            {data: 'ip_location', name: 'ip_location'},
            {data: 'browser', name: 'browser'},
            {data: 'device', name: 'device'},
            {data: 'os', name: 'os'},
        ],
        responsive: true,
        /*    order: [
                [sort_col, 'desc']
            ]*/
    });

          });
    </script>

</div>
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
@endpush
@endsection
