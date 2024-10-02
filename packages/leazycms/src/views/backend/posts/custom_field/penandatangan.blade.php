<small>Penandatangan</small>

    @php
    $penandatangan = query()->onType('unit')->whereIn('id',[Auth::user()->unit->id,Auth::user()->unit->parent->id])->select('data_loop')->get();
    $da = [];
    foreach($penandatangan as $row){
        if($row->data_loop){
            foreach ($row->data_loop as $key => $value) {
                array_push($da,$value);
            }
        }
    }
    $datastatis = collect($da)->pluck('jabatan')->toArray();
    @endphp

<input name="penandatangan"type="text" value="{{isset($field['penandatangan']) && !empty($field['penandatangan'])? $field['penandatangan'] :''}}" class="form-control form-control-sm" id="autocomplete" placeholder="Ketik jabatan">
    <div id="suggestions" class="autocomplete-suggestions"></div>
<script>
    $(document).ready(function() {
        var dataStatis = {!!json_encode($datastatis)!!};

        $('#autocomplete').on('input', function() {
            var query = $(this).val().toLowerCase();
            var suggestions = '';

            if (query) {
                var filteredData = dataStatis.filter(function(item) {
                    return item.toLowerCase().includes(query);
                });

                if (filteredData.length > 0) {
                    filteredData.forEach(function(item) {
                        suggestions += '<div class="autocomplete-suggestion" style="cursor:pointer">' + item + '</div>';
                    });
                } else {
                    suggestions += '<div class="autocomplete-suggestion">Tidak ditemukan</div>';
                }
            }

            $('#suggestions').html(suggestions);

            $('.autocomplete-suggestion').on('click', function() {
                $('#autocomplete').val($(this).text());
                $('#suggestions').empty();  
            });
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#autocomplete').length) {
                $('#suggestions').empty();
            }
        });
    });
</script>
