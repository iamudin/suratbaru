<?php
/*
use Leazycms\FLC\Models\File;
use Leazycms\Web\Models\Post;
use Illuminate\Support\Facades\DB;
    use Symfony\Component\Mime\MimeTypes;
    use Illuminate\Support\Facades\Route;
function getmime($name){

$fileName = $name; // Nama file
$extension = pathinfo($fileName, PATHINFO_EXTENSION); // Ambil ekstensi
$mimeTypes = new MimeTypes();
$mimeType = $mimeTypes->getMimeTypes($extension);
return count($mimeType) ? $mimeType[0] : $mimeType ;
}
Route::get('cekuser',function(){
  $datasuratlama = DB::connection('mysql3')->table('posts')->wherePostType('surat-keluar')->whereNotNull('data_field')->get();
  $datasurat = [];
  $newuser = DB::connection('mysql2')->table('users')->pluck('id','old_id');
// Post::where('id','!=',null)->ForceDelete();
// File::where('id','!=',null)->delete();
// exit;
foreach($datasuratlama as $row){

    $field = json_decode($row->data_field,true);
    $filesurat = !empty($field['arsipkan_surat_yang_sudah_tte']) ? $field['arsipkan_surat_yang_sudah_tte'] : $field['file_surat'];
    $a['keyword'] = $row->post_id;
    $a['created_at'] = $row->created_at;
    $a['updated_at'] = $row->updated_at;
    $a['type'] = 'surat-keluar';
    $a['category_id'] = $row->post_group == '2yGexYHLRs' ? 3 : 4;
    $a['user_id'] = $newuser[$row->author] ?? null;
    $a['title'] = isset($field['nomor']) ? $field['nomor'] : null;
    $a['data_field'] = [
        'file_surat' => '/media/'.basename($filesurat),
        'penandatangan'=>$field['penandatangan'],
        'instansi'=>$field['instansi'],
        'alamat'=>$field['alamat'],
        'diterbitkan'=>$field['diterbitkan'],
        'perihal'=>$field['perihal'],
        'jenis_file'=>$field['jenis_file'],
    ];
    // Post::whereKeyword($row->post_id)->update([
    //     'data_field'=>$a['data_field']
    // ]);
    $pos = Post::create($a);
    if(!empty($filesurat)){
    $pos->files()->create([
        'file_path' => $filesurat,
        'purpose'=> 'file-surat',
        'file_type'=>getmime(basename($filesurat)),
        'host'=>'esurat.com',
        'file_name'=>basename($filesurat),
        'user_id'=>$newuser[$row->author] ?? null,
    ]);
    }

    // $datasurat[] = $a;
}
// return $datasurat;
});
*/
