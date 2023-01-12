<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cancel extends CI_Controller
{
    private $numbers;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'error', 'user']);
        $this->numbers = array();
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function add($param = null)
    {
        if (special_access([1, 2])) {
            if ($param === null) {
                custom_404_admin();
            } else {
                $record = $this->db->get_where('record_add', array('enkripsi' => $param))->row();
                if ($record === null) {
                    custom_404_admin();
                } else {
                    $this->_add_cancel($record);
                }
            }
        } else {
            custom_404_admin();
        }
    }

    private function _add_cancel($record)
    {
        $office = get_user_office($this->session->userdata('id'));
        if (intval($record->number_to) - intval($record->number_from) > 0) {
            for ($int = intval($record->number_from); $int <= intval($record->number_to); $int++) {
                array_push(
                    $this->numbers,
                    str_pad($int, strlen($record->number_from), '0', STR_PAD_LEFT)
                );
            }
        }
        if ($record->numbers != null) {
            $number = explode(',', $record->numbers);
            foreach ($number as $num) array_push($this->numbers, trim($num));
        }
        $where_check = array(
            "(id_office <> '" . $office->id . "' ",
            "OR id_status <> 1) ",
            "AND nomor IN ('" . implode("', '", $this->numbers) . "') ",
            "AND id_asuransi = '" . $record->id_asuransi . "'"
        );
        $blanko_check = $this->db->query('SELECT id FROM blanko WHERE ' . implode('', $where_check))->result_array();
        if (empty($blanko_check)) {
            $this->db->where("id_asuransi = '" . $record->id_asuransi . "' AND nomor IN ('" . implode("', '", $this->numbers) . "')");
            $delete_blanko = $this->db->delete('blanko');
            $delete_record = $this->db->delete('record_add', array('id' => $record->id));
            if ($delete_blanko && $delete_record) {
                echo 'SUCCESS DELETE';
            } else {
                echo 'FAILED DELETE';
            }
        } else {
            echo 'CANNOT BE DELETE';
            var_dump($blanko_check);
        }
    }
}
