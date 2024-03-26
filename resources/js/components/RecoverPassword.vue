<template>
  <div class="auth">
    <div class="recover">
      <header class="auth-header">
        <div class="container">
          <p class="auth-header__label">Bienvenido a</p>

          <figure class="auth-header__logo">
            <img
              class="img-fluid"
              src="/img/integra-logo-oficial-gris.svg"
              alt="Integra - Red de Salas Cuna y Jardines Infantiles">
          </figure>

          <div class="auth-header__flag"></div>
        </div>
      </header>

      <div class="pt-10 pb-8 container">
        <ValidationObserver
          v-if="showForm"
          v-slot="{ handleSubmit }"
          tag="div">
          <form class="form" @submit.prevent="handleSubmit(onSubmit)" ref="form" novalidate>
            <fieldset class="form__item">
              <div class="form__fields">
                <p class="forgot__indication text-white">
                  Ingresa tu nueva contraseña 2 veces para cambiarla.
                </p>

                <ValidationProvider
                  class="form-group form-group--auth"
                  name="contraseña"
                  rules="required|confirmed:confirmation|min:6"
                  v-slot="{ classes, errors }"
                  tag="div">
                  <label class="form-label form-label--auth" for="password">
                    Nueva contraseña
                  </label>
                  <div :class="['form-wrapper', classes]">
                    <i class="form-icon material-icons">lock_outline</i>
                    <input
                      v-if="hidePassword"
                      type="password"
                      id="password"
                      :class="['form-control form-control--auth', classes]"
                      name="password"
                      v-model="data.password"
                      tabindex="1">
                    <input
                      v-else
                      type="text"
                      id="password"
                      :class="['form-control form-control--auth', classes]"
                      name="password"
                      v-model="data.password"
                      tabindex="1">
                    <button
                      class="form-button button"
                      type="button"
                      @click="hidePassword = !hidePassword">
                      <i
                        class="button__icon material-icons"
                        v-text="hidePassword ? 'visibility' : 'visibility_off'" />
                    </button>
                  </div>
                  <small v-if="errors.length" class="form-helper">
                    <i class="material-icons">info</i> {{ errors[0] }}
                  </small>
                </ValidationProvider>
                <ValidationProvider
                  class="form-group form-group--auth"
                  name="contraseña"
                  rules="required|min:6"
                  v-slot="{ classes, errors }"
                  tag="div"
                  vid="confirmation">
                  <label
                    class="form-label form-label--auth"
                    for="confirm_password">
                    Confirmar nueva contraseña
                  </label>
                  <div :class="['form-wrapper', classes]">
                    <i class="form-icon material-icons">lock_outline</i>
                    <input
                      v-if="hideConfirmPassword"
                      type="password"
                      id="confirm_password"
                      :class="['form-control form-control--auth', classes]"
                      name="confirm_password"
                      v-model="confirmation"
                      tabindex="2">
                    <input
                      v-else
                      type="text"
                      id="password"
                      :class="['form-control form-control--auth', classes]"
                      name="confirm_password"
                      v-model="confirmation"
                      tabindex="2">
                    <button
                      class="form-button button"
                      type="button"
                      @click="hideConfirmPassword = !hideConfirmPassword">
                      <i
                        class="button__icon material-icons"
                        v-text="hideConfirmPassword ? 'visibility' : 'visibility_off'" />
                    </button>
                  </div>
                  <small v-if="errors.length" class="form-helper">
                    <i class="material-icons">info</i> {{ errors[0] }}
                  </small>
                </ValidationProvider>
              </div>

              <div class="form__submit text-center">
                <!-- <vue-recaptcha
                  ref="recaptcha"
                  size="invisible"
                  @verify="onVerify"
                  @expired="onExpired"
                  :sitekey="recaptchaKey" /> -->

                <button
                  class="button button--primary button--white button--more w-100"
                  :class="{ 'button--loading' : loading }"
                  :disabled="loading"
                  type="submit">
                  <span class="button__text" v-text="loading ? 'Enviando' : 'Cambiar contraseña'">Cambiar contraseña</span>
                </button>
              </div>
            </fieldset>
          </form>
        </ValidationObserver>

        <div
          v-else
          class="text-center">
          <h3 class="title title--m text-white mb-4">La contraseña de tu cuenta ha sido modificada</h3>
          <p class="forgot__indication text-white">
            Ahora puedes ingresar a <strong>IntegrApp</strong> e ingresar con tu RUT y esta nueva contraseña.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { recover } from './../auth';

export default {
  props: {
    id: {
      type: [Number, String],
      default: 0,
    },
  },
  data() {
    return {
      loading: false,
      showForm: true,
      // recaptchaKey: process.env.MIX_RECAPTCHA_KEY,
      hidePassword: true,
      hideConfirmPassword: true,
      data: {
        id: this.id,
        password: '',
        // recaptchaResponse: '',
        _method: 'PUT',
      },
      confirmation: '',
    };
  },
  methods: {
    submitForm() {
      this.loading = true;

      this.sendNewPassword(this.parseDataBeforeSend(this.data))
        .then(() => {
          this.showForm = false;
        })
        .catch(() => {
          this.$toasted.show('Lo sentimos, no hemos podido cambiar tu contraseña. Vuelve a intentarlo.', {
            theme: 'outline',
            position: 'bottom-center',
            duration: 5000,
          });
        })
        .then(() => {
          this.loading = false;
        });
    },
    parseDataBeforeSend(data) {
      const formData = new FormData();
      Object.keys(data).forEach(key => formData.append(key, data[key]));
      return formData;
    },
    onSubmit() {
      this.loading = true;
      this.submitForm();
      // this.$refs.recaptcha.execute();
    },
    // onVerify(response) {
    //   this.data.recaptchaResponse = response;
    //   this.submitForm();
    // },
    // onExpired() {
    //   this.resetRecaptcha();
    // },
    // resetRecaptcha() {
    //   this.data.recaptchaResponse = '';
    //   this.$refs.recaptcha.reset();
    // },
    async sendNewPassword(data) {
      const response = await recover(data);

      return response.data;
    },
  },
};
</script>
