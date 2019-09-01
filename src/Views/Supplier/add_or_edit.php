<form action="" method="post">
    <input type="hidden" name="sent" value="true">

    <fieldset id="supplier">
        <legend>Dane dostawcy:</legend>

        <input type="hidden" name="supplier[id]" value="{{ $supplier->id ?? null }}">
        <input type="hidden" name="supplier[address_id]" value="{{ $supplier->address->id ?? null }}">

        <label for="supplier_name">Nazwa:</label>
        <input id="supplier_name" name="supplier[name]" type="text" value="{{ $supplier->name }}"/>
        {{ validatorFormMessage('supplier.name') }}

        <label for="supplier_nip">NIP:</label>
        <input id="supplier_nip" name="supplier[nip]" type="text" value="{{ $supplier->nip }}"/>
        {{ validatorFormMessage('supplier.nip') }}

    </fieldset>
    <fieldset id="supplier_address">
        <legend>Adres:</legend>

        <input type="hidden" name="supplier[address][id]" value="{{ $supplier->address->id ?? null }}">

        <label for="supplier_address_city">Miasto:</label>
        <input id="supplier_address_city" name="supplier[address][city]" type="text" value="{{ $supplier->address->city }}"/>
        {{ validatorFormMessage('supplier.address.city') }}

        <label for="supplier_address_street">Ulica:</label>
        <input id="supplier_address_street" name="supplier[address][street]" type="text" value="{{ $supplier->address->street }}"/>
        {{ validatorFormMessage('supplier.address.street') }}

        <label for="supplier_address_number">Numer domu:</label>
        <input id="supplier_address_number" name="supplier[address][number]" type="text" value="{{ $supplier->address->number }}"/>
        {{ validatorFormMessage('supplier.address.number') }}

        <label for="supplier_address_postcode">Kod pocztowy:</label>
        <input id="supplier_address_postcode" name="supplier[address][postcode]" type="text" value="{{ $supplier->address->postcode }}"/>
        {{ validatorFormMessage('supplier.address.postcode') }}

    </fieldset>
    <button type="submit">Zapisz</button>
</form>