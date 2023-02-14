<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Process extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation');
        $this->load->helper(['login', 'error', 'user', 'format', 'enkrip', 'image']);
        $this->load->model('Blanko_model', 'blankos');
        $this->office = get_user_office($this->session->userdata('id'));
    }

    public function index()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    public function used($param = null)
    {
        if ($param === null) {
            $this->_list_view();
        } else {
            $blanko_data = $this->blankos->get_one($param);
            if (
                !empty($blanko_data) &&
                $blanko_data['id_status'] == '1' &&
                $this->office->id == $blanko_data['id_office']
            ) {
                $this->form_validation->set_rules('nilai', 'Nilai Jaminan', 'required');
                $this->form_validation->set_rules('jaminan_num', 'Nomor Jaminan', 'required');
                if ($this->form_validation->run() === false) {
                    $this->_used_view($blanko_data);
                } else {
                    $data = array(
                        'id' => $blanko_data['id'],
                        'enkrip' => $param
                    );
                    $this->_used_process($data);
                }
            } else {
                $this->_errors();
            }
        }
    }

    private function _list_view()
    {
        if (is_login()) {
            $data['title'] = 'Blanko Tersedia';
            $data['bread'] = 'Blanko List,blanko|Tersedia';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['jscript'] = 'blanko/blanko.list.min';
            $data['head_name'] = 'Tersedia';
            $this->layout->variable($data);
            $this->layout->content('info/blanko_use');
            $this->layout->row(array([
                'blanko_list/filter',
                'revision/use'
            ], 'blanko_list/list'));
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    private function _used_view($blanko)
    {
        if (is_login()) {
            $data['title'] = 'Gunakan Blanko';
            $data['bread'] = 'Blanko List,blanko|Gunakan';
            $data['plugin'] = 'basic|fontawesome|scrollbar|fileinput|dateinput';
            $data['blanko'] = $blanko;
            $data['jscript'] = 'process/used.min';
            $this->layout->variable($data);
            $this->layout->content(array(
                'blanko_use/header',
                'blanko_use/form'
            ));
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    public function crash($id = null)
    {
        if ($id === null) {
            $this->_list_for_crash();
        } else {
            $blanko_data = $this->blankos->get_one($id);
            if (
                !empty($blanko_data) &&
                intval($blanko_data['id_status']) < 3 &&
                $this->office->id == $blanko_data['id_office']
            ) {
                $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
                if ($this->form_validation->run() === false) {
                    $this->_crash_view($blanko_data);
                } else {
                    $this->_crash_process($id);
                }
            } else {
                $this->_errors();
            }
        }
    }

    private function _crash_view($blanko)
    {
        if (is_login()) {
            $data['title'] = 'Lapor Kerusakan';
            $data['bread'] = 'Blanko List,blanko|Pencarian,process/crash|Lapor Rusak';
            $data['plugin'] = 'basic|fontawesome|scrollbar|fileinput';
            $data['blanko'] = $blanko;
            $this->layout->variable($data);
            $this->layout->content('blanko/crash');
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    public function crashes($param = null)
    {
        if ($param === null) {
            $this->_list_for_crash();
        } else {
            $blankodata = $this->blankos->get_one($param);
            if (!empty($blankodata) && $this->office->id == $blankodata['id_office']) {
                if ($blankodata['id_status'] == '1') {
                    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
                    if ($this->form_validation->run() === false) {
                        $this->_crashes_view($blankodata);
                    } else {
                        // $this->_crash_process($param);
                        echo 'Crashes Process';

                        var_dump($this->input->post());
                    }
                } else {
                    redirect('process/crash/' . $param);
                }
            } else {
                $this->_errors();
            }
        }
    }

    private function _crashes_view($blanko)
    {
        if (is_login()) {
            $data['title'] = 'Lapor Kerusakan';
            $data['bread'] = 'Blanko List,blanko|Pencarian,process/crash|Lapor Rusak';
            $data['plugin'] = 'basic|fontawesome|scrollbar|dateinput|fileinput';
            $data['blanko'] = $blanko;
            $data['jscript'] = 'process/used.min|process/crashes';
            $this->layout->variable($data);
            $this->layout->content('blanko/crashes');
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    private function _list_for_crash()
    {
        if (is_login()) {
            $data['title'] = 'Cari Blanko';
            $data['bread'] = 'Blanko List,blanko|Pencarian';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['jscript'] = 'blanko.search';
            $this->layout->variable($data);
            $this->layout->content('blanko/crash_list');
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    private function _used_process($params = [])
    {
        // $jaminan_data = array(
        //     'id' => date('ymdHis') . mt_rand(1000, 9999),
        //     'id_tipe' => $this->input->post('jenis'),
        //     'nomor' => trim($this->input->post('jaminan_num')),
        //     'id_principal' => str_replace('NUM', '', $this->input->post('principal')),
        //     'id_obligee' => str_replace('NUM', '', $this->input->post('obligee')),
        //     'id_currency' => $this->input->post('currency'),
        //     'nilai' => str_replace('.', '', $this->input->post('nilai')),
        //     'kontrak' => trim($this->input->post('contract')),
        //     'pekerjaan' => trim($this->input->post('pekerjaan')),
        //     'apply_date' => $this->input->post('tanggal_from'),
        //     'end_date' => $this->input->post('tanggal_to'),
        //     'apply_days' => $this->input->post('days')
        // );
        // $jaminan_data['enkripsi'] = self_md5($jaminan_data['id']);
        // $use_data = array(
        //     'id' => date('ymdHis') . mt_rand(1000, 9999),
        //     'id_blanko' => $params['id'],
        //     'id_jaminan' => $jaminan_data['id'],
        //     'id_user' => get_user_id($this->session->userdata('id'))
        // );
        // $use_data['enkripsi'] = self_md5($use_data['id']);
        // $dimension = array(
        //     'width' => intval($this->input->post('inp_width')),
        //     'height' => intval($this->input->post('inp_height'))
        // );
        // if (check_upload_file('image_upload')) $use_data['image'] = $this->_upload($dimension, 'blanko_use');

        // $new_principal = $this->input->post('principal_input');
        // if ($new_principal != '') $jaminan_data['id_principal'] = $this->_new_data($new_principal, 'principal');
        // $new_obligee = $this->input->post('obligee_input');
        // if ($new_obligee != '') $jaminan_data['id_obligee'] = $this->_new_data($new_obligee, 'obligee');

        $jaminan_data = $this->_data_jaminan();
        $use_data = $this->_data_used($params['id'], $jaminan_data['id']);
        $update_blanko = array('id_status' => 2);
        foreach ($jaminan_data as $k => $v) if ($v == '') unset($jaminan_data[$k]);
        foreach ($use_data as $k => $v) if ($v == '') unset($use_data[$k]);
        $result_jaminan = $this->db->insert('jaminan', $jaminan_data);
        $result_use = $this->db->insert('blanko_used', $use_data);
        $result_update = $this->db->update('blanko', $update_blanko, array('id' => $use_data['id_blanko']));
        if ($result_jaminan && $result_use && $result_update) {
            $flash_message = array('status' => 'success');
            $this->session->set_flashdata('flash_message', $flash_message);
            redirect('blanko/detail/' . $params['enkrip']);
        } else {
            $flash_message = array('status' => 'danger');
            $this->session->set_flashdata('flash_message', $flash_message);
            redirect('blanko/detail/' . $params['enkrip']);
        }
    }

    private function _crash_process($enkripsi)
    {
        // $crashdata = array(
        //     'id' => date('ymdHis') . mt_rand(1000, 9999),
        //     'id_blanko' => $this->input->post('blanko_id'),
        //     'keterangan' => $this->input->post('keterangan'),
        //     'id_user' => get_user_id($this->session->userdata('id'))
        // );
        // $crashdata['enkripsi'] = self_md5($crashdata['id']);
        // $dimension = array(
        //     'width' => intval($this->input->post('inp_width')),
        //     'height' => intval($this->input->post('inp_height'))
        // );
        // if (check_upload_file('image_upload')) $crashdata['image'] = $this->_upload($dimension, 'blanko_crash');

        $crashdata = $this->_data_crash();
        foreach ($crashdata as $k => $v) if ($v == '') unset($usedata[$k]);
        $result_insert = $this->db->insert('blanko_crash', $crashdata);
        $result_update = $this->db->update(
            'blanko',
            array('id_status' => 3),
            array('id' => $crashdata['id_blanko'])
        );
        if ($result_insert && $result_update) {
            $flash_message = array('status' => 'success');
            $this->session->set_flashdata('flash_message', $flash_message);
            redirect('blanko/detail/' . $enkripsi);
        } else {
            $flash_message = array('status' => 'danger');
            $this->session->set_flashdata('flash_message', $flash_message);
            redirect('blanko/detail/' . $enkripsi);
        }
    }

    private function _crashes_process()
    {
    }

    private function _new_data($name, $table)
    {
        $result = '';
        $office = get_user_office($this->session->userdata('id'));
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'nama' => strtoupper(trim($name)),
            'id_office' => $office->id,
            'visible' => 1
        );
        if ($this->db->insert($table, $data)) $result = $data['id'];
        return $result;
    }

    private function _upload($dimension, $folder = 'temp')
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
                $rotate_img = ($dimension['height'] == $upload_data['image_width'] &&
                    $dimension['width'] == $upload_data['image_height']);
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

    private function _errors()
    {
        if (is_login()) {
            custom_404_admin();
        } else {
            redirect();
        }
    }

    private function _data_used($blanko, $jaminan, $upload = true)
    {
        $dimension = $this->_data_dimension();
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'id_blanko' => $blanko,
            'id_jaminan' => $jaminan,
            'id_user' => get_user_id($this->session->userdata('id'))
        );
        $data['enkripsi'] = self_md5($data['id']);
        if (check_upload_file('image_upload') && $upload)
            $data['image'] = $this->_upload($dimension, 'blanko_use');
        return $data;
    }

    private function _data_crash($upload = true)
    {
        $dimension = $this->_data_dimension();
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'id_blanko' => $this->input->post('blanko_id'),
            'keterangan' => $this->input->post('keterangan'),
            'id_user' => get_user_id($this->session->userdata('id'))
        );
        $data['enkripsi'] = self_md5($data['id']);
        if (check_upload_file('image_upload') && $upload)
            $data['image'] = $this->_upload($dimension, 'blanko_crash');
        return $data;
    }

    private function _data_jaminan()
    {
        $data = array(
            'id' => date('ymdHis') . mt_rand(1000, 9999),
            'id_tipe' => $this->input->post('jenis'),
            'nomor' => trim($this->input->post('jaminan_num')),
            'id_principal' => str_replace('NUM', '', $this->input->post('principal')),
            'id_obligee' => str_replace('NUM', '', $this->input->post('obligee')),
            'id_currency' => $this->input->post('currency'),
            'nilai' => str_replace('.', '', $this->input->post('nilai')),
            'kontrak' => trim($this->input->post('contract')),
            'pekerjaan' => trim($this->input->post('pekerjaan')),
            'apply_date' => $this->input->post('tanggal_from'),
            'end_date' => $this->input->post('tanggal_to'),
            'apply_days' => $this->input->post('days')
        );
        $data['enkripsi'] = self_md5($data['id']);
        $new_principal = $this->input->post('principal_input');
        $new_obligee = $this->input->post('obligee_input');
        if ($new_principal != '') $data['id_principal'] = $this->_new_data($new_principal, 'principal');
        if ($new_obligee != '') $data['id_obligee'] = $this->_new_data($new_obligee, 'obligee');
        return $data;
    }

    private function _data_dimension()
    {
        $data = array(
            'width' => intval($this->input->post('inp_width')),
            'height' => intval($this->input->post('inp_height'))
        );
        return $data;
    }
}
