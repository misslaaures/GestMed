<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Patient;
use App\Entity\Praticien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

class ConsultationType extends AbstractType
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
            ->add('motif')
            ->add('examen')
            ->add('diagnostic')
            ->add('observation')
            ->add('patient')
            ->add('praticien')
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Patient $patient = null, Praticien $praticien = null) {
        // 4. Add the province element
        $form->add('patient', EntityType::class, array(
            'required' => true,
            'data' => $patient,
            'placeholder' => 'Sélectionnez un Patient...',
            'class' => Patient::class
        ));

        $form->add('praticien', EntityType::class, array(
            'required' => true,
            'data' => $praticien,
            'placeholder' => 'Sélectionnez un Praticien...',
            'class' => Praticien::class
        ));

    } 

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        $patient = $this->em->getRepository(Patient::class)->find($data['patient']);
        $praticien = $this->em->getRepository(Praticien::class)->find($data['praticien']);
        
        $this->addElements($form, $patient, $praticien);
    }

    function onPreSetData(FormEvent $event) {
        $consultation = $event->getData();
        $form = $event->getForm();

        $patient = $consultation->getPatient() ? $consultation->getPatient() : null;
        $praticien = $consultation->getPraticien() ? $consultation->getPraticien() : null;

        $this->addElements($form, $patient, $praticien);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
