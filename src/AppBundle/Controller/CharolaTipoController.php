<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of CharolaTipoController
 *
 * @author Omar Alejadro Gonzalez Cauich
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\CharolaTipo;

class CharolaTipoController extends Controller {

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

                    $nombreBase = $em->getRepository("BackendBundle:CharolaTipo")->findOneBy(
                            array(
                                "nombre" => $nombre
                    ));

                    $canaleta = new CharolaTipo();
                    $canaleta->setUsuario($usuario);
                    $canaleta->setNombre($nombre);

                    if ($nombre == null) {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "Para poder dar de alta a un tipo de canaleta, se deben llenar los campos marcados en rojo, intente de nuevo !"
                        );
                    } else {
                        if (isset($usuario) && $nombreBase == null) {

                            $em->persist($canaleta);
                            $em->flush();

                            $data = array(
                                "status" => "success",
                                "code" => 200,
                                "data" => $canaleta
                            );
                        } else {
                            $data = array(
                                "status" => "error",
                                "code" => 400,
                                "msg" => "Es posible que el tipo de canaleta que se quiere dar de alta ya este registrada en el sistema, intente de nuevo"
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
                "msg" => "Usted no cuenta con los permisos para crear dicha canaleta"
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

                $canaleta_id = $id;
                $usuario_id = ($identity->sub != null) ? $identity->sub : null;
                $nombre = (isset($params->nombre)) ? $params->nombre : null;

                if ($usuario_id != null && $canaleta_id != null) {
                    $em = $this->getDoctrine()->getManager();

                    $canaleta = $em->getRepository("BackendBundle:CharolaTipo")->findOneBy(
                            array(
                                "id" => $canaleta_id
                    ));

                    $canaleta->setNombre($nombre);

                    if (isset($identity->sub)) {
                        $em->persist($canaleta);
                        $em->flush();


                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Se ha actualizado correctamente !"
                                //"data" => $canaleta
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
                        "msg" => "No existe el id del usuario o canaleta a actualizar"
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

            $canaleta_id = $id;
            $usuario_id = ($identity->sub != null) ? $identity->sub : null;

            $em = $this->getDoctrine()->getManager();

            $canaleta = $em->getRepository("BackendBundle:CharolaTipo")->findOneBy(
                    array(
                        "id" => $canaleta_id
            ));

            if (is_object($canaleta) && $usuario_id != null) {
                $em->remove($canaleta);
                $em->flush();

                $data = array(
                    "status" => "success",
                    "code" => 200,
                    "msg" => "Se ha eliminado el tipo de canaleta con exito !"
                );
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "El tipo de canaleta especificado no existe, intente de nuevo"
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

        $dql = "SELECT a FROM BackendBundle:CharolaTipo a ORDER BY a.nombre ASC";
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

        $canaleta = $em->getRepository("BackendBundle:CharolaTipo")->findOneBy(array(
            "id" => $id
        ));


        if ($canaleta) {
            $data = array();
            $data["status"] = 'success';
            $data["code"] = 200;
            $data["data"] = $canaleta;
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "El tipo de canaleta no existe, intente con uno nuevo"
            );
        }


        return $helpers->json($data);
    }
   
}