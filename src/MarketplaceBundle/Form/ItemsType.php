<?php

namespace MarketplaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use MarketplaceBundle\Form\PictureType;


class ItemsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference',null,array('label' => 'label.reference'))
            ->add('name')
            ->add('description')
            ->add('priceHt')
            ->add('discount')
            ->add('stock',IntegerType::class, array('attr' =>array(
                'min' => '0',
                'max' => '10000',
                )))
            ->add('verify')
            ->add('tva',EntityType::class, array(
                                                'class'=> 'MarketplaceBundle\Entity\Tva',
                                                'choice_label' => 'value',
                                                'multiple' => false,
                                                'expanded' => false,  
                                                ))
            ->add('category',EntityType::class, array(
                                                'class'=> 'MarketplaceBundle\Entity\Category',
                                                'choice_label' => 'name',
                                                'multiple' => false,
                                                'expanded' => false,  
                                                ))
            ->add('shop', EntityType::class, array(
                                                'class'=> 'MarketplaceBundle\Entity\Shop',
                                                'choice_label' => 'commercialName',
                                                'multiple' => false,
                                                'expanded' => false,  
                                                ))
            ->add('picture', CollectionType::class, array(
                'entry_type' => PictureType::class,
                'allow_add' =>true,
                'allow_delete'=> true,
                'required' =>false
                ))
            // ->add('history', CollectionType::class, [
            //                     'attr' => [
            //                                 'hidden'=>true
            //                                 ],
            //                         ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MarketplaceBundle\Entity\Items'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'marketplacebundle_items';
    }


}
