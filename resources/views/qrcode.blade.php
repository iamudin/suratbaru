<div style="padding:0 0 0 5px;margin-top:-20px">
    <div class="description " style="float:left;margin-right:45px">
        <h3 style="border-bottom:2px solid #000;font-family: Arial, Helvetica, sans-serif">Catatan</h3>
        <ul style="font-size:18px;padding-left:20px;margin-top:-9px;font-family: Arial, Helvetica, sans-serif">
            <li>UU ITE Nomor 11 Tahun 2008 Pasal 5 Ayat (1);<br>"Informasi Elektronik dan/atau Dokumen Elektroni dan/atau hasil cetaknya merupakan alat bukti hukkum yang sah"</li>
            <li>Dokumen ini telah ditandatangi secara elektronik menggunakan sertifkat elektronik yang diterbitkan di BSrE;</li>
            <li>Surat ini dapat dibuktikan keasliaanya di https://e-surat.bengkaliskab.go.id dengan scan Qr-Code.</li>
        </ul>
    </div>
    <div class="qrcode" style="padding-top:30px" >

        {!! QrCode::size(110)->generate($url) !!}
    </div>
</div>
