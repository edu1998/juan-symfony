<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Porfavor ingrese un nombre',
                    ]),
                ]
            ])
            ->add('apellido', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese un apellido',
                    ]),
                ]
            ])
            ->add('telefono', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese un telefono',
                    ]),
                ]
            ])
            ->add('email', EmailType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese un email',
                    ]),
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese una contraseña',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('foto', FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor sube una foto ',
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Registrarme'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
