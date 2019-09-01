<?php

class Invoice extends MainModel
{
    use ModelTrait;

    protected function initialize(): void
    {
        $this->addHasMany('InvoiceItem');
        $this->addHasOne('Supplier');
        $this->addHasOne('Sender');
    }

    public function countItems(): int
    {
        return !empty($this->invoiceItems) ? count($this->invoiceItems) : 0;
    }

    public function countValue(): int
    {
        $value = 0;
        foreach ($this->invoiceItems as $invoiceItem) {
            $value += $invoiceItem->price;
        }

        return $value;
    }
}