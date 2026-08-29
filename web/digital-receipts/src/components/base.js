// src/components/base.js
export class Component {
  constructor(root) {
    this.root = root;               // DOM element where the component renders
    this.events = [];               // keep track for later cleanup
  }

  /** attach a listener and remember it for later removal */
  on(el, type, selector, handler) {
    const fn = e => {
      if (!selector || e.target.matches(selector)) handler(e);
    };
    el.addEventListener(type, fn);
    this.events.push({el, type, fn});
  }

  /** clear all listeners */
  destroy() {
    this.events.forEach(({el, type, fn}) => el.removeEventListener(type, fn));
    this.events = [];
    this.root.innerHTML = '';
  }
}
