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

    public function fetchAll(Array $dates = null)
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();

        if (!empty($dates)) {
            $select->where(array('date' => $dates));
        }
        $select->order('date ASC');
        $select->order('from ASC');
        $statement = $sql->prepareStatementForSqlObject($select);

        return $statement->execute();
    }

    // SELECT date FROM event GROUP BY date ORDER BY date DESC
    public function getDates()
    {
        $sql = $this->tableGateway->getSql();
        $select = $sql->select();

        $select->columns(array('date'));
        $select->group('date');
        $select->where('TIMESTAMP(`date`,`to`) > NOW()');
        //$select->order('date ASC');
        $select->limit(10);
        $statement = $sql->prepareStatementForSqlObject($select);

        return $statement->execute();
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
    public function isNotIntersect($id, \DateTime $date, \DateTime $from, \DateTime $to)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($id, $date, $from, $to) {
            $select->where(array("('{$from->format('H:i')}' > `from` AND '{$from->format('H:i')}' < `to` OR '{$to->format('H:i')}' > `from` AND '{$to->format('H:i')}' < `to`)", "`id` != $id", "`date` = '{$date->format('Y-m-d')}'" ));
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