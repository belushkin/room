<?php

namespace Event\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Event implements InputFilterAwareInterface, ServiceLocatorAwareInterface
{
    public $id;
    public $user_id;
    public $name;
    public $date;
    public $from;
    public $to;

    protected $inputFilter;
    protected $serviceLocator;

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
                'name'     => 'date',
                'required' => true,
                'validators'  => array(
                    array(
                        'name'    => 'Date',
                        'options' => array(
                            'format' => 'Y-m-d',
                        ),
                    ),
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

            $inputFilter->add(array(
                'name'     => 'from',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Date',
                        'options' => array(
                            'format' => 'H:i',
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'to',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Date',
                        'options' => array(
                            'format' => 'H:i',
                        ),
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'The To should be greater than From',
                            ),
                            'callback' => function($value, $context = array()) {
                                $from = \DateTime::createFromFormat('H:i', $context['from']);
                                $to = \DateTime::createFromFormat('H:i', $value);
                                return $to > $from;
                            },
                        ),
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'The Teo should be greater than From',
                            ),
                            'callback' => function($value, $context = array()) {
                                $from = \DateTime::createFromFormat('H:i', $context['from']);
                                $to = \DateTime::createFromFormat('H:i', $value);
                                print_r($this->serviceLocator->get('Event\Model\EventTable')->isIntersect($from, $to));
                                exit();
                                return true;
//                                var_dump($to == '16:38');
//                                return $to == '16:38';
                            },
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getServiceLocator ()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}