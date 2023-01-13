<?php

namespace App\Form;

use App\Entity\Facturation;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;


class FacturationType extends AbstractType
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
            ->add('qteP')
            ->add('rem')
            
            ->add('produit')
        ;
       
    }

    protected function addElements(FormInterface $form, Produit $produit = null) {
        // 4. Add the province element
        $form->add('produit', EntityType::class, array(
            'required' => true,
            'data' => $produit,
            'placeholder' => 'Sélectionnez un produit...',
            'class' => Produit::class
        ));  
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // Search for selected City and convert it into an Entity
        $produit = $this->em->getRepository(Produit::class)->find($data['produit']);
        
        $this->addElements($form, $produit);
    }

    function onPreSetData(FormEvent $event) {
        $facturation = $event->getData();
        $form = $event->getForm();

        $produit = $facturation->getProduit() ? $facturation->getProduit() : null;
        
        $this->addElements($form, $produit);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facturation::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_facturation';
    }
}
