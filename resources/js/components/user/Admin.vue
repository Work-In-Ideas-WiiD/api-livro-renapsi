<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista administradores</h3>
                    </div>
                    <div v-if="$auth.check(1)" class="col-4 text-right">
                        <a style="display: inline-block !important" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-primary">Adicionar</a>
                    </div>
                </div>
            </div>

            <!-- Light table -->
            <search v-on:search="searchAdmin" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Email</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="user in users.data" v-bind:key="user.id" style="margin-bottom: 5px;">
                        <td>{{ user.email }}</td>
                        <td>{{ user.criado_em }}</td>
                        <td>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-primary" title="Editar" block data-toggle="modal" data-target="#modal-form-edit" @click="editUser(user)"><i class="fas fa-fw fa-edit"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(user)"><i class="fas fa-fw fa-trash-alt"></i></button>
                            <button v-if="$auth.check(1)" class="btn-circle btn-sm btn-info" title="Reenviar Token" block @click="reenviarToken(user)"><i class="ni ni-send"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination :limit="12" align="right" :data="users" @pagination-change-page="getUsers"></pagination>
            </div>
          </div>
        </div>
      </div>

 <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Cadastrar Administrador</small></h5>
            </div>
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
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

                        <!-- <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
                                </div>
                                 <input class="form-control" v-mask="['(##) # ####-####', '(##) ####-####']" v-model="celular" placeholder="Celular" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.celular">
                                <strong>{{errors.celular[0]}}</strong>
                            </span>
                        </div> -->

                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                </div>
                                <b-form-select v-model="perfil" :options="perfis"></b-form-select>

                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.perfil">
                                <strong>{{errors.perfil[0]}}</strong>
                            </span>
                        </div>

                        <div v-if="perfil == 2" class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                 <input class="form-control" v-model="nome_vendedor" placeholder="Nome do vendedor" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome_vendedor">
                                <strong>{{errors.nome_vendedor[0]}}</strong>
                            </span>
                        </div>

                        <div v-if="perfil == 2" class="form-group">
                            <p style="text-align: center;" v-if="imagem_vendedor"> <img width="150px" :src="url_imagem_vendedor" ></p>
                            <b-form-file
                            v-model="imagem_vendedor"
                            @change="onImagemChange"
                            placeholder="Selecione a imagem do vendedor"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.imagem_vendedor">
                                <strong>{{errors.imagem_vendedor[0]}}</strong>
                            </span>
                            <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, outros formatos não serão aceitos como: JPEG, PNG</small>
                        </div>

                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button v-if="!loading" type="button" @click="postUser" class="btn btn-primary my-4">Salvar</button>
                            <button v-else type="button" disabled class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            </div>

        </div>
    </div>

    <div class="modal fade" ref="modal" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form-edit" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel"><small>Editar Administrador</small></h5>
            </div>
            <div class="modal-body p-0">
                <div class="card-body px-lg-5">
                    <form role="form">
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control" v-model="editUserData.email" placeholder="Email" type="email">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.email">
                                <strong>{{errors.email[0]}}</strong>
                            </span>
                        </div>
                        <!-- <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
                                </div>
                                 <input class="form-control" v-mask="['(##) # ####-####', '(##) ####-####']" v-model="editUserData.celular" placeholder="Celular" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.celular">
                                <strong>{{errors.celular[0]}}</strong>
                            </span>
                        </div> -->
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                </div>
                                <b-form-select v-model="editUserData.role" :options="perfis"></b-form-select>

                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.role">
                                <strong>{{errors.role[0]}}</strong>
                            </span>
                        </div>

                        <div v-if="editUserData.role == 2" class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                               <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                 <input class="form-control" v-model="nome_vendedor_update" placeholder="Nome do vendedor" type="text">
                            </div>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.nome_vendedor_update">
                                <strong>{{errors.nome_vendedor_update[0]}}</strong>
                            </span>
                        </div>

                        <div v-if="editUserData.role == 2" class="form-group">
                            <p style="text-align: center;" v-if="url_imagem_vendedor_update"> <img width="150px" :src="url_imagem_vendedor_update" ></p>
                            <b-form-file
                            v-model="imagem_vendedor_update"
                            @change="onImagemChangeUpdate"
                            placeholder="Selecione a imagem do vendedor"
                            drop-placeholder="Solte a imagem aqui..."
                            ></b-form-file>
                            <span class="invalid-feedback" style="display: block;" role="alert" v-if="errors.imagem_vendedor_update">
                                <strong>{{errors.imagem_vendedor_update[0]}}</strong>
                            </span>
                            <small class="text-muted" >tamanho mínimo da imagem - 192x192 pixels, será aceito somente imagem JPG, outros formatos não serão aceitos como: JPEG, PNG</small>
                        </div>

                        <div class="text-center">
                            <button type="button" data-dismiss="modal" class="btn btn-danger my-4">Fechar</button>
                            <button type="button" @click="updateUser" class="btn btn-primary my-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Excluir Administrador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar o administrador: <b>{{ deleteUserData.email }}</b></p>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="deleteUser(deleteUserData)">Excluir</b-button>
        </div>
        </div>
    </div>
    </div>

