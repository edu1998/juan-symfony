<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Entity\Usuario;
use App\Form\IncidenciaClientType;
use App\Form\IncidenciaType;
use App\Repository\ClienteRepository;
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
            'name_user'  => $this->getNameUser(),
            'incidencias' => $incidenciaRepository->findAll(),
        ]);
    }

    #[Route('/registro', name: 'app_incidencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncidenciaRepository $incidenciaRepository): Response
    {
        $incidencium = new Incidencia();
        $form = $this->createForm(IncidenciaType::class, $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidencium->setFecha(new \DateTime());
            $incidencium->setUsuario($this->getCurrentUser());
            $incidenciaRepository->add($incidencium);
            return $this->redirectToRoute('app_incidencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incidencia/new.html.twig', [
            'name_user'  => $this->getNameUser(),
            'form' => $form,
        ]);
    }

    #[Route('/registro/cliente/{id_cliente}', name: 'app_incidencia_new_with_client', methods: ['GET', 'POST'])]
    public function newWithClient(Request $request, IncidenciaRepository $incidenciaRepository, ClienteRepository $clienteRepository ,string $id_cliente): Response
    {
        $incidencium = new Incidencia();
        $cliente = $clienteRepository->find($id_cliente);
        $form = $this->createForm(IncidenciaClientType::class, $incidencium, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidencium->setFecha(new \DateTime());
            $incidencium->setUsuario($this->getCurrentUser());
            $incidencium->setCliente($cliente);
            $incidenciaRepository->add($incidencium);
            return $this->redirectToRoute('cliente_detail', [ 'id' => $id_cliente ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incidencia/new.html.twig', [
            'name_user'  => $this->getNameUser(),
            'form' => $form,
            'client_id' => $id_cliente
        ]);
    }

    #[Route('/{id}', name: 'app_incidencia_show', methods: ['GET'])]
    public function show(Incidencia $incidencium): Response
    {
        return $this->render('incidencia/show.html.twig', [
            'incidencium' => $incidencium,
            'name_user'  => $this->getNameUser(),
        ]);
    }

    #[Route('/{id}/edit/{id_cliente}', name: 'app_incidencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Incidencia $incidencium, IncidenciaRepository $incidenciaRepository, string $id_cliente): Response
    {
        $form = $this->createForm(IncidenciaType::class, $incidencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidenciaRepository->add($incidencium);
            return $this->redirectToRoute('cliente_detail', ['id' => $id_cliente], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incidencia/edit.html.twig', [
            'name_user'  => $this->getNameUser(),
            'incidencium' => $incidencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{id_cliente}', name: 'app_incidencia_delete', methods: ['POST'])]
    public function delete(Request $request, Incidencia $incidencium, IncidenciaRepository $incidenciaRepository, string $id_cliente): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$incidencium->getId(), $request->request->get('_token'))) {
            $incidenciaRepository->remove($incidencium);
            $this->addFlash(
                'notice',
                'Incidencia eliminada!'
            );
        }

        return $this->redirectToRoute('cliente_detail', ['id' => $id_cliente], Response::HTTP_SEE_OTHER);
    }

    private function getNameUser(): string {
        if($this->getUser()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var Usuario $user */
            $user = $this->getUser();
            if($user?->getId()) {
                return $user->getNombre() . ' ' . $user->getApellido();
            }
        }
        return '';
    }

    private function getCurrentUser() {
        if($this->getUser()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var Usuario $user */
            $user = $this->getUser();
            if($user?->getId()) {
               return $user;
            }
        }
    }
}
