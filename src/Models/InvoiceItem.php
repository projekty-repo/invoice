<?php

class InvoiceItem extends MainModel
{
    use ModelTrait;

    protected function initialize(): void
    {
        $this->addBelongsTo('Invoice');
    }
}