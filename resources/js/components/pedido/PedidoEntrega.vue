<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Lista Pedido</h3>
                    </div>
                    <div class="col-4 text-right">
                        <router-link v-if="$auth.check([1,2])" type="button" to="pedido_criar" class="btn btn-sm btn-primary">Adicionar</router-link >
                        <a v-if="$auth.check(1)" @click="exportRelatorio" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-success">Exportar</a>
                    </div>
                </div>
            </div>

            <!-- Light table -->
            <search v-on:search="searchPedidos" ></search>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="status">Data</th>
                    <th scope="col" class="sort" data-sort="status" >Cliente</th>
                    <th scope="col" class="sort" data-sort="status" >Entrega</th>
                    <th scope="col" class="sort" data-sort="status" >Total</th>
                    <th scope="col" class="sort" data-sort="status" >Data Entrega</th>
                    <th scope="col" class="sort" data-sort="completion">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="pedido in pedidos.data" v-bind:key="pedido.id">
                        <td>{{ getHumanDate(pedido.created_at) }}</td>
                        <td>{{ pedido.cliente.nome }}</td>
                        <td>{{ pedido.entrega.nome }}</td>
                        <td>{{ formatPrice(pedido.total) }}</td>
                        <td>{{ pedido.data_entrega != null ? pedido.data_entrega : '' }}</td>
                        <td v-bind:style="'color: ' + pedido.status.color">{{ pedido.status.nome }}</td>
                        <td>
                            <i v-if="$auth.check([1,2,4]) && pedido.a_caminho" class="fas fa-shipping-fast mr-4 text-success" style="font-size: 15pt"></i>
                            <i v-else-if="$auth.check([1,2])" class="fas fa-box mr-4 text-dark" style="font-size: 15pt"></i>
                            <button v-else-if="$auth.check(4)" class="btn-circle btn-sm btn-dark mr-3" @click.prevent="openModalConfirm(pedido.id)"><i class="fas fa-box"></i></button>

                            <button class="btn-circle btn-sm btn-info" title="Valores" @click="$router.push({name: 'pedido.ver', params: { id: pedido.id },})"> <i class="fas fa-fw fa-eye"></i></button>
                            <button v-if="pedido.status.nome == 'Aguardando'" class="btn-circle btn-sm btn-primary" title="Editar" @click="$router.push({name: 'pedido.editar', params: { id: pedido.id },})"> <i class="far fa-edit"></i></button>
                            <!-- <button class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(atributo)"><i class="fas fa-fw fa-trash-alt"></i></button> -->
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
                <pagination align="right" :limit="12" :data="pedidos" @pagination-change-page="getPedidos"></pagination>
            </div>
          </div>
        </div>
      </div>
        <div class="modal fade" id="modalConfirmEdit" tabindex="-1" role="dialog" aria-labelledby="modalConfirmEditLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmEditLabel">Confirmar Alteração</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Você está definindo este pedido como <strong>A CAMINHO</strong>!<br>
                    Esta ação é irreversível, deseja continuar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-primary" @click.prevent="saveEdit">Sim</button>
                </div>
                </div>
            </div>
        </div>
