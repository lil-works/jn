<?php

namespace AppBundle\Form;


use AppBundle\Entity\FingeringFinger;
use AppBundle\Form\Type\FingeringFingerType;
use AppBundle\Form\Type\DifficultyType;
use AppBundle\Form\Type\StringCaseType;
use AppBundle\Form\Type\FingersType;
use AppBundle\MyClass\FingeringFormater;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FingeringType extends AbstractType
{

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    private $fingersContainer = array();

    /**
     * Constructor
     *
     * @param Doctrine $doctrine
     */
    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $fingering = $event->getData();
            $form = $event->getForm();
            $a = array();
            for($s=0;$s<10;$s++){
                for($c=0;$c<10;$c++){
                    $a[$s."_".$c] = $s."_".$c;
                    $ff = new FingeringFinger();
                    $ff->setX($c);
                    $ff->setY($s);
                    $ff->setFingering($fingering);
                    $fingering->addFinger($ff);
                }
            }

        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $fingering = $event->getData();
            $ff = new FingeringFormater($fingering["fingers"] );
            foreach($ff->formatedData as $sc ){
                array_push($this->fingersContainer,$sc);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $fingering = $event->getData();
            $form = $event->getForm();

            foreach($fingering->getFingers() as $finger){
                $fingering->removeFinger($finger);
            }
            foreach($this->fingersContainer as $coordonate){
                $ff = new FingeringFinger();
                $ff->setX($coordonate["x"]);
                $ff->setY($coordonate["y"]);
                $ff->setLh($coordonate["lh"]);
                $ff->setRh($coordonate["rh"]);
                $ff->setFingering($fingering);
                $fingering->addFinger($ff);
            }

        });


        $builder
            ->add('description')
            ->add('difficulty',DifficultyType::class)
            ->add('arpeggio')
            ->add('fingers', CollectionType::class, array(
                'mapped'=>true,
                'allow_add'=>true,
                'required' => false,
                'entry_type'   => FingeringFingerType::class,


            ))
        /*    ->add('fingers', CollectionType::class, array(
                'mapped'=>true,
                'allow_add'=>true,
                'required' => false,
                'entry_type'   => FingersType::class,

            ))*/
            ;



    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Fingering'
        ));
    }
}