</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/Search.vue';
export default {
     data() {
      return {
        has_error: false,
        users: {},
        email: null,
        celular: null,
        nome_vendedor: null,
        imagem_vendedor: null,
        url_imagem_vendedor: null,
        nome_vendedor_update: null,
        imagem_vendedor_update: null,
        url_imagem_vendedor_update: null,
        password: null,
        password_confirmation: null,
        perfis: [
          { text: 'Administrador', value: 1 },
        //   { text: 'Vendedor', value: 2 },
        //   { text: 'Estoque', value: 3 },
        //   { text: 'Entrega', value: 4 },
        //   { text: 'Financeiro', value: 5 },
        //   { text: 'Gestor De Produção', value: 6 }
        ],
        perfil: 1,
        errors: {},
        editUserData: {},
        deleteUserData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
        loading: false,
      }
    },
    mounted() {
      this.getUsers()
    },
    methods: {
        onImagemChangeUpdate(e) {
            const file = e.target.files[0];
            this.imagem_vendedor_update = file;
            this.url_imagem_vendedor_update = URL.createObjectURL(file);
        },
        onImagemChange(e) {
            const file = e.target.files[0];
            this.imagem_vendedor = file;
            this.url_imagem_vendedor = URL.createObjectURL(file);
        },
        searchAdmin(payload){
            this.$http({
                url: 'users?filter[role]=1',
                method: 'GET',
                params: {
                like: payload.dados.nome,
                order: payload.dados.ordem,
                mostrar: payload.dados.mostrar
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.users = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').modal('hide')
        },
        getUsers(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `users?filter[role]=1&page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.users = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      postUser() {
        var app = this
        app.loading = true
        let formData = new FormData()
        formData.append('email',  app.email)
        formData.append('celular',  app.celular)
        formData.append('role',  app.perfil)
        formData.append('perfil',  0)
        formData.append('nome_vendedor',  app.nome_vendedor)
        formData.append('imagem_vendedor',  app.imagem_vendedor)

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `admin/criar-senha`,
          method: 'POST',
          config: config,
          data: formData,
            })
                .then(response=>{
                    // this.success=response.data;
                    response.data.role = 1,
                    this.users.data.unshift(response.data);

                    this.hideModal();

                    this.flashMessage.success({
                        message: 'Administrador cadastrado com sucesso!',
                        time: 5000
                    });

                    this.name = '';
                    this.email = '';
                    this.celular = '';
                    this.password = '';
                    this.password_confirmation = '';
                    this.nome_vendedor = null;
                    this.imagem_vendedor = null;
                    this.perfil = 1;
                    app.loading = false

                    this.getUsers()

                }, error => {
                    app.loading = false
                    switch(error.response.status){
                        case 422:
                            this.errors=error.response.data.errors;
                            break;
                        default:
                            this.flashMessage.error({
                                message: 'Erro ao cadastrado administrador!',
                                time: 5000
                            });
                            break;
                    }
                })
        },
        hideEditModal() {
          $('#modal-form-edit').modal('hide')
        },
        editUser(user) {
          this.editUserData = user;
          this.errors = {};
          if(user.vendedor != null){
              this.nome_vendedor_update = user.vendedor.nome
              this.url_imagem_vendedor_update = '../storage/vendedores/' + user.vendedor.imagem
          }
          // this.showEditModal();
        },
        updateUser() {
        var app = this
        app.loading = true
        let formData = new FormData()
        formData.append('email',  app.editUserData.email)
        formData.append('role',  app.editUserData.role)
        if(app.nome_vendedor_update != null){
            formData.append('nome_vendedor_update',  app.nome_vendedor_update)
        }
        if(app.imagem_vendedor_update != null){
            formData.append('imagem_vendedor_update',  app.imagem_vendedor_update)
        }

        let config = {
            headers: {
            'content-type': 'multipart/form-data'
            }
        }
        this.$http({
          url: `users/` + app.editUserData.id,
          method: 'PUT',
          config: config,
          data: formData,
        })
          .then(response=>{
            // this.success=response.data;
            // response.data.role = 1,

            this.nome_vendedor_update = null;
            this.imagem_vendedor_update = null;
            this.url_imagem_vendedor_update = null;
            app.loading = false

            this.getUsers()

            this.hideEditModal();
            this.flashMessage.success({
                message: 'Usuário atualizado com sucesso!',
                time: 5000
            });

          }, error => {
            app.loading = false
            switch(error.response.status){
                case 422:
                    this.errors=error.response.data.errors;
                    break;
                default:
                    this.flashMessage.error({
                        message: 'Erro ao atualizar usuário!',
                        time: 5000
                    });
                    break;
            }
          })
      },
      showDelete(user){
        this.deleteUserData = user;
      },
      hideDeleteModal() {
        $('#modal-delete').modal('hide')
      },
      deleteUser(user) {

        this.$http({
            url: `users/` + user.id,
            method: 'DELETE',
          })

          .then(response=>{
            this.users.data = this.users.data.filter(obj => {
                return obj.id != user.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Administraor deletado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Usuário!',
                time: 5000
            });
          })
      },
      reenviarToken(user) {

        this.$http({
            url: `reenviar_token/` + user.id,
            method: 'GET',
          })

          .then(response=>{

            this.flashMessage.success({
                message: 'Token reenviado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao reenviar token!',
                time: 5000
            });
          })
      },
    },
    components: {
      search
    }
}
</script>
