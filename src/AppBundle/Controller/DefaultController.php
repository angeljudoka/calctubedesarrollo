<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    public function loginAction(Request $request)
    {
        $helpers  = $this->get("app.helpers");
        $jwt_auth = $this->get("app.jwt_auth");
        //Recibir json por post
        $json = $request->get("json", null);

        if ($json != null) {
            $params = json_decode($json);
            // condicion ternaria
            $email    = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            //$id = (isset($params->id)) ? $params->id : null;
            $getHash = (isset($params->gethash)) ? $params->gethash : null;

            $emailConstrain          = new Assert\Email();
            $emailConstrain->message = "El email no es valido";

            $validate_email = $this->get("validator")->validate($email, $emailConstrain);

            $pwd = hash('sha256', $password);

            if (count($validate_email) == 0 && $password != null) {

                if ($getHash == null || $getHash == "false") {
                    $singup = $jwt_auth->singup($email, $pwd);
                    //return new \Symfony\Component\HttpFoundation\JsonResponse($singup);
                } else {
                    $singup = $jwt_auth->singup($email, $pwd, true);
                }

                return new JsonResponse($singup);

            } else {
                return $helpers->json(array(
                    "status" => "error",
                    "data"   => "Login no valido",
                ));
            }

        } else {
            return $helpers->json(array(
                "status" => "error",
                "data"   => "Send with post",
            ));
        }
    }

    public function pruebasAction(Request $request)
    {
        $helpers = $this->get("app.helpers");

        $hash  = $request->get("authorization", null);
        $check = $helpers->authCheck($hash, true);

        var_dump($check);
        die();
        /*
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:Usuario')->findAll();*/
        //return $helpers->json($users);
    }

}
