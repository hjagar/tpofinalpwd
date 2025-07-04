"use strict";
(function(window) {
  const http = (() => {
    const version = '1.0';
    const request = async (method, url, data = null, headers = {}) => {
      const options = {
        method: method.toUpperCase(),
        headers: { ...headers }
      };

      if (data instanceof FormData) {
        options.body = data;
      } else if (data !== null && typeof data === 'object') {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
      }

      try {
        const response = await fetch(url, options);
        const contentType = response.headers.get("content-type");

        let result;
        if (contentType && contentType.includes("application/json")) {
          result = await response.json();
        } else {
          result = await response.text();
        }

        if (!response.ok) {
          throw new Error(`Error ${response.status}: ${result}`);
        }

        return result;
      } catch (error) {
        console.error('http error:', error);
        throw error;
      }
    };

    const httpStatus = {
      ok: 200,
      created: 201,
      accepted: 202,
      no_content: 204,
      moved_permanently: 301,
      found: 302,
      not_modified: 304,
      bad_request: 400,
      unauthorized: 401,
      forbidden: 403,
      not_found: 404,
      method_not_allowed: 405,
      internal_server_error: 500,
      not_implemented: 501,
      bad_gateway: 502
    };

    return {
      get: (url, headers = {}) => request('GET', url, null, headers),
      post: (url, data, headers = {}) => request('POST', url, data, headers),
      put: (url, data, headers = {}) => request('PUT', url, data, headers),
      del: (url, headers = {}) => request('DELETE', url, null, headers),
      status: httpStatus,
      version
    };
  })();

  window.http = http;
})(window);
