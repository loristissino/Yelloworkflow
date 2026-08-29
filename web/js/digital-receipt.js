'use strict';

/*
 * Configuration supplied by PHP.
 *
 * Example:
 *
 * window.digitalReceiptConfig = {
 *     maxAmount: 1000,
 *     messages: {
 *         fillAllFields: 'Please fill all fields',
 *         priceCannotBeZero: 'The price / amount cannot be zero.',
 *         discountTooLarge: 'The discount cannot exceed the amount of',
 *         fillNotes: 'Please fill the notes field',
 *         cameraError: 'Camera error:'
 *     }
 * };
 */

const digitalReceiptConfig = window.digitalReceiptConfig || {
    maxAmount: Infinity,
    messages: {}
};


/*
 * Application state.
 */

let itemIndex = 0;
let selectedProductOption = null;


/*
 * --------------------------------------------------------------------------
 * DOM helpers
 * --------------------------------------------------------------------------
 *
 * These are deliberately named queryElement/queryElements rather than "$"
 * so that there is no resemblance to jQuery.
 */


/**
 * Return one DOM element.
 */
function queryElement(selector, parent = document) {
    return parent.querySelector(selector);
}


/**
 * Return all matching DOM elements as an Array.
 */
function queryElements(selector, parent = document) {
    return Array.from(parent.querySelectorAll(selector));
}


/**
 * Get the value of a form element.
 */
function getElementValue(selector) {
    const element = queryElement(selector);

    return element ? element.value : '';
}


/**
 * Set the value of a form element.
 */
function setElementValue(selector, value) {
    const element = queryElement(selector);

    if (element) {
        element.value = value ?? '';
    }
}


/**
 * Get an HTML attribute.
 */
function getElementAttribute(selector, attributeName) {
    const element = queryElement(selector);

    return element
        ? element.getAttribute(attributeName)
        : null;
}


/**
 * Set an HTML attribute.
 */
function setElementAttribute(selector, attributeName, value) {
    const element = queryElement(selector);

    if (element) {
        element.setAttribute(
            attributeName,
            value ?? ''
        );
    }
}


/**
 * Set visible text.
 */
function setElementText(selector, value) {
    const element = queryElement(selector);

    if (element) {
        element.textContent = value ?? '';
    }
}


/**
 * Hide all matching elements.
 */
function hideElements(selector) {
    queryElements(selector).forEach(element => {
        element.style.display = 'none';
    });
}


/**
 * Show all matching elements.
 */
function showElements(selector) {
    queryElements(selector).forEach(element => {
        element.style.display = '';
    });
}


/*
 * --------------------------------------------------------------------------
 * Bootstrap modal helpers
 * --------------------------------------------------------------------------
 *
 * Bootstrap 4 is still responsible for actually opening/closing the modal.
 *
 * We do NOT call:
 *
 *     $('#modal').modal(...)
 *
 * Instead we use the existing Bootstrap data attributes in the HTML.
 */


/**
 * Open a Bootstrap modal by activating its existing trigger.
 *
 * For example:
 *
 *     openModalUsingTrigger('#addItemModal');
 *
 * finds:
 *
 *     [data-toggle="modal"][data-target="#addItemModal"]
 *
 * and clicks it.
 */
function openModalUsingTrigger(modalSelector) {

    const trigger = queryElement(
        `[data-toggle="modal"][data-target="${modalSelector}"]`
    );

    if (trigger) {
        trigger.click();
    }
}


/**
 * Close a Bootstrap modal using its existing dismiss button.
 */
function closeModalUsingDismissButton(modalSelector) {

    const modal = queryElement(modalSelector);

    if (!modal) {
        return;
    }

    const dismissButton = queryElement(
        '[data-dismiss="modal"]',
        modal
    );

    if (dismissButton) {
        dismissButton.click();
    }
}


/*
 * --------------------------------------------------------------------------
 * Add-item modal
 * --------------------------------------------------------------------------
 */


/**
 * Update the row-total preview in the Add Item modal.
 */
