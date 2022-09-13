<?php

namespace App\Form;

use App\Entity\Pizza;
use App\Entity\Ingredient;
use App\Entity\PizzaIngredient;
use App\Repository\PizzaRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaType extends AbstractType
{    

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')       
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pizza::class,
            'allow_extra_fields' => true,
        ]);
    }
}
