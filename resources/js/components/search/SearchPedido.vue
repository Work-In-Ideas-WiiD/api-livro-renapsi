<template>
    <div style="margin-left: 10px;">
        <div class="form-row" style="float: right;">
        <div class="col-3" style="margin-bottom: 10px;">
            <input type="text" v-on:change.stop="search" v-model="nome" class="form-control" placeholder="Nome / Cliente">
        </div>
         <div class="col-3">
            <input type="text" v-on:change.stop="search" v-model="referencia" class="form-control" placeholder="Referência">
        </div>
        <div class="col-2" style="margin-bottom: 10px;">
            <select v-model="mostrar" class="custom-select">
            <option value="">Mostrar:</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            </select>
        </div>
        <div class="col-2" style="margin-bottom: 10px;">
            <select v-model="ordem" class="custom-select">
            <option value="">Ordem:</option>
            <option value="asc">Crescente</option>
            <option value="desc">Decrescente</option>
            </select>
        </div>
        <div class="col-4">
            <datepicker :bootstrap-styling="true" v-model="data_inicial" :placeholder="'Data Inicial'" :language="ptBR" ></datepicker>
        </div>
        <div class="col-4">
            <datepicker :bootstrap-styling="true" v-model="data_final" :placeholder="'Data Final'" :language="ptBR" ></datepicker>
        </div>
        <div class="col-2">
            <button type="button" v-on:click.stop="search" class="btn btn-primary mb-2"><i class="fas fa-fw fa-search"></i></button>
        </div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {en, ptBR} from 'vuejs-datepicker/dist/locale'
export default {
    data() {
      return {
        ptBR: ptBR,
       nome: '',
       referencia: '',
       mostrar: '',
       ordem: '',
       data_inicial: '',
       data_final: '',
      }
    },
    methods: {
        search() {
            this.$emit('search', {
                dados: {
                    nome: this.nome,
                    referencia: this.referencia,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                    data_inicial: this.data_inicial,
                    data_final: this.data_final,
                }
            });
        },
    },
    components: {
      Datepicker
    },
}
</script>
