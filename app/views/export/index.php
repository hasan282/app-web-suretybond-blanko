<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>PDF Export - Tanda Terima</title>
    <style>
        #doc-target {
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #000;
            line-height: 1.6em;
            margin: 0 auto;
        }

        #outer {
            padding: 72px 0pt 72pt 0px;
            border: 1px solid #000;
            margin: 0 auto;
            width: 582px;
        }
    </style>
</head>

<body>
    <div id="container">
        <p class="mt-3 text-center">
            <button class="btn btn-outline-primary" onclick="generatePdf()">Buat PDF</button>
        </p>
        <div id="outer">
            <div id="doc-target">
                <div class="row">
                    <div class="col ml-3">
                        <img src="<?= base_url('asset/img/icon/jis_logo.jpg'); ?>" alt="" height="75px" width="75px">
                    </div>
                    <div class="col text-right">
                        <p class="my-0 mr-3" style="font-size: 10px;">PT. JASMINE INDAH SERVISTAMA</p>
                        <p class="my-0 mr-3" style="font-size: 10px;">Email: rochman.jis@gmail.com</p>
                        <p class="my-0 mr-3" style="font-size: 10px;">jasmine.surety@gmail.com</p>
                    </div>
                </div>
                <div class="my-3 text-center">
                    <h5 class="text-uppercase" style="font-size: 10px;">
                        <strong>TANDA TERIMA BLANKO</strong>
                    </h5>
                    <h5 class="text-uppercase" style="font-size: 10px;">
                        <strong>PT. ASURANSI MAXIMUS</strong>
                    </h5>
                    <h5 style="font-size: 10px;">
                        <strong>UP : Bpk. Amiruddin</strong>
                    </h5>
                </div>
                <div class="row justify-content-center align-content-center">
                    <?php
                    $max_number = 20;
                    $count_data = sizeof($datasent);
                    $number_first = 0;
                    $number_last = $max_number - 1;
                    $cols = ceil($count_data / $max_number);
                    for ($i = 0; $i < $cols; $i++) : ?>
                        <div class="col-lg-3">
                            <table class="table table-bordered table-sm" style="font-size: 10px;">
                                <thead>
                                    <tr style="background-color: yellow;">
                                        <th scope="col">No</th>
                                        <th scope="col">No. Blanko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($u = $number_first; $u <= $number_last; $u++) : ?>
                                        <tr>
                                            <td>
                                                <?= $u + 1 ?>
                                            </td>
                                            <td>
                                                <?= $datasent[$u]["fullnumber"] ?>
                                            </td>
                                        </tr>
                                    <?php endfor;
                                    $number_first += $max_number;
                                    $number_last += $max_number;
                                    if ($number_last > $count_data - 1) {
                                        $number_last = $count_data - 1;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h5 style="font-size: 10px;"> <strong>Dikirim Oleh : </strong> </h5>
                        <h5 style="font-size: 10px; height: 50px;"> <strong>Nama :</strong> </h5>
                        <h5 style="font-size: 10px;"> <strong>Tanggal : </strong> </h5>
                    </div>
                    <div class="col-lg-4">
                        <h5 style="font-size: 10px;"> <strong>Diterima Oleh : </strong> </h5>
                        <h5 style="font-size: 10px; height: 50px;"> <strong>Nama :</strong> </h5>
                        <h5 style="font-size: 10px;"> <strong>Tanggal : </strong> </h5>
                    </div>
                    <div class="col-lg-4">
                        <h5 style="font-size: 10px;"> <strong>NOTE : </strong> </h5>
                        <h5 style="font-size: 10px;"> <strong>TTD ATAS NAMA : RICKY FIRMANSYAH</strong> </h5>
                        <h5 style="font-size: 10px;"> <strong>JABATAN : Branch Manager</strong> </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // https://html2canvas.hertzen.com/configuration
        // https://rawgit.com/MrRio/jsPDF/master/docs/module-html.html#~html
        // https://artskydj.github.io/jsPDF/docs/jsPDF.html
        window.jsPDF = window.jspdf.jsPDF;

        function generatePdf() {
            let jsPdf = new jsPDF('p', 'pt', 'letter');
            var htmlElement = document.getElementById('doc-target');
            // you need to load html2canvas (and dompurify if you pass a string to html)
            const opt = {
                callback: function(jsPdf) {
                    jsPdf.output('dataurlnewwindow')
                    // to open the generated PDF in browser window
                    // window.open(jsPdf.output('bloburl'));
                },
                margin: [72, 72, 72, 72],
                autoPaging: 'text',
                html2canvas: {
                    allowTaint: true,
                    dpi: 300,
                    letterRendering: true,
                    logging: false,
                    scale: .8
                },
                // x: 10,
                // y: 10,
                width: 216, //target width in the PDF document
                // windowWidth: 675 //window width in CSS pixels
            };

            jsPdf.html(htmlElement, opt);
        }
    </script>
</body>

</html>