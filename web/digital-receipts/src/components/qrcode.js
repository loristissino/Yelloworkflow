// src/components/qrcode.js
import {Component} from './base.js';
import {state, hashString} from '../store.js';
import {getReceipt} from '../db-core.js';

export default class QrcodePage extends Component {
  async render() {

    this.root.innerHTML = '';
    this.root.appendChild(document.getElementById('qrcode-page').content.cloneNode(true));
    
    let hash = (await hashString(state.currentReceipt + state.user.secret)).substring(0, 8);
    
    let url = window.digitalReceiptConfig.urlTemplates.onlineReceipt
        .replace('client_id', state.currentReceipt)
        .replace('hash', hash);
  
    new QRCode(document.getElementById("qrcode"), url);

    const receipt = await getReceipt(state.currentReceipt);
    
    this.root.querySelector('#qrcode-page-amount').innerHTML = receipt.total_amount;

    this.root.querySelector('#receipt_url').innerHTML = url;
    
    this.root.querySelector('#receipt-items').innerHTML = receipt.lines.map(item => 
        `<li><strong>${item.description}</strong> - <em>${item.notes}</em> €${item.unit_price.toFixed(2)} (${item.quantity})</li>`
    ).join('');
    
    this.on(this.root, 'click', '#homeBtn', () => {
        window.router.navigate('/digital-receipts/app/list');
    });
    
  }
  
}
