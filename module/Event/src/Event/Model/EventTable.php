<?php

namespace Event\Model;

use Zend\Db\TableGateway\TableGateway;

class EventTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getEvent($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEvent(Event $event)
    {
        $data = array(
            'user_id'   => $event->user_id,
            'name'      => $event->name,
            'from'      => $event->from,
            'to'        => $event->to,
        );

        $id = (int) $event->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getEvent($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Event id does not exist');
            }
        }
    }

    public function deleteEvent($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}