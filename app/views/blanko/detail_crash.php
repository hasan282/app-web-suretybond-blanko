<div class="card">
    <div class="card-header">
        <h3 class="card-title text-bold text-danger">Blanko Rusak</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <div class="text-center">
                    <h6 class="text-bold">Bukti Kerusakan :</h6>
                </div>
                <?php if ($blanko['image_crash'] === null) : ?>
                    <div class="text-center py-2 text-fade mt-4">
                        <p><i class="far fa-file-image fa-3x"></i></p>
                        <p>Tidak ada Bukti Kerusakan</p>
                    </div>
                    <div class="text-center mb-5">
                        <?php if ($true_office) : ?>
                            <a href="" class="btn btn-primary text-bold disabled"><i class="fas fa-upload mr-2"></i>Upload Bukti Kerusakan</a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="mw-400 mx-auto mb-3">
                        <img class="img-fluid img-bordered" src="<?= base_url('asset/img/blanko_crash/' . $blanko['image_crash']); ?>" alt="">
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md">
                <div class="form-group">
                    <label for="keterangan_rusak">Keterangan Rusak</label>
                    <textarea id="keterangan_rusak" name="keterangan_rusak" class="form-control" rows="4" readonly><?= $blanko['ket_crash']; ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>