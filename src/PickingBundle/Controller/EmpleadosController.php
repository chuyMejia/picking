<?php

namespace PickingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpleadosController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    public function validarCodEmpAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $codigo = $request->get('codigo');

        $empleado = $em->getRepository('PickingBundle:trv_emp_alm')->findOneBy([
            "codEmp" => $codigo
        ]);

        $response = new Response();

        if($empleado){
            $response->setStatusCode(200);
        }else{
            $response->setStatusCode(404);
            $response->setContent("Colaborador no encontrado");
        }

        return $response;
    }
}
