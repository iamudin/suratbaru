<?php
namespace Leazycms\Web\Http\Controllers;

use Leazycms\Web\Models\Post;
use App\Http\Controllers\Controller;


class SuratController extends Controller
{
  function index(Post $post,$keyword=null){
    $detail = Post::whereKeyword($keyword)->published()->first();
    abort_if(empty($detail),'404');
    return view('viewsurat',compact('detail'));
  }
}
