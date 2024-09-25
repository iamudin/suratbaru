<small for="{{_us($r[0])}}">{{$r[0]}}</small>
<input {{ isset($r[2]) ? 'required':'' }} type="text" value="@if($post->user->isOperator() && ( $r[0]=='Instansi' ||  $r[0]=='Alamat') && isset($field[_us($r[0])]) && empty($field[_us($r[0])])) @if($r[0]=='Instansi') {{ $post->user->unit->parent->title }} @else {{ $post->user->unit->parent->data_field['alamat'] }} @endif @else {{ isset($field[_us($r[0])]) ? trim($field[_us($r[0])]) :''}}@endif"
 class="form-control form-control-sm" name="{{_us($r[0])}}" placeholder="Entri {{$r[0]}}">
