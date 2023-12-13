<?php

namespace PickingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ReportesController extends Controller{

    private $session;

    function __construct()
    {
        $this->session = new Session();
    } 

    public function reportePickingAction(){
        return $this->render('PickingBundle:Reportes:reporte.picking.html.twig');
    }

    public function getDataFromPickingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrRes = [];

        $query = $em->createQueryBuilder()
            ->select('COUNT(p.picking) AS Total', 'e.nombreComp')
            ->from('PickingBundle:trv_picking', 'p')
            ->leftJoin('PickingBundle:trv_emp_alm', 'e', 'WITH', 'p.idEmpleado = e.id')
            ->where('p.fecha BETWEEN :fec1 AND :fec2')
            ->groupBy('e.nombreComp')
            ->orderBy('Total', 'DESC')
            ->setParameter('fec1', $request->get('fec1'))
            ->setParameter('fec2', $request->get('fec2'))
            ->getQuery();

        $pickings = $query->getResult();

        if ($pickings) {
            foreach ($pickings as $picking) {
                $arrRes[] = [
                    'total' => $picking['Total'],
                    'empleado' => $picking['nombreComp']
                ];
            }
        }

        $arrData = [
            'data' => $arrRes
        ];

        return new JsonResponse($arrData);
    }

}