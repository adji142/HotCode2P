<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
        <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h6>Detail Piutang</h6>
                    </center>
                    <label>Nota</label>
                    <button class="btn btn-warning pull-right" v-on:click="refresh">
                      <i class="fa fa-refresh"></i>
                    </button>
                    <v-client-table :data="data" :columns="columns" :options="options" @row-click="changeData">
                    </v-client-table>
                    <br>
                    <label>Pembayaran</label>
                    <v-client-table :data="datas" :columns="columnss" :options="optionss">
                    </v-client-table>
                  </div>
                </div>
            </div>
        </aside>
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
                toko:false,
                collector:false,
                item:{},
                data:[],
                datas:[],
                columns: ['nonota', 'nomnota',"tgljt","saldoPtg"],
                columnss: ['tgltrans', 'nomtrans',"kodetrans"],
                options: {
                  sortable: ['nonota','nomnota','tgljt']
                },
                optionss: {
                  sortable: ['tgltrans','kodetrans'],
                },
                byr:[]
            }
        },
    created : function(){
      this.summary()
      
    },
    methods : {
          summary()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/piutang';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                     let list = [];
                     let list2 = [];
                     let dats = {};
                     response.data.data.nota.jatuhtempo.map(function(value,key){
                        dats = {kartupiutang:value.idkartupiutang,nonota:value.nonota,nomnota:value.nomnota,tgljt:value.tgljt,saldoPtg:value.saldoPtg}
                        list.push(dats)
                     })

                     response.data.data.nota.blmJatuhTempo.map(function(value,key){
                        dats = {kartupiutang:value.idkartupiutang,nonota:value.nonota,nomnota:value.nomnota,tgljt:value.tgljt,saldoPtg:value.saldoPtg}
                        list.push(dats)
                     })

                     this.byr = response.data.data.bayar;
                     
                     this.data = list
                     this.datas = []
                      this.finish()
                   }
                })
            },
          start () {
              this.$Progress.start()
          },
          fail () {
              this.$Progress.fail()
          },
          finish(){
            this.$Progress.finish()
          },
          changeData: function (row) {
            let id = row.row.kartupiutang
            let list2 = [];
            let dats = {};
            this.byr.map(function(value,key){
              if(value.kartupiutangid == id){
                dats = {tgltrans:value.tgltrans,nomtrans:value.nomtrans,kodetrans:value.kodetrans}
                list2.push(dats)  
              }
              
            })
            
            this.datas = list2
          },
          refresh(){
            this.summary()
          }
    }
  }
</script>
