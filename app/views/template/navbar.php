<nav class="main-header navbar navbar-expand navbar-white navbar-light font-roboto">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <?php $nav_menu = array(
            ['menu' => 'Dashboard', 'icon' => 'fas fa-chart-pie', 'link' => 'dashboard'],
            ['menu' => 'Laporan', 'icon' => 'fas fa-chart-bar', 'link' => 'report']
        );
        foreach ($nav_menu as $nm) :
            $active = '';
            if (isset($title)) $active = ($nm['menu'] == $title) ? ' active' : ''; ?>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?= base_url($nm['link']); ?>" class="nav-link<?= $active; ?>"><i class="<?= $nm['icon']; ?> mr-2"></i><?= $nm['menu']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle mr-1"></i>
                <span><?= $this->session->userdata('user'); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= $this->session->userdata('nama'); ?></span>
                <?php $drop_menu = array(
                    ['menu' => 'Akun Saya', 'icon' => 'fas fa-user-alt', 'link' => 'user'],
                    ['menu' => 'Pengaturan Akun', 'icon' => 'fas fa-cog', 'link' => 'user/setting'],
                    ['menu' => 'Keluar', 'icon' => 'fas fa-sign-out-alt', 'link' => 'user/logout']
                );
                foreach ($drop_menu as $dm) : ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url($dm['link']); ?>" class="dropdown-item mr-2"><i class="fa-fw <?= $dm['icon']; ?> mr-2"></i><?= $dm['menu']; ?></a>
                <?php endforeach; ?>
            </div>
        </li>
    </ul>
</nav>