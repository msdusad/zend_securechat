<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Rituals implements InputFilterAwareInterface {

    public $id;
    public $traditional_id;
    public $name;
    public $num;
    public $day;
    public $content;
    public $ritual_id;
    public $date;
    public $user_id;
    public $cultural;
    public $preference;
    public $practical;
    public $interfaith;
     public $other;
    protected $inputFilter;

    public function exchangeArray($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->num = (isset($data['num'])) ? $data['num'] : null;
        $this->day = (isset($data['day'])) ? $data['day'] : null;
        $this->traditional_id = (isset($data['traditional_id'])) ? $data['traditional_id'] : null;
        $this->day = (isset($data['day'])) ? $data['day'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
        $this->ritual_id = (isset($data['ritual_id'])) ? $data['ritual_id'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->cultural = (isset($data['cultural'])) ? $data['cultural'] : null;
        $this->preference = (isset($data['preference'])) ? $data['preference'] : null;
        $this->practical = (isset($data['practical'])) ? $data['practical'] : null;
        $this->interfaith = (isset($data['interfaith'])) ? $data['interfaith'] : null;
        $this->other = (isset($data['other'])) ? $data['other'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();


            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'traditional_id',
                        'filters' => array(
                            array('name' => 'Null'),
                        ),
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}