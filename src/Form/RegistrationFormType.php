<?php

declare(strict_types=1);

namespace App\Form;

use App\Domain\Model\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('email', EmailType::class, [
				'attr' => [
					'class' => 'bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3',
				],
			])
			->add('agreeTerms', CheckboxType::class, [
				'mapped' => false,
				'constraints' => [
					new IsTrue([
						'message' => 'You should agree to our terms.',
					]),
				],
			])
			->add('plainPassword', PasswordType::class, [
				'mapped' => false,
				'attr' => [
					'class' => 'bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3',
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Please enter a password',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Your password should be at least {{ limit }} characters',
						'max' => 4096,
					]),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Usuario::class,
		]);
	}
}
