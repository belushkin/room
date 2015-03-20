<?php

namespace Event\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventListForm extends Form implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;
    protected $eventTable;
    protected $userTable;

    public function __construct($name = null, ServiceLocatorInterface $serviceLocator)
    {
        // we want to ignore the name passed
        parent::__construct('event-list');

        $this->setServiceLocator($serviceLocator);

        // Fetching all users for Organizer
        $options = array();
        $events = $this->getEventTable()->fetchAll();
        foreach ($events as $event) {
            $user = $this->getUserTable()->getUser($event->user_id);
            $options[$event->id] = "{$user->name}, " . date('H:i', strtotime($event->from)) . " - " . date('H:i', strtotime($event->to)) . "h, " . date("F jS", strtotime($event->date));
        }

        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'list-checkbox',
            'options' => array(
                'value_options' => $options,
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value'     => 'Delete',
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

    public function getEventTable()
    {
        if (!$this->eventTable) {
            $sm = $this->getServiceLocator();
            $this->eventTable = $sm->get('Event\Model\EventTable');
        }
        return $this->eventTable;
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