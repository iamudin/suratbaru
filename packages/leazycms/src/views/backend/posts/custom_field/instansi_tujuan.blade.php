<small>Instansi Tujuan</small>
<input name="instansi_tujuan" type="text" value="{{isset($field['instansi_tujuan']) && !empty($field['instansi_tujuan'])? $field['instansi_tujuan'] :''}}" class="form-control form-control-sm" id="autocomplete_tujuan" placeholder="Ketik Instansi Tujuan">
    <div id="suggestionstujuan" class="autocomplete-suggestionstujuan"></div>

@php 
$instansi = query()->onType('unit')->pinned()->get()->pluck('title')->toArray();
@endphp
<script>
    $(document).ready(function() {
        var dataStatis = {!!json_encode($instansi)!!};

        $('#autocomplete_tujuan').on('input', function() {
            var query = $(this).val().toLowerCase();
            var suggestions = '';

            if (query) {
                var filteredData = dataStatis.filter(function(item) {
                    return item.toLowerCase().includes(query);
                });

                if (filteredData.length > 0) {
                    filteredData.forEach(function(item) {
                        suggestions += '<div class="autocomplete-suggestiontujuan" style="cursor:pointer">' + item + '</div>';
                    });
                } else {
                    suggestions += '<div class="autocomplete-suggestiontujuan">Tidak ditemukan</div>';
                }
            }

            $('#suggestionstujuan').html(suggestions);

            $('.autocomplete-suggestiontujuan').on('click', function() {
                $('#autocomplete_tujuan').val($(this).text());
                $('#suggestionstujuan').empty();  
            });
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#autocomplete_tujuan').length) {
                $('#suggestionstujuan').empty();
            }
        });
    });
</script>
