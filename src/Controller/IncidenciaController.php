<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Form\IncidenciaType;
use App\Repository\IncidenciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/incidencia')]
class IncidenciaController extends AbstractController
{
    #[Route('/', name: 'app_incidencia_index', methods: ['GET'])]
    public function index(IncidenciaRepository $incidenciaRepository): Response
    {
        return $this->render('incidencia/index.html.twig', [
            'incidencias' => $incidenciaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_incidencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncidenciaRepository $incidenciaRepository): Response
    {
        $incidencium = new Incidencia();
        $form = $this->createForm(IncidenciaType::class, $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidenciaRepository->add($incidencium);
            return $this->redirectToRoute('app_incidencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incidencia/new.html.twig', [
            'incidencium' => $incidencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_incidencia_show', methods: ['GET'])]
    public function show(Incidencia $incidencium): Response
    {
        return $this->render('incidencia/show.html.twig', [
            'incidencium' => $incidencium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_incidencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Incidencia $incidencium, IncidenciaRepository $incidenciaRepository): Response
    {
        $form = $this->createForm(IncidenciaType::class, $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidenciaRepository->add($incidencium);
            return $this->redirectToRoute('app_incidencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incidencia/edit.html.twig', [
            'incidencium' => $incidencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_incidencia_delete', methods: ['POST'])]
    public function delete(Request $request, Incidencia $incidencium, IncidenciaRepository $incidenciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incidencium->getId(), $request->request->get('_token'))) {
            $incidenciaRepository->remove($incidencium);
        }

        return $this->redirectToRoute('app_incidencia_index', [], Response::HTTP_SEE_OTHER);
    }
}
