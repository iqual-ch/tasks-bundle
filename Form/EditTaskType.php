<?php
namespace TasksBundle\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('?');
        
        $builder->add('description', 'textarea', array(
            'label' => 'label.description',
            'translation_domain' => 'TasksBundle'
        ));

        $builder->add('deadline', 'text', array(
            'label' => 'label.deadline',
            'translation_domain' => 'TasksBundle',
            'data' => (new DateTime('now + 1 week'))->format('d.m.Y')
        ));

        $builder->add('remindIn', 'number', array(
            'label' => 'label.remind_in_days',
            'translation_domain' => 'TasksBundle',
            'data' => 7
        ));

        $builder->add('alert_date', 'text', array(
            'label' => 'label.alert_date',
            'translation_domain' => 'TasksBundle',
            'data' => (new DateTime('now + 1 week'))->format('d.m.Y')
        ));

        $builder->add('id', 'hidden', array(

        ));

        $builder->add('submit', 'submit', array(
            'label' => 'label.save',
            'translation_domain' => 'TasksBundle',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));
    }

    public function getName()
    {
        return '';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}