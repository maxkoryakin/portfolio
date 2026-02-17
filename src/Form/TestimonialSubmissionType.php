<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TestimonialSubmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locale = strtolower((string) $options['locale']);
        $suffix = $locale === 'fr' ? 'Fr' : 'En';

        $builder
            ->add('authorName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 150]),
                ],
            ])
            ->add('authorRole', TextType::class, [
                'required' => false,
                'property_path' => 'authorRole'.$suffix,
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'property_path' => 'company'.$suffix,
            ])
            ->add('content', TextareaType::class, [
                'property_path' => 'content'.$suffix,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 10]),
                ],
                'attr' => ['rows' => 5],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
            'locale' => 'en',
        ]);

        $resolver->setAllowedValues('locale', ['en', 'fr']);
    }
}
