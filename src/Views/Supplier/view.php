<h4>Dane nadawcy:</h4>

<dl>
    <dt>Nazwa:</dt>
    <dd>{{ $supplier->name }}</dd>

    <dt>NIP:</dt>
    <dd>{{ $supplier->nip }}</dd>
</dl>

<h4>Adres nadawcy:</h4>

<dl>
    <dt>Miasto:</dt>
    <dd>{{ $supplier->address->city }}</dd>

    <dt>Ulica:</dt>
    <dd>{{ $supplier->address->street }}</dd>

    <dt>Numer domu:</dt>
    <dd>{{ $supplier->address->number }}</dd>

    <dt>Kod pocztowy:</dt>
    <dd>{{ $supplier->address->postcode }}</dd>
</dl>

<a href="{{ generateUrl('supplier', 'edit', ['id' => $supplier->id]) }}">Edytuj</a>
<a href="{{ generateUrl('supplier', 'delete', ['id' => $supplier->id]) }}">Usuń</a>