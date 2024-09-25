@foreach ($module->form->custom_field as $r )
@if($r[1] == 'text')
@if(Auth::user()->isAdminKantor() && $post->type=='unit' && $post->pinned!='Y')
@else
@include('cms::backend.posts.custom_field.text')

@endif
@elseif ($r[1] == 'textarea')
@include('cms::backend.posts.custom_field.textarea')
@elseif ($r[1] == 'file')
@include('cms::backend.posts.custom_field.file')
@elseif ($r[1] == 'image')
@include('cms::backend.posts.custom_field.image')
@elseif ($r[1] == 'number')
@include('cms::backend.posts.custom_field.number')
@elseif ($r[1] == 'phone')
@include('cms::backend.posts.custom_field.phone')
@elseif ($r[1] == 'email')
@include('cms::backend.posts.custom_field.email')
@elseif ($r[1] == 'date')
@include('cms::backend.posts.custom_field.date')
@elseif ($r[1] == 'datetime')
@include('cms::backend.posts.custom_field.datetime')
@elseif ($r[1] == 'tujuan_surat')

@if($post->child)
<input type="hidden" name="tujuan_surat" value="{{ $field[_us($r[0])] }}">
@endif
@if(Auth::user()->isAdminKantor())
<small >Tujuan Surat</small>

<select @if($post->child ) disabled @endif class="form-control form-control-sm" name="tujuan_surat">
   <option value="">--pilih--</option>
   @foreach(\Leazycms\Web\Models\Post::whereType('unit')->select('id','title','user_id','parent_id')->where('id',Auth::user()->unit->id)->with('childs')->get() as $i)
   <option  {{($field && isset($field[_us($r[0])]) && $field[_us($r[0])]==$i->title) ? 'selected':'' }} value="{{$i->title}}">{{$i->title}}</option>
   @if($i->childs)
   @foreach($i->childs as $child)
   <option  {{($field && isset($field[_us($r[0])]) && $field[_us($r[0])]==$child->title.' - '.$i->title) ? 'selected':'' }} value="{{$child->title.' - '.$i->title}}">{{$child->title.' - '.$i->title}}</option>
   @endforeach
   @endif
   @endforeach
</select>
@endif
@elseif (is_array($r[1]))
@include('cms::backend.posts.custom_field.option')
@elseif($r[1]=='break')
@if($post->type=='surat-masuk' && Auth::user()->isOperator())
@else
<br>
<h6 for="" style="border-bottom:1px dashed #000">{{$r[0]}}</h6>
@endif
@else
@endif
@endforeach

