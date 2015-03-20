<?php

namespace Event\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Event\Model\Event;

class EventController extends AbstractActionController
{

    protected $eventTable;
    protected $eventForm;
    protected $eventListForm;
    protected $eventModel;

    public function indexAction()
    {
        return new ViewModel(array(
            'events' => $this->getEventTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = $this->getEventForm();
        $list = $this->getEventListForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $event = $this->getEventModel();
            $form->setInputFilter($event->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $event->exchangeArray($form->getData());
                $this->getEventTable()->saveEvent($event);

                // Redirect to list of events
                return $this->redirect()->toRoute('event');
            }
        }
        return array(
            'form' => $form,
            'list'  => $list,
            'events' => $this->getEventTable()->fetchAll(),
            'messages' => $form->getMessages()
        );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('edit', array(
                'action' => 'add'
            ));
        }

        // Get the Event with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $event = $this->getEventTable()->getEvent($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('event', array(
                'action' => 'index'
            ));
        }

        $form = $this->getEventForm();
        $form->bind($event);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($event->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEventTable()->saveEvent($event);

                // Redirect to list of events
                return $this->redirect()->toRoute('event');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->redirect()->toRoute('event');
        }

        $ids = $request->getPost('list-checkbox', array());
        $del = $request->getPost('del', 'No');

        if (empty($ids)) {
            return $this->redirect()->toRoute('event');
        }

        if ($del == 'Yes') {
            foreach ($ids as $id) {
                $this->getEventTable()->deleteEvent($id);
            }
            // Redirect to list of events
            return $this->redirect()->toRoute('event');
        }

        return array(
            'events' => $this->getEventTable()->getEvents($ids)
        );
    }

    public function getEventTable()
    {
        if (!$this->eventTable) {
            $sm = $this->getServiceLocator();
            $this->eventTable = $sm->get('Event\Model\EventTable');
        }
        return $this->eventTable;
    }

    public function getEventModel()
    {
        if (!$this->eventModel) {
            $sm = $this->getServiceLocator();
            $this->eventModel = $sm->get('Event\Model\Event');
        }
        return $this->eventModel;
    }

    public function getEventForm()
    {
        if (!$this->eventForm) {
            $sm = $this->getServiceLocator();
            $this->eventForm = $sm->get('Event\Form\EventForm');
        }
        return $this->eventForm;
    }

    public function getEventListForm()
    {
        if (!$this->eventListForm) {
            $sm = $this->getServiceLocator();
            $this->eventListForm = $sm->get('Event\Form\EventListForm');
        }
        return $this->eventListForm;
    }

}

