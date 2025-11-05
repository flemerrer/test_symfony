<?php

    namespace App\Form;

    use App\Entity\Wish;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Choice;
    use Symfony\Component\Validator\Constraints\Image;

    class WishType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('idUser', TextType::class, ['label' => 'User ID'])
                ->add('title', TextType::class, ['label' => 'Title'])
                ->add('description', TextAreaType::class, ['label' => 'Description'])
                ->add('author', TextType::class, ['label' => 'Author'])
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
