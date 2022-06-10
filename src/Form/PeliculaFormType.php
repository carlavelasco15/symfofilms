<?php

namespace App\Form;

use App\Entity\Pelicula;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class PeliculaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class)
            ->add('duracion', TextType::class, [
                'empty_data' => 0,
            ])
            ->add('director', TextType::class)
            ->add('genero', TextType::class)
            ->add('sinopsis', TextareaType::class, [
                'required' => false
            ])
            ->add('estreno', IntegerType::class, [
                'required' => false
            ])
            ->add('valoracion', IntegerType::class, [
                'required' => false
            ])
            ->add('imagen', FileType::class, [
                'required' => false,
                'data_class' => NULL,
                'constraints' => [
                    new File([
                        'maxSize' => '10240k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Debes subir una imagen png, jpg o gif'
                    ])
                ]
            ])
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary my-3']
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pelicula::class,
        ]);
    }
}
