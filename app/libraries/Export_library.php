<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/Excel_library.php';
class Export_library extends Excel_library
{
    private $header, $key, $type, $number;

    public function __construct()
    {
        parent::__construct();
        $this->header = array();
        $this->key = array();
        $this->type = array();
        $this->number = false;
    }

    public function set_fields(array $field, $number = false, $num_header = 'No.')
    {
        $this->key = array_keys($field);
        if ($number) {
            $this->number = true;
            array_push($this->header, $num_header);
        }
        foreach (array_values($field) as $head) {
            $vals = explode('|', $head);
            array_push($this->header, $vals[0]);
            if (isset($vals[1])) {
                array_push($this->type, $vals[1]);
            } else {
                array_push($this->type, 'string');
            }
        }
    }

    public function set_rows(array $data)
    {
        $this->_prepare();
        if (!empty($this->header)) {
            $this->_set_header();
        } else {
            if (!empty($data)) {
                $this->key = array_keys($data[0]);
                $this->header = array_keys($data[0]);
                $this->type = array_fill(0, sizeof($this->key), 'string');
            }
            $this->_set_header();
        }
        $this->_row_style();
        foreach ($data as $num => $dat) {
            if ($this->number) $this->_value(($num + 1), 'int');
            foreach ($this->key as $k => $field) $this->_value($dat[$field], $this->type[$k]);
            $this->_new_line();
        }
    }

    public function file(string $filename)
    {
        $this->_download($filename);
    }

    private function _set_header()
    {
        $this->_header_style();
        foreach ($this->header as $head) $this->_value($head);
        $this->_new_line();
    }
}
