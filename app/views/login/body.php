<a href="<?= base_url(); ?>" class="link-transparent">
    <div class="mx-auto mb-2" style="max-width:300px">
        <img class="img-fluid" src="<?= base_url('asset/img/icon/jis_suretybond.png'); ?>" alt="">
    </div>
</a>
<div class="card">
    <div class="card-body login-card-body">
        <div class="pb-3">
            <?php $flash_msg = $this->session->flashdata('message');
            if ($flash_msg) : ?>
                <p class="login-box-msg text-danger"><?= $flash_msg; ?></p>
            <?php else : ?>
                <p class="login-box-msg">Login sebagai User</p>
            <?php endif; ?>
        </div>
        <form action="<?= base_url('?log=' . $req_uri); ?>" method="POST">
            <?php $this->load->helper('cookie');
            if (is_null(get_cookie('u_id'))) : ?>
                <div class="input-group mb-4">
                    <input type="text" name="username_ptjis" id="username" value="<?= set_value('username_ptjis'); ?>" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-fw fa-user"></span>
                        </div>
                    </div>
                </div>
            <?php else :
                $user_info = get_user_info(get_cookie('u_id')); ?>
                <div class="text-center pb-3">
                    <img class="profile-image img-fluid img-circle" src="<?= base_url('asset/img/user/' . $user_info['foto']); ?>" alt="">
                    <h6 class="font-roboto text-bold mt-3 mb-1"><?= $user_info['nama']; ?></h6>
                    <a href="<?= base_url('login/other'); ?>">login ke akun lain</a>
                    <input type="hidden" name="username_ptjis" value="<?= $user_info['user']; ?>">
                </div>
            <?php endif; ?>
            <div class="input-group mb-4">
                <input type="password" name="password_ptjis" id="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text showpass" data-showpass="hide" style="cursor:pointer">
                        <span id="showpass" class="fas fa-fw fa-eye show-tooltip" title="show or hide"></span>
                    </div>
                </div>
            </div>
            <div class="p-3 mt-5 font-roboto">
                <button type="submit" id="btnlogin" class="btn btn-primary btn-block" disabled>
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span class="text-bold">Login</span>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $('.showpass').on('click', function() {
            if ($(this).data('showpass') == 'hide') {
                $('#password').attr('type', 'text');
                $('#showpass').attr('class', 'fas fa-fw fa-eye-slash');
                $(this).data('showpass', 'show');
            } else {
                $('#password').attr('type', 'password');
                $('#showpass').attr('class', 'fas fa-fw fa-eye');
                $(this).data('showpass', 'hide');
            }
        });
        $('#username').on('keyup', function() {
            const vals = $(this).val();
            $(this).val(vals.toLowerCase());
        });
        $('input').on('keyup', function() {
            if ($('#username').val() != '' && $('#password').val() != '') {
                $('#btnlogin').attr('disabled', false);
            } else {
                $('#btnlogin').attr('disabled', true);
            }
        });
    });
</script>