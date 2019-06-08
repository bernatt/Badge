<?php

namespace vending_machineBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class machineEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName', TextType::class, ['label' => 'Nazwa Produktu:'])
            ->add('price', NumberType::class, ['label' => 'Cena:'])
            ->add('stock', IntegerType::class, ['label' => 'Ilość:',
                'attr' =>[
                    'min' => 1,
                    'max' =>15,
                ]])
            ->add('save', SubmitType::class, ['label' => 'Edytuj produkt']);
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

