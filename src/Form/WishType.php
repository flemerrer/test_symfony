<?php

    namespace App\Form;

    use App\Entity\Wish;
    use App\Entity\WishCategory;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Image;

    class WishType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $wish = $event->getData();
                $form = $event->getForm();

                if ($wish && $wish->getImageFilename()) {
                    $form->add('deleteCb', CheckboxType::class, [
                        'mapped' => false,
                        'required' => false,
                        'label' => 'Check me to delete existing image'
                    ]);
                }
            });

            $builder
                ->add('title', TextType::class, ['label' => 'Title'])
                ->add('wishCategory', EntityType::class, [
                    'class' => WishCategory::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Choose a category'
                ])
                ->add('description', TextAreaType::class, ['label' => 'Description'])
                ->add('published', CheckboxType::class, ['label' => 'Published Status', 'required' => false])
                ->add('illustration', FileType::class, [
                    'label' => 'Image',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [new Image([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                        'maxSize' => '1024k'
                    ])]
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Wish::class,
            ]);
        }
    }
