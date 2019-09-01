<?php

class View
{
    /**
     * @var string
     */
    private $viewPathWithFileName;

    /**
     * @var string
     */
    private $layoutPathWithFileName;

    /**
     * @var string
     */
    private $menuPathWithFileName;

    /**
     * @var string
     */
    private $formValidationErrorPathWithFileName;

    /**
     * @var array
     */
    private $data = [];

    private const LAYOUT_FOLDER = 'Layout';
    private const LAYOUT_FILE = 'layout.php';
    private const MENU_FILE = 'menu.php';
    private const FORM_VALIDATION_ERROR_FILE = 'formValidationError.php';

    /**
     * @var Template
     */
    private $template;

    public function __construct(string $viewPathWithFileNameName)
    {
        $this->viewPathWithFileName = $viewPathWithFileNameName;
        $this->setLayout();
        $this->setMenu();
        $this->setFormValidationError();

        $this->template = new Template();
        $this->template->set('generateUrl', '$this->generateUrl');
        $this->template->set('displayMoney', '$this->displayMoney');
        $this->template->set('include', '$this->include');
        $this->template->set('messageSet', 'Message::set');
        $this->template->set('messageGet', 'Message::get');
        $this->template->set('messageIsSet', 'Message::isSet');
        $this->template->set('messageClear', 'Message::clear');
        $this->template->set('validatorFormMessage', '$this->validatorFormMessage');
    }

    private function setLayout(): void
    {
        $this->layoutPathWithFileName = Loader::SRC . '/' . Loader::VIEW_FOLDER . '/' . self::LAYOUT_FOLDER . '/' . self::LAYOUT_FILE;
    }

    private function setMenu(): void
    {
        $this->menuPathWithFileName = Loader::SRC . '/' . Loader::VIEW_FOLDER . '/' . self::LAYOUT_FOLDER . '/' . self::MENU_FILE;
    }

    private function setFormValidationError(): void
    {
        $this->formValidationErrorPathWithFileName = Loader::SRC . '/' . Loader::VIEW_FOLDER . '/' . self::LAYOUT_FOLDER . '/' . self::FORM_VALIDATION_ERROR_FILE;
    }

    final public function set(string $variable, $value): void
    {
        $this->data[$variable] = $value;
    }

    final public function render(): void
    {
        $menu = $this->renderFile($this->menuPathWithFileName);
        $this->set('menu', $menu);

        $viewContent = $this->renderFile($this->viewPathWithFileName, $this->data);
        $this->set('viewContent', $viewContent);

        echo $this->renderFile($this->layoutPathWithFileName, $this->data);
    }

    private function renderFile(string $file, array $viewData = []): string
    {
        if ($viewData) {
            extract($viewData, EXTR_OVERWRITE);
        }

        ob_start();
        eval(' ?>' . $this->template->render($file) . '<?php ');
        $viewContent = ob_get_contents();
        ob_end_clean();

        return $viewContent;
    }

    final public function include(string $fileName): string
    {
        $filePath = Loader::SRC . '/' . Loader::VIEW_FOLDER . '/' . $fileName . '.php';

        return $this->renderFile($filePath, $this->data);
    }

    public function displayMoney(int $value): string
    {
        return number_format($value / 100, 2, '.', '');
    }

    public function generateUrl(string $controller = null, string $action = null, array $parameters = []): string
    {
        return Router::generateUrl($controller, $action, $parameters);
    }

    public function validatorFormMessage(string $formField): string
    {
        if (empty($this->data['errors'])) {

            return '';
        }

        $error = $this->data['errors'];
        $elements = explode('.', $formField);
        foreach ($elements as $element) {
            if (!isset($error[$element])) {

                return '';
            }
            $error = $error[$element];
        }

        return $this->renderFile($this->formValidationErrorPathWithFileName, ['error' => $error]);
    }
}