<template>
<div class="main-content" id="panel">
    <TopNav></TopNav>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Definir Valor Atributos</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active">Definir Valor Atributos</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
    </div>
    </div>
    <!-- Page content -->
    <atributoValorList></atributoValorList>
       
</div>
</template>
<script>
import TopNav from '../../../components/layout/TopNav.vue'
import atributoValorList from '../../../components/atributovalor/AtributoValor.vue'
  export default {
    data() {
      return {
        //
      }
    },
    components: {
      TopNav,
      atributoValorList
    }
  }
</script>