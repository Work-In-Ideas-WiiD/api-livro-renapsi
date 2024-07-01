<template>
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Pedidos</h3>
                    </div>
                    <div class="col-4 text-right">
                        <!-- <router-link type="button" to="pedido_criar" class="btn btn-sm btn-primary">Adicionar</router-link > -->
                        <a @click="exportRelatorio" href="#" type="button" data-toggle="modal" data-target="#modal-form" class="btn btn-sm btn-success">Exportar</a>
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
                    <th scope="col" class="sort" data-sort="status" >Vendedor</th>
                    <th scope="col" class="sort" data-sort="status" >Entrega</th>
                    <th scope="col" class="sort" data-sort="status" >Total</th>
                    <th scope="col" class="sort" data-sort="status" >Data Pagamento</th>
                    <th scope="col" class="sort" data-sort="completion">Status</th>
                    <!-- <th scope="col"></th> -->
                  </tr>
                </thead>
                <tbody class="list">
                    <tr v-for="pedido in pedidos.data" v-bind:key="pedido.id" style="margin-bottom: 5px;">
                        <td>{{ getHumanDate(pedido.created_at) }}</td>
                        <td>{{ pedido.cliente ? pedido.cliente.nome : '' }}</td>
                        <td>{{ pedido.administrador.email }}</td>
                        <td>{{ pedido.entrega ? pedido.entrega.nome : '' }}</td>
                        <td>{{ formatPrice(pedido.total) }}</td>
                        <td>{{ pedido.data_pagamento }}</td>
                        <td v-bind:style="'color: ' + pedido.status.color">{{ pedido.status.nome }}</td>
                        <!-- <td>
                            <button class="btn-circle btn-sm btn-info" title="Valores" @click="$router.push({name: 'pedido.ver', params: { id: pedido.id },})"> <i class="fas fa-fw fa-eye"></i></button>
                            <button class="btn-circle btn-sm btn-danger" title="Deletar" data-toggle="modal" data-target="#modal-delete" block @click="showDelete(atributo)"><i class="fas fa-fw fa-trash-alt"></i></button>
                        </td> -->
                    </tr>
                </tbody>
              </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer py-4">
              <h4 align="right">
                Quantidade Total de Pedido:
                <small class="text-muted">{{ resumo.total }}</small>
              </h4>
              <h4 align="right">
                Quantidade Total de Peças:
                <small class="text-muted">{{ resumo.produtos }}</small>
              </h4>
              <h4 align="right">
                Valor Total:
                <small class="text-muted">{{ formatPrice(resumo.soma) }}</small>
              </h4>
              <h4 align="right">
                Ticket Médio:
                <small class="text-muted">{{ formatPrice(resumo.ticket_medio) }}</small>
              </h4>
              <h4 align="right">
                Lucro Total:
                <small class="text-muted">{{ formatPrice(resumo.lucro) }}</small>
              </h4>
            </div>
            <div class="card-footer py-4">
                <pagination align="right" :limit="12" :data="pedidos" @pagination-change-page="getPedidos"></pagination>
            </div>
          </div>
        </div>
      </div>

</div>


