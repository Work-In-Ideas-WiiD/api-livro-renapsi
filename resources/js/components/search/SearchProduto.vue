<template>
    <div style="margin-left: 10px;">
        <div class="form-row" style="float: right;">
        <div class="col-2">
            <input type="text" v-on:change.stop="search" v-model="nome" class="form-control" placeholder="Nome">
        </div>
        <div class="col-2">
            <input type="text" v-on:change.stop="search" v-model="categoria" class="form-control" placeholder="Categoria">
        </div>
        <div class="col-2">
            <input type="text" v-on:change.stop="search" v-model="codigo" class="form-control" placeholder="Código / Referência">
        </div>
        <div class="col-2">
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
        </div>
        <div class="col-2">
            <button type="button" v-on:click.stop="search" class="btn btn-primary mb-2 botaoPesquisar"><i class="fas fa-fw fa-search"></i></button>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
      return {
       nome: '',
       categoria: '',
       codigo: '',
       mostrar: '',
       ordem: '',
      }
    },
    methods: {
        search() {
            this.$emit('search', {
                dados: {
                    nome: this.nome,
                    categoria: this.categoria,
                    codigo: this.codigo,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
}
</script>
