<?php

namespace PickingBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetDataFromAXService
{
    //const URLAPI = "localhost/api/web/piking/";

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getData($query = ""){
        $em = $this->em;

        $rsm = new ResultSetMapping();

        if($query == ""){
            $rsm->addScalarResult('ordVenta','ordVenta');
            $rsm->addScalarResult('picking','picking');
            $rsm->addScalarResult('fechaPicking','fechaPicking');
            $rsm->addScalarResult('horaPicking','horaPicking');
            $rsm->addScalarResult('factura','factura');
            $rsm->addScalarResult('descStatus','descStatus');
            $rsm->addScalarResult('agente','agente');
            $rsm->addScalarResult('descModoEntrega','descModoEntrega');
            $rsm->addScalarResult('numLineas','numLineas');
            $rsm->addScalarResult('grwshipall','grwshipall');
            $rsm->addScalarResult('CREATEDBY','creadoPor');

            $sql = $em->createNativeQuery(
                "select * from vw_trvAlmPickingsPendientes_", $rsm
            );
        }else{
            $rsm->addScalarResult('TRANSREFID', 'ordVenta');

            $sql = $em->createNativeQuery(
                "Select * from vw_trvBuscarOrdVtaPicking where PICKINGROUTEID = '".$query ."'" , $rsm
            );
        }

        $data = $sql->getResult();

        return $data;
    }

    public function getManifiestos(\DateTime $fecha, $empresa){
        $em = $this->em;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('FECHA', 'FECHA');
        $rsm->addScalarResult('GUIA', 'GUIA');
        $rsm->addScalarResult('MODO', 'MODO');
        $rsm->addScalarResult('PAQUETERIA', 'PAQUETERIA');
        $rsm->addScalarResult('PESO', 'PESO');
        $rsm->addScalarResult('CLIENTE', 'CLIENTE');
        $rsm->addScalarResult('CODECTE', 'CODECTE');
        $rsm->addScalarResult('FACTURA', 'FACTURA');

        if($empresa == "TRV"){
            $sql = $em->createNativeQuery(
                "select * from vw_trvManifiestosguias where FECHA = :fecha", $rsm
            );
        }else if($empresa == "OUM"){
            $sql = $em->createNativeQuery(
                "select * from vw_trvManifiestosguiasOUM where FECHA = :fecha", $rsm
            );
        }else{
            throw new \Exception("No se recibió el parámetro empresa", 500);
        }

        $sql->setParameter("fecha", $fecha->format('Y-m-d'));

        $data = $sql->getResult();

        return $data;
    }



    public function callSPAX(){
        $em = $this->em;

        $rsm = new ResultSetMapping();

        $sp1 = $em->createNativeQuery('', $rsm);
        $sp2 = $em->createNativeQuery('', $rsm);

        $sp1->getResult();
        $sp2->getResult();

        return 0;
    }
}