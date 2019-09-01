<?php

class SenderController extends Controller
{
    /**
     * @var Sender
     */
    private $sender;

    public function indexAction(): void
    {
        $senders = Sender::createManager()->findAll();
        $this->view->set('senders', $senders);
    }

    public function viewAction(int $id): void
    {
        $this->setSender($id);
    }

    public function addAction(): void
    {
        $this->setView('Sender/add_or_edit');
        $this->setSender();
        $this->saveIfFormSended();
    }

    public function editAction(int $id = null): void
    {
        $this->setView('Sender/add_or_edit');
        $this->setSender($id);
        $this->saveIfFormSended();
    }

    public function deleteAction(int $id): void
    {
        $this->setSender($id);
        $this->redirectToRefererIfHasInvoices($this->sender);

        $this->sender->getManager()->delete();

        Message::set('Nadawca został usunięty');
        Router::redirect('sender', 'index');
    }

    private function setSender(int $id = null): void
    {
        if ($id) {
            $this->sender = Sender::createManager()->findById($id);
            $this->redirectToRefererIfNotExists($this->sender);
        } else {
            $this->sender = new Sender();
        }

        $this->view->set('sender', $this->sender);
    }

    private function saveIfFormSended(): void
    {
        $request = Request::create();
        if ($request->isEmpty() || !$request->isPost()) {

            return;
        }

        $sender = new Sender($request->all());

        $formValid = $this->validate($sender);
        if (!$formValid) {

            return;
        }

        $senderId = $sender->getManager()->save();
        Message::set('Dane nadawcy zostały zapisane');
        Router::redirect('sender', 'view', ['id' => $senderId]);
    }

    private function validate(Sender $sender): bool
    {
        $validator = $this->createValidator();
        $validator->validate($sender);
        $errors = $validator->getErrors();
        if (!$errors) {

            return true;
        }

        $this->view->set('errors', $errors);
        $this->view->set('sender', $sender);

        return false;
    }
}