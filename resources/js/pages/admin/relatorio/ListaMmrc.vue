<template>
<div class="main-content corpoDash" id="panel">
    <TopNav></TopNav>
    <div class="header bg-primary pb-6 headerDash">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-gray d-inline-block mb-0">Relatório Livros</h6>
              <!-- <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active">Relatório Livros</li>
                </ol>
              </nav> -->
            </div>
          </div>
        </div>
    </div>
    </div>
    <!-- Page content -->
    <mmrcList></mmrcList>
</div>
</template>
<script>
import TopNav from '../../../components/layout/TopNav.vue'
import mmrcList from '../../../components/relatorio/EscalaMmrc.vue'
  export default {
    data() {
      return {
        //
      }
    },
    components: {
      TopNav,
      mmrcList
    }
  }
</script>
