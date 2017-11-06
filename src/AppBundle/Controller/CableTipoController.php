<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of CableTipoController
 *
 * @author Omar Alejadro Gonzalez Cauich
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\CableTipo;

class CableTipoController extends Controller {

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

                if ($usuario_id != null) {

                    $em = $this->getDoctrine()->getManager();
                    $usuario = $em->getRepository("BackendBundle:Usuario")->findOneBy(
                            array(
                                "id" => $usuario_id
                    ));

                    $nombreBase = $em->getRepository("BackendBundle:CableTipo")->findOneBy(
                            array(
                                "nombre" => $nombre
                    ));

                    $cable = new CableTipo();
                    $cable->setUsuario($usuario);
                    $cable->setNombre($nombre);

                    if ($nombre == null) {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "Para poder dar de alta a un tipo de cable, se deben llenar los campos marcados en rojo, intente de nuevo !"
                        );
                    } else {
                        if (isset($usuario) && $nombreBase == null) {

                            $em->persist($cable);
                            $em->flush();

                            $data = array(
                                "status" => "success",
                                "code" => 200,
                                "data" => $cable
                            );
                        } else {
                            $data = array(
                                "status" => "error",
                                "code" => 400,
                                "msg" => "Es posible que el tipo de cable que se quiere dar de alta ya este registrada en el sistema, intente de nuevo"
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
                "msg" => "Usted no cuenta con los permisos para crear dicha cable"
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

                $cable_id = $id;
                $usuario_id = ($identity->sub != null) ? $identity->sub : null;
                $nombre = (isset($params->nombre)) ? $params->nombre : null;

                if ($usuario_id != null && $cable_id != null) {
                    $em = $this->getDoctrine()->getManager();

                    $cable = $em->getRepository("BackendBundle:CableTipo")->findOneBy(
                            array(
                                "id" => $cable_id
                    ));

                    $cable->setNombre($nombre);

                    if (isset($identity->sub)) {
                        $em->persist($cable);
                        $em->flush();


                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Se ha actualizado correctamente !"
                                //"data" => $cable
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
                        "msg" => "No existe el id del usuario o cable a actualizar"
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

            $cable_id = $id;
            $usuario_id = ($identity->sub != null) ? $identity->sub : null;

            $em = $this->getDoctrine()->getManager();

            $cable = $em->getRepository("BackendBundle:CableTipo")->findOneBy(
                    array(
                        "id" => $cable_id
            ));

            if (is_object($cable) && $usuario_id != null) {
                $em->remove($cable);
                $em->flush();

                $data = array(
                    "status" => "success",
                    "code" => 200,
                    "msg" => "Se ha eliminado el tipo de cable con exito !"
                );
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "El tipo de cable especificado no existe, intente de nuevo"
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

        $dql = "SELECT a FROM BackendBundle:cable a ORDER BY a.nombre ASC";
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

        $cable = $em->getRepository("BackendBundle:cable")->findOneBy(array(
            "id" => $id
        ));


        if ($cable) {
            $data = array();
            $data["status"] = 'success';
            $data["code"] = 200;
            $data["data"] = $cable;
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "El tipo de cable no existe, intente con uno nuevo"
            );
        }


        return $helpers->json($data);
    }
    
}