</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/SearchPedidoEntrega.vue';
export default {
     data() {
      return {
        has_error: false,
        pedidos: {},
        errors: {},
        deletePedidoData: {},
        searchLike: '',
        searchReferencia: '',
        searchOder: '',
        searchMostrar: '',
        searchDataInicial: '',
        searchDataFinal: '',
        searchDataInicialEntrega: '',
        searchDataFinalEntrega: '',
      }
    },
    mounted() {
      this.getPedidos()
    },
    methods: {
    saveEdit(){
            this.$http({
            url: `pedido/set/a/caminho/${this.pedido_edit}`,
            method: 'POST'
            })
            .then(response=>{
                if(response.data.success) {
                    this.flashMessage.success({
                        message: 'Pedido alterado com sucesso!',
                        time: 5000
                    });
                    this.closeModalConfirm();
                    this.getPedidos();
                }else{
                    this.flashMessage.error({
                        message: 'Error ao alterar Pedido!',
                        time: 5000
                    });
                }
            }, error=>{
                this.flashMessage.error({
                    message: 'Error ao alterar Pedido!',
                    time: 5000
                });
            })
        },
        openModalConfirm(id){
            this.pedido_edit = id;
            $('#modalConfirmEdit').modal('show');
        },
        closeModalConfirm(){
            this.pedido_edit = null;
            $('#modalConfirmEdit').modal('hide');
        },
        formatPrice(value) {
          let val = (value/1).toFixed(2).replace('.', ',')
          return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        exportRelatorio(){
          this.$http({
            url: `export/pedido`,
            method: 'GET'
            })
            .then(response=>{
                window.open(response.data.url)
            }, error=>{
                this.has_error = true
            })
        },
        searchPedidos(payload){
            this.$http({
                url: 'pedido',
                method: 'GET',
                params: {
                    like: payload.dados.nome,
                    referencia: payload.dados.referencia,
                    order: payload.dados.ordem,
                    mostrar: payload.dados.mostrar,
                    data_inicial: payload.dados.data_inicial,
                    data_final: payload.dados.data_final,
                    data_inicial_entrega: payload.dados.data_inicial_entrega,
                    data_final_entrega: payload.dados.data_final_entrega,
                    entrega: true,
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchReferencia = payload.dados.referencia;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.searchDataInicial = payload.dados.data_inicial;
                this.searchDataFinal = payload.dados.data_final;
                this.searchDataInicialEntrega = payload.dados.data_inicial_entrega;
                this.searchDataFinalEntrega = payload.dados.data_final_entrega;

                for( var k in response.data.data) {
                    if(response.data.data[k].data_entrega != null){
                        let data_entrega = response.data.data[k].data_entrega.split('-')
                        response.data.data[k].data_entrega = `${data_entrega[2]}/${data_entrega[1]}/${data_entrega[0]}`
                    }
                }

                this.pedidos = response.data;

            }, error=>{
                this.has_error = true
            })
        },
        hideModal() {
          $('#modal-form').removeClass('show')
        },
        getPedidos(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `pedido?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            referencia: this.searchReferencia,
            order: this.searchOder,
            mostrar: this.searchMostrar,
            data_inicial: this.searchDataInicial,
            data_final: this.searchDataFinal,
            data_inicial_entrega: this.searchDataInicialEntrega,
            data_final_entrega: this.searchDataFinalEntrega,
            entrega: true,
          },
        })
          .then(response => {
              for( var k in response.data.data) {
                if(response.data.data[k].data_entrega != null){
                    let data_entrega = response.data.data[k].data_entrega.split('-')
                    response.data.data[k].data_entrega = `${data_entrega[2]}/${data_entrega[1]}/${data_entrega[0]}`
                }
              }
              this.pedidos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getHumanDate : function (date) {
        return moment(date, 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
      },
        hideEditModal() {
          $('#modal-form-edit').removeClass('show')
        },
      showDelete(produto){
        this.deleteProdutoData = produto;
      },
      hideDeleteModal() {
        $('#modal-delete').removeClass('show')
      },
      deletePedido(pedido) {

        this.$http({
            url: `pedido/` + pedido.id,
            method: 'DELETE',
          })

          .then(response=>{
            this.pedidos.data = this.pedidos.data.filter(obj => {
                return obj.id != pedido.id;
            });

            this.hideDeleteModal();

            this.flashMessage.success({
                message: 'Pedido deletado com sucesso!',
                time: 5000
            });

          }, error =>{

             this.flashMessage.error({
                message: 'Error ao deletar Pedido!',
                time: 5000
            });
          })
      },
       onImagemChange(e) {
            const file = e.target.files[0];
            this.imagem = file;
            this.url_imagem = URL.createObjectURL(file);
        },
        onImagemChangeUpdate(e) {
            const file = e.target.files[0];
            this.imagem = file;
            this.url_imagem_update = URL.createObjectURL(file);
        },
    },
    components: {
      search
    }
}
</script>
