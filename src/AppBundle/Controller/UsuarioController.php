<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of UsuarioController
 *
 * @author Solidaridad
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\Usuario;

class UsuarioController extends Controller {

    public function newAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $json = $request->get("json", null);
        $params = json_decode($json);

        $data = array();

        if ($json != null) {

            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            $nombre = (isset($params->nombre)) ? $params->nombre : null;
            $apellidoPaterno = (isset($params->apellidoPaterno)) ? $params->apellidoPaterno : null;
            $apellidoMaterno = (isset($params->apellidoMaterno)) ? $params->apellidoMaterno : null;
            $emailConstrain = new Assert\Email();
            $emailConstrain->message = "El email no es valido";
            $validate_email = $this->get("validator")->validate($email, $emailConstrain);

            if ($email != null && count($validate_email) == 0 && $password != null) {

                $usuario = new Usuario;
                $usuario->setEmail($email);
                $usuario->setNombre($nombre);
                $usuario->setApellidoMaterno($apellidoMaterno);
                $usuario->setApellidoPaterno($apellidoPaterno);

                // cifrar passowrd
                $pwd = hash('sha256', $password);
                $usuario->setPassword($pwd);

                $em = $this->getDoctrine()->getManager();
                $isset_usuario = $em->getRepository("BackendBundle:Usuario")->findOneBy(
                        array(
                            "email" => $email
                ));

                if (count($isset_usuario) == 0) {
                    $em->persist($usuario);
                    $em->flush();

                    $data["status"] = 'success';
                    $data["msg"] = 'Nuevo usuario creado';
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Usuario ya existente en el sistema"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "No se ha proporsionado información"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "No se ha proporsionado información"
            );
        }
        return $helpers->json($data);
    }

    public function editAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {

            $identity = $helpers->authCheck($hash, true);

            $em = $this->getDoctrine()->getManager();
            $usuario = $em->getRepository("BackendBundle:Usuario")->findOneBy(array(
                "id" => $identity->sub
            ));

            $json = $request->get("json", null);
            $params = json_decode($json);

            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "No se ha podido actualizar, intente mas tarde!"
            );

            if ($json != null) {

                $email = (isset($params->email)) ? $params->email : null;
                $nombre = (isset($params->nombre)) ? $params->nombre : null;
                $apellidoPaterno = (isset($params->apellidoPaterno)) ? $params->apellidoPaterno : null;
                $apellidoMaterno = (isset($params->apellidoMaterno)) ? $params->apellidoMaterno : null;
                $password = (isset($params->password)) ? $params->password : null;

                $emailContraint = new Assert\Email();
                $emailContraint->message = "This email is not valid !!";
                $validate_email = $this->get("validator")->validate($email, $emailContraint);

                if ($email != null && count($validate_email) == 0) {

                    $usuario->setEmail($email);
                    $usuario->setNombre($nombre);
                    $usuario->setApellidoPaterno($apellidoPaterno);
                    $usuario->setApellidoMaterno($apellidoMaterno);
                    
                    if ($password != null && !empty($password)) {
                        //Cifrar la password
                        $pwd = hash('sha256', $password);
                        $usuario->setPassword($pwd);
                    }

                    $em = $this->getDoctrine()->getManager();
                    $isset_usuario = $em->getRepository("BackendBundle:Usuario")->findBy(
                            array(
                                "email" => $email
                    ));

                    $emailBd = $em->getRepository("BackendBundle:Usuario")->findOneBy(array(
                        "email" => $identity->email
                    ));

                    if (count($isset_usuario) == 0 || isset($emailBd)) {
                        $em->persist($usuario);
                        $em->flush();

                        $data["status"] = 'success';
                        $data["code"] = 200;
                        $data["msg"] = 'Se ha actualizado con exito !!';
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "Ya existe un correo electronico registrado con ese nombre, intente de nuevo"
                        );
                    }
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Authorization no valida"
                );
            }
        }
        return $helpers->json($data);
    }

    public function paginadoAction(Request $request) {
        $helpers = $this->get("app.helpers");

        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT u FROM BackendBundle:Usuario u ORDER BY u.id DESC";
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

}