function updateRowPreviewTotal() {

    const quantity =
        parseFloat(
            getElementValue('#item-qty')
        ) || 0;

    const price =
        parseFloat(
            getElementValue('#item-price')
        ) || 0;

    const originalPrice =
        parseFloat(
            getElementAttribute(
                '#item-price',
                'data-original_price'
            )
        );

    const maximumDiscount =
        parseFloat(
            getElementAttribute(
                '#max-discount',
                'data-max-discount'
            )
        );

    const addItemButton =
        queryElement('#btn-add-item');

    const priceInput =
        queryElement('#item-price');


    /*
     * Initially allow adding the item.
     */

    if (addItemButton) {
        addItemButton.disabled = false;
    }

    if (priceInput) {
        priceInput.style.backgroundColor = '';
    }


    /*
     * ISBN products have a maximum discount.
     */

    if (
        getElementAttribute(
            '#item-description',
            'data-isbn'
        )
    ) {

        if (
            price <
            originalPrice - maximumDiscount
        ) {

            if (priceInput) {
                priceInput.style.backgroundColor =
                    '#FFC0CB';
            }

            if (addItemButton) {
                addItemButton.disabled = true;
            }
        }
    }


    /*
     * Price cannot exceed the original price.
     */

    if (
        originalPrice > 0 &&
        price > originalPrice
    ) {

        if (priceInput) {
            priceInput.style.backgroundColor =
                '#FFC0CB';
        }

        if (addItemButton) {
            addItemButton.disabled = true;
        }
    }


    /*
     * Calculate preview.
     */

    const total =
        (quantity * price).toFixed(2);

    setElementText(
        '#preview-total',
        total
    );
}


/**
 * Add the currently selected product to the receipt.
 */
