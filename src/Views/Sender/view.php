<h4>Dane nadawcy:</h4>

<dl>
    <dt>Nazwa:</dt>
    <dd>{{ $sender->name }}</dd>

    <dt>NIP:</dt>
    <dd>{{ $sender->nip }}</dd>
</dl>

<h4>Adres nadawcy:</h4>

<dl>
    <dt>Miasto:</dt>
    <dd>{{ $sender->address->city }}</dd>

    <dt>Ulica:</dt>
    <dd>{{ $sender->address->street }}</dd>

    <dt>Numer domu:</dt>
    <dd>{{ $sender->address->number }}</dd>

    <dt>Kod pocztowy:</dt>
    <dd>{{ $sender->address->postcode }}</dd>
</dl>

<a href="{{ generateUrl('sender', 'edit', ['id' => $sender->id]) }}">Edytuj dane nadawcy</a>