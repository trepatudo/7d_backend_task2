<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DateTimezoneFormType extends AbstractType
{
    private bool $useTimezoneAsSelect;

    public function __construct(bool $useTimezoneAsSelect)
    {
        $this->useTimezoneAsSelect = $useTimezoneAsSelect;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('date', DateType::class, [
                'required' => true, // default is true, but just in case
                'widget'   => 'single_text',
                'format'   => 'Y-m-d',
                'html5'    => false,
            ])
            ->add('timezone',
                $this->useTimezoneAsSelect ? TimezoneType::class : TextType::class,
                [
                    'required' => true, // default is true, but just in case
                    'constraints' => new Assert\Timezone()
                ])
            ->add('handle', SubmitType::class, ['label' => 'Submit Date']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options hereO
        ]);
    }
}
