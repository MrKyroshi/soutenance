<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditPrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title', TextType::class, [
                'label' => 'Votre prestation',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez le titre de votre prestation'
                ]
            ])
            ->add('category', TextType::class, [
                'label' => 'Votre catégorie',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez votre catégory'
                ]
            ])

            ->add('duration',TextType::class,[
                'label'=> 'la durée de votre prestation',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez la durée de votre prestation'
                ]
            ])
            ->add('editPhoto', FileType::class,[
                'label'=>'Edit Photo',
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            'image/gif',
                            'image/jfif',
                            'image/webp'
                        ],
                        'mimeTypesMessage'=>'Format non géré'
                    ])
                ]
            ])
            ->add('date',DateType::class,[
                'widget' => 'choice',

            ])
            ->add('prix', NumberType::class,[
                'label'=>'Prix de votre prestation',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le prix de votre prestation'
                ]
            ])
            ->add('description', TextareaType::class,[
                'label'=>'Description de votre prestation',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez une description de votre prestation'
                ]
            ])

            ->add('enregistrer', SubmitType::class);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
