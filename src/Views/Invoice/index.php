<h1>Faktury</h1>

<div>
    {% if $invoices %}
        <table>
            <tr>
                <th>Numer faktury</th>
                <th>Data wystawienia</th>
                <th>Data płatności</th>
                <th>Nadawca</th>
                <th>Dostawca</th>
                <th>Ilość pozycji</th>
                <th>Wartość</th>
                <th></th>
            </tr>
            {% foreach $invoices as $invoice %}
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->created_date }}</td>
                    <td>{{ $invoice->payment_date }}</td>
                    <td>{{ $invoice->sender->name ?? '' }}</td>
                    <td>{{ $invoice->supplier->name ?? '' }}</td>
                    <td>{{ $invoice->countItems() }}</td>
                    <td>{{ displayMoney($invoice->countValue()) }} PLN</td>
                    <td>
                        <a href="{{ generateUrl('invoice', 'view', ['id' => $invoice->id]) }}">Wyświetl</a>
                        <a href="{{ generateUrl('invoice', 'edit', ['id' => $invoice->id]) }}">Edytuj</a>
                        <a href="{{ generateUrl('invoice', 'delete', ['id' => $invoice->id]) }}">Usuń</a>
                    </td>
                </tr>
            {% endforeach %}
        </table>
    {% else %}
        <p>Brak faktur</p>
    {% endif %}

    <a href="{{ generateUrl('invoice', 'add') }}">Dodaj fakturę</a>
</div>