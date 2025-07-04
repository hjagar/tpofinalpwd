function route(name, params = {}) {
  let url = window.ROUTES[name];
  if (!url) throw new Error(`Route "${name}" not found`);

  Object.keys(params).forEach(key => {
    url = url.replace(`{${key}}`, encodeURIComponent(params[key]));
  });

  return url;
}

function redirect(name, params = {}) {
    const url = route(name, params);

    document.location = url;
}