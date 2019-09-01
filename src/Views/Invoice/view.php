<h4>Dane faktury:</h4>

<dl>
    <dt>Numer faktury:</dt>
    <dd>{{ $invoice->number ?? '&nbsp' }}</dd>

    <dt>Wystawiający:</dt>
    <dd>{{ $invoice->issuer ?? '&nbsp' }}</dd>

    <dt>Data wystawienia:</dt>
    <dd>{{ $invoice->created_date ?? '&nbsp' }}</dd>

    <dt>Data sprzedaży:</dt>
    <dd>{{ $invoice->sale_date ?? '&nbsp' }}</dd>

    <dt>Data płatności:</dt>
    <dd>{{ $invoice->payment_date ?? '&nbsp' }}</dd>

    <dt>Podsumowanie:</dt>
    <dd>{{ $invoice->summary ?? '&nbsp' }}</dd>

    <dt>Nadawca:</dt>
    <dd>{{ $invoice->sender ?? '&nbsp' }}</dd>

    <dt>Dostawca:</dt>
    <dd>{{ $invoice->supplier ?? '&nbsp' }}</dd>

    <dt>Produkty/usługi:</dt>
    <dd>
        {% if $invoice->countItems() > 0 %}
        <table>
            <tr>
                <th>Lp.</th>
                <th>Nazwa</th>
                <th>Cena</th>
            </tr>
            {% foreach $invoice->invoiceItems as $index => $invoiceItem %}
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $invoiceItem->name }}</td>
                    <td>{{ displayMoney($invoiceItem->price) }}</td>
                </tr>
            {% endforeach %}
        </table>
        {% else %}
            Brak produktów i usług
        {% endif %}
    </dd>

    <dt>Wartość:</dt>
    <dd>{{ displayMoney($invoice->countValue()) }} PLN</dd>
</dl>

<a href="{{ generateUrl('invoice', 'edit', ['id' => $invoice->id]) }}">Edytuj fakturę</a>