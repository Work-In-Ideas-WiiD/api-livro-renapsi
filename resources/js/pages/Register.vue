<template>
<div class="main-content main-login">
    <!-- Header -->
    <div class="header py-7 py-lg-5 pt-lg-5">
      <div class="container">
        <div class="header-body text-center">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white tituloPrimeiro">Primeiro Acesso!</h1>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>  -->
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0 blocoLogin">
            <div class="card-header bg-transparent">
               <div class="text-center">
                  <img src="assets/img/brand/assinatura-biblioteca-digital.png" class="navbar-brand-img" alt="..." width="200px">
               </div>
            </div>
            <div class="card-body px-lg-5">
              <!-- <div class="text-center text-muted mb-4">
                <small>Entre com suas credenciais</small>
              </div> -->
              <div class="alert alert-danger" v-if="has_error">
                  <p>Error, Não foi possivel confirma email e senha.</p>
              </div>
              <form role="form" autocomplete="off" @submit.prevent="registarSenha" method="post">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-key-25"></i></span>
                    </div>
                    <input class="form-control" v-model="codigo" placeholder="Código de verificação enviado por e-mail" type="text">
                  </div>

                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.token">
                      <strong>{{errors.token[0]}}</strong>
                    </span>
                </div>
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" v-model="email" placeholder="Digite seu e-mail" type="email">
                  </div>

                  <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.email">
                    <strong>{{errors.email[0]}}</strong>
                  </span>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" v-model="password" placeholder="Digite sua senha" type="password">
                  </div>

                  <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.password">
                    <strong>{{errors.password[0]}}</strong>
                  </span>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" v-model="password_confirmation" placeholder="Confirmação de senha" type="password">
                  </div>

                  <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.password_confirmation">
                    <strong>{{errors.password_confirmation[0]}}</strong>
                  </span>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4 botaoLogin">Cadastrar</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <!-- <div class="col-6">
              <router-link to="/forgot_passaword" class="text-light"><small>Esqueceu sua senha?</small></router-link>
            </div> -->
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
        codigo: '',
        email: '',
        password: '',
        password_confirmation: '',
        errors: {},
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
      registarSenha() {
        var app = this
        this.$http({
          url: `admin/atualizar-senha`,
          method: 'PUT',
          params: {
            token: app.codigo,
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          },

        })
          .then(response=>{

              this.$router.push({name: 'login'})

          }, error => {
            switch(error.response.status){
                case 422:
                    this.errors=error.response.data.errors;
                    break;
                default:
                    app.has_error = true
                    break;
            }
          })
      }
    }
  }
</script>
