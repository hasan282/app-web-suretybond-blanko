<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url(); ?>" class="brand-link link-transparent">
        <img src="<?= base_url(); ?>asset/img/icon/emblem_for_dark.svg" alt="" class="brand-image">
        <span class="brand-text font-poppins font-weight-light">Surety Bond</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('asset/img/user/' . $this->session->userdata('foto')); ?>" class="img-circle elevation-1" alt="">
            </div>
            <div class="info font-roboto">
                <a href="<?= base_url('user'); ?>" class="d-block"><?= $this->session->userdata('nama'); ?></a>
            </div>
        </div>
        <nav class="mt-2 font-roboto">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php $top_menu = array(
                    ['menu' => 'Dashboard', 'icon' => 'fas fa-chart-pie', 'link' => 'dashboard'],
                    ['menu' => 'Laporan', 'icon' => 'fas fa-chart-bar', 'link' => 'report']
                );
                foreach ($top_menu as $tm) : ?>
                    <li class="nav-item">
                        <a href="<?= base_url($tm['link']); ?>" class="nav-link<?= ($title == $tm['menu']) ? ' active' : ''; ?>">
                            <i class="nav-icon <?= $tm['icon']; ?>"></i>
                            <p><?= $tm['menu']; ?></p>
                        </a>
                    </li>
                <?php endforeach;
                $this->load->helper('menu');
                $ide_menu = get_menu($this->session->userdata('id'));
                $menu = array(
                    array(
                        'group' => 'MENU',
                        'menu' => $ide_menu
                    ),
                    array(
                        'group' => 'USER',
                        'menu' => array(
                            ['icon' => 'fas fa-cog', 'menu' => 'Pengaturan Akun', 'link' => 'user/setting'],
                            ['icon' => 'fas fa-sign-out-alt', 'menu' => 'Keluar', 'link' => 'user/logout']
                        )
                    )
                );
                foreach ($menu as $mn) : ?>
                    <li class="nav-header"><?= $mn['group']; ?></li>
                    <?php foreach ($mn['menu'] as $item) : ?>
                        <li class="nav-item">
                            <a href="<?= base_url($item['link']); ?>" class="nav-link<?= ($title == $item['menu']) ? ' active' : ''; ?>">
                                <i class="nav-icon <?= $item['icon']; ?>"></i>
                                <p><?= $item['menu']; ?></p>
                            </a>
                        </li>
                <?php endforeach;
                endforeach; ?>
            </ul>
        </nav>
    </div>
</aside>
<div class="content-wrapper bg-pattern">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6 font-roboto">
                    <h1 class="font-weight-light"><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <?php $breadcrumb = explode('|', (isset($bread)) ? $bread : $title);
                        foreach ($breadcrumb as $key => $val) :
                            if ($key + 1 == sizeof($breadcrumb)) : ?>
                                <li class="breadcrumb-item active"><?= $val; ?></li>
                            <?php else :
                                $item = explode(',', $val); ?>
                                <li class="breadcrumb-item"><a href="<?= base_url($item[1]); ?>"><?= $item[0]; ?></a></li>
                        <?php endif;
                        endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container pb-4">