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
    'nilai' => ['val', $jaminan->nilai, 'keyup'],
    'jaminan_num' => ['val', $jaminan->nomor, 'keyup'],
    'principal' => ['val', 'NUM' . $jaminan->id_principal, 'change'],
    'obligee' => ['val', 'NUM' . $jaminan->id_obligee, 'change'],
    'contract' => ['html', $jaminan->kontrak, 'keyup'],
    'pekerjaan' => ['html', $jaminan->pekerjaan, 'keyup'],
    'val_tanggal_from' => ['val', $jaminan->apply_date, 'input'],
    'tanggal_from_input' => ['val', $jaminan->apply_date, 'input'],
    'val_tanggal_to' => ['val', $jaminan->end_date, 'input'],
    'tanggal_to_input' => ['val', $jaminan->end_date, 'input'],
    'days' => ['val', $jaminan->apply_days, 'keyup']
); ?>
<script>
    const jaminandata = JSON.parse('<?= str_replace("'", "\'", json_encode($guarantee)); ?>');
</script>