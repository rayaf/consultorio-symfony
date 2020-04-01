<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
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

    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory
        )
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }
    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);
        
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
    public function buscaUmMedico(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $codigoDeRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200; 
        return new JsonResponse($medico, $codigoDeRetorno);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function deletaMedico(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualizaMedicos(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        
        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisicao);
        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->crm = $medicoEnviado->crm;
        $medicoExistente->nome = $medicoEnviado->nome;

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    public function buscaMedico(int $id)
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioDeMedicos->find($id);
        return $medico;
    }
}
