<?php

/**
 * Class Zoo
 */
class Zoo {

    /**
     * @var Interface_Animal
     */
    private $_smallAnimal = null;

    /**
     * @var Interface_Animal
     */
    private $_largeAnimal = null;

    public function __constructor()
    {
        $this->_smallAnimal = new Cat();
        $this->_largeAnimal = new Horse();
    }

    public function setSmallAnimal(Interface_Animal &$animal)
    {
        $this->_smallAnimal = $animal;
    }

    public function setLargeAnimal(Interface_Animal &$animal)
    {
        $this->_largeAnimal = $animal;
    }
} 