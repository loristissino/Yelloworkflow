// src/components/login.js
import {Component} from './base.js';
import {state} from '../store.js';
import {loggedActions, clearLogs} from '../db-core.js';

export default class LogsPage extends Component {
  async render() {
    this.root.innerHTML = '';
    this.root.appendChild(document.getElementById('logs-page').content.cloneNode(true));
    this.root.querySelector('#logs').innerHTML = (await loggedActions()).map(log => {
        let s = `<strong>${log.time}</strong> ${log.message}`;
        if (log.data) {
            s+= `<pre>${JSON.stringify(log.data, null, 2)}</pre>`;
        }
        return `<div>${s}</div>`;
    }).join('');
    
    this.on(this.root, 'click', '#homeBtn', () => {
        window.router.navigate('/digital-receipts/app/list');
    });
    this.on(this.root, 'click', '#clearLogsBtn', () => {
        clearLogs();
        window.router.navigate('/digital-receipts/app/logs');
    });
  }
}
