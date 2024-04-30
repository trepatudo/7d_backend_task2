<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DateTimezoneFormType extends AbstractType
{
    private bool   $useTimezoneAsSelect;
    private string $dateFormat;

    public function __construct(bool $useTimezoneAsSelect, string $dateFormat)
    {
        $this->useTimezoneAsSelect = $useTimezoneAsSelect;
        $this->dateFormat          = $dateFormat;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $exampleDate = date($this->dateFormat);

        $builder
            ->add('date', TextType::class, [
                'required' => true, // default is true, but just in case
                'label' => "Date (format: {$this->dateFormat}; example: {$exampleDate})",
                // better to use TextType with constraint,
                // since we will initiate our own DateTime later
                'constraints' => new Assert\DateTime($this->dateFormat)
            ])
            ->add('timezone',
                $this->useTimezoneAsSelect ? TimezoneType::class : TextType::class,
                [
                    'required'    => true, // default is true, but just in case
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
