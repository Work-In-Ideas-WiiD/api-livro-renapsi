<template>
<div class="main-content main-login">
    <!-- Header -->
    <!-- <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Bem-vindo!</h1>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div> -->
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0 blocoLogin">
            <div class="card-header bg-transparent">
               <div class="text-center">
                  <img src="assets/img/brand/assinatura-biblioteca-digital.png" class="navbar-brand-img" alt="..." width="300px">
               </div>
            </div>
            <div class="card-body px-lg-5">
              <!-- <div class="text-center text-muted mb-4">
                <small>Entre com suas credenciais</small>
              </div> -->
              <div class="alert alert-danger" v-if="has_error">
                  <p>Error, Não foi possivel confirma email e senha.</p>
              </div>
              <form role="form" autocomplete="off" @submit.prevent="login" method="post">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" v-model="email" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" v-model="password" placeholder="Senha" type="password">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Lembrar</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4 botaoLogin">Login</button>
                </div>
              </form>
            </div>
            <div class="row mt-3 linksFooter">
              <div class="col-6">
                <router-link to="/forgot_passaword" class="text-white"><small>Esqueceu sua senha?</small></router-link>
              </div>
              <div class="col-6 text-right">
                <router-link to="/register" class="text-white"><small>Primeiro Acesso</small></router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        email: null,
        password: null,
        has_error: false
      }
    },
    mounted() {
       this.bgAlterar()
    },
    methods: {
      bgAlterar(){
        $('#bg').addClass('bg-default')
      },
      login() {
        // get the redirect object
        var redirect = this.$auth.redirect()
        var app = this
        this.$auth.login({
          params: {
            email: app.email,
            password: app.password
          },
          success: function() {
            // handle redirection
            const redirectTo = redirect ? redirect.from.name : this.$auth.user().role === 1 ? 'admin.dashboard' : 'dashboard'
            //console.log(this.$auth);
            this.$router.push({name: redirectTo})
          },
          error: function() {
            app.has_error = true
          },
          rememberMe: true,
          fetchUser: true
        })
      }
    }
  }
</script>
