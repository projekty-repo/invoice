<?php

class Template
{
    private $tags = [
        '{{' => '<?php echo ',
        '}}' => '; ?>',
        '{% foreach ' => '<?php foreach (',
        '{% endforeach %}' => '<?php endforeach; ?>',
        '{% if ' => '<?php if (',
        '{% else %}' => '<?php else: ?>',
        '{% endif %}' => '<?php endif; ?>',
        '{% ' => '<?php ',
        ' %}' => '): ?>',
    ];

    public function render(string $viewFile): string
    {
        if (file_exists($viewFile) && is_readable($viewFile)) {
            $templateContent = file_get_contents($viewFile);
        } else {
            throw new RuntimeException('File read error');
        }

        foreach ($this->tags as $templateTag => $htmlTag) {
            $templateContent = str_replace($templateTag, $htmlTag, $templateContent);
        }

        return $templateContent;
    }

    public function set(string $templateTag, string $htmlTag): void
    {
        $this->tags[$templateTag] = $htmlTag;
    }
}