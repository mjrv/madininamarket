<?php

namespace MarketplaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ShopType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commercialName')
            ->add('raisonSocial')
            ->add('immatriculation')
            ->add('apeCode')
            ->add('nameGerant')
            ->add('phone')
            ->add('phone2')
            ->add('email')
            ->add('adress')
            ->add('city')
            ->add('zipcode')
            ->add('logo')
            ->add('prefixeRef')
            ->add('cover')
            ->add('active')
            ->add('generateAutoRef',HiddenType::class,[
                'data' => '00000',
            ])
            ->add('retraitMag',ChoiceType::class,[
                'label' => 'Possibilité retrait magasin ?',
                'choices' =>array(
                    '?' => null,
                    'oui' => 1,
                    'non' => 0, 
                ),
                ])
            ->add('user',EntityType::class, array(
                                                'class'=> 'MarketplaceBundle\Entity\User',
                                                'choice_label' => 'username',
                                                'multiple' => true,
                                                'expanded' => false,  
                                                ))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MarketplaceBundle\Entity\Shop'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'marketplacebundle_shop';
    }


}
