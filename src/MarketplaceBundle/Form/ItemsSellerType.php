<?php

namespace MarketplaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use MarketplaceBundle\Form\PictureType;


class ItemsSellerType extends AbstractType
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
            ->add('shipmentType',EntityType::class, array(
                                                'class' => 'MarketplaceBundle\Entity\ShipmentPrice',
                                                'choice_label' => 'type',
                                                'multiple' => false,
                                                'expanded' => false,
            ))
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
            ->add('picture', CollectionType::class, array(
                'entry_type' => PictureType::class,
                'allow_add' =>true,
                'allow_delete'=> true,
                'required' =>false
                ))
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
