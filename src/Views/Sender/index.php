<h1>Nadawcy</h1>

<div>
    {% if count($senders) > 0 %}
        <table>
            <tr>
                <th>Nazwa</th>
                <th>Nip</th>
                <th>Adres</th>
                <th></th>
            </tr>
            {% foreach $senders as $sender %}
                <tr>
                    <td>{{ $sender->name }}</td>
                    <td>{{ $sender->nip }}</td>
                    <td>{{ $sender->address }}</td>
                    <td>
                        <a href="{{ generateUrl('sender', 'view', ['id' => $sender->id]) }}">Wyświetl</a>
                        <a href="{{ generateUrl('sender', 'edit', ['id' => $sender->id]) }}">Edytuj</a>
                        <a href="{{ generateUrl('sender', 'delete', ['id' => $sender->id]) }}">Usuń</a>
                    </td>
                </tr>
            {% endforeach %}
        </table>
    {% else %}
        <p>Brak nadawców</p>
    {% endif %}

    <a href="{{ generateUrl('sender', 'add') }}">Dodaj nadawcę</a>
</div>