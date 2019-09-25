<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Customer;
class CustomerType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('firstName')
      ->add('lastName')
      ->add('dateOfBirth', null, array(
            'widget' => 'single_text',
            'time_widget' => 'choice',
            'required' => false, 
        )
      )
      ->add('status', null, [
        'required'   => false,
        'empty_data' => 'new',
        ]
      )
      ->add('save', SubmitType::class)
    ;
  }
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Customer::class,
      'csrf_protection' => false,
      'allow_extra_fields' => true,
    ));
  }
}