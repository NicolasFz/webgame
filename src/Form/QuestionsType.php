<?php

namespace App\Form;

use App\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('questions', CollectionType::class, array(
            'entry_type' => QuestionType::class,
            'entry_options' => array('label' => true),
            'label' => 'Add new questions',
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ))
        ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
        ]);
    }
}
