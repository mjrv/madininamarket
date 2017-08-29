<?php

namespace MarketplaceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoleType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                    'choices' => array(
                         'Editeur'=>'ROLE_EDITOR',
                         //'Chef'=>'ROLE_SELLER', #role deu chef de boutique
                         'Admin'=>'ROLE_ADMIN'
                    ),
                    'choices_as_values' => true,
                )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }


}