function addReceiptItem() {
    
    console.log('addReceiptItem started');

    const description =
        getElementValue('#item-description');

    const quantity =
        parseFloat(
            getElementValue('#item-qty')
        );

    let price =
        parseFloat(
            getElementValue('#item-price')
        );

    let originalPrice =
        parseFloat(
            getElementAttribute(
                '#item-price',
                'data-original_price'
            )
        );

    const maximumDiscount =
        parseFloat(
            getElementAttribute(
                '#max-discount',
                'data-max-discount'
            )
        );

    let discount =
        (
            (originalPrice - price) *
            quantity
        ).toFixed(2);


    /*
     * If there is no original price, there is no discount.
     */

    if (originalPrice === 0) {

        discount = 0;

        originalPrice = price;
    }


    let displayedDiscount =
        discount;

    if (discount < 0) {
        displayedDiscount = '';
    }


    const productId =
        parseInt(
            getElementValue('#item-product-id'),
            10
        );

    const notes =
        getElementValue('#notes');

    const label =
        getElementValue('#item-label');


    /*
     * Escape apostrophes in the hidden notes field,
     * preserving the behaviour of the original code.
     */

    const escapedNotes =
        notes.replace(
            /'/g,
            '&#39;'
        );


    /*
     * Validation.
     */

    if (
        !description ||
        !quantity ||
        !price
    ) {

        alert(
            digitalReceiptConfig.messages.fillAllFields
        );

        return;
    }


    if (
        price <= 0 ||
        Number.isNaN(price)
    ) {

        alert(
            digitalReceiptConfig.messages.priceCannotBeZero
        );

        return;
    }


    /*
     * Check maximum discount for ISBN (books) products.
     */

    if (
        getElementAttribute(
            '#item-description',
            'data-isbn'
        )
    ) {

        if (
            price <
            originalPrice - maximumDiscount
        ) {

            alert(
                digitalReceiptConfig.messages.discountTooLarge +
                ' ' +
                maximumDiscount
            );

            return;
        }
    }


    /*
     * Some products require notes.
     */

    const extraInformationRequired =
        getElementAttribute(
            '#item-description',
            'data-extra_info_required'
        );

    if (
        !notes &&
        extraInformationRequired
    ) {

        const notesInput =
            queryElement('#notes');

        if (notesInput) {
            notesInput.style.backgroundColor =
                '#FFC0CB';
        }
        console.log('about to return because notes are required');
        alert(
            digitalReceiptConfig.messages.fillNotes
        );

        return;
    }


    /*
     * Clear the notes input.
     */

    const notesInput =
        queryElement('#notes');

    if (notesInput) {

        notesInput.setAttribute(
            'placeholder',
            ''
        );

        notesInput.value = '';
    }


    /*
     * Calculate row total.
     */

    const total =
        (
            quantity * price
        ).toFixed(2);


    /*
     * Construct the receipt row.
     */

    const row = `
        <tr>
            <td>
                ${label}<br>

                <span class="notes">
                    ${notes}
                </span>

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][description]"
                    value="${label}"
                >

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][notes]"
                    value="${escapedNotes}"
                >

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][product_id]"
                    value="${productId}"
                >
            </td>

            <td class="number">
                ${quantity}

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][quantity]"
                    value="${quantity}"
                >
            </td>

            <td class="number price-cell">
                <span class="price">
                    ${originalPrice.toFixed(2)}
                </span>

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][unit_price]"
                    value="${originalPrice.toFixed(2)}"
                >
            </td>

            <td class="number">
                ${displayedDiscount}

                <input
                    type="hidden"
                    name="DigitalReceiptLine[${itemIndex}][discount]"
                    value="${discount}"
                >
            </td>

            <td class="row-total amount number">
                ${total}
            </td>

            <td class="buttons">
                <button
                    type="button"
                    class="btn btn-sm btn-danger btn-remove"
                >
                    X
                </button>
            </td>
        </tr>
    `;


    const receiptRows =
        queryElement('#receipt-rows');

    if (receiptRows) {

        receiptRows.insertAdjacentHTML(
            'beforeend',
            row
        );
    }


    /*
     * Merge duplicate products.
     */

    compactReceiptTable();


    /*
     * Re-number the Yii form fields.
     */

    reindexReceiptTable();


    /*
     * Update totals.
     */

    calculateGrandTotal();


    itemIndex++;


    /*
     * Close the modal using its existing Bootstrap
     * dismiss button.
     */

    closeModalUsingDismissButton(
        '#addItemModal'
    );


    /*
     * Reset the Add Item form.
     */

    setElementValue(
        '#item-description',
        ''
    );

    setElementValue(
        '#item-qty',
        1
    );

    setElementValue(
        '#item-price',
        ''
    );


    if (notesInput) {
        notesInput.style.backgroundColor = '';
    }
}


/*
 * --------------------------------------------------------------------------
 * Discounts
 * --------------------------------------------------------------------------
 */


/**
 * Apply the standard discount.
 */
function applyStandardDiscount() {

    const originalPrice =
        parseFloat(
            getElementAttribute(
                '#item-price',
                'data-original_price'
            )
        );

    const standardDiscount =
        parseFloat(
            getElementAttribute(
                '#standard-discount',
                'data-standard-discount'
            )
        );


    const discountedPrice =
        (
            originalPrice -
            standardDiscount
        ).toFixed(2);


    setElementValue(
        '#item-price',
        discountedPrice
    );

    updateRowPreviewTotal();
}


/**
 * Remove the discount.
 */
function removeStandardDiscount() {

    const originalPrice =
        getElementAttribute(
            '#item-price',
            'data-original_price'
        );

    setElementValue(
        '#item-price',
        originalPrice
    );

    updateRowPreviewTotal();
}


/*
 * --------------------------------------------------------------------------
 * Product selection
 * --------------------------------------------------------------------------
 */


/**
 * Handle selection of a product.
 */
