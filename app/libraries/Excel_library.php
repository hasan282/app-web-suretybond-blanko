<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'asset/phplugin/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel_library
{
    private $sheet, $style, $setting, $alpha;
    private $col, $row;

    public function __construct()
    {
        $this->sheet = new Spreadsheet();
        $this->alpha = explode('|', 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z');
        $this->style = array();
        $this->_setting();
    }

    protected function _setting($set = array())
    {
        $setting = array(
            'position' => 'B2',
            'orientation' => 'potrait'
        );
        if (array_key_exists('orientation', $set)) {
            if (in_array($set['orientation'], array('potrait', 'landscape'))) {
                $setting['orientation'] = $set['orientation'];
            }
        }
        if (array_key_exists('position', $set)) {
            $length = (strlen($set['position']) === 2);
            $number = (intval(substr($set['position'], -1)) > 0);
            $word = (in_array(substr($set['position'], 0, 1), $this->alpha));
            if ($length && $number && $word) $setting['position'] = $set['position'];
        }
        $this->setting = $setting;
    }

    protected function _prepare()
    {
        $this->_setup_excel();
    }

    protected function _value($value, $type = 'string')
    {
        $column_value = $this->_setup_value($value, $type);
        $target_column = $this->_column($this->col) . $this->row;
        $this->sheet->getActiveSheet()->setCellValue($target_column, $column_value);
        $this->sheet->getActiveSheet()->getStyle($target_column)->applyFromArray($this->style);
        $this->col += 1;
    }

    protected function _new_line()
    {
        $keys_col = array_keys($this->alpha, substr($this->setting['position'], 0, 1));
        $this->col = $keys_col[0] + 1;
        $this->row += 1;
    }

    protected function _download($filename = 'excel_file')
    {
        header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control:max-age=0');
        $writer = new Xlsx($this->sheet);
        $writer->save('php://output');
    }

    protected function _header_style()
    {
        $this->style = array(
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE),
                'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            )
        );
    }

    protected function _row_style()
    {
        $style = array(
            'alignment' => array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER),
            'borders' => array(
                'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            )
        );
        $this->style = $style;
    }

    private function _setup_excel()
    {
        $keys_col = array_keys($this->alpha, substr($this->setting['position'], 0, 1));
        $this->col = $keys_col[0] + 1;
        $this->row = intval(substr($this->setting['position'], -1));
        for ($c = $this->col - 1; $c > 0; $c--) {
            $this->sheet->getActiveSheet()->getColumnDimension($this->_column($c))->setWidth(3);
        }
    }

    private function _column($col_number)
    {
        $colname = '';
        $number = $col_number - 1;
        while ($number >= 0) {
            $key = $number % sizeof($this->alpha);
            $colname = $this->alpha[$key] . $colname;
            $number = (($number - $key) / sizeof($this->alpha)) - 1;
        }
        return $colname;
    }

    private function _setup_value($value, $type)
    {
        $result_value = '';
        if ($value != null) {
            switch ($type) {
                case 'date':
                    $result_value = str_replace('-', '/', $value);
                    break;
                case 'dateflip':
                    $dates = explode('-', $value);
                    if (sizeof($dates) === 3) {
                        $result_value = $dates[2] . '/' . $dates[1] . '/' . $dates[0];
                    }
                    break;
                case 'int':
                    $result_value = intval($value);
                    break;
                case 'float':
                    $result_value = floatval($value);
                    break;
                case 'to_string':
                    $result_value = "'" . $value;
                    break;
                case 'prefix_space':
                    $result_value = " " . $value;
                    break;
                default:
                    $result_value = strval($value);
                    break;
            }
        }
        return $result_value;
    }
}
