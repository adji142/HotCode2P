<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
        <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
            <v-container fluid grid-list-md class="grey lighten-4">
        <v-layout row wrap>
        <v-flex xs3>
        </v-flex>
          <v-flex xs6>
            <v-card height="50%">
               <v-icon x-large class="teal--text text--darken-4 text-md-center">fa-key</v-icon>
                <v-container fill-height fluid>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <v-form v-model="valid" ref="form">
                        <v-text-field
                          label="Password Lama"
                          v-model="password_lama"
                          :rules="nameRules"
                          required
                        ></v-text-field>
                        <v-text-field
                            label="Password Baru"
                            v-model="password_baru"
                            :rules="emailRules"
                            type="password"
                          ></v-text-field>

                        <v-btn @click="submit" :class="{ green: valid, red: !valid }">Login</v-btn>
                        <v-btn @click="clear">clear</v-btn>
                      </v-form>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-media>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
            <!-- <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="col-md-12">
                      <v-form v-model="valid" ref="form">
                        <v-text-field
                          label="Password Lama"
                          v-model="password_lama"
                          :rules="nameRules"
                          required
                        ></v-text-field>
                        <v-text-field
                            label="Password Baru"
                            v-model="password_baru"
                            :rules="emailRules"
                            type="password"
                          ></v-text-field>

                        <v-btn @click="submit" :class="{ green: valid, red: !valid }">Login</v-btn>
                        <v-btn @click="clear">clear</v-btn>
                      </v-form>
                  </div>
                </div>
            </div> -->
        </aside>
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
                item:{},
                password_baru:"",
                password_lama:"",
                valid:false,
                isActive : false,
                nameRules: [
                  (v) => !!v || 'Password Lama is required'
                ],
                emailRules: [
                  (v) => !!v || 'Password baru is required'
                ],
            }
        },
    created : function(){
      this.$root.e1 = "Change Password"
      // let type = this.$session.get('userinfo').typeuser
      //   if(type == 'collector'){
      //      this.$router.push('/dashboard')
      //   }
    },
    methods : {
          start () {
              this.$Progress.start()
          },
          finish () {
              this.$Progress.finish()
          },
          fail () {
              this.$Progress.fail()
          },
          submit(){
            if (this.$refs.form.validate()) {
              this.item.password_lama = this.password_lama
              this.item.password_baru = this.password_baru
              let uri = this.$config.SERVER +'/api/toko/password/update';
              this.axios.post(uri,this.item,{headers: { 'accesstoken': this.$session.get('token')}})
              .then(response => {
                  if(response.data.status == "error"){
                       console.log(response.data.message); 
                  }else{
                      this.$router.push('/dashboard')
                  }
                  
              })
            }
            
          },
        clear () {
            this.$refs.form.reset()
          }
    }
  }
</script>
