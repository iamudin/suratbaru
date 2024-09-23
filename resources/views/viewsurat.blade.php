<!DOCTYPE html>
<html lang="en">

<head>

    <title>{{$detail->title.' - '.$detail->field->perihal.' - '.$detail->field->instansi}}</title>
    <link rel="shortcut icon" href="https://e-surat.bengkaliskab.go.id/favicon.png" type="image/x-icon" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="/backend/css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script>
            jQuery(document).ready(function(){
  jQuery(function() {
        jQuery(this).bind("contextmenu", function(event) {
            event.preventDefault();
            // alert('Right click disable in this site!!')
        });

    });
});
document.onkeydown = function (e) {
        return false;
}

$(document).bind('selectstart dragstart', function(e) {
e.preventDefault();
return false;
});

        </script>
</head>

<body onload="disableContextMenu();" >
    <!-- Navbar-->
    <!-- Sidebar menu-->
    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <section class="invoice container mt-5">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <img src="{{url('iconbks.png')}}" height="50" class="pull-left"> <img src="{{url('icon/bsre.png')}}" height="50"  class="pull-right">
                            <br>
                            <br>

                            <br>
                            <br>
                            <h2 class="page-header"><i class="fa fa-envelope"></i> Validasi File Surat Keluar</h2>
                            <span>Surat ini dibuat dan disahkan melalui aplikasi <a href="e-surat.bengakaliskab.go.id">e-surat.bengakaliskab.go.id</a> dengan Tanda Tangan Elektronik</span>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive-sm">
                            <table id="example" aria-describedby="example2_info"
                                class="table table-bordered  dataTable" role="grid" width="100%">
                                <tbody>
                                    <tr>
                                        <td class="td1">Jenis File</td>
                                        <td style="width: 2%;">:</td>
                                        <td>{{$detail->field->jenis_file}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Perihal</td>
                                        <td>:</td>
                                        <td>{{$detail->field->perihal}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Nomor</td>
                                        <td>:</td>
                                        <td>{{$detail->title}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Sifat</td>
                                        <td>:</td>
                                        <td>{{$detail->category->name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Penandatangan</td>
                                        <td>:</td>
                                        <td>{{$detail->field->penandatangan}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Nama Instansi</td>
                                        <td>:</td>
                                        <td>{{$detail->field->instansi}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Alamat</td>
                                        <td>:</td>
                                        <td>{{$detail->field->alamat}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td1">Diterbitkan</td>
                                        <td>:</td>
                                        <td> {{date('d F Y',strtotime($detail->field->diterbitkan))}}</td>
                                    </tr>
                                    @if(!empty($detail->field->arsipkan_surat_yang_sudah_tte))
                                    <tr>
                                        <td class="td1" colspan="3" align="center"><h3>Preview Surat</h3></td>
                                    </tr>
                                    <tr>
                                        <td class="td1" colspan="3">
                                            <iframe src="https://docs.google.com/viewer?embedded=true&url={{url($detail->field->arsipkan_surat_yang_sudah_tte)}}" style="width:100%;height:100vh;border:0;" frameborder="0"></iframe>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <footer class="footer">
                         <center>
                        <p class="text-muted" style="text-decoration:none; color: #555; text-align:center;">Development By <sup>©</sup> <a
                                style="text-decoration:none;color: #333;">Dinas Komunikasi, Informatika dan Statistik Kabupaten Bengkalis</a></p><a style="text-decoration:none;color: #333;">
                        </a>
                        </center>
                    </footer>
                </section>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="/backend/js/bootstrap.min.js"></script>

</body>

</html>
