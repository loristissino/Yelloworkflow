// src/app.js
import {Router} from './router.js';
import LoginPage from './components/login.js';
import TypePage  from './components/types.js';
import IssuePage from './components/issue.js';
import ListPage  from './components/list.js';
import QrcodePage  from './components/qrcode.js';
import LogsPage  from './components/logs.js';
import {initDB, state} from './store.js';
import { userReady } from '/app/js';

async function main() {
  await initDB();           

  const routes = {
    '/':                             LoginPage,
    '/digital-receipts/app/types':   TypePage,
    '/digital-receipts/app/issue':   IssuePage,
    '/digital-receipts/app/list':    ListPage,
    '/digital-receipts/app/qrcode':  QrcodePage,
    '/digital-receipts/app/logs':    LogsPage,
  };

  window.router = new Router(routes);
  // initial navigation – if a user object exists, go straight to the app
  state.user = await userReady;
  
  if (state.user) window.router.navigate('/digital-receipts/app/types');
  else window.router.render();
}
main();
