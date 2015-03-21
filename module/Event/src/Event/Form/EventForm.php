<?php

namespace Event\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventForm extends Form implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;
    protected $userTable;

    public function __construct($name = null, ServiceLocatorInterface $serviceLocator)
    {
        // we want to ignore the name passed
        parent::__construct('event');

        $this->setServiceLocator($serviceLocator);

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'date',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => 'Date',
                'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'value' => date("Y-m-d", time()),
                'min' => '2015-01-01',
                'max' => '2020-01-01',
                'step' => '1',
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name of your event',
            ),
            'attributes' => array(
                'placeholder'   => 'Add a event',
                'class'         => 'form-control'
            )
        ));

        // Fetching all users for Organizer
        $options = array();
        $users = $this->getUserTable()->fetchAll();
        foreach ($users as $user) {
            $options[$user->id] = $user->name;
        }
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'user_id',
            'options' => array(
                'label' => 'Organizer',
                'value_options' => $options,
            ),
            'attributes' => array(
                'value' => '1',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'from',
            'type' => 'Zend\Form\Element\Time',
            'options' => array(
                'label' => 'From',
                'format'=> 'H:i'
            ),
            'attributes' => array(
                'min'   => '00:00',
                'max'   => '23:59',
                'step'  => '60', // seconds; default step interval is 60 seconds
                'value' => date('H:i',time()),
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'to',
            'type' => 'Zend\Form\Element\Time',
            'options' => array(
                'label' => 'To',
                'format'=> 'H:i'
            ),
            'attributes' => array(
                'min'   => '00:00',
                'max'   => '23:59',
                'step'  => '60', // seconds; default step interval is 60 seconds
                'value' => date('H:i',time()),
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value'     => 'Add event',
                'id'        => 'submitbutton',
                'class'     => 'btn btn-default'
            ),
        ));
    }

    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Event\Model\UserTable');
        }
        return $this->userTable;
    }

}