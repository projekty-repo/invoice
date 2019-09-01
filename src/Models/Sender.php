<?php

class Sender extends MainModel
{
    use ModelTrait;

    protected function initialize(): void
    {
        $this->addHasOne('Address');
    }

    public function __toString(): string
    {
        return $this->name . ' NIP: ' . $this->nip . ' adres: ' . $this->address;
    }
}