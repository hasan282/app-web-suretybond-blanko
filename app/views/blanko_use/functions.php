<div class="card">
    <div class="card-body">

        <?php

        // var_dump($blanko);

        $jaminan = $this->db->get_where('jaminan', ['id' => $blanko['old']['jaminan']])->row();

        // var_dump($jaminan);

        $guarantee = array(
            'jenis' => ['val', $jaminan->id_tipe, 'change'],
            'currency' => ['val', $jaminan->id_currency, 'change'],
            'nilai' => ['val', $jaminan->nilai, 'keyup'],
            'jaminan_num' => ['val', $jaminan->nomor, 'keyup'],
            'principal' => ['val', $jaminan->id_principal, 'change'],
            'obligee' => ['val', $jaminan->id_obligee, 'change'],
            'contract' => ['html', $jaminan->kontrak, 'keyup'],
            'pekerjaan' => ['html', $jaminan->pekerjaan, 'keyup'],
            'val_tanggal_from' => ['val', $jaminan->apply_date, 'input'],
            'tanggal_from_input' => ['val', $jaminan->apply_date, 'input'],
            'val_tanggal_to' => ['val', $jaminan->end_date, 'input'],
            'tanggal_to_input' => ['val', $jaminan->end_date, 'input'],
            'days' => ['val', $jaminan->apply_days, 'keyup']
        );

        // var_dump($guarantee);

        // echo json_encode($guarantee);

        ?>

    </div>
</div>