<div class="row">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Profil User</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="user-panel d-flex py-2 mb-3 border-fade">
                    <div class="image ml-auto">
                        <img src="<?= base_url('asset/img/user/' . $this->session->userdata('foto')); ?>" class="img-circle elevation-1" alt="">
                    </div>
                    <div class="info mr-auto">
                        <a href="" class="link-black text-bold">Ganti Foto Profil<i class="fas fa-edit ml-2"></i></a>
                    </div>
                </div>
                <div class="mw-300 mx-auto">
                    <div class="form-group">
                        <label for="real_name">Nama Asli</label>
                        <input name="real_name" id="real_name" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="set_username">Username</label>
                        <input name="set_username" id="set_username" class="form-control" value="">
                        <small class="text-secondary ml-2"><i>karakter huruf kecil dan angka ( a~z 0~9 )</i></small>
                    </div>
                    <div class="border-fade py-2 text-center text-bold">
                        <a href="" class="link-black"><i class="fas fa-lock mr-2"></i>Ganti Password</a>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button class="btn btn-primary text-bold" disabled><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Profil Kantor Agen</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>

            <div class="card-body">


                <div class="mw-400 mx-auto">
                    <div class="form-group">
                        <input type="text" class="form-control" value="KANTOR AGEN" readonly>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Kantor</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="telpon_num">Nomor Telpon</label>
                        <input name="telpon_num" id="telpon_num" class="form-control" value="">
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button class="btn btn-primary text-bold" disabled><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
                </div>

            </div>
        </div>
    </div>
</div>