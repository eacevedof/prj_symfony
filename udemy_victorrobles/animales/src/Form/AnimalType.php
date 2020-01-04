<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("tipo", TextType::class,[
                        "label"=>"Tipo animal",
                        "attr"=>[
                            "class"=>"input"
                        ]
                    ])
                    ->add("color", TextType::class)
                    ->add("raza", TextType::class)
                    ->add("submit", SubmitType::class, [
                        "label"=>"Crear Animal",
                        "attr"=>[
                            "class"=>"btn"
                        ]
                    ])
                    ->getForm()
                    ;
    }
}//AnimalType