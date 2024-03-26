import axios from 'axios';

export const $http = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    Accept: 'application/json',
  },
});

class API {
  get(url, params) {
    params = params || {};
    return $http.get(url, params);
  }

  post(url, params) {
    params = params || {};
    return $http.post(url, params);
  }

  put(url, params) {
    params = params || {};
    return $http.put(url, params);
  }
}

export const APIHelper = new API();
