<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHash, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if($this->getUser()) {
            return $this->redirectToRoute('cliente');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // encode the plain password
            $user->setPassword(
                $userPasswordHash->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            /** @var UploadedFile $foto */
            $foto = $form->get('foto')->getData();
            $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();
            $foto->move(
                $this->getParameter('foto_directory'),
                $newFilename
            );

            $user->setFoto($newFilename);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('login');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form,
            'name_user' => ''
        ]);
    }
}
