<?php
return [
    'menu' => [
        'surat-keluar' => [
            'position' => 3,
            'name' => 'surat-keluar',
            'title' => 'Surat Keluar',
            'description' => 'Menu Untuk Mengelola Surat Keluar',
            'parent' => false,
            'icon' => 'fa-envelope-open',
            'route' => ['index','create','show','update','delete'],
            'datatable'=>[
                'custom_column' => 'Instansi',
                'data_title' => 'NOMOR SURAT',
            ],
            'form'=>[
                'unique_title' => true,
                'post_parent' => ['Referensi Surat (opsional)','surat-masuk'],
                'thumbnail' => false,
                'editor' => false,
                'category' => true,
                'tag' => false,
                'looping_name'=>'Arsip',
                'looping_data' => false,
                'custom_field' => array(
                    ['File Surat','file','required'],
                    ['Jenis File',['Surat Keluar','Surat Tugas','Nota Dinas','Undangan','Surat Edaran','Telaah Staf']],
                    ['Perihal','text','required'],
                    ['Instansi','text','required'],
                    ['Alamat','text','required'],
                    ['Penandatangan','penandatangan','required'],
                    ['Instansi Tujuan','instansi_tujuan','required'],
                    ['Diterbitkan','date','required'],
                    )
            ],
            'web'=>[
                'api' => true,
                'archive' => true,
                'index' => false,
                'detail' => false,
                'history' => true,
                'auto_query' => true,
                'sortable'=>false,
            ],
            'public' => false,
            'cache' => false,
            'active' => true,
        ],
        'surat-masuk' => [
            'position' => 3,
            'name' => 'surat-masuk',
            'title' => 'Surat Masuk',
            'description' => 'Menu Untuk Mengelola Surat Masuk',
            'parent' => false,
            'icon' => 'fa-envelope',
            'route' => ['index','create','show','update','delete'],
            'datatable'=>[
                'custom_column' => false,
                'data_title' => 'NOMOR SURAT',
            ],
            'form'=>[
                'unique_title' => true,
                'post_parent' => false,
                'thumbnail' => false,
                'editor' => false,
                'category' => false,
                'tag' => false,
                'looping_name'=>'Arsip',
                'looping_data' => false,
                'custom_field' => array(
                    ['File Surat','file','required'],
                    ['Instansi Pengirim','text','required'],
                    ['Tanggal Surat','date','required'],
                    ['Hal','text','required'],
                    ['Tanggal Diterima','date','required'],
                    ['Butuh Dibalas',['Ya','Tidak'],'required'],

                    )
            ],
            'web'=>[
                'api' => true,
                'archive' => true,
                'index' => false,
                'detail' => false,
                'history' => true,
                'auto_query' => true,
                'sortable'=>false,
            ],
            'public' => false,
            'cache' => false,
            'active' => true,
        ],
        'unit' => [
            'position' => 3,
            'name' => 'unit',
            'title' => 'Unit',
            'description' => 'Menu Untuk Mengelola Perangkat Daerah',
            'parent' => false,
            'icon' => 'fa-building',
            'route' => ['index','create','show','update','delete'],
            'datatable'=>[
                'custom_column' => false,
                'data_title' => 'Nama Unit',
            ],
            'form'=>[
                'unique_title' => false,
                'post_parent' => ['Unit Induk','unit'],
                'thumbnail' => false,
                'editor' => false,
                'category' => true,
                'tag' => false,
                'looping_name'=>'Pejabat Penandatangan di Unit ini',
                'looping_data' => array(
                    ['Nama','text'],
                    ['Jabatan','text'],
                    ),
                'custom_field' => array(
                    ['Alamat','text'],
                    ['Email','text'],
                    )
            ],
            'web'=>[
                'api' => true,
                'archive' => true,
                'index' => false,
                'detail' => false,
                'history' => true,
                'auto_query' => true,
                'sortable'=>false,
            ],
            'public' => false,
            'cache' => false,
            'active' => true,
        ]
        ],
        'config'=> [
            'web_type'=> null,
            'option'=> array(),
        ],
        'option'=> array(),
        'option_cached'=> null,
        'used'=> array(),
        'current'=> null,
        'extension_module'=> array(),
        'detail_visited'=> false,
        'data'=> null,
        'domain'=>null,
        'installed'=>env('APP_INSTALLED',false),
        'public_path'=>env('PUBLIC_PATH',null),
        'version'=>null,
];
