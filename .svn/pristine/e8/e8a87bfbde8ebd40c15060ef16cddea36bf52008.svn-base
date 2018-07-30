<template>
    <div class="container">
      <vue-progress-bar></vue-progress-bar>
      <notify-me
            :event-bus="bus">
          <template slot="content" slot-scope="{data}">
              <div style="width: 100%; word-break: break-all; text-align: left">
                  <h4><b>{{data.title}}</b></h4>
                  <p style="margin: 0">{{data.text}}</p>
              </div>
          </template>
      </notify-me>
      <center>
        <h4>Login to Paycoll</h4>
        <v-form v-model="valid" ref="form">
          <v-text-field
            label="Username"
            v-model="username"
            :rules="nameRules"
            required
          ></v-text-field>
          <v-text-field
              label="Enter your password"
              v-model="password"
              :rules="emailRules"
              type="password"
              placeholder="******"
            ></v-text-field>
          <v-btn block large @click="login" :class="{ blue: valid , red: !valid}"><span class="white--text">Login</span></v-btn>
        </v-form>
      </center>
    </div>
</template>
<script>
    import Vue from 'vue';
    import Notify from 'vue-notify-me'
    const bus = new Vue();
  export default{
    components: {
      'notify-me': Notify
    },
    data(){
            return{
                valid: false,
                e3: false,
                item:{},
                username:"",
                password:"",
                bus,
                isActive : false,
                nameRules: [
                  (v) => !!v || 'Username is required'
                ],
                emailRules: [
                  (v) => !!v || 'Password is required'
                ]
            }
        },
    beforeCreate: function () {
        if (this.$session.has('token')) {
          this.$router.push('/dashboard')
        }
    },
    methods : {
          login()
            {
              if (this.$refs.form.validate()) {
                this.item.username = this.username
                this.item.password = this.password
                this.start();
                let uri = this.$config.SERVER + '/api/login';
                  this.axios.post(uri,this.item,{
                                                  headers: { 'apikey': this.$config.API}
                                                })
                  .then(response => {
                      this.isActive = false
                      if(response.data.status == 'success'){
                        this.finish();
                        this.$session.set('userinfo', response.data.data.userinfo)
                        this.$session.set('token', response.data.data.userinfo.api_token)
                        this.$router.push('dashboard')
                      }else{
                        this.fail();
                        this.bus.$emit('notify-me', {
                          status: 'is-danger',
                          data: {
                              title: 'Login',
                              text: response.data.message
                          }
                    
                        });
                      }
                  })
              }
              // this.isActive = true
              // 
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
          clear () {
            this.$refs.form.reset()
          }
    }
  }
</script>
