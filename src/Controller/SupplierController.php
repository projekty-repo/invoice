<?php

class SupplierController extends Controller
{
    /**
     * @var Supplier
     */
    private $supplier;

    public function indexAction(): void
    {
        $suppliers = Supplier::createManager()->findAll();
        $this->view->set('suppliers', $suppliers);
    }

    public function viewAction(int $id): void
    {
        $this->setSupplier($id);
    }

    public function addAction(): void
    {
        $this->setView('Supplier/add_or_edit');
        $this->setSupplier();
        $this->saveIfFormSended();
    }

    public function editAction(int $id): void
    {
        $this->setView('Supplier/add_or_edit');
        $this->setSupplier($id);
        $this->saveIfFormSended();
    }

    public function deleteAction(int $id): void
    {
        $this->setSupplier($id);
        $this->redirectToRefererIfHasInvoices($this->supplier);

        $this->supplier->getManager()->delete();

        Message::set('Dostawca został usunięty');
        Router::redirect('supplier', 'index');
    }

    private function setSupplier(int $id = null): void
    {
        if ($id) {
            $this->supplier = Supplier::createManager()->findById($id);
            $this->redirectToRefererIfNotExists($this->supplier);
        } else {
            $this->supplier = new Supplier();
        }

        $this->view->set('supplier', $this->supplier);
    }

    private function saveIfFormSended(): void
    {
        $request = Request::create();
        if ($request->isEmpty() || !$request->isPost()) {

            return;
        }

        $supplier = new Supplier($request->all());

        $formValid = $this->validate($supplier);
        if (!$formValid) {

            return;
        }

        $supplierId = $supplier->getManager()->save();
        Message::set('Dane dostawcy zostały zapisane');
        Router::redirect('supplier', 'view', ['id' => $supplierId]);
    }

    private function validate(Supplier $supplier): bool
    {
        $validator = $this->createValidator();
        $validator->validate($supplier);
        $errors = $validator->getErrors();
        if (!$errors) {

            return true;
        }

        $this->view->set('errors', $errors);
        $this->view->set('supplier', $supplier);

        return false;
    }
}