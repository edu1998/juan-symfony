<?php

namespace App\Form;

use App\Entity\Cliente;
use App\Entity\Incidencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('nombre', TextType::class ,[])
            ->add('apellido', TextType::class ,['required' => false,])
            ->add('telefono', TextType::class, [])
            ->add('direccion', TextType::class, ['required' => false,])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }

}