(function () {
    bindAddInvoiceItemButton();
    bindRemoveInvoiceItemButton();
    bindSelect('sender');
    bindSelect('supplier');
    disableElementsOnStart('sender_id');
    disableElementsOnStart('supplier_id');
})();

function bindAddInvoiceItemButton() {
    document.getElementById('add').addEventListener('click', function () {
        addInvoiceItem()
    });
}

function addInvoiceItem() {
    let invoiceItems = document.getElementById('invoice_items');
    let invoiceItemsNumber = invoiceItems.getElementsByClassName('invoice_item_name').length;
    let div = document.createElement('div');

    let nameLabel = document.createElement('label');
    nameLabel.innerHTML = 'Nazwa: ';
    div.appendChild(nameLabel);

    let nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.setAttribute('class', 'invoice_item_name');
    nameInput.setAttribute('name', 'invoice[invoiceItems][' + invoiceItemsNumber + '][name]');
    div.appendChild(nameInput);

    let priceLabel = document.createElement('label');
    priceLabel.innerHTML = ' Cena<small>w groszach</small>: ';
    div.appendChild(priceLabel);

    let priceInput = document.createElement('input');
    priceInput.type = 'text';
    priceInput.setAttribute('class', 'invoice_item_price');
    priceInput.setAttribute('name', 'invoice[invoiceItems][' + invoiceItemsNumber + '][price]');
    div.appendChild(priceInput);

    addRemoveButton(div);

    invoiceItems.insertBefore(div, document.getElementById('add'))
}

function addRemoveButton(div) {
    let removeButton = document.createElement('button');
    removeButton.innerText = 'Usuń';
    removeButton.setAttribute("type", "button");
    removeButton.setAttribute("class", "remove");
    removeButton.onclick = function (e) {
        removeInvoiceItem(e);
    };
    div.appendChild(removeButton);
}

function removeInvoiceItem(e) {
    let button = e.target;
    let div = button.parentElement;
    let fieldset = div.parentElement;

    fieldset.removeChild(div);

    let nameInputs = document.getElementsByClassName('invoice_item_name');
    for (let i = 0; i < nameInputs.length; i++) {
        nameInputs[i].setAttribute('name', 'invoice[invoiceItems][' + i + '][name]');
    }

    let priceInputs = document.getElementsByClassName('invoice_item_price');
    for (let i = 0; i < priceInputs.length; i++) {
        priceInputs[i].setAttribute('name', 'invoice[invoiceItems][' + i + '][price]');
    }
}

function bindRemoveInvoiceItemButton() {
    let removeButtons = document.getElementsByClassName('remove');
    for (let removeButton of removeButtons) {
        removeButton.addEventListener('click', (event) => {
            removeInvoiceItem(event);
        });
    }
}

function bindSelect(fieldsetId) {
    document.getElementById(fieldsetId + '_id').addEventListener('change', (event) => {
        if (event.target.value) {
            inputsNamePrefix = 'invoice[' + fieldsetId + ']';
            selectedValueFromDatabase(event, fieldsetId, inputsNamePrefix);
            selectedValueFromDatabase(event, fieldsetId + '_address', inputsNamePrefix + '[address]');
        } else {
            selectedEmptyValue(fieldsetId);
            selectedEmptyValue(fieldsetId + '_address');
        }
    });
}

function selectedValueFromDatabase(event, elementId, inputsNamePrefix) {
    disableElements(document.getElementById(elementId).getElementsByTagName('input'));

    let select = event.target;
    let selectedOptionIndex = select.selectedIndex;
    let selectedOption = select.options[selectedOptionIndex];
    let formData = selectedOption.dataset[elementId];
    let jsonFormData = JSON.parse(formData);
    setFormValuesFromJson(jsonFormData, inputsNamePrefix);
}

function disableElements(elements) {
    for (let element of elements) {
        element.setAttribute('disabled', 'disabled');
    }
}

function selectedEmptyValue(fieldsetId) {
    enableElements(document.getElementById(fieldsetId).getElementsByTagName('input'));
    setEmptyValue(document.getElementById(fieldsetId).getElementsByTagName('input'));
}

function enableElements(elements) {
    for (let element of elements) {
        element.removeAttribute('disabled');
    }
}

function setEmptyValue(elements) {
    for (let element of elements) {
        element.value = null;
    }
}

function setFormValuesFromJson(formData, name) {
    for (let key in formData) {
        if (formData.hasOwnProperty(key)) {
            document.getElementsByName(name + '[' + key + ']')[0].value = formData[key];
        }
    }
}

function disableElementsOnStart(selectId) {
    let select = document.getElementById(selectId);
    let selectedOptionIndex = select.selectedIndex;
    let selectedOption = select.options[selectedOptionIndex];

    if (selectedOption.value > 0) {
        disableElements(select.parentElement.getElementsByTagName('input'));
    }
}