<?php

namespace PickingBundle\Controller;

use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardPickingController extends Controller
{

    /*
     * Retorna la vista del dashboard de almacén
     * */
    public function indexAction()
    {
        return $this->render('PickingBundle:Dashboard:dashboard.picking.html.twig', []);
    }

    /*
     * Retorna la lista de pickings a la tabla dentro de la vista del dashboard de almacén
     * */
    public function loadDataAction()
    {
        //Obtiene el manager de Doctrine
        $em = $this->getDoctrine()->getManager();
        //Recupera la lista de pickings registrados dentro la base de datos de consultas
        $pickings = $em->getRepository('PickingBundle:trv_picking')->findAll();
        //Guarda dentro de un array el número de picking
        $arrPickings = array_map(function ($picking) {
            return $picking->getPicking();
        }, $pickings);

        //Establece la conexión con el servicio que recupera los datos de la vista de Picking
        $service = $this->get('picking.connect_ax_service');
        //Obtiene y transforma el array de la lista de pickings
        $data = $service->getData();
        $data = json_decode(json_encode($data), true);

        //Se almacena dentro de un array los pickings con la información que se va a presentar en la tabla
        $arrInfo = array_map(function ($item) use ($arrPickings, $em, $pickings) {
            $estatus = $item['descStatus'];

            //Preguntamos si el picking de la vista ya está dentro de la lista de pickings registrados
            //Y también preguntamos si el picking tiene estatus de "Activado".
            //En caso de que el picking ya haya sido registrado en la tabla trv_picking  y no tenga
            //hora de cierre se debe asignar el estatus de "En Proceso". Si el picking aún tiene estatus de activado y
            //se regista la hora de cierre, el estatus deberá ser "En área de Facturación"
            if (in_array($item['picking'], $arrPickings) && $item['descStatus'] == 'Activado') {

                //Filtramos el item recuperado desde la vista dentro de la lista de pickings ya registrados
                //en la tabla trv_picking
                $filteredPickings = array_filter($pickings, function ($elemento) use ($item) {
                    return $elemento->getPicking() == $item['picking'];
                });

                //Si el elemento ya existe, tenemos que preguntar si ya tiene hora de cierre registrada
                //En caso de que no, el estatus será "En Proceso"
                //En caso de que sí, el estatus será "En área de Facturación"
                if (!empty($filteredPickings)) {
                    $elemento = reset($filteredPickings);
                    if ($elemento->getHorafin() == null) {
                        $estatus = 'En Proceso';
                    } else {
                        $estatus = 'En área de Facturación';
                    }
                }

            } else if ($item['factura'] != 'SIN FACTURA' && $item['descStatus'] == 'Completado') {
                $estatus = 'Facturado';
            }

            //Se debe determinar la hora del picking está dentro del horario de trabajo del almacén
            //Para eso usaremos la función "validaRangoHora()"
            $hora = new \DateTime($item["horaPicking"]);
            $horario = $this->validaRangoHora($hora->format('H:i')) ? "En horario" : "Fuera de horario";

            //Debemos poner el nombre del empleado que registró el picking
            $pickingEntity = $em->getRepository('PickingBundle:trv_picking')->findOneBy(['picking' => $item['picking']]);
            $pickeador = $pickingEntity ? $pickingEntity->getIdempleado()->getNombrecomp() : "Pendiente";
            //En caso de que no se haya registrado la hora de inicio del picking, se deberá poner pendiente
            //Si se encuentra la hora, se deberá dar formato
            $horaRegistroPicking = $pickingEntity ? $pickingEntity->getHora()->format('H:i:s') : "Pendiente";

            //En caso de que no se haya registrado la hora de cierre del picking, se deberá poner pendiente
            //Si se encuentra la hora, se deberá dar formato
            $horaCierre = "Pendiente";
            if($pickingEntity){
                if($pickingEntity->getHorafin() != null){
                    $horaCierre = $pickingEntity->getHorafin()->format('H:i:s');
                }
            }

            //Para el campo de "Entrega completa", se declara una varible con valor "No"
            $entregaCompleta = "No";

            //Si se encuentra que la orden tiene activado el check de GRWShipAll dentro de la orden de venta
            //la entrega completa pasará a ser "Sí"
            if($item['grwshipall'] != null && $item['grwshipall'] != 0){
                $entregaCompleta = "Si";
            }

            //Se debe poner el nombre de la persona que creo que orden de venta en AX
            $creadoPor = "S/N";
            if($item['creadoPor'] != null){
                $creadoPor = $item['creadoPor'];
            }

            //Retornamos cada posición del array con los campos para cada picking
            return [
                'ordVenta' => $item['ordVenta'],
                'picking' => $item['picking'],
                'pickeador' => $pickeador,
                'entrega' => $item['descModoEntrega'],
                'factura' => $item['factura'],
                'fecha' => $item['fechaPicking'],
                'hora' => $item['horaPicking'],
                'horaPicking' => $horaRegistroPicking,
                'horaCierre' => $horaCierre,
                'descStatus' => $estatus,
                'horario' => $horario,
				'numLineas' => $item['numLineas'],
                'entregaComp' => $entregaCompleta,
                'creadoPor' => $creadoPor
            ];
        }, $data);

        //Ordenamos el array por fecha y hora
        usort($arrInfo, function ($a, $b) {
            return strtotime($b['fecha'] . ' ' . $b['hora']) - strtotime($a['fecha'] . ' ' . $a['hora']);
        });

        $arrData = [
            'data' => $arrInfo,
        ];

        return new JsonResponse($arrData);
    }

    /*
     * Función que recibe una hora y valida si se encuentra dentro del rango de horario del almacén
     * */
    function validaRangoHora($input){
        $from = '08:00';
        $to = '17:00';
        $dateFrom = \DateTime::createFromFormat('!H:i', $from);
        $dateTo = \DateTime::createFromFormat('!H:i', $to);
        $dateInput = \DateTime::createFromFormat('!H:i', $input);
        if($dateFrom > $dateTo) $dateTo->modify('+1 day');
        return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
    }

    public function indexVentasAction(){
        return $this->render('PickingBundle:Dashboard:dashboard.ventas.html.twig');
    }

    public function loadDataVentasAction(){
        $em = $this->getDoctrine()->getManager();
        $pickings = $em->getRepository('PickingBundle:trv_picking')->findAll();
        $arrPickings = array_map(function ($picking) {
            return $picking->getPicking();
        }, $pickings);

        $service = $this->get('picking.connect_ax_service');
        $data = $service->getData();
        $data = json_decode(json_encode($data), true);

        $arrInfo = array_map(function ($item) use ($arrPickings, $em) {
            $estatus = $item['descStatus'];

            if (in_array($item['picking'], $arrPickings) && $item['descStatus'] == 'Activado') {
                $estatus = 'En Proceso';
            } else if ($item['factura'] != 'SIN FACTURA' && $item['descStatus'] == 'Completado') {
                $estatus = 'Facturado';
            }

            $hora = new \DateTime($item["horaPicking"]);
            $horario = $this->validaRangoHora($hora->format('H:i')) ? "En horario" : "Fuera de horario";


            return [
                'ordVenta' => $item['ordVenta'],
                'picking' => $item['picking'],
                'agente' => $item['agente'],
                'entrega' => $item['descModoEntrega'],
                'factura' => $item['factura'],
                'fecha' => $item['fechaPicking'],
                'hora' => $item['horaPicking'],
                'descStatus' => $estatus,
                'horario' => $horario
            ];
        }, $data);

        usort($arrInfo, function ($a, $b) {
            return strtotime($a['fecha'] . ' ' . $a['hora']) <=> strtotime($b['fecha'] . ' ' . $b['hora']);
        });

        $arrData = [
            'data' => $arrInfo,
        ];

        return new JsonResponse($arrData);
    }

    public function indexMtyAction(){
        return $this->render('PickingBundle:Dashboard:dashboard.mty.html.twig');
    }
}
