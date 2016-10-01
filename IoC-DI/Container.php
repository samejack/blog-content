<?php

/**
 * Class Container
 */
class Container {

    public function __constructor()
    {
        $zoo = new Zoo();
        $zoo->setSmallAnimal(new Cat());
        $zoo->setLargeAnimal(new Horse());
    }

}