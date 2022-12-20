<?php

namespace App\Form;


use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use function Sodium\add;

class ProductType extends AbstractType
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


            ->add('photo', FileType::class,[
                'label'=>'Photo',
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

            ->add('dateDebut',DateType::class,[
                'label'=>'Date de debut prestation',
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])

            ->add('dateFin',DateType::class,[
                'label'=>'Date de fin prestation',
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])

            ->add('prix', NumberType::class,[
                'label'=>'Prix de votre prestation',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le prix de votre prestation'
                ]
            ])
            ->add('participant',IntegerType::class,[
                'label'=>'Nombre de participant',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Nombre participant'
                ]
            ])
            ->add('ville', TextType::class,[
                'label'=>'Lieu prestation',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez  votre lieu prestation'
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
