<table class="display table table-hover table-bordered datatable" style="background:#f7f7f7;width:100%">
    <thead style="text-transform:uppercase;color:#444">
      <tr>

        <th style="width:10px;vertical-align: middle">No</th>

        <th style="vertical-align: middle">{{current_module()->datatable->data_title}}</th>
        <th style="vertical-align: middle" style="width:200px">Tgl Terima</th>

        <th style="vertical-align: middle">Hal</th>
        <th style="vertical-align: middle">Asal</th>
        @if($custom = current_module()->datatable->custom_column)
        <th style="vertical-align: middle">{{$custom}}</th>
        @endif
        <th style="width:60px;vertical-align: middle">Dibuat</th>

        @if(get_post_type()!='media')<th style="width:60px;vertical-align: middle">Diubah</th>@endif
        @if(current_module()->web->detail)
        <th  style="width:30px;vertical-align: middle">Hits</th>
        @endif
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
