<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Game;
use App\Entity\LapTime;
use App\Entity\Track;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 */
class LapTimeType extends AbstractType
{
    private $kernelInterface;

    public function __construct(string $env)
    {
        $this->kernelInterface = $env;
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('Date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('Game', EntityType::class, [
                'class' => Game::class,
                'choice_label' => 'Name',
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->orderBy('u.Name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-select js-select2',
                ],
            ])
            ->add('Car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'Name',
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->orderBy('u.Name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-select js-select2',
                ],
            ])
            ->add('Track', EntityType::class, [
                'class' => Track::class,
                'choice_label' => 'Name',
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->orderBy('u.Name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-select js-select2',
                ],
            ])
            ->add('Time', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                ],
                'constraints' => [new NotBlank(), new Length(['max' => 255])],
            ])
            ->add('IsPractice', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select',
                ],
                'choices' => [
                    'No' => 0,
                    'Yes' => 1,
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('ExtraNotes', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                ],
                'constraints' => [new Length(['max' => 255])],
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LapTime::class,
            'csrf_protection' =>
                $this->kernelInterface === 'test' ? false : true,
        ]);
    }
}
