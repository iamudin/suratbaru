<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function() {
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
                    data: 'ext_column.diterbitkan',
                    searchable: false,
                    name: 'ext_column.diterbitkan',
                    orderable: false
                },
                {
                    data: 'ext_column.jenis_file',
                    searchable: false,
                    name: 'ext_column.jenis_file',
                    orderable: false
                },
                    {
                        data: 'data_field',
                        name: 'data_field',
                        orderable: false,
                        searchable: true
                    },
                {
                    data: 'ext_column.tujuan',
                    searchable: false,
                    name: 'ext_column.tujuan',
                    orderable: false
                },
                {
                    data: 'ext_column.perihal',
                    searchable: false,
                    name: 'ext_column.perihal',
                    orderable: false
                },
            @if(request()->user()->isOperator())

                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        orderable: true,
                        searchable: false
                    },
            @endif
                @if (!request()->user()->isAdmin())
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                @endif
            ],
            responsive: true,

        });


    });
</script>
