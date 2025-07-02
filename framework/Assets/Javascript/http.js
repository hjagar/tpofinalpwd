(function(window) {
  const http = (() => {
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

    return {
      get: (url, headers = {}) => request('GET', url, null, headers),
      post: (url, data, headers = {}) => request('POST', url, data, headers),
      put: (url, data, headers = {}) => request('PUT', url, data, headers),
      del: (url, headers = {}) => request('DELETE', url, null, headers),
    };
  })();

  window.http = http;
})(window);
