@if(!Auth::user()->isOperator() && $post->type=='surat-keluar')
@php $key = _us($r[0]) @endphp

<small for="{{_us($r[0])}}">File Surat</small><br>
@if(isset($field[_us($r[0])]) && media_exists($post->field->$key))
<a href={{asset($field[_us($r[0])]) }} class="btn btn-outline-info btn-sm" style="margin-top:4px">Lihat {{$r[0]}} (.{{ str(get_ext($field[_us($r[0])]))->upper() }})</a>
@else
<small><i class="text-danger">Belum ada</i></small>
@endif
<br>
@else
<small for="{{_us($r[0])}}">{{$r[0]}} </small><br>
@php $key = _us($r[0]) @endphp
@if(isset($field[_us($r[0])]) && media_exists($post->field->$key))
<input  type="hidden" name="{{_us($r[0])}}" value="{{ $field[_us($r[0])]}}">
<a href={{asset($field[_us($r[0])]) }} class="btn btn-outline-info btn-sm" style="margin-top:4px">Lihat {{$r[0]}} (.{{ str(get_ext($field[_us($r[0])]))->upper() }})</a>

@if($post->user_id==Auth::user()->id)<a  title="Hapus dokumen untuk mengganti" data-toggle="tooltip"  class="fa fa-trash text-danger" style="cursor:pointer" onclick="media_destroy('{{ $field[_us($r[0])]}}')"></a>@endif
@if($post->type=='surat-keluar' && _us($r[0]) == 'file_surat' )
<br>
<a href={{url('generate/'.$post->keyword) }} target="_blank" class="btn btn-outline-success btn-sm" style="margin-top:4px"> <i class="fa fa-qrcode"></i> Lihat hasil {{$r[0]}} di Qr-Code</a><br>
@endif
@else
<input {{ isset($r[2]) ? 'required':'' }} accept="{{ $post->type=='surat-keluar' && _us($r[0]) == 'file_surat' ? 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' : allow_mime() }}" type="file" class="form-control-sm" value="{{ $field[_us($r[0])]??null }}" name="{{_us($r[0])}}" placeholder="Entri {{$r[0]}}">
@endif
<br>
@endif

