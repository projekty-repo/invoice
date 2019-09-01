<form action="" method="post">
    <input type="hidden" name="sent" value="true">

    <fieldset id="sender">
        <legend>Dane dostawcy:</legend>

        <input type="hidden" name="sender[id]" value="{{ $sender->id ?? null }}">
        <input type="hidden" name="sender[address_id]" value="{{ $sender->address->id ?? null }}">

        <label for="sender_name">Nazwa:</label>
        <input id="sender_name" name="sender[name]" type="text" value="{{ $sender->name }}"/>
        {{ validatorFormMessage('sender.name') }}

        <label for="sender_nip">NIP:</label>
        <input id="sender_nip" name="sender[nip]" type="text" value="{{ $sender->nip }}"/>
        {{ validatorFormMessage('sender.nip') }}

    </fieldset>
    <fieldset id="sender_address">
        <legend>Adres:</legend>

        <input type="hidden" name="sender[address][id]" value="{{ $sender->address->id ?? null }}">

        <label for="sender_address_city">Miasto:</label>
        <input id="sender_address_city" name="sender[address][city]" type="text" value="{{ $sender->address->city }}"/>
        {{ validatorFormMessage('sender.address.city') }}

        <label for="sender_address_street">Ulica:</label>
        <input id="sender_address_street" name="sender[address][street]" type="text" value="{{ $sender->address->street }}"/>
        {{ validatorFormMessage('sender.address.street') }}

        <label for="sender_address_number">Numer domu:</label>
        <input id="sender_address_number" name="sender[address][number]" type="text" value="{{ $sender->address->number }}"/>
        {{ validatorFormMessage('sender.address.number') }}

        <label for="sender_address_postcode">Kod pocztowy:</label>
        <input id="sender_address_postcode" name="sender[address][postcode]" type="text" value="{{ $sender->address->postcode }}"/>
        {{ validatorFormMessage('sender.address.postcode') }}

    </fieldset>
    <button type="submit">Zapisz</button>
</form>