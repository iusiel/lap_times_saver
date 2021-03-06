<?php

namespace App\Form;

use App\Entity\Track;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrackType extends AbstractType
{
    private $kernelInterface;

    public function __construct(string $env)
    {
        $this->kernelInterface = $env;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add('Name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'maxlength' => 255,
            ],
            'constraints' => [new NotBlank(), new Length(['max' => 255])],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
            'csrf_protection' =>
                $this->kernelInterface === 'test' ? false : true,
        ]);
    }
}
