<?php

namespace App\Form;

use App\Entity\AssignedItems;
use App\Entity\User;
use App\Entity\Inventory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getName() . ' ' . $u->getSurname() . ' (' . $u->getDepartment() . ')',
                'placeholder' => 'Select a user',
                'required' => true,
                'attr' => [
                    'class' => 'form-select', 
                ],
            ])
            ->add('inventoryId', EntityType::class, [
                'class' => Inventory::class,
                'choice_label' => fn(Inventory $i) => $i->getName() . ' - ' . $i->getType(),
                'required' => true,
                'disabled' => true, 
            ])
            ->add('qualityDescription', TextareaType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Optional quality notes'],
            ])
            ->add('serialNumber', TextType::class, [
                'required' => false,
                'label' => 'Serial Number',
                'attr' => ['placeholder' => 'Optional serial number'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssignedItems::class,
        ]);
    }
}