<template>
    <div >
        <vue-progress-bar></vue-progress-bar>
        <v-layout row>
          <v-flex xs12 sm6 offset-sm3>
            <v-card>
              <v-card-media
                class="white--text"
                height="150px"
                src="http://toko-baju.sistemtoko.com/templates/template4/pattern.gif"
              >
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline">{{items.namatoko}}</span><br>
                      <span>{{items.alamat}}</span><br>
                       <span>{{items.kota}} </span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
              <v-list two-line>
                <v-list-tile @click="">
                  <v-list-tile-action>
                    <v-icon class="indigo--text">person_pin</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>{{items.namaNew}}</v-list-tile-title>
                    <v-list-tile-sub-title>Pemilik</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="">
                  <v-list-tile-action>
                    <v-icon class="indigo--text">store</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>{{items.tokoidwarisan}}</v-list-tile-title>
                    <v-list-tile-sub-title>ID</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                
                <v-list-tile @click="">
                  <v-list-tile-action>
                    <v-icon class="indigo--text">phone</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>{{items.telp}}</v-list-tile-title>
                    <v-list-tile-sub-title>Telp</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="">
                  <v-list-tile-action>
                    <v-icon class="indigo--text">location_on</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>{{items.customwilayah}}</v-list-tile-title>
                    <v-list-tile-sub-title>Kode Wilayah</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-card>
          </v-flex>
        </v-layout>
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
                }
            }
        },
    created : function(){
      this.$root.e1 = "Profil"
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
              let uri = this.$config.SERVER +'/api/toko/detail';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      this.items = response.data.data.detail
                      let namaNew = this.items.namatoko
                      namaNew = namaNew.split(' ')[0]
                      this.items.namaNew = namaNew

                      this.finish()
                   }else{
                    this.fail()
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
          }
    }
  }
</script>
