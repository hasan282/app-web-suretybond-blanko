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

    public function change(array $blanko, array $change = [])
    {
        $result_delete = $updates = array();
        foreach ($change as $ch) {
            $param = explode('=>', $ch);
            switch ($param[0]) {
                case 'status':
                    if (sizeof($param) === 2) $updates['id_status'] = $param[1];
                    break;
                case 'crash':
                    if (sizeof($param) === 2) array_push(
                        $result_delete,
                        $this->db->delete('blanko_crash', ['id' => $param[1]])
                    );
                    break;
                case 'used':
                    if (sizeof($param) === 2) array_push(
                        $result_delete,
                        $this->db->delete('blanko_used', ['id' => $param[1]])
                    );
                    break;
                case 'jaminan':
                    if (sizeof($param) === 2) array_push(
                        $result_delete,
                        $this->db->delete('jaminan', ['id' => $param[1]])
                    );
                    break;
                case 'produksi':
                    if (sizeof($param) === 2 && $param[1] == 'null') $updates['laprod'] = null;
                    break;
                case 'from':
                    if (sizeof($param) === 2) {
                        if ($this->db->delete('revisi', array(
                            'id_from' => $param[1],
                            'id_to' => $blanko['id']
                        ))) array_push(
                            $result_delete,
                            $this->db->update('blanko', ['id_status' => 2], ['id' => $param[1]])
                        );
                    }
                    break;
                case 'to':
                    if (sizeof($param) === 2) array_push(
                        $result_delete,
                        $this->db->delete('revisi', array('id_from' => $blanko['id'], 'id_to' => $param[1]))
                    );
                    break;
                default:
                    break;
            }
        }
        $result_change = false;
        if (!empty($result_delete) && !in_array(false, $result_delete)) {
            $result_change = $this->db->update('blanko', $updates, ['id' => $blanko['id']]);
        }
        return $result_change;
    }
}