function handleProductSelection() {

    const productSelect =
        queryElement('#item-description');

    if (!productSelect) {
        return;
    }

    selectedProductOption =
        productSelect.options[
            productSelect.selectedIndex
        ];

    console.log(selectedProductOption);

    if (!selectedProductOption) {
        return;
    }


    /*
     * Read product data directly from the
     * selected <option>'s data-* attributes.
     */

    const productId =
        selectedProductOption.dataset.id;

    const description =
        selectedProductOption.dataset.description;

    const originalPrice =
        parseFloat(
            selectedProductOption.dataset.original_price
        );

    const isbn =
        selectedProductOption.dataset.isbn || '';

    const maximumDiscount =
        parseFloat(
            selectedProductOption.dataset.max_discount ||
            selectedProductOption.dataset.maxDiscount ||
            0
        );

    const standardDiscount =
        parseFloat(
            selectedProductOption.dataset.standard_discount ||
            0
        );

    const extraInformationRequired =
        selectedProductOption.dataset.extra_info_required ||
        '';
    
    const minimumPrice =
        (
            originalPrice -
            maximumDiscount
        ).toFixed(2);


    /*
     * Populate hidden fields.
     */

    setElementValue(
        '#item-product-id',
        productId
    );

    setElementValue(
        '#item-label',
        description
    );


    /*
     * Display discount information.
     */

    setElementText(
        '#standard-discount',
        standardDiscount
    );

    setElementAttribute(
        '#standard-discount',
        'data-standard-discount',
        standardDiscount
    );


    setElementText(
        '#max-discount',
        maximumDiscount
    );

    setElementAttribute(
        '#max-discount',
        'data-max-discount',
        maximumDiscount
    );


    setElementText(
        '#min-price',
        minimumPrice
    );


    /*
     * Set the selected product's price.
     */

    setElementValue(
        '#item-price',
        originalPrice
    );

    setElementAttribute(
        '#item-price',
        'data-original_price',
        originalPrice
    );


    /*
     * Store product information on the select itself.
     */

    setElementAttribute(
        '#item-description',
        'data-isbn',
        isbn
    );

    setElementAttribute(
        '#item-description',
        'data-extra_info_required',
        extraInformationRequired
    );


    /*
     * Set the notes placeholder.
     */

    const notesInput =
        queryElement('#notes');

    if (notesInput) {

        notesInput.setAttribute(
            'placeholder',
            extraInformationRequired
        );
    }


    /*
     * Show or hide discount information.
     */

    if (
        isbn !== '' ||
        maximumDiscount > 0
    ) {

        showElements('.discount');

    } else {

        hideElements('.discount');
    }


    /*
     * Focus the quantity input.
     */

    const quantityInput =
        queryElement('#item-qty');

    if (quantityInput) {

        quantityInput.focus();

        quantityInput.select();
    }


    updateRowPreviewTotal();
}


/*
 * --------------------------------------------------------------------------
 * Receipt totals
 * --------------------------------------------------------------------------
 */


/**
 * Calculate the grand total.
 */
function calculateGrandTotal() {

    let grandTotal = 0;


    const receiptRows =
        queryElements(
            '#receipt-rows tr'
        );


    receiptRows.forEach(row => {

        const totalElement =
            queryElement(
                '.row-total',
                row
            );


        const rowTotal =
            totalElement
                ? parseFloat(
                    totalElement.textContent
                ) || 0
                : 0;


        grandTotal += rowTotal;
    });


    const formattedTotal =
        grandTotal.toFixed(2);


    setElementText(
        '#grand-total',
        formattedTotal
    );


    setElementValue(
        '#total_amount',
        formattedTotal
    );


    setElementValue(
        '#cash_payment_amount',
        ''
    );


    setElementValue(
        '#electronic_payment_amount',
        ''
    );


    updateIssueButtonState();
}


/**
 * Enable or disable the Issue button.
 */
function updateIssueButtonState() {

    const total =
        parseFloat(
            getElementValue('#total_amount')
        ) || 0;

    const cash =
        parseFloat(
            getElementValue(
                '#cash_payment_amount'
            )
        ) || 0;

    const electronic =
        parseFloat(
            getElementValue(
                '#electronic_payment_amount'
            )
        ) || 0;

    const issueButton =
        queryElement('#issueButton');


    if (!issueButton) {
        return;
    }


    const paymentsMatch =
        cash + electronic === total;


    const amountIsAllowed =
        total > 0 &&
        total <=
            Number(
                digitalReceiptConfig.maxAmount
            );


    issueButton.disabled =
        !paymentsMatch ||
        !amountIsAllowed;
}


