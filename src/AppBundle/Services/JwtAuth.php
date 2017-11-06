<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Services
 *
 * @author Solidaridad
 */

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth {

    public $manager;
    public $key;

    public function __construct($manager) {
        $this->manager = $manager;
        $this->key = "1Q2W3E4r5t6y7u8i9o0p";
    }

    public function singup($email, $password, $getHash = NULL) {
        $key = $this->key;

        $usuario = $this->manager->getRepository('BackendBundle:Usuario')->findOneBy(
                array(
                    "email" => $email,
                    "password" => $password
                )
        );

        $singupUsuario = false;

        if (is_object($usuario)) {
            $singupUsuario = true;
        }

        if ($singupUsuario == true) {
            $token = array(
                "sub" => $usuario->getId(),
                "email" => $usuario->getEmail(),
                "password" => $usuario->getPassword(),
                "nombre" => $usuario->getNombre(),
                "apellidoPaterno" => $usuario->getApellidoPaterno(),
                "apellidoMaterno" => $usuario->getApellidoMaterno(),
                "iat" => time(),
                "exp" => time() + (7 * 24 * 60 * 60)
            );
            $jwt = JWT::encode($token, $key, 'HS256');
            $decoded = JWT::decode($jwt, $key, array('HS256'));

            if ($getHash != null) {
                return $jwt;
            } else {
                return $decoded;
            }
        } else {
            return array(
                "status" => "error",
                "data" => "No existe un usuario registrado con dichos parametros, intente de nuevo"
            );
        }
    }

    public function checkToken($jwt, $getIdentity = false) {
        $key = $this->key;
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $key, array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity == true) {
            return $decoded;
        } else {
            return $auth;
        }
    }

}
