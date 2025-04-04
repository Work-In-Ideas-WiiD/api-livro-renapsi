<template>
    <div style="margin-left: 10px;">
        <div class="form-row" style="float: right;">
        <div class="col-3">
            <input type="text" v-model="nome" class="form-control" placeholder="Nome / CPF">
        </div>
        <div class="col-6">
            <select v-model="escala" class="custom-select">
            <option value="">Escala:</option>
            <option value="sofre_falta_ar">Só sofre de falta de ar durante exercícios intensos</option>
            <option value="andando_subindo_rampa">Sofre de falta de ar quando andando apressadamente ou subindo uma rampa leve</option>
            <option value="andando_devagar">Anda mais devagar do que pessoas da mesma idade por causa de falta de ar ou tem que parar para respirar mesmo quando andando devagar</option>
            <option value="andando_menos_100m">Para para respirar depois de andar menos de 100m ou após alguns minutos</option>
            <option value="sofre_falta_ar_vestindo">Sente tanta falta de ar que não sai mais de casa ou sente falta de ar quando está se vestindo</option>
            </select>
        </div>
        <div class="col-1">
            <select v-model="mostrar" class="custom-select">
            <option value="">Mostrar:</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            </select>
        </div>
        <div class="col-1">
            <select v-model="ordem" class="custom-select">
            <option value="">Ordem:</option>
            <option value="asc">Crescente</option>
            <option value="desc">Decrescente</option>
            </select>
        </div>
        <div class="col-1">
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
       mostrar: '',
       ordem: '',
       escala: ''
      }
    },
    methods: {
        search() {
            this.$emit('search', { 
                dados: {
                    nome: this.nome,
                    escala: this.escala,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
}
</script>