</template>
<script>
import moment from 'moment/moment';
import search from '../search/SearchRelatorioPedido.vue';
export default {
     data() {
      return {
        has_error: false,
        pedidos: {},
        errors: {},
        resumo: '',
        deletePedidoData: {},
        searchDataInicial: '',
        searchDataFinal: '',
        searchDataInicialPagamento: '',
        searchDataFinalPagamento: '',
        searchCliente: '',
        searchVendedor: '',
        searchStatus: '',
        searchCusto: '',
        searchLike: '',
        searchReferencia: '',
        searchOder: '',
        searchMostrar: ''
      }
    },
    mounted() {
      this.getPedidos(),
      this.getTotalPedidos()
    },
    methods: {
        formatPrice(value) {
          let val = (value/1).toFixed(2).replace('.', ',')
          return 'R$ ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        exportRelatorio(){
          this.$http({
            url: `export/pedido_venda`,
            method: 'GET',
            params: {
              like: this.searchLike,
              data_inicial: this.searchDataInicial,
              data_final: this.searchDataFinal,
              data_inicial_pagamento: this.searchDataInicialPagamento,
              data_final_pagamento: this.searchDataFinalPagamento,
              cliente: this.searchCliente,
              vendedor: this.searchVendedor,
              status: this.searchStatus,
              custo: this.searchCusto,
              order: this.searchOder,
              mostrar: this.searchMostrar
            },
            })
            .then(response=>{
                window.open(response.data.url)
            }, error=>{
                this.has_error = true
            })
        },
        searchPedidos(payload){
            this.$http({
                url: 'relatorio_pedido',
                method: 'GET',
                params: {
                    like: payload.dados.nome,
                    referencia: payload.dados.referencia,
                    data_inicial: payload.dados.data_inicial,
                    data_final: payload.dados.data_final,
                    data_inicial_pagamento: payload.dados.data_inicial_pagamento,
                    data_final_pagamento: payload.dados.data_final_pagamento,
                    cliente: payload.dados.cliente,
                    vendedor: payload.dados.vendedor,
                    status: payload.dados.status,
                    custo: payload.dados.custo,
                    order: payload.dados.ordem,
                    mostrar: payload.dados.mostrar
                },
            })
            .then(response=>{
                this.searchLike = payload.dados.nome;
                this.searchReferencia = payload.dados.referencia;
                this.searchMostrar = payload.dados.mostrar;
                this.searchOder = payload.dados.ordem;
                this.searchDataInicial = payload.dados.data_inicial
                this.searchDataFinal = payload.dados.data_final,
                this.searchDataInicialPagamento = payload.dados.data_inicial_pagamento
                this.searchDataFinalPagamento = payload.dados.data_final_pagamento,
                this.searchCliente = payload.dados.cliente,
                this.searchVendedor = payload.dados.vendedor,
                this.searchStatus = payload.dados.status,
                this.searchCusto = payload.dados.custo;
                for( var k in response.data.data) {
                    if(response.data.data[k].data_pagamento != null){
                        let data_pagamento = response.data.data[k].data_pagamento.split('-')
                        response.data.data[k].data_pagamento = `${data_pagamento[2]}/${data_pagamento[1]}/${data_pagamento[0]}`
                    }
                }
                this.pedidos = response.data;

                this.getTotalPedidos();

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
          url: `relatorio_pedido?page=` + page,
          method: 'GET',
          params: {
            like: this.searchLike,
            data_inicial: this.searchDataInicial,
            data_final: this.searchDataFinal,
            data_inicial_pagamento: this.searchDataInicialPagamento,
            data_final_pagamento: this.searchDataFinalPagamento,
            cliente: this.searchCliente,
            vendedor: this.searchVendedor,
            status: this.searchStatus,
            custo: this.searchCusto,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
            for( var k in response.data.data) {
                if(response.data.data[k].data_pagamento != null){
                    let data_pagamento = response.data.data[k].data_pagamento.split('-')
                    response.data.data[k].data_pagamento = `${data_pagamento[2]}/${data_pagamento[1]}/${data_pagamento[0]}`
                }
            }
            this.pedidos = response.data;
          }).catch(error=>{
              this.has_error = true
          })
      },
      getTotalPedidos(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        this.$http({
          url: `dashboard/relatorio_venda_total`,
          method: 'GET',
          params: {
            like: this.searchLike,
            data_inicial: this.searchDataInicial,
            data_final: this.searchDataFinal,
            data_inicial_pagamento: this.searchDataInicialPagamento,
            data_final_pagamento: this.searchDataFinalPagamento,
            cliente: this.searchCliente,
            vendedor: this.searchVendedor,
            status: this.searchStatus,
            custo: this.searchCusto,
            order: this.searchOder,
            mostrar: this.searchMostrar
          },
        })
          .then(response => {
              this.resumo = response.data;
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
