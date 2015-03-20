<?php

namespace Event\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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

    public function getEvents(Array $ids = array())
    {
        $rowset = $this->tableGateway->select(array('id' => $ids));
        return $rowset;
    }

    // Explain SELECT id FROM `event` as e where '16:30' between e.from and e.to OR '16:45' between e.from and e.to
    public function isNotIntersect(\DateTime $from, \DateTime $to)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($from, $to) {
            $select->where("'{$from->format('H:i')}' BETWEEN `from` AND `to` OR '{$to->format('H:i')}' BETWEEN `from` AND `to`");
        });
        return ($rowset->count()) ? false : true;
    }

    public function saveEvent(Event $event)
    {
        $data = array(
            'user_id'   => $event->user_id,
            'name'      => $event->name,
            'date'      => $event->date,
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