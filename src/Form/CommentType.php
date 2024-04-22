<?php

namespace App\Form;

use DateTimeZone;
use App\Entity\Users;
use App\Entity\Tricks;
use DateTimeImmutable;
use App\Entity\Comments;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentType extends AbstractType
{
    public function __construct(private Security $security)
    {
        
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => false
            ])
            ->add('trick', HiddenType::class)
            ->add('save' , SubmitType::class, [
                'label' => 'Commenter'
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoTimestamps(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoUser(...))
        ;
    }

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

    public function autoUser(PostSubmitEvent $event): void
    {
        $trick = $event->getData();
        $user = $this->security->getUser();
        $trick->setUser($user);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