/*
 * --------------------------------------------------------------------------
 * Duplicate receipt rows
 * --------------------------------------------------------------------------
 */


/**
 * Merge duplicate receipt rows.
 */
function compactReceiptTable() {

    const rows =
        queryElements(
            '#receipt-rows tr'
        );


    const seenItems =
        new Map();


    rows.forEach(row => {

        const productIdInput =
            queryElement(
                'input[name$="[product_id]"]',
                row
            );

        const unitPriceInput =
            queryElement(
                'input[name$="[unit_price]"]',
                row
            );

        const notesInput =
            queryElement(
                'input[name$="[notes]"]',
                row
            );

        const quantityInput =
            queryElement(
                'input[name$="[quantity]"]',
                row
            );

        const discountInput =
            queryElement(
                'input[name$="[discount]"]',
                row
            );

        const rowTotalElement =
            queryElement(
                '.row-total',
                row
            );


        /*
         * Ignore malformed rows.
         */

        if (
            !productIdInput ||
            !unitPriceInput ||
            !notesInput ||
            !quantityInput ||
            !discountInput ||
            !rowTotalElement
        ) {
            return;
        }


        const productId =
            productIdInput.value;

        const unitPrice =
            parseFloat(
                unitPriceInput.value
            );

        const notes =
            notesInput.value;

        const quantity =
            parseFloat(
                quantityInput.value
            );

        const discount =
            parseFloat(
                discountInput.value
            ) || 0;


        const rowTotal =
            parseFloat(
                rowTotalElement.textContent
            ) || 0;


        /*
         * Calculate the net unit price.
         */

        const netUnitPrice =
            (
                unitPrice -
                discount / quantity
            ).toFixed(2);


        /*
         * Items with the same product,
         * net price and notes are duplicates.
         */

        const key =
            `${productId}|${netUnitPrice}|${notes}`;


        if (!seenItems.has(key)) {

            seenItems.set(
                key,
                {
                    row,
                    quantity,
                    quantityInput,
                    discount,
                    discountInput,
                    total: rowTotal,
                    rowTotalElement
                }
            );

            return;
        }


        /*
         * Duplicate found.
         */

        const existing =
            seenItems.get(key);


        /*
         * Merge quantities.
         */

        existing.quantity += quantity;

        existing.quantityInput.value =
            existing.quantity;

        replaceVisibleCellText(
            existing.quantityInput.parentElement,
            existing.quantity
        );


        /*
         * Merge discounts.
         */

        existing.discount += discount;

        existing.discountInput.value =
            existing.discount.toFixed(2);

        replaceVisibleCellText(
            existing.discountInput.parentElement,
            existing.discount.toFixed(2)
        );


        /*
         * Merge totals.
         */

        existing.total += rowTotal;

        existing.rowTotalElement.textContent =
            existing.total.toFixed(2);


        /*
         * Remove duplicate row.
         */

        row.remove();
    });
}

/**
 * Prepare object to send via API.
 */
function getReceiptFromForm() {
    const lines = [];

    document.querySelectorAll('#receipt-rows tr').forEach(row => {
        lines.push({
            description: row.querySelector(
                'input[name$="[description]"]'
            )?.value ?? '',

            notes: row.querySelector(
                'input[name$="[notes]"]'
            )?.value ?? '',

            product_id: Number(
                row.querySelector(
                    'input[name$="[product_id]"]'
                )?.value
            ),

            quantity: Number(
                row.querySelector(
                    'input[name$="[quantity]"]'
                )?.value
            ),

            unit_price: Number(
                row.querySelector(
                    'input[name$="[unit_price]"]'
                )?.value
            ),

            discount: Number(
                row.querySelector(
                    'input[name$="[discount]"]'
                )?.value
            )
        });
    });

    return {
        client_id: crypto.randomUUID(),

        digital_receipt_type_id: -1, // this will be updated later

        total_amount: Number(
            document.querySelector('#total_amount')?.value || 0
        ),

        cash_payment_amount: Number(
            document.querySelector('#cash_payment_amount')?.value || 0
        ),

        electronic_payment_amount: Number(
            document.querySelector('#electronic_payment_amount')?.value || 0
        ),
        
        email: document.querySelector('#digitalreceipt-email')?.value || '',

        phone: document.querySelector('#digitalreceipt-phone')?.value || '',

        lines
    };
}

