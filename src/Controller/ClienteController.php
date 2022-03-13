<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Usuario;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use App\Repository\IncidenciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    #[Route('/cliente', name: 'cliente')]
    public function index(ClienteRepository $clienteRepository): Response
    {
        $name = '';
        if($this->getUser()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var Usuario $user */
            $user = $this->getUser();
            if($user?->getId()) {
                $name = $user->getNombre() . ' ' . $user->getApellido();
            }
        }

        return $this->render('cliente/index.html.twig', [
            'clientes' => $clienteRepository->findAll(),
            'name_user'       => $this->getNameUser()
        ]);
    }

    #[Route('/cliente/registro', name: 'cliente_registro')]
    public function registrar(Request $request, ClienteRepository $clienteRepository): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $clienteRepository->add($cliente);
            return $this->redirectToRoute('cliente', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cliente/registro.html.twig', [
            'name_user'  => $this->getNameUser(),
            'registerForm'       => $form,
        ]);
    }

    #[Route('/cliente/borrar/{id}', name: 'cliente_borrar', methods: ['POST'])]
    public function borrar(Request $request, Cliente $cliente, ClienteRepository $clienteRepository) {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $clienteRepository->remove($cliente);
        }
        return $this->redirectToRoute('cliente', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cliente/detalle/{id}', name: 'cliente_detail')]
    public function detalle(Request $request, Cliente $cliente, ClienteRepository $clienteRepository) {

        return $this->render('cliente/detail.html.twig', [
            'name_user'  => $this->getNameUser(),
            'cliente'    => $cliente
        ]);
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
}
