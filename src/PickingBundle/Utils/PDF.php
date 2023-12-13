<?php

namespace PickingBundle\Utils;

class PDF extends \FPDF
{

    public function Header()
    {
        $this->Image('https://www.travers.com.mx/media/logo/stores/1/travers-logo.png', 120,5, 80, 12);
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