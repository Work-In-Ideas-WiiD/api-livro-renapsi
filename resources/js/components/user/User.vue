<template>
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                    <!--         <h3 class="mb-0">Lista alunos</h3> -->
                    <search v-on:search="searchUser" ></search>
                    </div>
                    <!-- <div class="col-4 text-right">
                        <router-link to="usuario_criar" class="btn btn-sm btn-primary">Adicionar</router-link>
                    </div> -->
                </div>
            </div>

            <!-- Light table -->
            
            <div class="table-responsive">
              <table class="table align-items-center table-flush tabelaDash">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="budget">Username</th>
                    <th scope="col" class="sort" data-sort="status">E-mail</th>
                    <th scope="col" class="sort" data-sort="completion">Data</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="cliente in clientes.data" v-bind:key="cliente.id" style="margin-bottom: 5px;">
                        <td>{{ cliente.email.split("@")[0] }}</td>
                        <td>{{ cliente.email }}</td>
                        <td>{{ cliente.criado_em }}</td>
                        <td>
                            <!-- <button class="btn-circle btn-sm btn-primary" data-toggle="modal" data-target="#modal-form-edit" block @click="$router.push({name: 'user.edit', params: { id: cliente.id },})"><i class="fas fa-fw fa-edit"></i></button> -->
                            <button class="btn-circle btn-sm btn-danger" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(cliente)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>
            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :limit="12" :data="clientes" @pagination-change-page="getUsers"></pagination>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Excluir Aluno</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-block">
                <p>Tem certeza que deseja deletar o aluno: <b>{{ deleteUserData.nome }}</b></p>
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
        clientes: {},
        errors: {},
        deleteUserData: {},
        searchLike: '',
        searchOder: '',
        searchMostrar: '',
      }
    },
    mounted() {
      this.getUsers()
    },
    methods: {
        searchUser(payload){
            this.$http({
                url: 'users?filter[role]=0',
                method: 'GET',
                params: {
                'filter[email]': payload.dados.nome,
                sort: payload.dados.ordem,
                per_page: payload.dados.mostrar
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.clientes = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        getUsers(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }

        this.$http({
          url: `users?filter[role]=0&page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.clientes = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
      hideModal() {
        $('#modal-form').modal('hide')
      },
      hideEditModal() {
          $('#modal-form-edit').modal('hide')
        },
        editUser(cliente) {
          this.editUserData = cliente;
          this.errors = {};
          // this.showEditModal();
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
            // this.clientes.data = this.clientes.data.filter(obj => {
            //     return obj.id != cliente.id;
            // });

            this.getUsers();

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Aluno deletado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Cliente!',
                time: 5000
            });
          })
      },
      atualizarStatus(cliente, status){
          var app = this
          this.$http({
          url: `cliente_status/` + cliente.id,
          method: 'PUT',
          params: {
              status: status,
          },
          })
          .then(response=>{

              this.getUsers();

              this.flashMessage.success({
                  message: 'Cliente ' +response.data.status+ ' com sucesso!',
                  time: 5000
              });

          }, error => {
              this.flashMessage.error({
                  message: 'Error ao aprovar cliente!',
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
