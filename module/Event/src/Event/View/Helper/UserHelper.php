<?php

namespace Event\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserHelper extends AbstractHelper
{
    protected $serviceManager;

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function __invoke(Array $event)
    {
        $user = $this->serviceManager->getServiceLocator()->get('Event\Model\UserTable')->getUser($event['user_id']);
        return $user->name;
    }

}
