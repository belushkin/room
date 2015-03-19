<?php

namespace Event\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Event\Model\Event;
use Event\Form\EventForm;

class EventController extends AbstractActionController
{

    protected $eventTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'events' => $this->getEventTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new EventForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $event = new Event();
            $form->setInputFilter($event->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $event->exchangeArray($form->getData());
                $this->getEventTable()->saveEvent($event);

                // Redirect to list of events
                return $this->redirect()->toRoute('event');
            }
        }
        return array('form' => $form);

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

        $form  = new EventForm();
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
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('event');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getEventTable()->deleteEvent($id);
            }

            // Redirect to list of events
            return $this->redirect()->toRoute('event');
        }

        return array(
            'id'    => $id,
            'event' => $this->getEventTable()->getEvent($id)
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
}

