<?php

class Address extends MainModel
{
    use ModelTrait;

    public function __toString(): string
    {
        return 'ul. ' . $this->street . ' ' . $this->number . ' ' . $this->postcode . ' ' . $this->city;
    }
}