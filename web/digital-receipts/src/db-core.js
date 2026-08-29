// src/db_core.js

const DB_NAME = 'ywf_db';
const DB_VERSION = 1;
let syncInProgress = false;

export function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open(DB_NAME, DB_VERSION);
    request.onupgradeneeded = e => {
      const db = e.target.result;
      if (!db.objectStoreNames.contains('receiptTypes')) db.createObjectStore('receiptTypes', {keyPath: 'id'});
      if (!db.objectStoreNames.contains('products'))     db.createObjectStore('products',     {keyPath: 'id'});
      if (!db.objectStoreNames.contains('receipts'))     db.createObjectStore('receipts',     {keyPath: 'client_id'});
      if (!db.objectStoreNames.contains('logs'))         db.createObjectStore('logs',         {keyPath: 'id', autoIncrement: true});
    };
    request.onsuccess = e => resolve(e.target.result);
    request.onerror = e => reject(e.target.error);
  });
}

export async function getAll(storeName) {
  const db = await openDB();
  return new Promise((res, rej) => {
    const tx = db.transaction(storeName, 'readonly');
    const req = tx.objectStore(storeName).getAll();
    req.onsuccess = () => res(req.result);
    req.onerror = e => rej(e);
  });
}

export async function addItem(storeName, item) {
  const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(storeName, 'readwrite');
        const store = tx.objectStore(storeName);

        const req = store.add(item);

        req.onsuccess = () => resolve(req.result);
        req.onerror = () => reject(req.error);

        tx.onabort = () => reject(tx.error);
    });
}

export async function putItem(storeName, item) {
  const db = await openDB();
  return new Promise((res, rej) => {
    const tx = db.transaction(storeName, 'readwrite');
    const req = tx.objectStore(storeName).put(item);
    req.onsuccess = () => res(req.result);
    req.onerror = e => rej(e);
  });
}

export async function getItem(storeName, key) {
  const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(storeName, 'readonly');
        const store = tx.objectStore(storeName);
        const req = store.get(key);

        req.onsuccess = () => resolve(req.result);
        req.onerror = () => reject(req.error);
    });
}

export async function logAction(message, data = null) {
  const db = await openDB();
  const tx = db.transaction('logs', 'readwrite');
  tx.objectStore('logs').add({ time: new Date().toISOString(), message, data });
}

export async function syncReceipt(receipt) {
  try {
    await logAction('sync start', receipt.client_id);
    const resp = await fetch('/api/v1/digital-receipts', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(receipt)
    });
    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
    
    const result = await resp.json();
    receipt.synced = true;
    receipt.id = result.id;
    receipt.status = result.status;
    
    await putItem('receipts', receipt);
    await logAction('saved to IndexedDB', result);
    return resp.status == 201 ? 1 : 0; // the server returns 201 if the receipt wasn't in the DB, 200 if it was already there
  } catch (error) {
    await logAction('failure', { client_id: receipt.client_id, message: error.message });
    return false;
  }
}

export async function syncReceipts() {
  if (syncInProgress) {
    await logAction('sync skipped: already in progress');
    return;
  }
  syncInProgress = true;
  let result = {transmitted: 0, added: 0, present: 0, failed: 0};
  try {
    const receipts = await getAll('receipts');
    const unsynced = receipts.filter(r => !r.synced);
    result.transmitted = unsynced.length;
    for (const receipt of unsynced) {
        let res = await syncReceipt(receipt);
        if (res === false) {
            result.failed++;
        }
        else {
            if (res==1) {
                result.added++;
            }
            else {
                result.present++;
            }
        }
    }
    await logAction('receipts synced', result);
    if ('BroadcastChannel' in self) {
      new BroadcastChannel('sync_channel').postMessage({ type: 'SYNC_COMPLETE' });
    }
  } finally {
    syncInProgress = false;
  }
  return result;
}

export async function fetchReceiptTypes() {
  return fetchFromAPIService('/api/v1/digital-receipt-types');
}

export async function fetchProducts() {
  return fetchFromAPIService('/api/v1/products');
}

async function fetchFromAPIService(url) {
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`Failed to fetch receipt types: ${response.status}`);
  }
  return await response.json();
}

export async function updateProducts() {
  logAction('updating catalog of products', '');
  try {
    const products = await fetchProducts();
    await replaceAll('products', products);
    logAction('catalog of products updated', products.length);
    return products;
  } catch (e) {
    logAction('error in updating the catalog of products', e);
    return -1;
  }
}

/* Add a new receipt (called from Issue component) */
export async function addReceipt(receipt) {

    console.log('ADDING RECEIPT:', receipt);

    await addItem('receipts', receipt);

    console.log(
        'IndexedDB after add:',
        await getAll('receipts')
    );

    return receipt;
}

export async function requestBackgroundSync() {
    console.log('requestBackgroundSync called');
    if (!('serviceWorker' in navigator)) {
        console.log('no service worker available');
        return;
    }

    const registration = await navigator.serviceWorker.ready;

    console.log('sw registration found');

    if ('sync' in registration) {
        console.log('waiting for sync-receipts');
        await registration.sync.register('sync-receipts');
        console.log('ok, returning');
    }
}

export async function getUnsyncedReceipts() {

    logAction('retrieving unsynced', '');

    const receipts = await getAll('receipts');

    const unsynced = receipts.filter(
        receipt => !receipt.synced
    );
    
    logAction('unsynced retrieved', unsynced.length);

    return unsynced;
}

export async function getReceipt(client_id) {
    return getItem('receipts', client_id);
}

export async function clearLogs() {
    await replaceAll('logs', []);
}

export async function loggedActions() {
    return await getAll('logs');
}

export async function replaceAll(storeName, items) {
  const db = await openDB();
  return new Promise((resolve, reject) => {
    const tx = db.transaction(storeName, 'readwrite');
    const store = tx.objectStore(storeName);

    store.clear();

    for (const item of items) {
      store.put(item);
    }

    tx.oncomplete = () => resolve();
    tx.onerror = () => reject(tx.error);
  });
}
