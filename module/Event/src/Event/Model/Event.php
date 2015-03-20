<?php

namespace Event\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Event implements InputFilterAwareInterface
{
    public $id;
    public $user_id;
    public $name;
    public $date;
    public $from;
    public $to;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id       = (!empty($data['id']))         ? $data['id']       : null;
        $this->user_id  = (!empty($data['user_id']))    ? $data['user_id']  : null;
        $this->name     = (!empty($data['name']))       ? $data['name']     : null;
        $this->date     = (!empty($data['date']))       ? $data['date']     : null;
        $this->from     = (!empty($data['from']))       ? $data['from']     : null;
        $this->to       = (!empty($data['to']))         ? $data['to']       : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            /*$inputFilter->add(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));*/

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}