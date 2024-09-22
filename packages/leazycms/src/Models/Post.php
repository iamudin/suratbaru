<?php

namespace Leazycms\Web\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leazycms\FLC\Traits\Fileable;
use Leazycms\FLC\Traits\Commentable;

class Post extends Model
{
    use SoftDeletes,Fileable,Commentable;
    public $selected = ['id','description','short_content','type','category_id','user_id','title','created_at','updated_at','parent_id','media','media_description','url','slug','data_field','pinned','sort','status','mime'];

    protected $userselectcolumn = ['id','name','url','unit_id','level'];
    protected $categoryselectcolumn = ['id','name','url','slug'];
    protected $fillable = [
        'short_content','title', 'slug', 'content', 'url', 'media', 'media_description', 'keyword', 'description', 'parent_id', 'category_id', 'user_id', 'pinned', 'parent_type', 'type', 'redirect_to', 'status', 'allow_comment', 'mime', 'data_field', 'data_loop', 'created_at','sort','password','deleteable'
    ];
    protected $casts = [
        'data_field' => 'array',
        'data_loop' => 'array',
        'allow_comment' => 'string',
        'pinned' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->select($this->userselectcolumn);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class)->select($this->categoryselectcolumn);
    }

    public function childs()
    {
        return $this->hasMany(Post::class, 'parent_id', 'id')->select($this->selected);
    }
    public function child()
    {
        return $this->hasOne(Post::class, 'parent_id', 'id')->select($this->selected);
    }

    public function getThumbnailAttribute()
    {
        return $this->media ? $this->media : noimage();
    }
    public function getThumbnailTextAttribute()
    {
        return $this->media_description ? $this->media_description : null;
    }
    public function getCreatedAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i T');
    }
    public function getVisitedAttribute()
    {
        return $this->visitors_count;
    }
    public function getUpdatedAttribute()
    {
        return $this->updated_at->translatedFormat('d F Y H:i T');
    }
    public function getDateAttribute()
    {
        return $this->created_at->translatedFormat('d');
    }
    public function getYearAttribute()
    {
        return $this->updated_at->translatedFormat('Y');
    }
    public function getLinkAttribute()
    {
        return url($this->url);
    }
    public function getMonthAttribute()
    {
        return $this->updated_at->translatedFormat('F');;
    }
    public function getFieldAttribute()
    {
        return json_decode(json_encode($this->data_field));
    }
    public function getDatadAttribute()
    {
        return json_decode(json_encode($this->data_loop));
    }
    public function getThumbnailDescriptionAttribute()
    {
        return $this->media_description;
    }

    function count($type)
    {
        return $this->onType($type)->published()->count();
    }
    function scopeOnType($query,$type)
    {
        return $query->whereType($type);
    }
    function scopePublished($query)
    {
        return $query->whereStatus('publish');
    }
    function scopePinned($query)
    {
        return $query->wherePinned('Y');
    }
    function scopeWithCountVisitors($query)
    {
        return $query->withCount('visitors');
    }

    function scopeSelectedColumn($query)
    {
        return $query->select($this->selected);
    }
    function scopeLikeSlug($query,$slug)
    {
        return $query->where('slug','like',$slug.'%');
    }
    function cachedpost($type = false)
    {
        return $type ? Cache::get($type) : [];
    }
    public function categories($type)
    {
        return collect(Cache::get('category_' . $type))->sortBy('sort');
    }



    function index_limit($type, $limit)
    {
        if (get_module($type)?->cache) {
            return collect($this->cachedpost($type)->values())->take($limit);
        } else {
            return $this->withCountVisitors()
            ->selectedColumn()
            ->onType($type)
            ->published()
            ->latest('created_at')
            ->limit($limit)
            ->get();
        }
    }

    function index_category($type)
    {
        if (get_module($type)?->cache) {
            return collect($this->categories($type)->values());
        } else {
            return Category::withCountPosts()
            ->onType($type)
            ->published()
            ->orderBy('sort','ASC')
            ->get();
        }
    }
    function index_skip($type, $skip, $limit)
    {
        if (get_module($type)?->cache) {
            return collect($this->cachedpost($type)->values())->skip($skip)->take($limit);
        } else {
            return $this->selectedColumn()
            ->withCountVisitors()
            ->onType($type)
            ->published()
            ->latest()
            ->skip($skip)
            ->take($limit)
            ->get();
        }
    }
    function index_tags()
    {
        return Tag::whereStatus('publish')->whereHas('posts')->get();
    }
    function index_sort($type,$order='asc')
    {
        if (get_module($type)?->cache) {
            return $order=='asc'? collect($this->cachedpost($type)->values())->sortBy($order) : collect($this->cachedpost($type)->values())->sortByDesc($order);
        } else {
            $order = $order!='asc' ? 'desc':'asc';
            return $this->selectedColumn()->onType($type)->published()->orderBy('sort',$order)->get();
        }
    }
    function index_sort_by_parent($type,$order='asc')
    {
            $order = $order!='asc' ? 'desc':'asc';
            return $this->select('id','user_id')
            ->with('childs')
            ->onType($type)
            ->published()
            ->orderBy('sort',$order)->get();

    }
    public function index($type, $paginate = null)
    {
        $q = $this->selectedColumn()
        ->withCountVisitors()
        ->with('user', 'category')
        ->onType($type)
        ->published()
        ->latest('created_at');
        if ($paginate===null)
        return $q->get();
        return $q->paginate($paginate);

    }
    public function index_popular($type)
    {
        return $this->selectedColumn()
        ->withCountVisitors()
        ->onType($type)
        ->published()
        ->orderBy('visitors_count', 'desc')->take('5')->get();
    }

    function index_pinned($limit, $type = false)
    {
       if($type){
        return $this->selectedColumn()
        ->pinned()
        ->published()
        ->onType($type)
        ->take($limit)
        ->latest()
        ->get();
    }else{
        return $this->selectedColumn()
        ->pinned()
        ->published()
        ->take($limit)
        ->latest()
        ->get();
       }
    }
    function index_by_category($type, $slug, $limit = false)
    {
        $modul = get_module($type);
        if ($modul && $modul->cache) {
            $cek = $this->categories($type) ? collect($this->categories($type))->where('slug', $slug)->first() : null;
            return $cek && collect($cek->posts)->count() > 0 ? ($limit ? collect($cek->posts)->take($limit)->sortByDesc('created_at') : collect($cek->posts))->sortByDesc('created_at') : collect([]);
        } else {
            return $limit ? $this->selectedColumn()->with('user')
            ->whereHas('category', function ($q) use ($slug,$type) {
                $q->where('slug', $slug)->whereType($type)->whereStatus('publish');
            })->onType($type)->published()->latest('created_at')->take($limit)->get() :
            $this->selectedColumn()->with('user')->WhereHas('category', function ($q) use ($slug,$type) {
                    $q->where('slug', $slug)->whereType($type)->whereStatus('publish');
                })->onType($type)->published()->latest('created_at')
                ->paginate(get_option('post_perpage'));
        }
    }

    function index_recent($type, $except = null)
    {
        if (get_module($type)->cache) {
            return $except ? $this->cachedpost($type)->whereNotIn('id', [$except])->take(5) : $this->cachedpost($type)->take(5);
        } else {
            $query = $this->selectedColumn()->onType($type)->published();
            if($except){
                $query = $query->whereNotIn('id', [$except]);
            }
            return $query->latest('created_at')->take(5)->get();
        }
    }

    function index_child($type, $id)
    {
        if (get_module($type)->cache) {
            return $this->cachedpost($type)->where('parent_id', $id);
        } else {
            return $this->selectedColumn()
            ->onType($type)
            ->published()
            ->where('parent_id', $id)
            ->latest('created_at')
            ->get();
        }
    }
    function detail_by_title($type, $title)
    {
        return $this->whereTitle($title)->onType($type)->published()->first();
    }
    function detail($type, $name = false)
    {
        if ($name) {
            if (get_module($type)->form->category) {
                $with[] = 'category';
                $with[] = 'user';
            }
            return $this->where('type', $type)
            ->likeSlug($name)
            ->published()
            ->with($with ?? ['user'])
            ->withCountVisitors()
            ->first();

        } else {
            if (get_module($type)->cache) {
                return collect($this->cachedpost($type))->first();
            } else {
                return $this->onType($type)->published()->first();
            }
        }
    }
    function history($post_id, $currenttime)
    {
        $type = get_post_type();
        if (get_module($type)->web->history) {
            $cekpre = collect($this->cachedpost($type))->where('id', '!=', $post_id)->where('type', get_post_type())->where('created_at', '<', $currenttime)->first();
            $ceknex = collect($this->cachedpost($type))->where('id', '!=', $post_id)->where('type', get_post_type())->where('created_at', '>', $currenttime)->sortBy('created_at')->first();
            //add new change post_thumbnail to thumbnail
            return json_decode(json_encode([
                'next' => $ceknex ? ['url' => url($ceknex->url), 'title' => $ceknex->title, 'thumbnail' => $ceknex->media] : array(),
                'previous' => $cekpre ? ['url' => url($cekpre->url), 'title' => $cekpre->title, 'thumbnail' => $cekpre->media] : array(),

            ]));
        } else {
            return false;
        }
    }
}
