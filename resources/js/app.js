import Vue from 'vue';
import VueRecaptcha from 'vue-recaptcha';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import es from 'vee-validate/dist/locale/es';
import Toasted from 'vue-toasted';
Vue.use(Toasted, {});
Vue.component('VueRecaptcha', VueRecaptcha);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

for (const rule in rules) {
  extend(rule, {
    ...rules[rule],
    message: es.messages[rule],
  });
}

require('./bootstrap');

Vue.component('recover-password', require('./components/RecoverPassword.vue').default);

new Vue({
  el: '#app',
});
