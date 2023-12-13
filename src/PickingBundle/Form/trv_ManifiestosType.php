<?php

namespace PickingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class trv_ManifiestosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('picking', TextType::class, [
                "label" => "Picking",
                "attr" => ["class" => "form-control", "maxlength" => 10, "autofocus" => true]
            ])*/
            /*->add('factura', TextType::class, [
                "label" => "No. Factura",
                "attr" => ["class" => "form-control"]
            ])*/
            //->add('fecha')
            //->add('hora')
            //->add('estatus')
            ->add('button', SubmitType::class, [
                "label" => "Imprimir",
                "attr" => ["class" => "form-submit btn btn-success"]
            ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PickingBundle\Entity\trv_picking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pickingbundle_trv_picking';
    }


}
