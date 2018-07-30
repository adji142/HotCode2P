<template>
    <div class="container">
     <vue-progress-bar></vue-progress-bar>
        <div class="row" xs12>
            <v-btn fab dark fixed right class="blue" @click="addTrans">
              <v-icon dark>add</v-icon>
            </v-btn>
            <v-client-table :data="data" :columns="columns" :options="options">
            </v-client-table>
        </div>
    </div>
</template>
<script>
  import {ServerTable, ClientTable, Event} from 'vue-tables-2';
  import Vue from 'vue';
  import VueProgressBar from 'vue-progressbar'
  Vue.use(VueProgressBar, {
    color: 'rgb(0, 11, 239)',
    failedColor: 'red',
    height: '2px'
  })
  Vue.use(ClientTable,{perPage: 5});
  export default{
    data(){
            return{
                item:[],
                data:[],
                columns: ['tgltransfer', 'namabank','nominaltransfer','beritatransfer'],
                options: {
                  sortable: ['tgltransfer','namabank']
                }
            }
        },
    created : function(){
      this.$root.e1 = "Transfer Bank"
      this.listRegister()
    },
    methods : {
          listRegister()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/setoruang/lists';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      let list=[];
                      response.data.data.setoruang.map(function(value, key) {
                         let data = {tgltransfer:value.tgltransfer,namabank:value.namabank, nominaltransfer:value.nominaltransfer, beritatransfer:value.beritatransfer};
                         list.push(data);
                       });
                     this.data = list
                     this.finish()
                   }
                })
            },
            start () {
              this.$Progress.start()
            },
            finish () {
                this.$Progress.finish()
            },
            fail () {
                this.$Progress.fail()
            },
            addTrans(){
              this.$router.push('/add/transfer')
            }
    }
  }
</script>
