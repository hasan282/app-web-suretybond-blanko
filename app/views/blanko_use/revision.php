<div class="mw-600 mx-auto">
    <div class="card">
        <div class="card-body text-center">
            <p class="m-0 text-info">Revisi Pemakaian Dari Nomor Blanko
                <span class="text-bold">
                    <?= $blanko['old']['prefix'] . $blanko['old']['nomor']; ?>
                </span>
            </p>
        </div>
    </div>
</div>
<?php $jaminan = $this->db->get_where('jaminan', ['id' => $blanko['old']['jaminan']])->row();
$guarantee = array(
    'jenis' => ['val', $jaminan->id_tipe, 'change'],
    'currency' => ['val', $jaminan->id_currency, 'change'],
    'nilai' => ['val', self_number_format($jaminan->nilai, 2), 'keyup'],
    'jaminan_num' => ['val', $jaminan->nomor, 'keyup'],
    'principal' => ['val', 'NUM' . $jaminan->id_principal, 'change'],
    'obligee' => ['val', 'NUM' . $jaminan->id_obligee, 'change'],
    'contract' => ['html', $jaminan->kontrak, 'keyup'],
    'pekerjaan' => ['html', $jaminan->pekerjaan, 'keyup'],
    'val_tanggal_from' => ['val', format_date($jaminan->apply_date, 'DD2/MM1/YY2'), 'input'],
    'tanggal_from_input' => ['val', '', 'focusout'],
    'val_tanggal_to' => ['val', format_date($jaminan->end_date, 'DD2/MM1/YY2'), 'input'],
    'tanggal_to_input' => ['val', '', 'focusout'],
    'days' => ['val', $jaminan->apply_days, 'keyup']
);
foreach ($guarantee as $key => $gr) $guarantee[$key][1] = trim($gr[1]); ?>
<script>
    const jaminandata = <?= json_encode($guarantee); ?>;
</script>