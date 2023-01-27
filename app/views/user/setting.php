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
                        <a href="#" class="link-black text-bold">Ganti Foto Profil<i class="fas fa-edit ml-2"></i></a>
                    </div>
                </div>

                <div class="mw-300 mx-auto">
                    <div class="form-group">
                        <label for="real_name">Nama Lengkap</label>
                        <input name="real_name" id="real_name" class="form-control" value="<?= $this->session->userdata('nama'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="set_username">Username</label>
                        <input name="set_username" id="set_username" class="form-control" value="<?= $this->session->userdata('user'); ?>">
                        <small class="text-secondary ml-2"><i>karakter huruf kecil dan angka ( a~z 0~9 )</i></small>
                    </div>
                    <div class="border-fade py-2 text-center text-bold">
                        <button type="button" class="btn btn-link text-bold p-0" data-toggle="modal" data-target="#pwdModal">
                            <i class="fas fa-lock mr-2"></i>Ganti Password
                        </button>
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
                        <input type="text" class="form-control" value="<?= $office['nama']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Kantor</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="5"><?= $office['alamat']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="telpon_num">Nomor Telpon</label>
                        <input name="telpon_num" id="telpon_num" class="form-control" value="<?= $office['telpon']; ?>">
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button class="btn btn-primary text-bold" disabled><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pwdModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="pwdModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pwdModalLabel">Ganti Password Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('user/setting/' . self_md5('password')); ?>" method="POST">
                <div class="modal-body">
                    <div class="mx-auto mw-400 py-3">
                        <div class="form-group">
                            <label for="pass_before">Masukkan Password Sebelumnya</label>
                            <input type="password" name="pass_before" id="pass_before" class="form-control" placeholder="Password sekarang" value="">
                        </div>
                        <div class="form-group mt-5">
                            <label for="pass_new">Password Baru</label>
                            <input type="password" name="pass_new" id="pass_new" class="form-control" placeholder="Password baru" value="">
                        </div>
                        <div class="form-group">
                            <label for="pass_comfirm">Konfirmasi Password Baru</label>
                            <input type="password" name="pass_comfirm" id="pass_comfirm" class="form-control" placeholder="Konfirmasi" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary text-bold"><i class="fas fa-save mr-2"></i>Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>