/**
 * Replace visible text in a table cell without
 * removing its hidden input.
 */
function replaceVisibleCellText(
    cell,
    newValue
) {

    if (!cell) {
        return;
    }


    /*
     * Remove text nodes only.
     *
     * The hidden input remains untouched.
     */

    Array.from(
        cell.childNodes
    ).forEach(node => {

        if (
            node.nodeType ===
            Node.TEXT_NODE
        ) {
            node.remove();
        }
    });


    cell.prepend(
        document.createTextNode(
            String(newValue)
        )
    );
}


/**
 * Re-index Yii's DigitalReceiptLine inputs.
 */
function reindexReceiptTable() {

    const rows =
        queryElements(
            '#receipt-rows tr'
        );


    rows.forEach(
        (row, index) => {

            const newIndex =
                index + 1;


            const inputs =
                queryElements(
                    'input',
                    row
                );


            inputs.forEach(input => {

                if (!input.name) {
                    return;
                }


                input.name =
                    input.name.replace(
                        /\[\d+\]/,
                        `[${newIndex}]`
                    );
            });
        }
    );
}


/*
 * --------------------------------------------------------------------------
 * ISBN scanner
 * --------------------------------------------------------------------------
 */


/**
 * Initialise the barcode scanner.
 */
function initialiseBarcodeScanner() {

    hideElements(
        '#info-isbn-not-found'
    );


    const scannerContainer =
        queryElement(
            '#scanner-container'
        );

    const resultElement =
        queryElement(
            '#scanned-result'
        );

    const addBookButton =
        queryElement(
            '#btn-add-book-by-isbn'
        );


    if (
        !scannerContainer ||
        !resultElement ||
        !addBookButton
    ) {
        return;
    }

    /*
     * Called when an ISBN has been detected.
     */

    function handleSuccessfulScan(isbn) {

        resultElement.textContent =
            isbn;

        addBookButton.disabled =
            false;
    }


    /*
     * Add the scanned product.
     */

    addBookButton.addEventListener(
        'click',
        () => {

            const scannedISBN =
                resultElement.textContent.trim();
            
            /*
             * Find the option corresponding
             * to the scanned ISBN.
             *
             * CSS.escape() protects the value when
             * constructing the CSS selector.
             */

            let matchingOption = null;


            if (
                scannedISBN &&
                window.CSS &&
                typeof window.CSS.escape === 'function'
            ) {

                matchingOption =
                    queryElement(
                        `#item-description option[data-isbn="${CSS.escape(scannedISBN)}"]`
                    );

            } else {

                /*
                 * Fallback which doesn't depend on CSS.escape().
                 */

                const options =
                    queryElements(
                        '#item-description option'
                    );


                matchingOption =
                    options.find(
                        option =>
                            option.dataset.isbn ===
                            scannedISBN
                    ) || null;
            }


            if (matchingOption) {
                
                const productSelect =
                    queryElement(
                        '#item-description'
                    );

                if (productSelect) {
                    
                    productSelect.value =
                        matchingOption.value;

                    /*
                     * Trigger the normal change handler.
                     */

                    productSelect.dispatchEvent(
                        new Event(
                            'change',
                            {
                                bubbles: true
                            }
                        )
                    );
                }


                /*
                 * Open the Add Item modal through
                 * its existing Bootstrap trigger.
                 */

                openModalUsingTrigger(
                    '#addItemModal'
                );

                resultElement.textContent =
                    '';


                hideElements(
                    '#info-isbn-not-found'
                );


                closeModalUsingDismissButton(
                    '#scanISBNModal'
                );


            } else {

                showElements(
                    '#info-isbn-not-found'
                );
            }
        }
    );


    /*
     * BarcodeDetector is not supported
     * in every browser.
     */

    if (
        !('BarcodeDetector' in window)
    ) {
        hideElements(
            '#scanISBNButton'
        );

        return;
    }

    /*
     * Create video element.
     */

    const video =
        document.createElement('video');


    video.id =
        'native-video';

    video.autoplay =
        true;

    video.playsInline =
        true;


    scannerContainer.appendChild(
        video
    );


    /*
     * Create barcode detector.
     */

    const barcodeDetector =
        new BarcodeDetector({
            formats: ['ean_13']
        });


    /*
     * Ask for camera access.
     */

    navigator.mediaDevices
        .getUserMedia({
            video: {
                facingMode: 'environment'
            }
        })
        .then(stream => {

            video.srcObject =
                stream;


            video.addEventListener(
                'play',
                () => {

                    detectBarcode(
                        video,
                        barcodeDetector
                    );
                }
            );
        })
        .catch(error => {

            /*
             * The original code attempted to use
             * "badge" here, but that variable does
             * not exist.
             *
             * Use the actual scan-result element.
             */

            resultElement.textContent =
                (
                    digitalReceiptConfig
                        .messages
                        .cameraError ||
                    'Camera error:'
                ) +
                ' ' +
                error.message;
        });


    /**
     * Continuously look for a barcode.
     */
    async function detectBarcode(
        videoElement,
        detector
    ) {

        try {

            const barcodes =
                await detector.detect(
                    videoElement
                );


            if (
                barcodes.length > 0
            ) {

                handleSuccessfulScan(
                    barcodes[0].rawValue
                );
            }


        } catch (error) {

            console.error(
                'Barcode detection error:',
                error
            );
        }


        requestAnimationFrame(
            () => {

                detectBarcode(
                    videoElement,
                    detector
                );
            }
        );
    }
}


