<template>
    <div>
        <div class="form-row" style="float: right;">
        <div class="col-4">
            <input type="text" v-on:change.stop="search" v-model="nome" class="form-control" placeholder="Nome">
        </div>
        <div class="col-3">
            <select v-model="mostrar" class="custom-select">
            <option value="">Mostrar:</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            </select>
        </div>
        <div class="col-3">
            <select v-model="ordem" class="custom-select">
            <option value="">Ordem:</option>
            <option value="criado_em">Crescente</option>
            <option value="-criado_em">Decrescente</option>
            </select>
        </div>
        <div class="col-2">
            <button type="button" v-on:click.stop="search" class="btn btn-primary mb-2"><i class="fas fa-fw fa-search"></i></button>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
      return {
       nome: '',
       mostrar: '',
       ordem: '',
      }
    },
    methods: {
        search() {
            this.$emit('search', {
                dados: {
                    nome: this.nome,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
}
</script>
