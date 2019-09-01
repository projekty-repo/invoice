<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <title>Faktury</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/faktura/public/style.css">
    </head>
    <body>
        <header>
            {{ $menu }}
        </header>
        {% if messageIsSet() %}
            <div id="message">
                {{ messageGet() }}
            </div>
        {% endif %}
        <section>
            {{ $viewContent }}
        </section>
    </body>
</html>