/*
 * --------------------------------------------------------------------------
 * Loader
 * --------------------------------------------------------------------------
 */


/**
 * Show the submit loader.
 */
function initialiseSubmitLoader() {

    queryElements(
        '.loader'
    ).forEach(button => {

        button.addEventListener(
            'click',
            () => {

                showElements(
                    '#loader'
                );
            }
        );
    });
}


/*
 * --------------------------------------------------------------------------
 * Client UUID
 * --------------------------------------------------------------------------
 */


/**
 * Generate the client UUID.
 */
function initialiseClientUuid() {

    const uuidInput =
        queryElement(
            '#client-uuid'
        );


    if (
        uuidInput &&
        window.crypto &&
        typeof window.crypto.randomUUID === 'function'
    ) {

        uuidInput.value =
            window.crypto.randomUUID();
    }
}


/*
 * --------------------------------------------------------------------------
 * Bootstrap modal focus
 * --------------------------------------------------------------------------
 *
 * Bootstrap 4 emits its modal events through jQuery.
 *
 * Rather than listening to a jQuery event, we focus the product selector
 * shortly after the Add Item trigger has been activated.
 *
 * This keeps this JavaScript completely independent of jQuery.
 */


/**
 * Initialise Add Item modal behaviour.
 */
function initialiseAddItemModal() {

    const modalTrigger =
        queryElement(
            '[data-toggle="modal"][data-target="#addItemModal"]'
        );


    if (!modalTrigger) {
        return;
    }


    modalTrigger.addEventListener(
        'click',
        () => {

            /*
             * Bootstrap will open the modal after this click.
             *
             * A short delay allows Bootstrap to finish
             * inserting/displaying the modal.
             */

            window.setTimeout(
                () => {

                    const productSelect =
                        queryElement(
                            '#item-description'
                        );


                    if (productSelect) {
                        productSelect.focus();
                    }
                },
                100
            );
        }
    );
}


