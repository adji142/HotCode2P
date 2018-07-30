<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
    <v-flex xs12 sm6 offset-sm3>
      <v-card>
        <v-toolbar class="white" right>
            <v-btn
                @click="req"
                class="pink"
                dark
                small
                absolute
                bottom
                right
                fab
              >
                <v-icon>add</v-icon>
              </v-btn>
          </v-toolbar>
        <v-list two-line subheader>
          <v-subheader-title>
            List Request
          </v-subheader-title>
          <v-list-tile avatar v-for="item in data" v-bind:key="item.id">
            <v-list-tile-avatar>
                 <v-icon>fa-ticket</v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{ item.tglrequest }}</v-list-tile-title>
              <v-list-tile-sub-title>{{ item.keterangan }}</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
        </v-card>
    </v-flex>
    </div>
</template>
<script>
  import Vue from 'vue';
  import VueProgressBar from 'vue-progressbar'
  Vue.use(VueProgressBar, {
    color: 'rgb(0, 11, 239)',
    failedColor: 'red',
    height: '2px'
  })
  export default{
    data(){
            return{
                items:{},
                data:[],
                columns: ['tglrequest','keterangan'],
                options: {
                  sortable: ['tglrequest']
                },
                hidden:false
            }
        },
    created : function(){
      this.$root.e1 = "Request"
      let type = this.$session.get('userinfo').typeuser
        if(type == 'collector'){
           this.$router.push('/dashboard')
        }else if(type == 'toko'){
          this.request()
        }
    },
    methods : {
          request()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/request/list';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      this.items = response.data.data
                      let list = [];
                      let datas = {};
                      response.data.data.request.map(function(value,key){
                        datas = {tglrequest:value.tglrequest,keterangan:value.keterangan,id:value.id}
                        list.push(datas)
                      })

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
          req(){
            this.$router.push('/add/request')
          }
    }
  }
</script>
