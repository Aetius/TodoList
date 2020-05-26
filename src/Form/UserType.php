<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'required' => $options['required'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => $options['required'],
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
                'mapped' => $options['required'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => $options['required'],
            ]);

        if (true === $options['with_role_choice']) {
            $this->user = $options['data'];
            $builder->add('role', ChoiceType::class, [
                'choices' => $this->getRolesChoices(),
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'with_role_choice' => false,
        ]);
        $resolver->setAllowedTypes('required', 'bool');
        $resolver->setAllowedTypes('with_role_choice', 'bool');
    }

    private function getRolesChoices()
    {
        return [
            'Role utilisateur' => 'ROLE_USER',
            'Role administrateur' => 'ROLE_ADMIN',
        ];
    }
}
