// src/components/list.js
import {Component} from './base.js';
import {state} from '../store.js';
import {syncReceipts, getUnsyncedReceipts, getAll} from '../db-core.js';

export default class ListPage extends Component {
  async render() {
    this.root.innerHTML = '';
    this.root.appendChild(document.getElementById('receipts-list').content.cloneNode(true));
    await this.populate();
    await this.manageNotifications();

    this.on(this.root, 'click', '#syncBtn', async () => {
      await syncReceipts();
      this.render();   // re‑render to update sync count
    });

    this.on(this.root, 'click', '#newReceiptBtn', () => {
      window.router.navigate('/digital-receipts/app/types');
    });

    this.on(this.root, 'click', '.qrcodeLink', async (event) => {
        state.currentReceipt = event.target.dataset.client_id;
        window.router.navigate('/digital-receipts/app/qrcode');
    });

    this.on(this.root, 'click', '#logsBtn', async () => {
        window.router.navigate('/digital-receipts/app/logs');
    });
    /*
    this.on(this.root, 'click', '', async () => {
    });
*/
/*
    this.on(this.root, 'click', '#notificationsBtn', async () => {
        console.log('requesting permission...');
        const permission = await Notification.requestPermission();
        console.log(permission);
        if (permission === "granted") {
            this.root.querySelector('#notificationsBtn').classList.add('hidden');
            this.root.querySelector('#notifications-enabled-message').classList.remove('hidden');
        };
    });
*/    
    this.on(this.root, 'click', '#notificationsBtn', async () => {
        try {
            console.log('requesting permission...');
            
            // 1. Check native support
            if (!('Notification' in window) || !('serviceWorker' in navigator)) {
                console.warn('Notifications or Service Workers are not supported');
                return;
            }

            // 2. Obtain active Service Worker registration
            const registration = await navigator.serviceWorker.ready;

            // 3. Request permission (handles both modern Promise and older callback APIs for mobile compatibility)
            let permission = Notification.permission;
            if (permission === 'default') {
                permission = await new Promise((resolve) => {
                    const res = Notification.requestPermission(resolve);
                    if (res) {
                        res.then(resolve);
                    }
                });
            }

            console.log('Permission state:', permission);

            if (permission === 'granted') {
                this.root.querySelector('#notifications-enabled-message')?.classList.remove('hidden');
            }
            
            this.root.querySelector('#notificationsBtn')?.classList.add('hidden');
            
        } catch (err) {
            console.error('Failed to request notification permission:', err);
        }
    });
    
    
  }

  async populate() {
    const ul = this.root.querySelector('#receiptList');
    const receipts = await getAll('receipts');
    const receiptTypes = await getAll('receiptTypes');
    ul.innerHTML = receipts.sort((a, b) => a.date > b.date ? 1 : -1).map(r => {
      const type = receiptTypes.find(t => t.id === r.typeId)?.name ?? '';
      const status = r.synced ? '✅' : '🕒';
      let html = `<li>${status} ${type} – ${new Date(r.date).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' })} (${r.client_id.substring(0, 6)}…) <strong>€&nbsp;${r.total_amount.toFixed(2)}</strong>`;
      if (navigator.onLine && r.synced) {
          let link = window.digitalReceiptConfig.urlTemplates.onlineReceipt.replace('client_id', r.client_id);
          html += ` <a href="${link}" target="_blank" style="text-decoration: none">👁️</a>`;
      }
      if (!r.synced) {
          html += ` <a href="#" class="qrcodeLink" data-client_id="${r.client_id}" style="text-decoration: none">🪧</a>`;
      }
      html += `</li>`;
      return html;
      //return `<li>${status} ${type} – ${r.client_id}</li>`;
    }).join('');
    const unsync = await getUnsyncedReceipts();
    this.root.querySelector('#outOfSyncNo').innerHTML = unsync.length;
    if (unsync.length==0) {
        const s = this.root.querySelector('#syncBtn').style;
        s.opacity = 1;
        (function fade(){(s.opacity-=.1)<0?s.display="none":setTimeout(fade,100)})();
    }

  }
  
  async manageNotifications() {
      console.log(Notification.permission);
      switch (Notification.permission) {
        case "granted":
          this.root.querySelector('#notifications-enabled-message').classList.remove('hidden');
          break;

        case "denied":
          this.root.querySelector('#notifications-disabled-message').classList.remove('hidden');
          break;

        case "default":
          this.root.querySelector('#notificationsBtn').classList.remove('hidden');
          break;
      }
  } 
}
