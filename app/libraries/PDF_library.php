<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'asset/phplugin/tcpdf/tcpdf.php';
class PDF_library
{
    protected $pdf;

    public function __construct()
    {
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }

    protected function _info($title = '', $subject = '')
    {
        $this->pdf->setCreator(PDF_CREATOR);
        $this->pdf->setAuthor('surety.ptjis.com');
        $this->pdf->setTitle($title);
        $this->pdf->setSubject($subject);
        $this->pdf->setKeywords('');
    }
}
