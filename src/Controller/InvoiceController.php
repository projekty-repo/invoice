<?php

class InvoiceController extends Controller
{
    /**
     * @var Invoice
     */
    private $invoice;

    public function indexAction(): void
    {
        $invoices = Invoice::createManager()->findAll();
        $this->view->set('invoices', $invoices);
    }

    public function viewAction(int $id): void
    {
        $this->setInvoice($id);
    }

    public function addAction(): void
    {
        $this->setView('Invoice/add_or_edit');
        $this->setInvoice();
        $this->addOrEdit();
        $this->saveIfFormSended();
    }

    public function editAction(int $id): void
    {
        $this->setView('Invoice/add_or_edit');
        $this->setInvoice($id);
        $this->addOrEdit();
        $this->saveIfFormSended();
    }

    public function deleteAction(int $id): void
    {
        $this->setInvoice($id);
        $this->invoice->getManager()->delete();

        Message::set('Faktura została usunięta');
        Router::redirect('invoice', 'index');
    }

    private function setInvoice(int $id = null): void
    {
        if ($id) {
            $this->invoice = Invoice::createManager()->findById($id);
            $this->redirectToRefererIfNotExists($this->invoice);
        } else {
            $this->invoice = new Invoice();
        }

        $this->view->set('invoice', $this->invoice);
    }

    private function addOrEdit(): void
    {
        $senders = Sender::createManager()->findAll();
        $suppliers = Supplier::createManager()->findAll();

        $this->view->set('suppliers', $suppliers);
        $this->view->set('senders', $senders);
    }

    private function saveIfFormSended(): void
    {
        $request = Request::create();
        if ($request->isEmpty() || !$request->isPost()) {

            return;
        }

        $invoice = new Invoice($request->all());

        $formValid = $this->validate($invoice);
        if (!$formValid) {

            return;
        }

        $invoiceId = $invoice->getManager()->save();
        Message::set('Dane faktury zostały zapisane');
        Router::redirect('invoice', 'view', ['id' => $invoiceId]);
    }

    private function validate(Invoice $invoice): bool
    {
        $validator = $this->createValidator();
        $validator->validate($invoice);
        $errors = $validator->getErrors();
        if (!$errors) {

            return true;
        }

        $senderId = $invoice->sender_id ?? null;
        if ($senderId) {
            $invoice->sender = Sender::createManager()->findById($senderId);
        }

        $supplierId = $invoice->supplier_id ?? null;
        if ($supplierId) {
            $invoice->supplier = Supplier::createManager()->findById($supplierId);
        }

        $this->view->set('errors', $errors);
        $this->view->set('invoice', $invoice);

        return false;
    }
}