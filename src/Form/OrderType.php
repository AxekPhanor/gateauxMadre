<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('address', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices'=> $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('carrier', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-success btn-block',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
