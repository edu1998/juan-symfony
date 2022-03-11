<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController
{
    #[Route('/cliente', name: 'cliente')]
    public function index(): Response
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
            'controller_name' => 'ClienteController',
            'name_user'       => $this->getNameUser()
        ]);
    }

    #[Route('/cliente/registro', name: 'cliente/registro')]
    public function registrar(): Response
    {
        return $this->render('cliente/registro.html.twig', [
            'name_user'       => $this->getNameUser()
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
