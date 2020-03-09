// Axios Setup
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Register CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Devour Setup
import Devour from 'devour-client';

window.jsonApi = new Devour({
    apiUrl:'http://studies-laravel-api.test/api/v1',
});

jsonApi.axios = axios;

require('./models');
