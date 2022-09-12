<?php

namespace App\Form;

use App\Entity\Library;
use App\Entity\Shelf;
use App\Repository\LibraryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ShelfType extends AbstractType
{
    public function __construct(
        private LibraryRepository $libraryRepository,
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom',
                'required'=> true,
                ])
            ->add('image',VichImageType::class,[
                'label'=> 'l\'image représentant la collection'
            ])
            ->add('library', EntityType::class,[
                'class' => Library::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label' => 'app.library.add.shelf',
                'choices' => $this->libraryRepository->findAllByUser(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shelf::class,
        ]);
    }
}
