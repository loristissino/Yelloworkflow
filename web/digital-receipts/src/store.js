// src/store.js

import * as db from './db-core.js';

export const state = {
  user: null,               // {id, name}
  receiptTypes: [],         // [{id, name}]
  products: [],             // [{id, name, price}]
  receipts: [],             // [{id, typeId, items:[{productId, qty}], synced:false}]
  currentReceipt: 0
};

export async function initDB() {
  state.receiptTypes = await db.getAll('receiptTypes');
  state.products     = await db.getAll('products');
  state.receipts     = await db.getAll('receipts');
  
  console.log("products imported: " + state.products.length);
  
  try {
    const receiptTypes = await db.fetchReceiptTypes();
    await db.replaceAll('receiptTypes', receiptTypes);
    state.receiptTypes = receiptTypes;
  } catch (e) {
    console.warn('Could not fetch receipt types, using cached data:', e);
  }
  // Then try to refresh products from the server.
  await db.updateProducts();  
}

// Broadcast channel updates main thread memory when background sync finishes
const syncChannel = new BroadcastChannel('sync_channel');
syncChannel.onmessage = async (event) => {
  if (event.data.type === 'SYNC_COMPLETE') {
    state.receipts = await db.getAll('receipts');
  }
};


export async function hashString(str) {
    const encoder = new TextEncoder();
    const data = encoder.encode(str);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);

    // Convert to hex string
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    return hashHex;    
}

// export { syncReceipts } from './db-core.js';
