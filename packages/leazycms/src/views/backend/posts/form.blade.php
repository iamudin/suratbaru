@if(get_post_type()!='surat-keluar' && get_post_type()!='surat-masuk')
@include('cms::backend.posts.form-d')
@else
@include('cms::backend.posts.form-default')
@endif
