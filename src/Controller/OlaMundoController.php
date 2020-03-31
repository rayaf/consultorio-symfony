<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlaMundoController {

    /**
     * @Route("/ola")
     */
    public function olaMundo(Request $request): Response
    {
        $paramentro = $request->query->get('parametro');
        return new JsonResponse([
            'mensagem'=>'olá mundo',
            'parametro'=> $paramentro
        ]);
    }   
}