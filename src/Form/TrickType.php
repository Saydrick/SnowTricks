<?php

namespace App\Form;

use DateTimeZone;
use App\Entity\Users;
use App\Entity\Tricks;
use DateTimeImmutable;
use App\Entity\TrickGroups;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

// use Symfony\Component\Validator\Constraints as Assert;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length(min: 10)
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('user', EntityType::class, [
                'class' => users::class,
                'choice_label' => 'username',
            ])
            ->add('trick_group', EntityType::class, [
                'class' => trickGroups::class,
                'choice_label' => 'label',
            ])
            ->add('save' , SubmitType::class, [
                'label' => 'Créer'
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoTimestamps(...))
        ;
    }

    public function autoSlug(PostSubmitEvent $event): void
    {
        $trick = $event->getData();
        $slugger = new AsciiSlugger();
        $slug = strtolower($slugger->slug($trick->getName()));
        $trick->setSlug($slug);
    }

    // TODO Modifier la timezone
    public function autoTimestamps(PostSubmitEvent $event): void
    {
        $data = $event->getData();
     
        $timezone = new DateTimeZone('Europe/Paris');

        $data->setUpdatedAt(new DateTimeImmutable('now', $timezone));
        if(!$data->getId())
        {
            $data->setCreatedAt(new DateTimeImmutable('now', $timezone));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
