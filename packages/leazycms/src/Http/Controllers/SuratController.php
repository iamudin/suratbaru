<?php
namespace Leazycms\Web\Http\Controllers;

use Leazycms\Web\Models\Post;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Shared\Converter;
use Barryvdh\Snappy\Facades\SnappyImage;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratController extends Controller
{
  function index(Post $post,$keyword=null){
    $detail = Post::whereKeyword($keyword)->published()->first();
    abort_if(empty($detail),'404');
    return view('viewsurat',compact('detail'));
  }


  function qr_surat($keyword){
    $url = url('surat-keluar/'.$keyword);

    // Menggunakan make() untuk menghasilkan gambar dari URL
    return SnappyImage::loadView('qrcode', compact('url'))
        ->setOption('width', 1115)
        ->setOption('height', 145)
        ->setOption('quality', 90)
        ->inline();  // Tampilkan gambar secara inline

  }

  function addFooterImageToExistingDocx($surat)
{
    // Path file DOCX yang telah ada
    $filedocx = DB::table('files')->whereFileName(basename($surat->field->file_surat))->first()?->file_path;
    $templatePath = Storage::path($filedocx);
    $imageUrl = url('qr_surat/'.$surat->keyword);

    // Tentukan path sementara untuk menyimpan gambar yang diunduh
    $tempImagePath = 'qr_surat/'.$surat->keyword.'.jpg';

    // Unduh gambar dari URL dan simpan secara lokal
    File::put(Storage::path($tempImagePath), file_get_contents($imageUrl));
    // Buat instance dari TemplateProcessor
    $templateProcessor = new TemplateProcessor($templatePath);

    // Akses section terakhir dari dokumen untuk menambahkan footer
    // Di TemplateProcessor, kita tidak bisa langsung mengakses footer,
    // tetapi kita bisa memodifikasi struktur teks atau placeholder

    // Misalkan kita telah menambahkan placeholder <w:footer> di footer sebelumnya
    // Atau kita bisa melakukan ini dengan TemplateProcessor untuk menambah teks atau gambar di bawah teks tertentu

    // Sebagai contoh, jika Anda punya placeholder di file .docx, kita bisa mengeditnya:
    $templateProcessor->setValue('footer_placeholder', 'Footer ini sudah dimodifikasi.');

    // Tambahkan gambar pada tempat yang diinginkan di footer dengan placeholder
    // Gambar ini akan menggantikan placeholder di footer halaman terakhir
    $templateProcessor->setImageValue('footer_image', array(
        'path' => Storage::path($tempImagePath),
        'width' => Converter::cmToPixel(4), // Lebar gambar
        'height' => Converter::cmToPixel(2), // Tinggi gambar
        'ratio' => true
    ));
   // Simpan dokumen yang sudah dimodifikasi
   $outputDocPath = 'hasil_docx/'.$surat->keyword.'.docx';
   $templateProcessor->saveAs(Storage::path($outputDocPath));

   // Hapus file gambar sementara setelah selesai
//    Storage::delete($tempImagePath);

   return $outputDocPath;
}

function addImageToLastFooter($templatePath, $imageUrl, $outputDocPath,$keyword)
{
    // Buat instance PhpWord
    $phpWord = IOFactory::load($templatePath);

    // Unduh gambar dari URL dan simpan secara lokal
    $tempImagePath = 'qr_surat/'.$keyword.'.jpg';
    File::put(Storage::path($tempImagePath), file_get_contents($imageUrl));

    $sections = $phpWord->getSections();

    // Ambil section terakhir dari dokumen (sebelum kita membuat section baru)
    $lastSection = $sections[0];
    $lastSection->addTextBreak(4);
    list($originalWidth, $originalHeight) = getimagesize(Storage::path($tempImagePath));

    // Hitung lebar gambar maksimal dalam pixel
    $maxWidth = Converter::cmToPixel(13); // Lebar 19 cm (menyesuaikan dengan lebar halaman)

    // Hitung rasio untuk menjaga proporsi gambar
    $ratio = $originalHeight / $originalWidth;
    $height = $maxWidth * $ratio; // Hitung tinggi berdasarkan lebar 100%
    // Tambahkan gambar ke footer
    $lastSection->addImage(Storage::path($tempImagePath), array(
        'width' => $maxWidth, // Lebar gambar
        'height' => $height, // Tinggi gambar
        'ratio' => true,
        'align' => 'left' // Atur penempatan gambar
    ));

    // Simpan dokumen yang sudah dimodifikasi
    $phpWord->save(public_path($outputDocPath));

    // Hapus file gambar sementara setelah selesai
    Storage::delete($tempImagePath);

    return file_get_contents("https://view.officeapps.live.com/op/view.aspx?src=".url('hasil_docx/'.$keyword.'.docx'));
}
function generate_surat($keyword){
    abort_if(!request()->user()->isOperator(),'403','Access Limited!');
    $data = Post::onType('surat-keluar')->whereKeyword($keyword)->first();
    abort_if(empty($data),404);
    $filedocx = DB::table('files')->whereFileName(basename($data->field->file_surat))->first()?->file_path;
    $templatePath = Storage::path($filedocx);
    $imageUrl = url('qr_surat/'.$data->keyword);
    // Tentukan URL gambar
    // Tentukan path output untuk dokumen yang sudah dimodifikasi
    $outputDocPath = 'hasil_docx/'.$keyword.'.docx';
    // return $this->addFooterImageToExistingDocx($data);
    return $this->addImageToLastFooter($templatePath, $imageUrl, $outputDocPath,$keyword);
}
}
