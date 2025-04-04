<template>
    <div>
        <div class="form-row" style="float: right;">
        <div class="col-3">
            <input type="text" v-model="nome" class="form-control" placeholder="Nome / CPF">
        </div>
        <div class="col-3">
            <select v-model="escala" class="custom-select">
            <option value="">Escala:</option>
            <option value="tenho_tosse">Nunca tenho tosse</option>
            <option value="nao_tenho_catarro">Não tenho nenhum catarro (secreção) no peito</option>
            <option value="nao_sinto_pressao_peito">Não sinto nenhuma pressão no peito</option>
            <option value="faltar_ar_subindo_escada">Não sinto falta de ar quanod subo uma ladeira ou um andar de escada</option>
            <option value="nao_sinto_limitacao_casa">Não sinto nenhuma limitação nas minhas atividades em casa</option>
            <option value="nao_sinto_limitacao_sair">Sinto-me confiante para sair de casa, apesar dda minha doença pulmonar</option>
            <option value="durmir_profundamente">Durmo profundamento</option>
            <option value="energia_disposicao">Tenho muita energia (Disposição)</option>
            </select>
        </div>
         <div class="col-1">
            <select v-model="nivel" class="custom-select">
            <option value="">Nivel:</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
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
       escala: '',
       nivel: ''
      }
    },
    methods: {
        search() {
            this.$emit('search', { 
                dados: {
                    nome: this.nome,
                    escala: this.escala,
                    nivel: this.nivel,
                    mostrar: this.mostrar,
                    ordem: this.ordem,
                }
            });
        },
    },
}
</script>