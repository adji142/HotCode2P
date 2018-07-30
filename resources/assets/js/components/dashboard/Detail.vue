<template>
  <div>
  <button class="btn btn-info" v-on:click="view">Detail</button>
  </div>
</template>

<script>
    export default{
      props:['data'],
      methods:{
            view(){
              this.$session.set('nota',this.data.nota)
              this.$router.push('detail')
            }
      }
    }
</script>