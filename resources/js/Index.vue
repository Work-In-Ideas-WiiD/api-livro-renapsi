<template>
<div>
  <router-view v-if="!$auth.check()"></router-view>
    <div  v-if="$auth.check()" id="main">
      <navigationMenu></navigationMenu>

      <router-view></router-view>    
    </div>
      <FlashMessage position="right top"></FlashMessage>
</div>
</template>
<script>
  import navigationMenu from './components/layout/Menu.vue'
  export default {
    data() {
      return {
        //
      }
    },
    components: {
      navigationMenu,
    }
  }
</script>