/*
 * --------------------------------------------------------------------------
 * Event registration
 * --------------------------------------------------------------------------
 */


/**
 * Register all application event handlers.
 */
function initialiseDigitalReceipt() {

    /*
     * Initial state.
     */

    hideElements(
        '.discount'
    );

    setElementValue(
        '#item-description',
        ''
    );


    /*
     * Add item.
     */

    const addItemButton =
        queryElement(
            '#btn-add-item'
        );


    if (addItemButton) {

        addItemButton.addEventListener(
            'click',
            addReceiptItem
        );
    }


    /*
     * Quantity / price changes.
     */

    const quantityInput =
        queryElement(
            '#item-qty'
        );

    if (quantityInput) {

        quantityInput.addEventListener(
            'input',
            updateRowPreviewTotal
        );
    }


    const priceInput =
        queryElement(
            '#item-price'
        );

    if (priceInput) {

        priceInput.addEventListener(
            'input',
            updateRowPreviewTotal
        );
    }


    /*
     * Remove receipt item.
     *
     * Event delegation is used because receipt rows
     * are created dynamically.
     */

    const receiptRows =
        queryElement(
            '#receipt-rows'
        );


    if (receiptRows) {

        receiptRows.addEventListener(
            'click',
            event => {

                const removeButton =
                    event.target.closest(
                        '.btn-remove'
                    );


                if (!removeButton) {
                    return;
                }


                const row =
                    removeButton.closest(
                        'tr'
                    );


                if (row) {
                    row.remove();
                }


                calculateGrandTotal();
            }
        );
    }


    /*
     * Discount buttons.
     */

    const applyDiscountButton =
        queryElement(
            '#apply-discount'
        );


    if (applyDiscountButton) {

        applyDiscountButton.addEventListener(
            'click',
            applyStandardDiscount
        );
    }


    const removeDiscountButton =
        queryElement(
            '#remove-discount'
        );


    if (removeDiscountButton) {

        removeDiscountButton.addEventListener(
            'click',
            removeStandardDiscount
        );
    }


    /*
     * Product selection.
     */

    const productSelect =
        queryElement(
            '#item-description'
        );


    if (productSelect) {

        productSelect.addEventListener(
            'change',
            handleProductSelection
        );
    }


    /*
     * Cash payment.
     */

    const cashPaymentSelector =
        queryElement(
            '#payment-selector-cash'
        );


    if (cashPaymentSelector) {

        cashPaymentSelector.addEventListener(
            'click',
            () => {

                setElementValue(
                    '#cash_payment_amount',
                    getElementValue(
                        '#total_amount'
                    )
                );


                setElementValue(
                    '#electronic_payment_amount',
                    ''
                );


                updateIssueButtonState();
            }
        );
    }


    /*
     * Card payment.
     */

    const cardPaymentSelector =
        queryElement(
            '#payment-selector-card'
        );


    if (cardPaymentSelector) {

        cardPaymentSelector.addEventListener(
            'click',
            () => {

                setElementValue(
                    '#cash_payment_amount',
                    ''
                );


                setElementValue(
                    '#electronic_payment_amount',
                    getElementValue(
                        '#total_amount'
                    )
                );


                updateIssueButtonState();
            }
        );
    }


    /*
     * UUID.
     */

    initialiseClientUuid();


    /*
     * Submit loader.
     */

    initialiseSubmitLoader();


    /*
     * Add Item modal.
     */

    initialiseAddItemModal();


    /*
     * ISBN scanner.
     */

    initialiseBarcodeScanner();
}


/*
 * --------------------------------------------------------------------------
 * Start application
 * --------------------------------------------------------------------------
 */

if (
    document.readyState === 'loading'
) {

    document.addEventListener(
        'DOMContentLoaded',
        initialiseDigitalReceipt
    );

} else {

    initialiseDigitalReceipt();
}
