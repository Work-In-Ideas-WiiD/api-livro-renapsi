<template>
    <div style="margin-left: 10px;">
        <div class="form-row" style="float: right;">
        <div class="col-5" style="margin-bottom: 10px;">
            <model-select v-on:change.stop="search" :options="vendedores"
                    v-model="vendedor"
                    placeholder="Escolhar Vendedor">
            </model-select>
        </div>
        <div class="col-5" style="margin-bottom: 10px;" >
            <model-select v-on:change.stop="search" :options="clientes"
                    v-model="cliente"
                    placeholder="Escolhar Cliente">
            </model-select>
        </div>
         <div class="col-5" style="margin-bottom: 10px;" >
            <!-- <multi-select v-on:change.stop="search" :options="status_pedidos"
                    :selected-options="status"
                    placeholder="Escolhar Status">
            </multi-select> -->
             <multi-select :options="status_pedidos"
                       :selected-options="status"
                       placeholder="Escolhar Status"
                       @select="onSelect">
            </multi-select>
        </div>
        <div class="col-5">
            <input style="height: calc(0.45em + 1.25rem + 12px);" type="text" v-mask="['##.##', '###.##', '####.##']" v-on:change.stop="search" v-model="referencia" class="form-control" placeholder="Preço Maior Que">
        </div>
        <div class="col-5 mb-1">
            <datepicker :bootstrap-styling="true" v-model="data_inicial" :placeholder="'Data Inicial'" :language="ptBR" ></datepicker>
        </div>
        <div class="col-5 mb-1">
            <datepicker :bootstrap-styling="true" v-model="data_final" :placeholder="'Data Final'" :language="ptBR" ></datepicker>
        </div>
        <div class="col-4 mb-1">
            <datepicker :bootstrap-styling="true" v-model="data_inicial_pagamento" :placeholder="'Data Inicial Pagamento '" :language="ptBR" ></datepicker>
        </div>
        <div class="col-4 mb-1">
            <datepicker :bootstrap-styling="true" v-model="data_final_pagamento" :placeholder="'Data Final Pagamento'" :language="ptBR" ></datepicker>
        </div>
        <!-- <div class="col-2">
            <select v-model="mostrar" class="custom-select">
            <option value="">Mostrar:</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            </select>
        </div>
        <div class="col-2">
            <select v-model="ordem" class="custom-select">
            <option value="">Ordem:</option>
            <option value="asc">Crescente</option>
            <option value="desc">Decrescente</option>
            </select>
        </div> -->
        <div class="col-2">
            <button type="button" v-on:click.stop="search" class="btn btn-primary mb-2 botaoPesquisar"><i class="fas fa-fw fa-search"></i></button>
        </div>
        </div>
    </div>
</template>
<script>
import _ from 'lodash'
import { ModelSelect } from 'vue-search-select'
import { MultiSelect } from 'vue-search-select'
import Datepicker from 'vuejs-datepicker';
import {en, ptBR} from 'vuejs-datepicker/dist/locale'
export default {
    data() {
      return {
       nome: '',
       ptBR: ptBR,
       referencia: '',
       custo: '',
       mostrar: '',
       ordem: '',
       cliente: '',
       clientes: [],
       vendedor: '',
       vendedores: [],
       status_pedidos: [],
       status: [],
       lastSelectItem: {},
       data_inicial: '',
       data_final: '',
       data_inicial_pagamento: '',
       data_final_pagamento: '',
      }
    },
    mounted() {
        this.getClientes(),
        this.getVendedores(),
        this.getStatus()
    },
    methods: {
        onSelect (items, lastSelectItem) {
            this.status = items
            this.lastSelectItem = lastSelectItem
        },
        // deselect option
        reset () {
            this.items = [] // reset
        },
        // select option from parent component
        selectFromParentComponent () {
            this.items = _.unionWith(this.items, [this.options[0]], _.isEqual)
        },
        getClientes() {
            this.$http({
            url: `lista_cliente`,
            method: 'GET'
            })
                .then(response=>{

                this.clientes = response.data;

                }).catch(error=>{
                this.has_error = true
                })
        },
        getVendedores() {
            this.$http({
            url: `lista_user`,
            method: 'GET'
            })
                .then(response=>{

                this.vendedores = response.data;

                }).catch(error=>{
                this.has_error = true
                })
        },
        getStatus() {
            this.$http({
            url: `lista_status`,
            method: 'GET'
            })
                .then(response=>{

                this.status_pedidos = response.data;

                }).catch(error=>{
                this.has_error = true
                })
        },
        search() {
            this.$emit('search', {
                dados: {
                    nome: this.nome,
                    referencia: this.referencia,
                    custo: this.custo,
                    cliente: this.cliente,
                    vendedor: this.vendedor,
                    status: this.status,
                    data_inicial: this.data_inicial,
                    data_final: this.data_final,
                    data_inicial_pagamento: this.data_inicial_pagamento,
                    data_final_pagamento: this.data_final_pagamento,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
    components: {
      ModelSelect,
      MultiSelect,
      Datepicker
    },
}
</script>
