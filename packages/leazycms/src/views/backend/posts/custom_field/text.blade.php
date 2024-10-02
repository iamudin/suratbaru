<small for="{{_us($r[0])}}">{{str_replace('Perihal','Hal',$r[0])}}</small>
@if(get_post_type()=='surat-keluar' && Auth::user()->isOperator())
@php
if($r[0]=='Instansi'){
    $value = Auth::user()->unit->parent->title;
}elseif($r[0]=='Alamat'){
    $value = Auth::user()->unit->parent->data_field['alamat'];
}else{
    $value = isset($field[_us($r[0])]) ? trim($field[_us($r[0])]) : null;
}
@endphp
<input {{ isset($r[2]) ? 'required':'' }} type="text" value="{{ empty($field[_us($r[0])]) ? $value :  $field[_us($r[0])] }}"
 class="form-control form-control-sm" name="{{_us($r[0])}}" placeholder="Entri {{$r[0]}}">

@else
<input {{ isset($r[2]) ? 'required':'' }} type="text" value="{{ isset($field[_us($r[0])]) ? trim($field[_us($r[0])]) : null}}"
 class="form-control form-control-sm" name="{{_us($r[0])}}" placeholder="Entri {{$r[0]}}">
@endif
