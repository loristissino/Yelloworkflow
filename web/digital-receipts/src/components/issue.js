// src/components/issue.js
import {Component} from './base.js';
import {state} from '../store.js';
import {addReceipt, syncReceipt, requestBackgroundSync, logAction} from '../db-core.js';

export default class IssuePage extends Component {
  render() {
    const type = state.receiptTypes.find(t => t.id === state.currentTypeId);
    window.digitalReceiptConfig.maxAmount = type.amount_hard_limit;
    console.log("rendering issue page");
    
    const products = state.products
        .filter((p) => p.digital_receipt_type_id == state.currentTypeId)
        .sort((a, b) => {
            if (a.rank !== b.rank) {
                return a.rank - b.rank;
            }
            return a.description.localeCompare(b.description);
            }
        )
        .map(p => `<option 
            data-original_price="${p.unit_price}" 
            data-isbn="${p.isbn}" 
            data-max-discount="${p.max_discount}"
            data-standard_discount="${p.standard_discount}"
            data-extra_info_required="${p.extra_info_required ?? ''}"
            data-description="${p.description}"
            data-id="${p.id}"
            value="${p.id}"
            >${p.description} – ${p.unit_price}&nbsp;€</option>`)
        .join('');
    this.root.innerHTML = '';
    this.root.appendChild(document.getElementById('receipt-page').content.cloneNode(true));
    this.root.querySelector('#item-description').innerHTML = products;
    this.root.querySelector('#receipt-type-description').innerHTML = type?.description ?? '';
    this.root.querySelector('#receipt-type-description-in-use').innerHTML = type?.label ?? '';
    this.root.querySelector('#receipt-type-max_amount').innerHTML = this.root.querySelector('#receipt-type-max_amount').innerHTML.replace('{amount}', '€ ' + Math.round(type?.amount_hard_limit ?? 0), 0);
    const emailFieldHint = this.root.querySelector('#receipt-email-hint');
    emailFieldHint.innerHTML = emailFieldHint.innerHTML.replace(/{email}/, state.user.organizational_unit.email);
    this.root.querySelector('#digitalreceipt-email').addEventListener('change', function(event) {
        if (!this.validity.valid) {
            alert(window.digitalReceiptConfig.messages.invalidEmail);
            event.target.focus();
        }
    });
    this.root.querySelector('#digitalreceipt-phone').addEventListener('change', function(event) {
        // Allows: +1234567890, 1234567890, +1 234 567 890, etc.
        const phoneRegex = /^\+?[\d\s\-().]{7,}$/;;
        if (this.value && !phoneRegex.test(this.value)) {
            alert(window.digitalReceiptConfig.messages.invalidPhone);
            event.target.focus();
        }
    });
    
    initialiseDigitalReceipt(); // legacy code

    this.items = []; // {productId, qty}

    // open modal
    this.on(this.root, 'click', '#addItemBtn', () => {
      let modal = this.root.querySelector('#addItemModal');
      modal.classList.remove('hidden');
    });

    this.on(this.root, 'click', '#scanISBNButton', () => {
      let modal = this.root.querySelector('#scanISBNModal');
      modal.classList.remove('hidden');
    });

    // cancel
    this.on(this.root, 'click', '#addItem-closeBtn', () => {
      this.root.querySelector('#addItemModal').classList.add('hidden');
    });
    
    this.on(this.root, 'click', '#scanISBN-closeBtn', () => {
      this.root.querySelector('#scanISBNModal').classList.add('hidden');
    });

    // save receipt
    this.on(this.root, 'click', '#issueButton', async () => {
      const receipt = getReceiptFromForm();
      receipt.digital_receipt_type_id = state.currentTypeId;
      receipt.organizational_unit_id = state.user.organizational_unit.id;
      receipt.date = new Date().toISOString();
      
      await addReceipt(receipt);
      logAction('Saving receipt...');
      let res = await syncReceipt(receipt);
      logAction('Receipt saved?', res);
      
      if (res) {
            let link = window.digitalReceiptConfig.urlTemplates.onlineReceipt.replace('client_id', receipt.client_id);
            window.open(link, '_blank');
      }
      else {
            console.log('syncReceipt failed');
            await requestBackgroundSync();
            logAction('Requested background sync', receipt);
            console.log('background sync requested');
            state.currentReceipt = receipt.client_id;
            console.log(state.currentReceipt);
            window.router.navigate('/digital-receipts/app/qrcode');
            return;
      }
      window.router.navigate('/digital-receipts/app/list');
    });

    this.on(this.root, 'click', '#backBtn', () => {
      window.router.navigate('/digital-receipts/app/types');
    });
  }

  updateList() {
    const ul = this.root.querySelector('#itemList');
    ul.innerHTML = this.items.map(i => {
      const prod = state.products.find(p => p.id === i.productId);
      return `<li>${prod.description} × ${i.qty}</li>`;
    }).join('');
  }
}
