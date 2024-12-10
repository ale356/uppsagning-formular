<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['services'] as $service) {
            $builder->add("service_{$service['id']}", CheckboxType::class, [
                'label' => $service['name'],
                'required' => false,
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Next',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'services' => [], // Expect an array of services
        ]);
    }
}