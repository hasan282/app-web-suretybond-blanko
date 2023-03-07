<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
    private $office;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Plugin_library', null, 'plugin');
        $this->load->library('form_validation', null, 'forms');
        $this->load->helper(['login', 'user', 'id', 'enkrip', 'error', 'image']);
        $this->office = (array) get_user_office($this->session->userdata('id'));
        $config = array('new_line_remove' => true);
        $this->load->library('Layout_library', $config, 'layout');
    }

    public function index()
    {
        if (is_login()) {
            $data['title'] = 'User';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $this->layout->variable($data);
            $this->layout->content('user/main');
            $this->layout->script()->print();
        } else {
            redirect(login_url());
        }
    }

    public function setting($param = null)
    {
        if (is_login()) {
            switch ($param) {
                case self_md5('office'):
                    $this->_setting_office();
                    break;
                case self_md5('password'):
                    $this->_setting_password();
                    break;
                default:
                    $this->_setting_view();
                    break;
            }
        } else {
            redirect(login_url());
        }
    }

    private function _setting_view()
    {
        $data['title'] = 'Pengaturan Akun';
        $data['bread'] = 'User,user|Pengaturan';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $data['jscript'] = 'user/setting';
        $data['office'] = $this->office;
        $this->layout->variable($data);
        $this->layout->content('user/setting');
        $this->layout->script()->print();
    }

    private function _setting_office()
    {
        $officedata = array(
            'alamat' => $this->input->post('alamat'),
            'telpon' => $this->input->post('telpon_num')
        );
        foreach ($officedata as $key => $val) if ($val == $this->office[$key]) unset($officedata[$key]);
        if (empty($officedata)) {
            $this->session->set_flashdata('text', 'Tidak Ada Data yang Diubah');
        } else {
            if ($this->db->update('office', $officedata, array('id' => $this->office['id']))) {
                $changed_keys = array_keys($officedata);
                foreach ($changed_keys as $key => $ck) $changed_keys[$key] = ucwords($ck);
                $message = 'Data ' . implode(' dan ', $changed_keys) . ' Kantor Telah Diubah';
                $this->session->set_flashdata('type', 'success');
                $this->session->set_flashdata('text', $message);
            } else {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Terjadi Kesalahan Saat Merubah Data');
            }
        }
        redirect('user/setting');
    }

    private function _setting_password()
    {
        $this->forms->set_rules('pass_before', 'Password Sebelum', 'required');
        $this->forms->set_rules('pass_new', 'Password Baru', 'required');
        $this->forms->set_rules('pass_confirm', 'Konfirmasi Password', 'required');
        if ($this->forms->run() === false) {
            redirect('user/setting');
        } else {
            $userenkrip = $this->session->userdata('id');
            $userdata = $this->db->query('SELECT id, password FROM user WHERE enkripsi = ?', $userenkrip)->row();
            $pass_before = self_md5($this->input->post('pass_before'));
            $pass_after1 = self_md5($this->input->post('pass_new'));
            $pass_after2 = self_md5($this->input->post('pass_confirm'));
            $valid_old = ($pass_before === $userdata->password);
            $valid_new = ($pass_after1 === $pass_after2);
            if (!$valid_new) {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Konfirmasi Password Baru Tidak Sesuai');
            }
            if (!$valid_old) {
                $this->session->set_flashdata('type', 'danger');
                $this->session->set_flashdata('text', 'Password Lama Tidak Sesuai');
            }
            if ($valid_new && $valid_old) {
                if ($this->db->update('user', array('password' => $pass_after1), array('id' => $userdata->id))) {
                    $this->session->set_flashdata('type', 'success');
                    $this->session->set_flashdata('text', 'Password Telah Diubah');
                } else {
                    $this->session->set_flashdata('type', 'danger');
                    $this->session->set_flashdata('text', 'Terjadi Kesalahan Saat Merubah Password');
                }
            }
            redirect('user/setting');
        }
    }

    public function manage()
    {
        if (special_access(1)) {
            $this->load->model('User_model', 'users');
            $data['title'] = 'User Manajemen';
            $data['bread'] = 'User,user|User Manajemen';
            $data['plugin'] = 'basic|fontawesome|scrollbar';
            $data['userlist'] = $this->users->get_list();
            $this->load->view('template/head', $data);
            $this->load->view('template/navbar');
            $this->load->view('template/sidebar');
            $this->load->view('user/manage');
            $this->load->view('template/footer');
            $this->load->view('template/foot');
        } else {
            custom_404_admin();
        }
    }

    public function add()
    {
        if (special_access(1)) {
            $this->forms->set_rules('in_nama', 'Nama', 'required');
            $this->forms->set_rules('in_username', 'Username', 'required|regex_match[/^[a-z0-9]*$/]');
            $this->forms->set_rules('in_role', 'Role', 'required');
            $this->forms->set_rules('in_office', 'Kantor', 'required');
            if ($this->forms->run() === false) {
                $this->_add_view();
            } else {
                $this->_add_process();
            }
        } else {
            custom_404_admin();
        }
    }

    private function _add_view()
    {
        $data['title'] = 'Tambah User Baru';
        $data['bread'] = 'User,user|Manajemen,user/manage|Tambah Baru';
        $data['plugin'] = 'basic|fontawesome|scrollbar';
        $this->load->view('template/head', $data);
        $this->load->view('template/navbar');
        $this->load->view('template/sidebar');
        $this->load->view('user/add_form');
        $this->load->view('template/footer');
        $this->load->view('template/foot');
    }

    private function _add_process()
    {
        $password = $this->db->get_where('reference', array('ref' => 'password'))->row();
        $userdata = array(
            'id' => date('ymdHis'),
            'username' => $this->input->post('in_username'),
            'password' => self_md5($password->vals),
            'nama' => $this->input->post('in_nama'),
            'photo' => $this->input->post('in_photo'),
            'id_office' => $this->input->post('in_office'),
            'id_access' => intval($this->input->post('in_role')),
            'is_active' => 1
        );
        $userdata['enkripsi'] = self_md5($userdata['id']);
        if ($this->db->insert('user', $userdata)) {
            // success
            redirect('user/manage');
        } else {
            // failed
            redirect('user/manage');
        }
    }

    public function edit()
    {
        if (is_login() && !empty($_POST)) {
            $username = $this->input->post('set_username');
            $realname = $this->input->post('real_name');
            $change_user = $username != $this->session->userdata('user');
            $change_name = $realname != $this->session->userdata('nama');
            if (
                !$change_user && !$change_name ||
                ($username == '' || $realname == '')
            ) {
                // no edit
                redirect('user/setting');
            } else {
                $dataedit = array();
                $datasession = array();
                if ($change_name) {
                    $datasession['nama'] = $realname;
                    $dataedit['nama'] = $realname;
                }
                if ($change_user) {
                    $users = $this->db->get_where('user', ['username' => $username])->num_rows();
                    if ($users === 0) {
                        $datasession['user'] = $username;
                        $dataedit['username'] = $username;
                    } else {
                        $this->session->set_flashdata('type', 'danger');
                        $this->session->set_flashdata('text', 'Username telah digunakan user lain');
                    }
                }
                if (!empty($dataedit)) {
                    $resultedit = $this->db->update('user', $dataedit, [
                        'enkripsi' => $this->session->userdata('id')
                    ]);
                    if ($resultedit) {
                        // success
                        $this->session->set_userdata($datasession);
                        $this->session->set_flashdata('type', 'success');
                        $this->session->set_flashdata('text', 'Profil User Telah Diubah');
                    } else {
                        // failed
                    }
                }
                redirect('user/setting');
            }
        } else {
            redirect('user/setting');
        }
    }

    public function photo($param = null)
    {
        if (is_login()) {
            if ($param === null) {
                $this->_photo_view();
            } else {
                switch ($param) {
                    case 'male':
                        $this->_photo_change('user_default_male.jpg');
                        break;
                    case 'female':
                        $this->_photo_change('user_default_female.jpg');
                        break;
                    case 'new':
                        $this->_photo_new();
                        break;
                    default:
                        custom_404_admin();
                        break;
                }
            }
        } else {
            redirect(login_url());
        }
    }

    private function _photo_view()
    {
        $data['title'] = 'Pengaturan Foto Profil';
        $data['bread'] = 'User,user|Pengaturan,user/setting|Foto Profil';
        $data['plugin'] = 'basic|fontawesome|scrollbar|fileinput';
        // $data['jscript'] = 'user/setting';
        // $data['office'] = $this->office;
        $this->layout->variable($data);
        $this->layout->content('user/photo');
        $this->layout->script()->print();
    }

    private function _photo_change($photo)
    {
        $result = $this->db->update(
            'user',
            ['photo' => $photo],
            ['enkripsi' => $this->session->userdata('id')]
        );
        if ($result) {
            // success
            $this->session->set_userdata(['foto' => $photo]);
            redirect('user/setting');
        } else {
            // failed
            redirect('user/setting');
        }
    }

    private function _photo_new()
    {
        if (!empty($_POST) && check_upload_file('image_upload')) {
            $dimension = array(
                'width' => intval($this->input->post('inp_width')),
                'height' => intval($this->input->post('inp_height'))
            );
            // var_dump($_POST);
            $new_photo = $this->_upload($dimension);
            if ($new_photo != '') {
                if ($this->db->update(
                    'user',
                    ['photo' => $new_photo],
                    ['enkripsi' => $this->session->userdata('id')]
                )) {
                    // success
                    $this->session->set_userdata(['foto' => $new_photo]);
                    redirect('user/setting');
                } else {
                    // failed update
                    redirect('user/setting');
                }
            } else {
                // failed upload
                redirect('user/setting');
            }
        } else {
            redirect('user/setting');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata(
            ['id', 'user', 'nama', 'foto', 'role']
        );
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }

    private function _upload()
    {
        $uploaded_file = '';
        $max_pixel = 400;
        $upload_config = array(
            'upload_path' => './asset/img/user/',
            'allowed_types' => 'jpg|jpeg|gif|png|bmp',
            'file_name' => 'user_' . self_md5(date('Ymd_His'), '_1m4935' . mt_rand(1000, 9999))
        );
        $this->load->library('upload', $upload_config);
        if ($this->upload->do_upload('image_upload')) {
            $upload_data = $this->upload->data();
            $uploaded_file = $upload_data['file_name'];
            $this->load->library('image_lib');
            $dimension = array(
                'width' => $upload_data['image_width'],
                'height' => $upload_data['image_height']
            );
            if ($dimension['width'] != $dimension['height']) {
                $uploaded_file = $this->_crop($upload_data, $dimension);
            }
            if ($dimension['height'] > $max_pixel && $dimension['width'] > $max_pixel) {
                $uploaded_file = $this->_resize($upload_data, $max_pixel, $uploaded_file);
            }
            var_dump($upload_data);
        }
        return $uploaded_file;
    }

    private function _crop($data, $dimension)
    {
        $sizes = ($dimension['width'] > $dimension['height']) ? $dimension['height'] : $dimension['width'];
        $crop_config = array(
            'source_image' => $data['full_path'],
            'new_image' => $data['file_path'] . $data['raw_name'] . '_crop' . $data['file_ext'],
            'x_axis' => floor(($dimension['width'] - $sizes) / 2),
            'y_axis' => floor(($dimension['height'] - $sizes) / 2),
            'maintain_ratio' => false,
            'width' => $sizes,
            'height' => $sizes
        );
        $this->image_lib->clear();
        $this->image_lib->initialize($crop_config);
        $this->image_lib->crop();
        unlink($crop_config['source_image']);
        return $data['raw_name'] . '_crop' . $data['file_ext'];
    }

    private function _resize($data, $maxpixel, $filename)
    {
        $resize_config = array(
            'source_image' => $data['file_path'] . $filename,
            'new_image' => $data['file_path'] . 'resize_' . $filename,
            'width' => $maxpixel,
            'height' => $maxpixel
        );
        $this->image_lib->clear();
        $this->image_lib->initialize($resize_config);
        $this->image_lib->resize();
        unlink($resize_config['source_image']);
        return 'resize_' . $filename;
    }
}
