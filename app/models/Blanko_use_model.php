<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Blanko_use_model extends CI_Model
{
    private $office, $change;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['user', 'enkrip', 'image']);
        $this->office = get_user_office($this->session->userdata('id'));
        $this->change = array();
    }

    public function process($used, $revision_from = null)
    {
        $global_id = date('ymdHis') . mt_rand(1000, 9999);
        $used_data = array(
            'id' => $global_id,
            'enkripsi' => self_md5($global_id),
            'id_blanko' => $used,
            'id_jaminan' => $global_id,
            'id_user' => get_user_id($this->session->userdata('id'))
        );
        if (check_upload_file('image_upload')) {
            $dimension = array(
                'width' => intval($this->input->post('inp_width')),
                'height' => intval($this->input->post('inp_height'))
            );
            $used_data['image'] = $this->upload($dimension, 'blanko_use');
        }
        $used_update = array_merge(array('id_status' => 2), $this->change);
        $jaminan_data = array(
            'id' => $global_id,
            'enkripsi' => self_md5($global_id),
            'id_tipe' => $this->input->post('jenis'),
            'nomor' => $this->input->post('jaminan_num'),
            'id_principal' => str_replace('NUM', '', $this->input->post('principal')),
            'id_obligee' => str_replace('NUM', '', $this->input->post('obligee')),
            'id_currency' => $this->input->post('currency'),
            'nilai' => str_replace('.', '', $this->input->post('nilai')),
            'kontrak' => $this->input->post('contract'),
            'pekerjaan' => $this->input->post('pekerjaan'),
            'apply_date' => $this->input->post('tanggal_from'),
            'end_date' => $this->input->post('tanggal_to'),
            'apply_days' => $this->input->post('days')
        );
        $new_principal = $this->input->post('principal_input');
        if ($new_principal != '') $jaminan_data['id_principal'] = $this->_new_data($new_principal, 'principal');
        $new_obligee = $this->input->post('obligee_input');
        if ($new_obligee != '') $jaminan_data['id_obligee'] = $this->_new_data($new_obligee, 'obligee');
        foreach ($used_data as $k => $v) if ($v == '') unset($used_data[$k]);
        foreach ($jaminan_data as $k => $v) if ($v == '') unset($jaminan_data[$k]);
        $result_used = $this->db->insert('blanko_used', $used_data);
        $result_jaminan = $this->db->insert('jaminan', $jaminan_data);
        $result_update = $this->db->update('blanko', $used_update, array('id' => $used));
        $result_revisi = true;
        if ($revision_from != null) $result_revisi = $this->_revision($revision_from, $used);
        return ($result_used && $result_jaminan && $result_update && $result_revisi);
    }

    public function set_change($change)
    {
        $this->change = $change;
    }

    public function set_office($office)
    {
        $query = 'SELECT office.id AS id, nama, alamat, telpon, id_tipe, tipe FROM office INNER JOIN office_tipe ON office.id_tipe = office_tipe.id WHERE office.id = ?';
        $data = $this->db->query($query, $office)->row();
        if ($data != null) $this->office = $data;
    }

    private function _new_data($name, $table)
    {
        $result = '';
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'nama' => strtoupper($name),
            'id_office' => $this->office->id
        );
        if ($this->db->insert($table, $data)) $result = $data['id'];
        return $result;
    }

    private function _revision($from, $to)
    {
        $revisi_data = array(
            'id_from' => $from,
            'id_to' => $to
        );
        $revisi_update = array('id_status' => 4);
        $result_insert = $this->db->insert('revisi', $revisi_data);
        $result_update = $this->db->update('blanko', $revisi_update, array('id' => $from));
        return ($result_insert && $result_update);
    }

    public function upload($dimension, $folder = 'temp')
    {
        $uploaded_file = '';
        $max_pixel = 1200;
        $dir = create_directory($folder);
        $upload_config = array(
            'upload_path' => $dir['directory'] . '/',
            'allowed_types' => 'jpg|jpeg|gif|png|bmp',
            'file_name' => 'img_' . self_md5(date('Ymd_His'), '_1m4935' . mt_rand(1000, 9999))
        );
        $this->load->library('upload', $upload_config);
        if ($this->upload->do_upload('image_upload')) {
            $upload_data = $this->upload->data();
            $uploaded_file = $upload_data['file_name'];
            if ($dimension['height'] > $max_pixel || $dimension['width'] > $max_pixel) {
                $rotate_img = ($dimension['height'] == $upload_data['image_width'] && $dimension['width'] == $upload_data['image_height']);
                $uploaded_file = $this->_resize($upload_data, $max_pixel, $rotate_img);
            }
        }
        return $dir['folder'] . '/' . $uploaded_file;
    }

    private function _resize($data, $size, $rotate = false)
    {
        $rotation = '270';
        $resize_config = array(
            'source_image' => $data['full_path'],
            'new_image' => $data['file_path'] . $data['raw_name'] . '_resize' . $data['file_ext'],
            'width' => $size,
            'height' => $size
        );
        $this->load->library('image_lib', $resize_config);
        $this->image_lib->resize();
        if ($rotate) {
            $this->image_lib->clear();
            $this->image_lib->initialize(array(
                'source_image' => $resize_config['new_image'],
                'rotation_angle' => $rotation
            ));
            $this->image_lib->rotate();
        }
        unlink($resize_config['source_image']);
        return $data['raw_name'] . '_resize' . $data['file_ext'];
    }
}
