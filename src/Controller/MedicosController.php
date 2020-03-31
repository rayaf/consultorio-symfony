<?php

namespace App\Controller;

use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadoToJson = json_decode($corpoRequisicao);

        $medico = new Medico();
        $medico->crm = $dadoToJson->crm;
        $medico->nome = $dadoToJson->nome;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscaMedicos(): Response
    {
        $rspositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicos = $rspositorioDeMedicos ->findAll();
        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function buscaMedico(Request $request): Response
    {
        $id = $request->get('id');
        $rspositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $rspositorioDeMedicos ->find($id);
        return new JsonResponse($medico);
    }
}
