<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Mairie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserregisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idDiscord', TextType::class)
            ->add('mail', EmailType::class)
            ->add('password', RepeatedType::class, array(
				'type' => PasswordType::class,
				'first_options' => array('label' => 'Password'),
				'second_options' => array('label' => 'Repeat Password'),
			))
            ->add('pseudo', TextType::class)
            ->add('villeMairie', EntityType::class, [
                'class' => Mairie::class,
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->createQueryBuilder('mairie')->orderBy('mairie.ville', 'ASC');
                 },
					'choice_value' => 'ville',
                    'choice_label' => 'ville',
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
