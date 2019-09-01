<?php

abstract class Controller
{
    /**
     * @var View
     */
    protected $view;

    public function __construct()
    {
        $this->setView();
    }

    final public function setView(string $viewFile = null): void
    {
        $viewFile = $viewFile ?? Router::getView();
        $viewFile = Loader::SRC . '/' . Loader::VIEW_FOLDER . '/' . $viewFile . '.php';
        Loader::loadView();
        $this->view = new View($viewFile);
    }

    final public function getView(): View
    {
        return $this->view;
    }

    protected function redirectToRefererIfNotExists(?Model $modelObject): void
    {
        if (!empty($modelObject->id)) {

            return;
        }

        Message::set('Niepoprawny identyfikator');
        Router::redirectToReferer();
    }

    protected function redirectToRefererIfHasInvoices(Model $model): void
    {
        $invoiceDatabaseManager = Invoice::createManager();
        $invoiceHasOneRelations = DatabaseRelations::createHasOne($invoiceDatabaseManager);
        $invoiceSenderForeignKey = $invoiceHasOneRelations->generateForeignKey($model);
        $senderInvoices = $invoiceDatabaseManager->findAllBy($invoiceSenderForeignKey, $model->id);
        if ($senderInvoices) {
            $invoicesNumber = count($senderInvoices);
            $messageContent = 'Nie można usunąć ponieważ występuje na ' . ($invoicesNumber > 1 ? $invoicesNumber . ' fakturach' : ' fakturze');
            Message::set($messageContent);
            Router::redirectToReferer();
        }
    }

    protected function createValidator(): Validator
    {
        return new ValidatorImplementation();
    }
}