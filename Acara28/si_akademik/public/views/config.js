// Tentukan alamat API dari folder public, satu tingkat di atas folder views.
const BASE_URL = new URL('../', window.location.href).href.replace(/\/$/, '');
