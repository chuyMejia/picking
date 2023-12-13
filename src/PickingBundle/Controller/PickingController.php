<?php

namespace PickingBundle\Controller;

use PickingBundle\Entity\trv_picking;
use PickingBundle\Form\trv_pickingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PickingController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function newAction(){
        return $this->render('PickingBundle:Picking:registroc.picking.html.twig');
    }

    public function registroAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $numPicking = $request->request->get('numpicking');
        $codigo = $request->request->get('codigo');
        $response = new Response();

        try {
            $empleado = $em->getRepository('PickingBundle:trv_emp_alm')->findOneBy([
                "codEmp" => $codigo
            ]);

            $service = $this->get('picking.connect_ax_service');
            $data = $service->getData($numPicking);
            $data = json_decode(json_encode($data), true);
            $ordVenta = $data[0]['ordVenta'];

            $picking = new trv_picking();
            $picking->setPicking($numPicking);
            $picking->setOrdenventa($ordVenta);
            $picking->setFecha(new \DateTime("now"));
            $picking->setFactura(0);
            $picking->setHora(new \DateTime("now"));
            $picking->setEstatus("En Proceso");
            $picking->setIdEmpleado($empleado);

        
            $em->persist($picking);
            $em->flush();

            $status = "Picking registrado";
            $response->setStatusCode(200);
            $response->setContent($status);
        }catch (\Exception $e){
            $status = $e->getMessage();
            $response->setStatusCode(500);
            $response->setContent($status);
        }

        return $response;
    }

    public function validaExistenciaPickingAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $picking = $request->get('picking');

        $oPicking = $em->getRepository('PickingBundle:trv_picking')->findOneBy([
            "picking" => $picking
        ]);


        $response = new Response();
        $response->setStatusCode(200);
        if($oPicking == null){

        }else{
            $response->setStatusCode(500);
        }

        return $response;
    }

    public function viewCierrePickingAction(){
        return $this->render('PickingBundle:Picking:registro.fin.picking.html.twig');
    }

    public function validaHoraFinRegistradaAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $picking = $request->get('picking');

        $oPicking = $em->getRepository('PickingBundle:trv_picking')->findOneBy([
            "picking" => $picking
        ]);

        $content = "";
        $statusCode = 200;
        if(is_null($oPicking->getHorafin())){
            $content = "";
            $statusCode = 200;
        }else{
            $content = "El Picking ya tiene hora de cierre registrada";
            $statusCode = 500;
        }

        return new Response($content, $statusCode);
    }

    public function registraHoraCierreAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $numPicking = $request->request->get('numpicking');
        $response = new Response();
        try {
            $picking = $em->getRepository('PickingBundle:trv_picking')->findOneBy([
                "picking" => $numPicking
            ]);

            if($picking){
                $picking->setHorafin(new \DateTime("now"));

                $em->persist($picking);
                $em->flush();

                $status = "Hora de cierre registrada correctamente";
                $response->setStatusCode(200);
                $response->setContent($status);
            }else{
                $status = "No se encontró información del Picking";
                $response->setStatusCode(500);
                $response->setContent($status);
            }
        }catch (\Exception $e){
            $status = $e->getMessage();
            $response->setStatusCode(500);
            $response->setContent($status);
        }

        return $response;
    }
}
