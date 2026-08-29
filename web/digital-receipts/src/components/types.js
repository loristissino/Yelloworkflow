// src/components/types.js
import {Component} from './base.js';
import {state} from '../store.js';
import {updateProducts} from '../db-core.js';

export default class TypePage extends Component {
  render() {
    console.log(state.receiptTypes);
    const list = state.receiptTypes
      .map(t => `<li data-id="${t.id}" class="btn btn-success" style="margin-right: 6px">➕ ${t.label}</li>`).join('');
    this.root.innerHTML = '';
    this.root.appendChild(document.getElementById('types-page').content.cloneNode(true));
    this.root.querySelector('#typeList').innerHTML = list;

    this.on(this.root, 'click', '#typeList li', e => {
      const typeId = +e.target.dataset.id;
      state.currentTypeId = typeId;
      window.router.navigate('/digital-receipts/app/issue');
    });

    this.on(this.root, 'click', '#list', () => {
      window.router.navigate('/digital-receipts/app/list');
    });

    this.on(this.root, 'click', '#update-catalog', async () => {
      state.products = await updateProducts();
      if (state.products.length >=0)
      {
          let message = window.digitalReceiptConfig.messages.catalogUpdated.replace('{number}', state.products.length);
          alert(message);
      }
    });

    this.on(this.root, 'click', '#logout', () => {
      state.user = null;
      window.router.navigate('/app/');
    });
  }
}
