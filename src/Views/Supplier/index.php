<h1>Dostawcy</h1>

<div>
    {% if $suppliers %}
        <table>
            <tr>
                <th>Nazwa</th>
                <th>NIP</th>
                <th>Adres</th>
                <th></th>
            </tr>
            {% foreach $suppliers as $supplier %}
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->nip }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>
                        <a href="{{ generateUrl('supplier', 'view', ['id' => $supplier->id]) }}">Wyświetl</a>
                        <a href="{{ generateUrl('supplier', 'edit', ['id' => $supplier->id]) }}">Edytuj</a>
                        <a href="{{ generateUrl('supplier', 'delete', ['id' => $supplier->id]) }}">Usuń</a>
                    </td>
                </tr>
            {% endforeach %}
        </table>
    {% else %}
        <p>Brak dostawców</p>
    {% endif %}

    <a href="{{ generateUrl('supplier', 'add') }}">Dodaj dostawcę</a>
</div>