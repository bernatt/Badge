<?php

namespace vending_machineBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class machineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName', TextType::class, ['label' => 'Nazwa Produktu:',
                    'attr' =>[
                        'placeholder' => 'Wpisz nazwę produktu',
                ]])
            ->add('price', NumberType::class, ['label' => 'Cena:',
                'attr' =>[
                    'placeholder' => 'Cena produktu',
                ]])
            ->add('stock', IntegerType::class, ['label' => 'Ilość:',
                'attr' =>[
                    'min' => 1,
                    'max' =>15,
                    'placeholder' => 'Ilość produktu',
                ]])
            ->add('save', SubmitType::class, ['label' => 'Dodaj produkt']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'vending_machineBundle\Entity\machine'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'vending_machinebundle_machine';
    }
}

