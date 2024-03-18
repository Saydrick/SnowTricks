<?php

namespace App\Form;

use App\Entity\users;
use App\Entity\Tricks;
use DateTimeImmutable;
use App\Entity\trickGroups;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('trick_group', EntityType::class, [
                'class' => TrickGroups::class,
                'choice_label' => 'label',
            ])
            ->add('save' , SubmitType::class, [
                'label' => 'Créer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoTimestamps(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        $slugger = new AsciiSlugger();
        $data['slug'] = strtolower($slugger->slug($data['title']));
        $event->setData($data);
    }

    public function autoTimestamps(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        $data->setUpdatedAt(new DateTimeImmutable());
        if(!$data->getId())
        {
            $data->setCreatedAt(new DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
