<?php

namespace PickingBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use PickingBundle\Form\trv_ManifiestosType;
use PickingBundle\Utils\PDF;
use PickingBundle\Utils\PDFToho;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ManifiestosController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('PickingBundle:Manifiestos:manifiestos.html.twig', [
        ]);
    }

    public function generarPDFAction(Request $request, $param){
        $fecha = new \DateTime("now");
        $arrManifiestos = [];

        $service = $this->get('picking.connect_ax_service');
        $data = $service->getManifiestos($fecha, "TRV");

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetTitle('Manifiestos de etiquetas TRAVERS');
        $pdf->Cell(0,0,utf8_decode('REPORTE DE MANIFIESTOS DE PAQUETERÍAS PARA EL DÍA '.$fecha->format('Y-m-d')), '0','0', 'C');
        $pdf->Ln(5);

        if($data != null){
            //$modos = array_unique(array_column($data, 'MODO'));

            if($param == 1){
                $logoUrl = 'https://1000marcas.net/wp-content/uploads/2020/01/DHL-Logo.png';
                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "42" || $item["MODO"] == "43"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }else if($param == "2"){
                $logoUrl = 'https://globalpaq.com/img/logos-sc-paquetexpress.png';
                $flagPE = true;

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "5" || $item["MODO"] == "31"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"] ,
                        ];
                    }
                }
            }else if($param == 3){
                $logoUrl = 'https://irp.cdn-website.com/f1d4cc40/MOBILE/png/641.png';

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "4"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }else if($param == 4){
                $logoUrl = 'https://w7.pngwing.com/pngs/1001/978/png-transparent-logo-ups-logos-and-brands-icon.png';

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "3"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }

            if($arrManifiestos != null){
                $pdf->SetFont('Helvetica','B',8);
                $pdf->Cell(40,0,utf8_decode("GUÍA"),0,0);
                $pdf->Cell(50,0,utf8_decode('MODO DE ENTREGA'),0,0);
                $pdf->Cell(15,0,utf8_decode('PESO'),0,0);
                $pdf->Cell(40,0,utf8_decode('CLIENTE'),0,0);
                $pdf->Cell(40,0,utf8_decode('FACTURA'),0,0);
                $pdf->Ln(5);

                foreach ($arrManifiestos as $manifiesto){
                    $pdf->SetFont('Helvetica','',9);
                    $pdf->Cell(40,0,$manifiesto["guia"],0,0);
                    $pdf->Cell(50,0,$manifiesto["paqueteria"],0,0);
                    $pdf->Cell(15,0,number_format($manifiesto["peso"], 2,".", ''),0,0);
                    $pdf->Cell(40,0,utf8_decode($manifiesto["codecte"]),0,0);
                    $pdf->Cell(40,0,utf8_decode($manifiesto["factura"]),0,0);
                    $pdf->Ln(3);
                }

                $pdf->Ln(5);
                $pdf->SetFont('Helvetica','B',16);
                $pdf->Cell(0,0,utf8_decode('TOTAL DE GUÍAS: '). count($arrManifiestos),0,0,'L');

                $pdf->Ln(10);
            }

            $pdf->Ln(20);
            $pdf->SetFont('Helvetica','B',10);
            $pdf->Cell(90,0,utf8_decode('____________________________'),0,0,'C');
            $pdf->Cell(90,0,utf8_decode('____________________________'),0,0,'C');
            $pdf->Ln(5);
            $pdf->Cell(90,0,utf8_decode('Responsable Paquetería'),0,0,'C');
            $pdf->Cell(90,0,utf8_decode('Responsable Travers'),0,0,'C');

        }else{
            $pdf->Ln(20);
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(0,0,utf8_decode('NO SE ENCONTRÓ INFORMACIÓN DE MANIFIESTOS'),0,0,'C');
        }

        return new Response($pdf->Output(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    public function indexTohoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('PickingBundle:Manifiestos:manifiestos.toho.html.twig', [
        ]);
    }

    public function generarPDFTohoAction(Request $request, $param){
        $em = $this->getDoctrine()->getManager();

        $fecha = new \DateTime("now");
        $arrManifiestos = [];

        $service = $this->get('picking.connect_ax_service');
        $data = $service->getManifiestos($fecha, "OUM");

        $pdf = new PDFToho();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetTitle('Manifiestos de etiquetas TOHO');
        $pdf->Cell(0,0,utf8_decode('REPORTE DE MANIFIESTOS DE PAQUETERÍAS PARA EL DÍA '.$fecha->format('Y-m-d')), '0','0', 'C');
        $pdf->Ln(5);

        if($data != null){
            //$modos = array_unique(array_column($data, 'MODO'));

            if($param == 1){
                $logoUrl = 'https://1000marcas.net/wp-content/uploads/2020/01/DHL-Logo.png';
                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "42" || $item["MODO"] == "43"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }else if($param == "2"){
                $logoUrl = 'https://globalpaq.com/img/logos-sc-paquetexpress.png';
                $flagPE = true;

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "5" || $item["MODO"] == "31"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"] ,
                        ];
                    }
                }
            }else if($param == 3){
                $logoUrl = 'https://irp.cdn-website.com/f1d4cc40/MOBILE/png/641.png';

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "4"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }else if($param == 4){
                $logoUrl = 'https://w7.pngwing.com/pngs/1001/978/png-transparent-logo-ups-logos-and-brands-icon.png';

                $pdf->Cell(0, 0, $pdf->Image($logoUrl, $pdf->GetX(), $pdf->GetY(), 30, 18));
                $pdf->Ln(20);

                foreach ($data as $item){
                    if($item["MODO"] == "3"){
                        $arrManifiestos[] = [
                            'fecha' => $item["FECHA"],
                            'guia' => $item["GUIA"],
                            'modo' => $item["MODO"],
                            'paqueteria' => $item["PAQUETERIA"],
                            'peso' => $item["PESO"],
                            'cliente' => $item["CLIENTE"],
                            'codecte' => $item["CODECTE"],
                            'factura' => $item["FACTURA"],
                        ];
                    }
                }
            }

            if($arrManifiestos != null){
                $pdf->SetFont('Helvetica','B',8);
                $pdf->Cell(40,0,utf8_decode("GUÍA"),0,0);
                $pdf->Cell(50,0,utf8_decode('MODO DE ENTREGA'),0,0);
                $pdf->Cell(15,0,utf8_decode('PESO'),0,0);
                $pdf->Cell(40,0,utf8_decode('CLIENTE'),0,0);
                $pdf->Cell(40,0,utf8_decode('FACTURA'),0,0);
                $pdf->Ln(5);

                foreach ($arrManifiestos as $manifiesto){
                    $pdf->SetFont('Helvetica','',9);
                    $pdf->Cell(40,0,$manifiesto["guia"],0,0);
                    $pdf->Cell(50,0,$manifiesto["paqueteria"],0,0);
                    $pdf->Cell(15,0,number_format($manifiesto["peso"], 2,".", ''),0,0);
                    $pdf->Cell(40,0,utf8_decode($manifiesto["codecte"]),0,0);
                    $pdf->Cell(40,0,utf8_decode($manifiesto["factura"]),0,0);
                    $pdf->Ln(3);
                }

                $pdf->Ln(5);
                $pdf->SetFont('Helvetica','B',10);
                $pdf->Cell(0,0,utf8_decode('TOTAL DE GUÍAS: '). count($arrManifiestos),0,0,'L');

                $pdf->Ln(10);
            }

            $pdf->Ln(20);
            $pdf->Cell(90,0,utf8_decode('____________________________'),0,0,'C');
            $pdf->Cell(90,0,utf8_decode('____________________________'),0,0,'C');
            $pdf->Ln(5);
            $pdf->Cell(90,0,utf8_decode('Responsable Paquetería'),0,0,'C');
            $pdf->Cell(90,0,utf8_decode('Responsable Travers'),0,0,'C');

        }else{
            $pdf->Ln(20);
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(0,0,utf8_decode('NO SE ENCONTRÓ INFORMACIÓN DE MANIFIESTOS'),0,0,'C');
        }

        return new Response($pdf->Output(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    public function manifiestoExtendidoAction(){
        return $this->render('PickingBundle:Manifiestos:registro.manifiesto.html.twig', []);
    }

    public function registroManifiestoAction(Request $request){
        $em = $this->getDoctrine()->getManager();


    }
}
