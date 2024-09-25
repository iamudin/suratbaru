@extends('cms::backend.layout.app', ['title' => get_post_type('title_crud')])
@section('content')
    <form class="editor-form" action="{{ route(get_post_type() . '.update', $post->id) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12">
                <h3 style="font-weight:normal">
                    <i class="fa {{ $module->icon }}" aria-hidden="true"></i> {{ get_post_type('title_crud') }}
                    <a href="{{ route(get_post_type()) }}" class="btn btn-danger btn-sm pull-right"
                        data-toggle="tooltip" title="Kembali Ke Index Data"> <i class="fa fa-undo" aria-hidden></i>
                        Kembali</a>
                </h3>
                <br>
                @if (!empty($post && $module->web->detail && $post->title && $post->status=='publish') && $module->public)
                    <div style="border-left:3px solid green" class="alert alert-success"><b>URL : </b><a
                            title="Kunjungi URL" data-toggle="tooltip" href="{{ url($post->url) }}"
                            target="_blank"><i><u>{{ str(url($post->url))->limit('160','...') }}</u></i></a> <span
                            title="Klik Untuk Menyalin alamat URL {{ $module->title }}" data-toggle="tooltip"
                            class="pointer copy pull-right badge badge-primary" data-copy="{{ url($post->url) }}"><i
                                class="fa fa-copy" aria-hidden></i> <b>Salin</b></span></div>
                @endif

                @if ($post->status=='publish' && get_post_type() == 'surat-keluar')
                <div style="border-left:3px solid green" class="alert alert-success"><b>URL : </b><a
                        title="Kunjungi URL" data-toggle="tooltip" href="{{ url('surat-keluar/'.$post->keyword) }}"
                        target="_blank"><i><u>{{ url('surat-keluar/'.$post->keyword) }}</u></i></a> <span
                        title="Klik Untuk Menyalin alamat URL {{ $module->title }}" data-toggle="tooltip"
                        class="pointer copy pull-right badge badge-primary" data-copy="{{ url('surat-keluar/'.$post->keyword) }}"><i
                            class="fa fa-copy" aria-hidden></i> <b>Salin</b></span></div>
            @endif
                @include('cms::backend.layout.error')
            </div>
            <div class="col-lg-9">
                <div class="form-group">
                    <input data-toggle="tooltip" title="Masukkan {{ $module->datatable->data_title }}" required
                        name="title" type="text" value="{{ $post->title ?? '' }}"
                        placeholder="Masukkan {{ $module->datatable->data_title }}" class="form-control form-control-lg">

                </div>

                @if ($module->form->editor)
                    <div class="form-group">

                          @if($post->type=='docs')
                          @php $type = "application/x-httpd-php"; @endphp
                          <textarea name="content" placeholder="Dokumentasi" id="editor" class="custom_html">{{ $post->content ?? '' }}</textarea>
                          @include('cms::backend.layout.codemirrorjs')
                          @else
                            <textarea name="content" placeholder="Keterangan..." id="editor">{{ $post->content ?? '' }}</textarea>
                          @endif
                    </div>
                @endif

                @if ($pp = $module->form->post_parent)
                    <?php
                    if (isset($pp[1])) {
                        if (isset($pp[2]) && $pp[2] != 'all') {
                            $par = Leazycms\Web\Models\Post::withwherehas('category', function ($q) {
                                $q->where('slug', $pp[2]);
                            })
                                ->whereType($pp[1])
                                ->whereStatus('publish')
                                ->select('id', 'title')
                                ->get();
                        } else {
                            $par = Leazycms\Web\Models\Post::whereType($pp[1])
                                ->whereNotIn('id',[$post->id]);
                            if($post->type=='unit'){
                               $par = $par->wherePinned('Y');
                            }
                            if($post->type=='surat-keluar' && Auth::user()->isOperator()){

                                    $par = $par->WhereJsonContains('data_field->tujuan_surat', Auth::user()->unit->title.' - '.Auth::user()->unit->parent?->title);


                            }
                               $par =  $par->select('id', 'title','parent_id')->published();
                               if(Auth::user()->isAdminKantor() && $post->type=='surat-keluar'){
                                $par =  $par->whereId($post->parent_id)->first();
                               }else{
                                $par =  $par->get();
                               }
                        }
                    }
                    ?>
                     @if(Auth::user()->isAdminKantor() && $post->type=='unit')
                    <input type="hidden" name="parent_id" value="{{ Auth::user()->unit->id == $post->id ? null :  Auth::user()->unit->id }}">
                     @endif
                    @if(Auth::user()->isAdminKantor() && $post->type=='surat-keluar')
                    <h6>{{ $pp[0] }}</h6>
                    @if($par)
                    <a  href="/panel/surat-masuk/{{ $par->id }}/edit" target="_blank" class="btn btn-sm btn-success "> <i class="fa fa-eye"></i> {{ $par->title }}</a><br>
                    @else
                    <span class="badge badge-danger"> <i class="fa fa-close"></i> Tidak Ada Referensi Surat</span><br>
                    @endif

                    @endif
                    @if((!Auth::user()->isAdminKantor() && $post->type=='surat-keluar') || Auth::user()->isAdmin() && $post->type=='unit')

                    <h6>{{ $pp[0] }}  <small id="refsurat" style="cursor:pointer;display:none" href="" class="badge badge-success"> <i class="fa fa-eye"></i> Lihat Surat</small></h6>
                    <select @if (isset($pp[3]) && $pp[3] == 'required') required @endif class="form-control form-control-sm"
                    @if($post->type=='surat-keluar')
                    onchange="if(this.value) {$('#refsurat').attr('onclick', 'window.open(\'/panel/surat-masuk/' + this.value + '/edit\', \'_blank\')').show()}else{$('#refsurat').hide()}"
                    @endif

                        name="parent_id">
                        <option value="">--pilih--</option>

                        @foreach ($par as $row)
                            <option @if ($post && $post->parent_id == $row->id) selected @endif value="{{ $row->id }}">
                                {{ $row->title }}</option>
                        @endforeach

                    </select>
                    @endif
                @endif

                @if ($module->form->custom_field)
                    @include('cms::backend.posts.custom_field.form')

                @endif
                @if ($module->form->looping_data)
                @include('cms::backend.posts.looping_data.form')
                @endif


                @if (get_module_info('looping'))
                    <br>
                    <h6 style="border-bottom:1px dashed #000;font-weight:normal"><b>{{ get_module_info('looping') }}</b>
                        <span class="text-muted pull-right">{{ get_module_info('looping_for') }}</span> </h6>

                    @if (get_module_info('post_type') != 'menu')
                        @if (get_module_info('post_type') == 'lasyanan')
                            @include('admin.hasil-skm')
                        @else
                            @include('cms::backend.looping-data')
                        @endif
                    @else
                        @include('cms::backend.list-menu')
                    @endif

                @endif
                @if($module->name=='surat-keluar')


                @endif
            </div>
            <div class="col-lg-3">
                @if ($module->name == 'surat-keluar')
                <div class="card">
                    <p class="card-header"> <i class="fa fa-qrcode" aria-hidden></i> QR Code</p>
                   <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(300)->generate(url('sura-keluar/'.$post->keyword))) }}">
                   <div class="btn-group">
                   <a href="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(300)->generate(url('sura-keluar/'.$post->keyword))) }}" download="{{ $post->keyword }}-qr.jpg" class="btn btn-info btn-sm"> <i class="fa fa-download" aria-hidden></i> QR</a>
                    <a href="{{ url('qr_surat/'.$post->keyword) }}" download="{{ $post->keyword }}-footnote.jpg" class="btn btn-success btn-sm"> <i class="fa fa-download" aria-hidden></i> Footnote</a>
                </div>
                </div>
                <br>
                <div class="alert alert-info">
                    Untuk membubuhi QR-Code bisa dengan klik tombol <img height="20" src="data:image/png;base64,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" alt="" class="src"> setelah file surat diupload. Jika hasilnya tidak sesuai, silahkan bubuhkan manual dengan download QR atau Footnote (untuk catatan kaki) ditombol diatas
                </div>
            @endif
                @if ($module->form->thumbnail)
                    <div class="card">
                        <p class="card-header"> <i class="fa fa-image" aria-hidden></i> Gambar</p>

                        <img class="img-responsive" style="border:none" id="thumb" src="{{ $post->thumbnail }}"/>
                        <input onchange="readURL(this);" accept="image/png,image/jpeg" type="file" class="form-control-file form-control-sm"
                            name="media" value="">
                        @if ($module->web->index && $module->web->detail)
                            <span style="padding:10px">
                                <textarea placeholder="Keterangan Gambar" type="text" class="form-control form-control-sm"
                                    name="media_description">{{ $post->media_description ?? '' }}</textarea>
                            </span>
                        @endif

                    </div>

                @endif

                @if ($module->web->detail || $modname = $module->name=='banner')
                    <small>Pengalihan URL {!! help('Opsi Jika Ingin Mengalihkan Konten Ini ke suatu halaman web atau url') !!} </small>
                    <input type="text" class="form-control form-control-sm" name="redirect_to"
                        placeholder="URL dimulai https:// atau http://" value="{{ $post->redirect_to ?? '' }}">
                    @if(!isset($modname))
                    <small for="">Deskripsi {!! help('Opsi deskripsi singkat tentang konten yang dapat ditelusuri oleh mesin pencarian') !!} </small>
                    <textarea placeholder="Tulis Deskripsi" type="text" class="form-control form-control-sm" name="description">{{ $post->description ?? '' }}</textarea>
                    <small for="">Kata Kunci {!! help('Kata kunci tentang konten yang dapat ditelusuri oleh mesin pencarian') !!}</small>
                    <input placeholder="Keyword1,Keyword2,Keyword3" type="text" class="form-control form-control-sm"
                        name="keyword" value="{{ $post->keyword ?? '' }}">
                        @endif
                @if ($module->form->tag)

                        <small for="">Tags {!! help('Penanda untuk memudahkan pencarian topik') !!}</small>
                        <select name="tags[]" id="select2" class="form-control form-control-sm form-control-select" multiple id="">
                            @foreach($tags as $row)
                            <option  {{ $post?->tags()->find($row->id) ? 'selected' : '' }} value="{{  $row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>

                @else
                @endif

                @endif
                @if ($module->form->category)
                @if(Auth::user()->isAdminKantor() && $post->type=='unit')
                <input type="hidden" name="category_id" value="{{ Auth::user()->unit->category_id }}">
                @else
                    <small for="">Kategori {{ $module->title }} </small><br>
                    <select class="form-control form-control-sm" name="category_id">
                        <option value=""> --pilih-- </option>
                        @foreach ($category as $row)
                            <option value="{{ $row->id }}"
                                {{ $row->id == $post->category_id ? 'selected=selected' : '' }}>{{ $row->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                    @if(Auth::user()->isAdmin())
                    <div class="text-right"><small class="text-primary">
                        <a href="{{ route($post->type . '.category') }}"> <i
                                    class="fa fa-plus" aria-hidden></i> Tambah Baru</a></small></div>
                                    @endif
                @else
                @endif
                @if ($module->web->sortable)
                <small for="">Urutan {!! help('Urutan konten yang akan ditampilkan') !!}</small>
                <select class="form-control form-control-sm" name="sort">
                    @php $count = query()->whereType(get_post_type())->count();@endphp
                    @for ($i=1; $i<=$count; $i++)
                        <option value="{{ $i }}"  {{ $post->sort ==$i ? 'selected=selected' : '' }}>{{ $i }}
                        </option>
                    @endfor
                </select>
                <div class="mb-2"></div>

                @else
                <div class="mb-2"></div>

                @endif

                @if ($module->web->detail)

                    <div @if (!$module->web->detail) ) style="margin-top:10px" @endif class="animated-checkbox">
                        <label>
                            <input type="checkbox" {{ $post && $post->allow_comment == 'Y' ? 'checked=checked' : '' }}
                                name="allow_comment" value="Y"><span class="label-text"><small>Izinkan Komentar
                                    {!! help('Jika dicentang, maka pengunjung bisa mengirim komentar pada postingan ini') !!}</small></span>
                        </label>
                    </div>
                @endif
                @if($module->name=='unit' && !Auth::user()->isAdminKantor())
                    <div class="animated-checkbox">
                        <label>
                            <input {{ $post && $post->pinned == 'Y' ? 'checked=checked' : '' }} type="checkbox"
                                name="pinned" value="Y"><span class="label-text"><small>Unit Utama
                                   </small></span>
                        </label>
                    </div>
                    @endif
                <div class="form-group form-inline">
                    <div class="animated-radio-button">
                        <label>
                            <input {{ $post && $post->status == 'publish' ? 'checked=checked' : '' }} required
                                type="radio" name="status" value="publish"><small
                                class="label-text">Publikasikan</small>
                        </label>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class="animated-radio-button">
                        <label>
                            <input {{ $post && $post->status == 'draft' ? 'checked=checked' : '' }} required type="radio"
                                name="status" value="draft"><small class="label-text">Draft</small>
                        </label>
                    </div>
                </div>
                <button data-toggle="tooltip"   class="btn btn-md btn-primary w-100 add" @if(Auth::user()->id == $post->user_id ||( Auth::user()->isAdminKantor() && $post->type=='unit')) type="submit" title="Simpan Perubahan" @else disabled title="Anda Tidak Memiliki Akses" @endif>SIMPAN</button><br><br>
            </>
        </div>
    </form>
    @if ($post->mime != 'html' && $post->type!='docs' && $module->form->editor)
        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        @endpush
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        @endpush
        @include('cms::backend.layout.summernote')
    @endif
    @include('cms::backend.layout.js')

@endsection
