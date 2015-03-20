<?php

namespace Event;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Event\Model\Event;
use Event\Model\EventTable;
use Event\Model\User;
use Event\Model\UserTable;
use Event\Form\EventForm;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ViewHelperProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Event\Model\EventTable' =>  function($sm) {
                    $tableGateway = $sm->get('EventTableGateway');
                    $table = new EventTable($tableGateway);
                    return $table;
                },
                'Event\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'Event\Form\EventForm' =>  function($sm) {
                    $form = new EventForm('event', $sm);
                    return $form;
                },
                'EventTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Event());
                    return new TableGateway('event', $dbAdapter, null, $resultSetPrototype);
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },

            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'event_helper' => function ($sm) {
                    $helper = new View\Helper\EventHelper($sm);
                    return $helper;
                },
            ),

        );
    }
}
