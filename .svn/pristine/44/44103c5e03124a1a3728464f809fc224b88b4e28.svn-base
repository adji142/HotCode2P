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
              this.$session.set('pwdnya', this.data.password)
              this.$session.set('listnota',this.data.listnota)
              this.$session.set('namatoko', this.data.nama)
              this.$session.set('idtoko', this.data.TokoIDWiser)
              this.$router.push('nota')
            }
      }
    }
</script>