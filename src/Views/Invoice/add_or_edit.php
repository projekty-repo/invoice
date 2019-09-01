<form action="" method="post">
    <input type="hidden" name="sent" value="true">

    <fieldset>
        <legend>Faktura:</legend>
        <input type="hidden" name="invoice[id]" value="{{ $invoice->id ?? '' }}">

        <label for="invoice_number">Numer faktury</label>
        <input id="invoice_number" name="invoice[number]" type="text" value="{{ $invoice->number ?? '' }}"/>
        {{ validatorFormMessage('invoice.number') }}

        <label for="invoice_issuer">Wystawiający</label>
        <input id="invoice_issuer" name="invoice[issuer]" type="text" value="{{ $invoice->issuer ?? '' }}"/>
        {{ validatorFormMessage('invoice.issuer') }}

        <label for="invoice_created_date">Data wystawienia</label>
        <input id="invoice_created_date" name="invoice[created_date]" type="date" value="{{ $invoice->created_date ?? '' }}"/>
        {{ validatorFormMessage('invoice.created_date') }}

        <label for="invoice_sale_date">Data sprzedaży</label>
        <input id="invoice_sale_date" name="invoice[sale_date]" type="date" value="{{ $invoice->sale_date ?? '' }}"/>
        {{ validatorFormMessage('invoice.sale_date') }}

        <label for="invoice_payment_date">Data płatności</label>
        <input id="invoice_payment_date" name="invoice[payment_date]" type="date" value="{{ $invoice->payment_date ?? '' }}"/>
        {{ validatorFormMessage('invoice.payment_date') }}

        <label for="invoice_summary">Podsumowanie</label>
        <textarea id="invoice_summary" name="invoice[summary]">{{ $invoice->summary ?? '' }}</textarea>
        {{ validatorFormMessage('invoice.summary') }}
    </fieldset>
    <fieldset>
        <legend>Nadawca:</legend>

        <label for="sender_id">Wybierz z listy lub dodaj nowego:</label>
        <select id="sender_id" name="invoice[sender_id]">
            <option></option>

            {% foreach $senders as $sender %}
            <option
                value="{{ $sender->id }}"
                data-sender='{{ json_encode(["id" => $sender->id, "address_id" => $sender->address_id, "name" => $sender->name, "nip" => $sender->nip]) }}'
                data-sender_address='{{ json_encode($sender->address) }}'
                {% if !empty($invoice) && $sender->id == $invoice->sender_id %} selected {% endif %}
            >
                {{ $sender }}
            </option>
            {% endforeach %}
        </select>

        <fieldset id="sender">
            <legend>Dane dostawcy:</legend>

            <input type="hidden" name="invoice[sender][id]" value="{{ $invoice->sender->id ?? null }}">
            <input type="hidden" name="invoice[sender][address_id]" value="{{ $invoice->sender->address->id ?? null }}">

            <label for="sender_name">Nazwa:</label>
            <input id="sender_name" name="invoice[sender][name]" type="text" value="{{ $invoice->sender->name }}"/>
            {{ validatorFormMessage('invoice.sender.name') }}

            <label for="sender_nip">NIP:</label>
            <input id="sender_nip" name="invoice[sender][nip]" type="text" value="{{ $invoice->sender->nip }}"/>
            {{ validatorFormMessage('invoice.sender.nip') }}

        </fieldset>
        <fieldset id="sender_address">
            <legend>Adres:</legend>

            <input type="hidden" name="invoice[sender][address][id]" value="{{ $invoice->sender->address->id ?? null }}">

            <label for="sender_address_city">Miasto:</label>
            <input id="sender_address_city" name="invoice[sender][address][city]" type="text" value="{{ $invoice->sender->address->city }}"/>
            {{ validatorFormMessage('invoice.sender.address.city') }}

            <label for="sender_address_street">Ulica:</label>
            <input id="sender_address_street" name="invoice[sender][address][street]" type="text" value="{{ $invoice->sender->address->street }}"/>
            {{ validatorFormMessage('invoice.sender.address.street') }}

            <label for="sender_address_number">Numer domu:</label>
            <input id="sender_address_number" name="invoice[sender][address][number]" type="text" value="{{ $invoice->sender->address->number }}"/>
            {{ validatorFormMessage('invoice.sender.address.number') }}

            <label for="sender_address_postcode">Kod pocztowy:</label>
            <input id="sender_address_postcode" name="invoice[sender][address][postcode]" type="text" value="{{ $invoice->sender->address->postcode }}"/>
            {{ validatorFormMessage('invoice.sender.address.postcode') }}

        </fieldset>
    </fieldset>

    <fieldset>
        <legend>Dostawca:</legend>
        <label for="supplier_id">Wybierz z listy lub dodaj nowego:</label>
        <select name="invoice[supplier_id]" id="supplier_id">
            <option></option>

            {% foreach $suppliers as $supplier %}
            <option
                value="{{ $supplier->id }}"
                data-supplier='{{ json_encode(["id" => $supplier->id, "address_id" => $supplier->address_id, "name" => $supplier->name, "nip" => $supplier->nip]) }}'
                data-supplier_address='{{ json_encode($supplier->address) }}'
                {% if !empty($invoice) && $supplier->id == $invoice->supplier_id %} selected {% endif %}
            >
                {{ $supplier }}
            </option>
            {% endforeach %}
        </select>

        <fieldset id="supplier">
            <legend>Dane dostawcy:</legend>

            <input type="hidden" name="invoice[supplier][id]" value="{{ $invoice->supplier->id ?? null }}">
            <input type="hidden" name="invoice[supplier][address_id]" value="{{ $invoice->supplier->address->id ?? null }}">

            <label for="supplier_name">Nazwa:</label>
            <input id="supplier_name" name="invoice[supplier][name]" type="text" value="{{ $invoice->supplier->name }}"/>
            {{ validatorFormMessage('invoice.supplier.name') }}

            <label for="supplier_nip">NIP:</label>
            <input id="supplier_nip" name="invoice[supplier][nip]" type="text" value="{{ $invoice->supplier->nip }}"/>
            {{ validatorFormMessage('invoice.supplier.nip') }}

        </fieldset>
        <fieldset id="supplier_address">
            <legend>Adres:</legend>

            <input type="hidden" name="invoice[supplier][address][id]" value="{{ $invoice->supplier->address->id ?? null }}">

            <label for="supplier_address_city">Miasto:</label>
            <input id="supplier_address_city" name="invoice[supplier][address][city]" type="text" value="{{ $invoice->supplier->address->city }}"/>
            {{ validatorFormMessage('invoice.supplier.address.city') }}

            <label for="supplier_address_street">Ulica:</label>
            <input id="supplier_address_street" name="invoice[supplier][address][street]" type="text" value="{{ $invoice->supplier->address->street }}"/>
            {{ validatorFormMessage('invoice.supplier.address.street') }}

            <label for="supplier_address_number">Numer domu:</label>
            <input id="supplier_address_number" name="invoice[supplier][address][number]" type="text" value="{{ $invoice->supplier->address->number }}"/>
            {{ validatorFormMessage('invoice.supplier.address.number') }}

            <label for="supplier_address_postcode">Kod pocztowy:</label>
            <input id="supplier_address_postcode" name="invoice[supplier][address][postcode]" type="text" value="{{ $invoice->supplier->address->postcode }}"/>
            {{ validatorFormMessage('invoice.supplier.address.postcode') }}

        </fieldset>
    </fieldset>

    <fieldset id="invoice_items">
        <legend>Towary/usługi:</legend>
            {% foreach $invoice->invoiceItems as $index => $invoiceItem %}
                <div>
                    <label>Nazwa:</label>
                    <input class="invoice_item_name" name="invoice[invoiceItems][{{ $index ?? '' }}][name]" type="text" value="{{ $invoice->invoiceItems[$index]->name }}">
                    {{ validatorFormMessage("invoice.invoiceItems.$index.name") }}

                    <label>Cena<small>(w groszach)</small>:</label>
                    <input class="invoice_item_price" name="invoice[invoiceItems][{{ $index ?? '' }}][price]" type="text" value="{{ $invoice->invoiceItems[$index]->price }}">
                    {{ validatorFormMessage("invoice.invoiceItems.$index.price") }}

                    <button class="remove" type="button">Usuń</button>
                </div>
            {% endforeach %}
        <button id="add" type="button" value="Add">Dodaj</button>
    </fieldset>

    <button type="submit">Zapisz</button>
</form>

<script src="invoice.js"></script>