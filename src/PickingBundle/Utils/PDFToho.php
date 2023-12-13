<?php

namespace PickingBundle\Utils;

class PDFToho extends \FPDF
{

    public function Header()
    {
        $this->Image('https://cdn.shopify.com/s/files/1/0267/4045/7551/files/Toho_logohorizontal.png', 120,5, 80, 12);
        $this->SetFont('Helvetica', 'B', '15');
        $this->Ln(23);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10, utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');
    }
}