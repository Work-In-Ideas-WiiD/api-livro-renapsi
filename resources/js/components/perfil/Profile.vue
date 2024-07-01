<template>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
            <img src="assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="assets/img/theme/avatar_blue.jpg" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
              <div class="d-flex justify-content-between">
                <!-- <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center">
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h5 class="h3">
                 {{ this.name }}
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i> {{ this.email }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8 order-xl-1">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Editar perfil </h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form @submit.prevent="updateUser"  method="PUT" >
                <h6 class="heading-small text-muted mb-4">Informações do Usuário</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email</label>
                        <input type="email" v-model="email" id="input-email" class="form-control" placeholder="email@example.com">

                        <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.email">
                            <strong>{{errors.email[0]}}</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-first-name">Senha</label>
                        <input type="password" v-model="password" id="input-first-name" class="form-control" placeholder="Digite Senha" >

                        <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.password">
                            <strong>{{errors.password[0]}}</strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-last-name">Confirmar Senha</label>
                        <input type="password" v-model="password_confirmation" id="input-last-name" class="form-control" placeholder="Digite Novamente">

                        <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.password_confirmation">
                            <strong>{{errors.password_confirmation[0]}}</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                 <div class="text-center">
                    <button type="button" @click="$router.go(-1)" class="btn btn-danger mt-4">Cancelar</button>
                    <button type="submit" class="btn btn-primary mt-4">Atualizar</button>
                 </div>
              </form>
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
        id: this.$auth.user().id,
        email: this.$auth.user().email,
        password: null,
        password_confirmation: null,
        errors: {},
      }
    },
    methods: {
        updateUser() {
        var app = this
        this.$http({
          url: `administrador/` + app.id,
          method: 'PUT',
          params: {
            email: app.email,
            password: app.password,
            password_confirmation: app.password_confirmation
          },

        })
          .then(response=>{
              this.errors = {}

              // this.success=response.data;
              this.flashMessage.success({
                  message: 'Perfil atualizado com sucesso!',
                  time: 5000
              });

          }, error => {
            switch(error.response.status){
                case 422:
                    this.errors=error.response.data.errors;
                    break;
                default:
                    this.flashMessage.error({
                        message: 'Error ao atualizar perfil!',
                        time: 5000
                    });
                    break;
            }
          })
      },
    }
}
</script>
