<?php

namespace App\Form;

use App\Entity\Medias;
use App\Entity\Tricks;
use App\Entity\TypesMedia;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_media', EntityType::class, [
                'class' => TypesMedia::class,
                'choice_label' => 'label',
            ])
            ->add('mediaFile', FileType::class, [
                'required' => false,
                'mapped' => false,
                'multiple' => true
                // 'attr'     => [
                //     'accept' => 'image/*',
                //     'multiple' => 'multiple'
                // ],
                // 'constraints' => [
                //     new File()
                // ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medias::class,
        ]);
    }
}
