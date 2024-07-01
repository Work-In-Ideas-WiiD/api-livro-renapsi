<template>
    <div>
        <div class="form-row" style="float: right;">
        <div class="col-2">
            <input type="text" v-model="nome" class="form-control" placeholder="Nome / CPF">
        </div>
        <div class="col-3">
            <!-- <input type="text" v-model="nome" class="form-control" placeholder="Nome"> -->
            <select v-model="pergunta" class="custom-select">
            <option value="">Pergunta:</option>
            <option value="1">Diário de Sintomas</option>
            <option value="2">Pergunta relacionada aos seus sintomas</option>
            </select>
        </div>
        <div class="col-2">
            <!-- <input type="text" v-model="nome" class="form-control" placeholder="Nome"> -->
            <select v-model="sintoma" class="custom-select">
            <option value="">Sintoma:</option>
            <option value="1">Falta de ar</option>
            <option value="2">Catarro</option>
            <option value="3">Tosse</option>
            <option value="4">Dor de Garganta</option>
            <option value="5">Chiado no Peito</option>
            <option value="6">Nariz escorrendo ou entupido</option>
            <option value="7">Febre</option>
            </select>
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
        <div class="col-1">
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
       sintoma: '',
       pergunta: ''
      }
    },
    methods: {
        search() {
            this.$emit('search', { 
                dados: {
                    nome: this.nome,
                    sintoma: this.sintoma,
                    pergunta: this.pergunta,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
}
</script>