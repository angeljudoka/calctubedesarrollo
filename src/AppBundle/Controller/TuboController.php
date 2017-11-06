<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of TuboController
 *
 * @author Omar Alejadro Gonzalez Cauich
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\Tubo;

class TuboController extends Controller {

    public function newAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {

            $identity = $helpers->authCheck($hash, true);
            $json = $request->get("json", null);
            $params = json_decode($json);


            if ($json != null) {

                $usuario_id = ($identity->sub != null) ? $identity->sub : null;
                $nombre = (isset($params->nombre)) ? $params->nombre : null;
                $tamano = (isset($params->tamano)) ? $params->tamano : null;
                $designacion = (isset($params->designacion)) ? $params->designacion : null;
                $diametro = (isset($params->diametro)) ? $params->diametro : null;
                $tubo_tipo = (isset($params->tuboTipo)) ? $params->tuboTipo : null;
                
                if ($usuario_id != null) {

                    $em = $this->getDoctrine()->getManager();
                    
                    $usuario = $em->getRepository("BackendBundle:Usuario")->findOneBy(
                            array(
                                "id" => $usuario_id
                    ));

                    $nombreBase = $em->getRepository("BackendBundle:Tubo")->findOneBy(
                            array(
                                "nombre" => $nombre
                    ));
                    
                    $tuboTipo = $em->getRepository("BackendBundle:TuboTipo")->findOneBy(
                            array(
                                "id" => $tubo_tipo
                    ));

                    $tubo = new Tubo();
                    $tubo->setNombre($nombre);
                    $tubo->setTamanoComercial($tamano);
                    $tubo->setDesignacionMetrica($designacion);
                    $tubo->setDiametroInterno($diametro);
                    $tubo->settuboTipo($tuboTipo);

                    if ($nombre == null) {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "Para poder dar de alta a un tubo, se deben llenar los campos marcados en rojo, intente de nuevo !"
                        );
                    } else {
                        if (isset($usuario) && $nombreBase == null) {

                            $em->persist($tubo);
                            $em->flush();

                            $data = array(
                                "status" => "success",
                                "code" => 200,
                                "data" => $tubo
                            );
                        } else {
                            $data = array(
                                "status" => "error",
                                "code" => 400,
                                "msg" => "Es posible que La tubo que se quiere dar de alta ya este registrada en el sistema, intente de nuevo"
                            );
                        }
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "No existe el id del usuario"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "No se ha proporcionado ninguna información, favor intente de nuevo"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Usted no cuenta con los permisos para crear dicha tubo"
            );
        }

        return $helpers->json($data);
    }

    public function editAction(Request $request, $id = null) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {

            $identity = $helpers->authCheck($hash, true);
            $json = $request->get("json", null);
            $params = json_decode($json);


            if ($json != null) {

                $tubo_id = $id;
                $usuario_id = ($identity->sub != null) ? $identity->sub : null;
                $nombre = (isset($params->nombre)) ? $params->nombre : null;
                $tamano = (isset($params->tamano)) ? $params->tamano : null;
                $designacion = (isset($params->designacion)) ? $params->designacion : null;
                $diametro = (isset($params->diametro)) ? $params->diametro : null;
                $tubo_tipo = (isset($params->tuboTipo)) ? $params->tuboTipo : null;

                if ($usuario_id != null && $tubo_id != null) {
                    $em = $this->getDoctrine()->getManager();

                    $tubo = $em->getRepository("BackendBundle:Tubo")->findOneBy(
                            array(
                                "id" => $tubo_id
                    ));
                    
                    $tuboTipo = $em->getRepository("BackendBundle:TuboTipo")->findOneBy(
                            array(
                                "id" => $tubo_tipo
                    ));

                    $tubo->setNombre($nombre);
                    $tubo->setTamanoComercial($tamano);
                    $tubo->setDesignacionMetrica($designacion);
                    $tubo->setDiametroInterno($diametro);
                    $tubo->settuboTipo($tuboTipo);

                    if (isset($identity->sub)) {
                        $em->persist($tubo);
                        $em->flush();


                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Se ha actualizado correctamente !"
                                //"data" => $tubo
                        );
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "No tiene los permisos necesarios para realizar dicha actualización, intente mas tarde"
                        );
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "No existe el id del usuario o tubo a actualizar"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Favor de introducir los campos solicitados"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Sin autorizacion"
            );
        }

        return $helpers->json($data);
    }

    public function deleteAction(Request $request, $id = null) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $helpers->authCheck($hash, true);

            $tubo_id = $id;
            $usuario_id = ($identity->sub != null) ? $identity->sub : null;

            $em = $this->getDoctrine()->getManager();

            $tubo = $em->getRepository("BackendBundle:Tubo")->findOneBy(
                    array(
                        "id" => $tubo_id
            ));

            if (is_object($tubo) && $usuario_id != null) {
                $em->remove($tubo);
                $em->flush();

                $data = array(
                    "status" => "success",
                    "code" => 200,
                    "msg" => "Se ha eliminado la tubo con exito !"
                );
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "La tubo especificada no existe, intente de nuevo"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Sin autorizacion"
            );
        }
        return $helpers->json($data);
    }

    public function paginadoAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT a FROM BackendBundle:Tubo a ORDER BY a.nombre ASC";
        $query = $em->createQuery($dql);

        $page = $request->query->getInt("page", 1);
        $paginator = $this->get("knp_paginator");
        $items_per_page = 10;

        $pagination = $paginator->paginate($query, $page, $items_per_page);
        $total_items_count = $pagination->getTotalItemCount();

        $data = array(
            "status" => "success",
            "total_items_count" => $total_items_count,
            "page_actual" => $page,
            "items_per_page" => $items_per_page,
            "total_pages" => ceil($total_items_count / $items_per_page),
            "data" => $pagination
        );

        return $helpers->json($data);
    }
    
    public function detailAction(Request $request, $id = null) {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $tubo = $em->getRepository("BackendBundle:Tubo")->findOneBy(array(
            "id" => $id
        ));


        if ($tubo) {
            $data = array();
            $data["status"] = 'success';
            $data["code"] = 200;
            $data["data"] = $tubo;
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "La tubo no existe, intente con uno nuevo"
            );
        }


        return $helpers->json($data);
    }
   
}
