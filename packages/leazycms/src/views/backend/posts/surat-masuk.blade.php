<table class="display table table-hover table-bordered datatable" style="background:#f7f7f7;width:100%">
    <thead style="text-transform:uppercase;color:#444">
      <tr>

        <th style="width:10px;vertical-align: middle">No</th>

        <th style="vertical-align: middle">{{current_module()->datatable->data_title}}</th>
        <th style="vertical-align: middle" style="width:200px">Tgl Surat</th>
        <th style="vertical-align: middle" style="width:200px">Tgl Terima</th>
        <th style="vertical-align: middle">Asal</th>
        <th style="vertical-align: middle">Hal</th>
        @if($custom = current_module()->datatable->custom_column)
        <th style="vertical-align: middle">{{$custom}}</th>
        @endif
        <th style="vertical-align: middle">Disposisi</th>
        <th style="vertical-align: middle">Balasan</th>
        <th style="width:60px;vertical-align: middle">Diedit</th>

        @if (get_post_type()!='unit' && !request()->user()->isAdmin())

        <th style="width:40px;vertical-align: middle">Aksi</th>
        @endif
      </tr>
    </thead>

    <tbody style="background:#fff">

    </tbody>


    </table>

    </div>
    </div>
    @include('cms::backend.posts.datatable-surat-masuk')
