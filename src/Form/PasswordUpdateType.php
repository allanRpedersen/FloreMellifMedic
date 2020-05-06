<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('oldPassword', PasswordType::class, [
				'label' => "ancien mot de passe",
				'attr' => [
					'placeholder' => "Donnez votre mot de passe actuel ..."
				]
			])
			->add('newPassword', PasswordType::class, [
				'label' => "nouveau mot de passe",
				'attr' => [
					'placeholder' => "Choisissez un bon mot de passe ..."
				]
			])
			->add('confirmPassword', PasswordType::class, [
				'label' => "confirmation du mot de passe",
				'attr' => [
					'placeholder' => "Veuillez confirmer votre nouveau mot de passe ..."
				]
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
