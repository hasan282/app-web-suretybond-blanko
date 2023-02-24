<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Status_model extends SELF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function gets($enkrip = null)
    {
        $select = 'blanko.id AS id, blanko.enkripsi AS enkripsi, blanko.id_status AS status_id, blanko.laprod AS produksi, blanko_used.id AS used, blanko_used.id_jaminan AS jaminan, blanko_crash.id AS crash, revisi_from.id_from AS from_id, revisi_to.id_to AS to_id';
        $queries = 'SELECT ' . $select . ' FROM (((blanko LEFT OUTER JOIN blanko_used ON blanko.id = blanko_used.id_blanko) ';
        $queries .= 'LEFT OUTER JOIN blanko_crash ON blanko.id = blanko_crash.id_blanko) ';
        $queries .= 'LEFT OUTER JOIN (SELECT enkripsi, prefix, nomor, id_from, id_to FROM blanko INNER JOIN revisi ON blanko.id = revisi.id_from) AS revisi_from ON blanko.id = revisi_from.id_to) ';
        $queries .= 'LEFT OUTER JOIN (SELECT enkripsi, prefix, nomor, id_from, id_to FROM blanko INNER JOIN revisi ON blanko.id = revisi.id_to) AS revisi_to ON blanko.id = revisi_to.id_from';
        if ($enkrip !== null) {
            $queries .= ' WHERE blanko.enkripsi = ?';
            $this->binds = $enkrip;
        }
        $this->query = $queries;
        return $this;
    }
}
