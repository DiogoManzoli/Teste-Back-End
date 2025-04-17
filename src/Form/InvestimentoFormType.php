<?php

namespace App\Form;

use App\Entity\Investimento;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestimentoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('saldo_inicial', NumberType::class, [
            'label' => false, 
            'attr' => [
                'placeholder' => 'Digite um número']
        ])
        ->add('data_investimento', DateType::class, [
            'widget' => 'single_text',
            'label' => false, 
            'attr' => [
                'id' => 'dataInput',
                'onchange' => 'atualizaSaida()',
            ],
            'constraints' => [
                new Assert\LessThan([
                    'value' => 'today',
                    'message' => 'A data não pode ser no futuro.',
                ]),
            ],
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Investimento::class,
        ]);
    }
}
