<?php

namespace Event\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Event\Model\Event;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventHelper extends AbstractHelper
{
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke(Event $event)
    {
        $user = $this->serviceLocator->getServiceLocator()->get('Event\Model\UserTable')->getUser($event->user_id);
        return "{$user->name}, " . date('H:i', strtotime($event->from)) . " - " . date('H:i', strtotime($event->to)) . "h, " . date("F jS", strtotime($event->date));
    }

}