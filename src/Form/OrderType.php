<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Book;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', EntityType::class, [
            'class' => Book::class,
            'choice_label' => 'title',
            'multiple' => true,
            'expanded' => false
            ])
            ->add('Quantity', IntegerType::class)
            ->add('UserName', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'FullName',
            ])
            ->add('UserPhone', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'Mobile',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
