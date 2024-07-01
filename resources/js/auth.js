import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: {
    request: function (req, token) {
      this.options.http._setHeaders.call(this, req, { Authorization: 'Bearer ' + token });
    },
    response: function (res) {
      return res.data.access_token
    }
  },
  http: axios,
  router: router,
  tokenDefaultName: 'laravel-vue-spa',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  registerData: {url: 'auth/register', method: 'POST', redirect: '/login'},
  loginData: {url: 'auth/login', method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: 'auth/logout', method: 'POST', redirect: '/login', makeRequest: true},
  fetchData: {url: 'auth/me', method: 'POST', enabled: true},
  parseUserData: function (response) {
    return response;
  },
  refreshData: {url: 'auth/refresh', method: 'POST', enabled: true, interval: 30}
}

export default config
