<template>
  <div>
  <!-- <input type="checkbox" name="" v-on:click="view" v-show="viewall"> -->
  <input type="number" name="" placeholder="jumlah" v-model="data.bayarSementara" v-on:keyup="input">
  </div>
</template>

<script>
    export default{
      props:['data'],
      data(){
        return{
          sh:false,
          val:0,
          viewall:true
        }
      },
      created : function(){
        this.$root.$data.total = 1000
        // let ses = this.$session.get('amountBayar')
        // if(ses > 0 && ses != ""){
        //   this.viewall = true
        // }
      },
      methods:{
            view(row){
              if(this.sh){
                this.sh = false  
                this.$session.remove(this.data.nonota)
              }else{
                this.sh = true  
              }
            },
            input(e){
              console.log(this.data.bayarSementara)
              if(this.data.bayarSementara != null && this.data.bayarSementara != ""){
                  if((parseFloat(this.data.rptagih) - parseFloat(this.data.bayar)) >= parseFloat(this.data.bayarSementara)){
                    this.$session.set(this.data.nonota , parseFloat(this.data.bayarSementara)) 
                  }else{
                    this.data.bayarSementara = 0
                    this.$session.remove(this.data.nonota)
                  }  
              }              
            }
      }
    }
</script>