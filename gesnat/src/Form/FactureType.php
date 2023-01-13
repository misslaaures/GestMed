<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;


class FactureType extends AbstractType
{

    private $em;
    
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('patient')
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Patient $patient = null) {
        // 4. Add the province element
        $form->add('patient', EntityType::class, array(
            'required' => true,
            'data' => $patient,
            'placeholder' => 'Sélectionnez un Patient...',
            'class' => Patient::class
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        $patient = $this->em->getRepository(Patient::class)->find($data['patient']);
        
        $this->addElements($form, $patient);
    }

    function onPreSetData(FormEvent $event) {
        $facture = $event->getData();
        $form = $event->getForm();

        $patient = $facture->getPatient() ? $facture->getPatient() : null;
        
        $this->addElements($form, $patient);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
