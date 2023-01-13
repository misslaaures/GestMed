<?php

namespace App\Form;

use App\Entity\Consultation;
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


class ConsultationsType extends AbstractType
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
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Patient $patient = null) {
        // 4. Add the province element
        $form->add('patient', EntityType::class, array(
            'required' => true,
            'data' => $patient,
            'placeholder' => 'Sélectionnez un patient...',
            'class' => Patient::class
        ));
        
        // Consultations empty, unless there is a selected Patient (Edit View)
        $consultations = array();
        
        // If there is a patient stored in the Person entity, load the consultations of it
        if ($patient) {
            // Fetch Consultations of the Patient if there's a selected patient
            $repoConsultation = $this->em->getRepository(Consultation::class);
            
            $consultations = $repoConsultation->createQueryBuilder("q")
                ->where("q.patient = :patientid")
                ->setParameter("patientid", $patient->getId())
                ->getQuery()
                ->getResult();
        }
        
        // Add the Consultations field with the properly data
        $form->add('consultation', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Sélectionnez un patient au préalable...',
            'class' => Consultation::class,
            'choices' => $consultations
        ));
    }
    
    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // Search for selected Patient and convert it into an Entity
        $patient = $this->em->getRepository(Patient::class)->find($data['patient']);
        
        $this->addElements($form, $patient);
    }

    function onPreSetData(FormEvent $event) {
        $consult = $event->getData();
        $form = $event->getForm();

        // When you create a new consult, the Patient is always empty
        $patient = $consult->getPatient() ? $consult->getPatient() : null;
        
        $this->addElements($form, $patient);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_consultation';
    }
}
