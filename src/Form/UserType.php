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
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var mixed
     */
    private $user;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
                'mapped' => $options['required']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => $options['required'],
            ]);

        if ($this->security->isGranted("form_user")) {
            $this->user = $options['data'];
            $builder->add('roles', ChoiceType::class, [
                'choices' => $this->getRolesChoices(),
                'choice_attr' => function ($choice) {
                    if ($choice === $this->user->getRoles()[0]) {
                        return ['selected' => true];
                    }
                    return [];
                },
                'mapped' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
        ]);
        $resolver->setAllowedTypes('required', 'bool');
    }

    private function getRolesChoices()
    {
        return [
            'Role utilisateur' => 'ROLE_USER',
            'Role administrateur' => 'ROLE_ADMIN'
        ];
    }
}
