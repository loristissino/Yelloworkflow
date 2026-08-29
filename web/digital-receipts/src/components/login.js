// src/components/login.js
import {Component} from './base.js';
import {state} from '../store.js';
import { userReady } from '/app/js';

const user = await userReady;

console.log('User is ready:', user);
export default class LoginPage extends Component {
  render() {
    this.root.innerHTML = `
      <div>${window.digitalReceiptConfig.messages.mustBeLoggedIn}</div>
    `;
  }
}
