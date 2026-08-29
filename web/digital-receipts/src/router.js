// src/router.js
export class Router {
  constructor(routes) {
    this.routes = routes;   // {path: componentClass}
    window.addEventListener('popstate', () => this.render());
  }

  navigate(path) {
    history.pushState(null, '', path);
    this.render();
  }

  render() {
    const path = location.pathname;
    const ComponentClass = this.routes[path] || this.routes['/'];
    if (this.current) this.current.destroy();
    this.current = new ComponentClass(document.getElementById('app'));
    this.current.render();
  }
}
