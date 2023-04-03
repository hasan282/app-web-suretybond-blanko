<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PDF Export - Tanda Terima</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        :root {
            --padding: 10px 10px 10px 10px;
            --height:
        }

        #doc-target {
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #000;
            line-height: 1em;
            margin: 0 auto;
        }

        #outer {
            padding: var(--padding);
            border: 1px solid #000;
            margin: 0 auto;
            width: 785px;
            /* height: 115px; */
        }

        .judul {
            /* font-family: "Times New Roman", Times, serif; */
        }

        .header table {
            font-size: 13px;
        }

        .body table {
            font-size: 13px;
            /* font-family: 'Open Sans', sans-serif; */
        }

        .table th,
        .table td {
            padding: 0.25rem !important;
        }

        .footer table {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div id="container">
        <p class="mt-3 text-center">
            <button class="btn btn-outline-primary" onclick="generatePdf()">Buat PDF</button>
        </p>
        <div class="container w-50">

            <!-- <div class="form-group">
                <label for="exampleInputEmail1">Input Note Title:</label>
                <input type="text" class="form-control" id="input_note_title" aria-describedby="emailHelp" placeholder="ex : UP : ....">
            </div>
            <button id="submit1" class="btn btn-primary mb-3">Submit</button>

            <div class="form-group">
                <label for="input_note_footer">Input Note Footer:</label>
                <textarea class="form-control" id="input_note_footer" rows="3"></textarea>
            </div>
            <button id="submit2" class="btn btn-primary mb-3">Submit</button> -->

            <div class="row">
                <div class="col-sm-6">
                    <h5>Ubah Menjadi Ukuran A4 :</h5>
                    <button onclick="a4()" class="btn btn-primary mb-3">Ubah</button>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Input Height nya</label>
                        <input type="number" class="form-control" id="inputHeight">
                    </div>
                    <button id="submit3" class="btn btn-primary mb-3">Submit</button>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Input Margin Yang Kiri</label>
                        <input type="number" class="form-control" id="inputMarginKiri">
                    </div>
                    <button id="submit_margin_kiri" class="btn btn-primary mb-3">Submit</button>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Input Margin Yang Tengah</label>
                        <input type="number" class="form-control" id="inputMarginTengah">
                    </div>
                    <button id="submit_margin_tengah" class="btn btn-primary mb-3">Submit</button>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Input Margin Yang Kanan</label>
                        <input type="number" class="form-control" id="inputMarginKanan">
                    </div>
                    <button id="submit_margin_kanan" class="btn btn-primary mb-3">Submit</button>
                </div>
            </div>
        </div>
        <div id="outer">
            <div id="doc-target">
                <div class="container">
                    <div class="header">
                        <h4 class="text-center text-uppercase mb-5 judul text-primary">Jaminan Pemeliharaan</h4>
                        <table class="table table-borderless">
                            <tr>
                                <th width="1%"></th>
                                <td width="25%">Nomor Jaminan: <b>22.08.02.1106.023489.DRAFT</b></td>
                                <td width="25%" class="text-right">Nilai: <b>Rp. 22,618,081.00</b></td>
                            </tr>
                        </table>
                    </div>
                    <div class="body">
                        <table class="table table-borderless">
                            <tbody class="text-justify">
                                <tr>
                                    <th scope="row">1.</th>
                                    <td>Dengan ini dinyatakan, bahwa kami : <strong>PT. FIBERHOME TECHNOLOGIES INDONESIA</strong>, APL Tower Lantai 30, Jl. Jend. S. Parman Kav. 28, Jakarta Barat 11470 sebagai Penyedia, selanjutnya disebut <strong>PRINCIPAL</strong>, dan <strong>PT. ASURANSI MAXIMUS GRAHA PERSADA Tbk, Kantor Cabang Bogor</strong>, Ruko VIP No. 88 B Jl. Raya Pajajaran, Bogor 16128 sebagai <strong>PENJAMIN</strong>, selanjutnya disebut sebagai <strong>SURETY</strong>, bertanggung jawab dan dengan tegas terikat pada <strong>PT. EKA MAS REPUBLIK</strong>, SML Plaza, Tower 2, Lt.25, Jl. MH Thamrin No.51 Jakarta Pusat sebagai pemilik pekerjaan, selanjutnya disebut <strong>OBLIGEE</strong> atas uang sejumlah <strong>Rp. 22,618,081.00 <i>(Terbilang : Dua Puluh Dua Juta Enam Ratus Delapan Belas Ribu Delapan Puluh Satu Rupiah)</i></strong> </td>
                                </tr>
                                <tr>
                                    <th scope="row">2.</th>
                                    <td>Maka kami, <strong>PRINCIPAL</strong> dan <strong>SURETY</strong> dengan ini mengikatkan diri untuk melakukan pembayaran jumlah tersebut di atas dengan baik dan benar bilamana <strong>PRINCIPAL</strong> tidak memenuhi kewajiban dalam melaksanakan <b>PEKERJAAN ROLL OUT NEW – BOGOR</b> yang telah dipercayakan kepadanya atas dasar <strong><i>FINAL ACCEPTANCE CERTIFICATE (FAC) No.: BGR002771/RETENTION/09/2022/7400004436, BGR002762/RETENTION/09/2022/7400004889</i></strong> tanggal<strong> 28 September 2022</strong></td>
                                </tr>
                                <tr>
                                    <th scope="row">3.</th>
                                    <td>Surat Jaminan ini berlaku selama <strong>365 (Tiga Ratus Enam Puluh Lima)</strong> hari kalender dan efektif mulai tanggal <strong> 28 September 2022</strong> sampai dengan tanggal <strong> 28 September 2022</strong></td>
                                </tr>
                                <tr>
                                    <th scope="row">4.</th>
                                    <td>Jaminan ini berlaku apabila : <br>
                                        <strong>PRINCIPAL </strong> tidak memenuhi kewajibannya melakukan pemeliharaan sebagaimana ditentukan dalam Dokumen Kontrak.
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">5.</th>
                                    <td><strong>SURETY </strong> akan membayar kepada <strong>OBLIGEE </strong> sejumlah nilai jaminan tersebut diatas dalam waktu paling lambat 14 (empat belas) hari kerja dengan syarat <i>( Conditional )</i> setelah menerima tuntutan pencairan secara tertulis dari <strong>OBLIGEE</strong> berdasar Keputusan <strong>OBLIGEE</strong> mengenai pengenaan sanksi akibat <strong>PRINCIPAL</strong> cidera janji.</td>
                                </tr>
                                <tr>
                                    <th scope="row">6.</th>
                                    <td>Menunjuk pada Pasal 1831 KUH Perdata dengan ini ditegaskan kembali bahwa <strong>SURETY</strong> menggunakan hak-hak istimewa untuk menuntut supaya harta benda <strong>PRINCIPAL</strong> lebih dahulu disita dan dijual guna dapat melunasi hutangnya sebagaimana dimaksud dalam Pasal 1831 KUH Perdata.</td>
                                </tr>
                                <tr>
                                    <th scope="row">7.</th>
                                    <td>Tuntutan pencairan terhadap <strong>SURETY</strong> berdasarkan Jaminan ini harus sudah diajukan selambat-lambatnya dalam waktu 30 (tiga puluh) hari kalender sesudah berakhirnya masa berlaku Jaminan ini. </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="footer">
                        <table class="table table-borderless">
                            <tr>
                                <th width="1%" scope="row"></th>
                                <td width="40%">
                                    Dikeluarkan di <strong>Bogor</strong><br>
                                    Pada Tanggal <strong>18 Januari</strong>
                                </td>
                            </tr>
                        </table>
                        <div id="target3" style="height: 10vh;"></div>
                        <table class="table table-borderless text-center">
                            <tr>
                                <th></th>
                                <td>PRINCIPAL</td>
                                <td></td>
                                <td>SURETY</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th id="target4" width="2%" scope="row"></th>
                                <td width="25%"><strong>PT. FIBERHOME TECHNOLOGIES INDONESIA</strong></td>
                                <td id="target5" width="15%"></td>
                                <td width="25%"><strong>PT. ASURANSI MAXIMUS GRAHA PERSADA Tbk, KANTOR CABANG BOGOR</strong></td>
                                <td id="target6" width="2%"></td>
                            </tr>
                            <tr style="height: 10vh;">
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><b>HUANG LIANG </b><br>Direktur</td>
                                <td></td>
                                <td><b>RICKY FIRMANSYAH </b><br>Branch Manager</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js" integrity="sha512-AFwxAkWdvxRd9qhYYp1qbeRZj6/iTNmJ2GFwcxsMOzwwTaRwz2a/2TX225Ebcj3whXte1WGQb38cXE5j7ZQw3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var r = document.querySelector(':root');

        function a4() {

            r.style.setProperty('--padding', '60px 50px 30px 40px');
        }

        function a5() {

            r.style.setProperty('--padding', '10px 30px 10px 10px');
        }
        window.jsPDF = window.jspdf.jsPDF;

        function generatePdf() {
            let jsPdf = new jsPDF('p', 'pt', 'legal');
            var htmlElement = document.getElementById('doc-target');
            const opt = {
                callback: function(jsPdf) {
                    jsPdf.output('dataurlnewwindow')
                },
                margin: [100, 0, 30, 30],
                autoPaging: 'text',
                html2canvas: {
                    allowTaint: true,
                    dpi: 300,
                    letterRendering: true,
                    logging: false,
                    scale: .8
                },

                // windowWidth: 500,
            };

            jsPdf.html(htmlElement, opt);
        }

        $(document).ready(function() {

            $("#submit1").click(function() {
                var nilai = $("#input_note_title").val();
                $("#target1").html(nilai);
            })

            $("#submit2").click(function() {
                var nilai = $("#input_note_footer").val();
                $("#target2").html(nilai);
            })

            $('#submit3').click(function() {
                var nilai = $("#inputHeight").val();
                document.getElementById("target3").style.height = nilai + 'vh';
                // console.log(nilai);
            })
            $('#submit_margin_kiri').click(function() {
                var nilai = $("#inputMarginKiri").val();
                document.getElementById("target4").style.width = nilai + 'vw';
                // console.log(nilai);
            })
            $('#submit_margin_tengah').click(function() {
                var nilai = $("#inputMarginTengah").val();
                document.getElementById("target5").style.width = nilai + 'vw';
                // console.log(nilai);
            })
            $('#submit_margin_kanan').click(function() {
                var nilai = $("#inputMarginKanan").val();
                document.getElementById("target6").style.width = nilai + 'vw';
                // console.log(nilai);
            })
        });
    </script>

</body>

</html>