<template>
<div>
<vue-progress-bar></vue-progress-bar>
<div class="text-xs-center" v-show="collector">
  <div class="panel panel-default">
    <div class="col-md-10">
      <center>
        <h6>Daftar Register</h6>
      </center>
      <v-client-table :data="data" :columns="columns" :options="options">
      </v-client-table>
    </div>
  </div>
</div>
<div v-show="toko" lg12>
        <v-container fluid grid-list-md class="grey lighten-4">
        <v-layout row wrap>
          <v-flex>
            <v-card height="100px">
               <v-icon x-large class="teal--text text--darken-4">fa-money</v-icon>
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline cyan--text text--darken-4">Rp {{item.saldoTotalPiutang}}</span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
            </v-card>
          </v-flex>
          <v-flex>
            <v-card height="100px">
               <v-icon x-large class="blue--text text--darken-4">fa-calendar</v-icon>
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline cyan--text text--darken-4">{{item.tanggalBayarTerakhir}}</span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
            </v-card>
          </v-flex>
          <v-flex>
            <v-card height="100px">
               <v-icon x-large class="red--text text--darken-4">fa-usd</v-icon>
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline cyan--text text--darken-4">Rp {{item.jatuhTempo}}</span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
            </v-card>
          </v-flex>
          <v-flex>
            <v-card height="100px">
               <v-icon x-large class="teal--text text--darken-4">fa-usd</v-icon>
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline cyan--text text--darken-4">Rp {{item.blmJatuhTempo}}</span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
</div>      
</div>

</template>
<script>
  import {ServerTable, ClientTable, Event} from 'vue-tables-2';
  import Detail from './Detail.vue';
  import Vue from 'vue';
  import VueProgressBar from 'vue-progressbar'
  Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red'
  })
  Vue.use(ClientTable,{perPage: 5});
  export default{
    data(){
            return{
                toko:false,
                collector:false,
                item:{},
                data:[],
                columns: ['Nomor_register', 'tgl_register',"det"],
                options: {
                  sortable: ['Nomor_register','tgl_register'],
                  templates : {
                      det: Detail
                    }
                }
            }
        },
    created : function(){
      this.$root.e1 = "Dashboard"
      this.condition()
      if(this.toko){
        this.summary()
        this.$root.itemleft = this.$root.itemtoko
      }else if(this.collector){
        this.listRegister()  
        this.$root.itemleft = this.$root.itemcollector
      }
      
    },
    methods : {
          condition(){
            let type = this.$session.get('userinfo').typeuser
            if(type === 'collector'){
              this.collector = true
              this.toko = false
            }else if(type === 'toko'){
              this.collector = false
              this.toko = true
            }
          },
          listRegister()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/register/lists';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      let list=[];
                      response.data.data.register.map(function(value, key) {
                         let data = {Nomor_register:value.noreg,tgl_register:value.tglreg, nota:value.nota};
                         list.push(data);
                       });
                     this.data = list
                     this.finish()
                   }
                })
            },
          summary()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/piutang';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                     this.item = response.data.data
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
          detailptg () {
              this.$router.push('/detail-piutang')
          }
    }
  }
</script>
