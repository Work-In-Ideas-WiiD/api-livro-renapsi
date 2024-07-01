<template>
    <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <!-- <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Bem-vindo!</h1>
            </div> -->
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent">
               <div class="text-center">
              <img src="assets/img/brand/DPCO_Mais.png" class="navbar-brand-img" alt="..." width="150px">
               </div>
                <div class="alert alert-danger" v-if="has_error">
                    <p>Error ao enviar link de redefinição de senha!</p>
                </div>
                <!-- <div class="alert alert-success" v-if="has_success">
                    <p>Link de redefinição de senha enviado com sucesso!</p>
                </div> -->
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <!-- <div class="text-center text-muted mb-4">
                <small>Entre com suas credenciais</small>
              </div> -->
              <form role="form" autocomplete="off" @submit.prevent="requestResetPassword" method="post">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" v-model="email" placeholder="Email" type="email">
                  </div>
                    <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.email">
                        <strong>{{errors.email[0]}}</strong>
                    </span>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Enviar link de redefinição de senha</button>
                </div>
              </form>
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
        has_error: false,
        has_success: false,
        errors: {},
      }
    },
    mounted() {
       this.bgAlterar()
    },
    methods: {
        bgAlterar(){
            $('#bg').addClass('bg-default')
        },
        requestResetPassword() {
            this.$http.post("/auth/reset-password", {
                email: this.email
            })
             .then(response=>{		
                // this.success=response.data;
                this.has_success = true;

                this.flashMessage.success({
                    message: 'Link de redefinição de senha enviado com sucesso!',
                    //message: response.data.message,
                    time: 5000
                });

            }).catch(error=>{
                switch(error.response.status){
                    case 422:
                        this.errors=error.response.data.errors;
                        break;
                    default:
                        this.flashMessage.error({
                            message: 'Error ao redifinir senha!',
                            time: 5000
                        });
                        this.has_error = true
                        break;
                }
            })   
        }
    }
}
</script>