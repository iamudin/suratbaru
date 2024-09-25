<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function() {
     var sort_col = $('.datatable').find("th:contains('Diedit')")[0].cellIndex;
        var table = $('.datatable').DataTable({
            responsive: true,

            processing: true,
            serverSide: true,
            aaSorting: [],

            ajax: {
                method: "POST",
                url: "{{ route(get_post_type() . '.datatable') }}",
                data: {_token:"{{csrf_token()}}"}
            },
            lengthMenu: [10, 20, 50, 100, 200, 500],
            deferRender: true,
            columns: [

                {
                    className: 'text-center',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'title',
                    searchable: true,
                    name: 'title',
                    orderable: false
                },
                {
                    data: 'ext_column.tgl_surat',
                    searchable: false,
                    name: 'ext_column.tgl_surat',
                    orderable: false
                },
                {
                    data: 'ext_column.tgl_diterima',
                    searchable: false,
                    name: 'ext_column.tgl_diterima',
                    orderable: false
                },
                {
                    data: 'ext_column.asal',
                    searchable: false,
                    name: 'ext_column.asal',
                    orderable: false
                },
                {
                    data: 'ext_column.perihal',
                    searchable: false,
                    name: 'ext_column.perihal',
                    orderable: false
                },

                @if (current_module()->datatable->custom_column)
                    {
                        data: 'data_field',
                        name: 'data_field',
                        orderable: false,
                        searchable: true
                    },
                @endif
                {
                        data: 'ext_column.disposisi',
                        name: 'ext_column.disposisi',
                        orderable: false,
                        searchable: false
                    },
                {
                        data: 'butuh_balas',
                        name: 'butuh_balas',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        orderable: true,
                        searchable: false
                    },
                @if (current_module()->web->detail)
                    {
                        data: 'visitors_count',
                        name: 'visitors_count',
                        orderable: true,
                        searchable: false
                    },
                @endif
                @if (get_post_type()!='unit' && !request()->user()->isAdmin())
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                @endif
            ],
            responsive: true,
            order: [
                [sort_col, 'desc']
            ],
        });


    });
</script>
