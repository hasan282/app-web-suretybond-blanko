<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h6><?= $blanko['asuransi']; ?></h6>
                <h3 class="mb-0"><span id="prefix_view"><?= $blanko['prefix']; ?></span><span class="text-bold"><?= $blanko['nomor']; ?></span></h3>
                <p class="mt-2 text-secondary mb-0">Status Blanko : <span class="text-<?= $blanko['color']; ?>"><?= $blanko['status']; ?></span></p>
                <?php if ($blanko['id_status'] == '2') :
                    $use_data = $this->db->query('SELECT principal.nama AS principal, jaminan.nomor AS jaminan, jaminan.nilai AS nilai FROM (blanko_used INNER JOIN jaminan ON blanko_used.id_jaminan = jaminan.id) INNER JOIN principal ON jaminan.id_principal = principal.id WHERE id_blanko = ?', $blanko['id'])->row(); ?>
                    <table class="mt-2 table table-bordered table-sm text-left text-secondary">
                        <tr>
                            <td>Nama Principal</td>
                            <td><?= (isset($use_data->principal)) ? $use_data->principal : '-'; ?></td>
                        </tr>
                        <tr>
                            <td>Nomor Jaminan</td>
                            <td><?= (isset($use_data->jaminan)) ? $use_data->jaminan : '-'; ?></td>
                        </tr>
                        <tr>
                            <td>Nilai Jaminan</td>
                            <td><?= (isset($use_data->nilai)) ? self_number_format($use_data->nilai) : '-'; ?></td>
                        </tr>
                    </table>
                    <div class="text-center pt-3">
                        <a href="<?= base_url('blanko/detail/' . self_md5($blanko['id'])); ?>" class="btn btn-info btn-sm"><i class="fas fa-info-circle mr-2"></i>Lihat Detail Blanko</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <form action="<?= base_url(uri_string()); ?>" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
            <div class="card">
                <div class="card-body">
                    <?php if ($blanko['id_status'] == '1') : ?>
                        <div class="text-center pb-3">
                            <a href="<?= base_url('process/crashes/' . self_md5($blanko['id'])); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-list-alt mr-2"></i>Input Bersama Data Jaminan</a>
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="blanko_id" value="<?= $blanko['id']; ?>">
                    <div class="form-group">
                        <label for="keterangan">Keterangan Blanko Rusak <small>(wajib diisi)</small></label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="2" placeholder="Keterangan"></textarea>
                    </div>
                    <div class="form-group mw-300">
                        <label for="image_upload">Bukti Kerusakan</label>
                        <div class="custom-file">
                            <label class="custom-file-label" for="image_upload">Pilih Gambar</label>
                            <input type="file" class="custom-file-input input-sm" id="image_upload" name="image_upload">
                        </div>
                        <small class="text-secondary ml-2"><i>format file .jpg .png .gif .bmp</i></small>
                    </div>
                    <input type="hidden" value="0" name="inp_width" id="inp_width">
                    <input type="hidden" value="0" name="inp_height" id="inp_height">
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary text-bold" disabled><i class="fas fa-save mr-2"></i>Simpan Laporan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="preview-cover">
                    <div class="text-center py-5 text-fade">
                        <p><i class="far fa-file-image fa-3x"></i></p>
                        <p>Image Preview</p>
                    </div>
                </div>
                <div class="preview-image zero-height">
                    <img id="img_preview" src="#" alt="" class="img-fluid">
                    <div class="text-center pt-3">
                        <p class="text-secondary"><span id="num_width">0</span> x <span id="num_height">0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('form[method="POST"]').submit(function() {
            $('button[type="submit"]').attr('disabled', true);
        });
        $('#keterangan').keyup(function() {
            const vals = $(this).val();
            $('button[type="submit"]').attr('disabled', (vals.length < 5));
        });
    });
</script>