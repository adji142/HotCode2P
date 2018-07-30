<template>
    <v-app>
      <v-toolbar dark class="primary">
        <v-toolbar-title class="white--text">PAYCOLL</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="changeloc">
          <v-icon >shopping_cart</v-icon>
        </v-btn>
         <v-menu bottom left>
            <v-btn icon slot="activator" dark>
              <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
              <v-list-tile v-for="item in navleft" :key="item.title" @click="click(item.title)">
                <v-icon>{{item.icon}}</v-icon>
                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                 
              </v-list-tile>
            </v-list>
          </v-menu>
        <!-- <v-btn icon>
          <v-icon>more_vert</v-icon>
        </v-btn> -->
      </v-toolbar>
      <main>
          <v-container fluid>
            <router-view></router-view>
          </v-container>
      </main>
      <!-- <v-card height="110px"> -->
        <v-bottom-nav :value="true" :active.sync="e1" class="transparent">
          <v-btn flat class="blue--text" v-for="item in itemleft" :key="item.title" :value="item.title" @click="click(item.title)">
            <span>{{item.title}}</span>
            <v-icon>{{item.icon}}</v-icon>
          </v-btn>
          
        </v-bottom-nav>
      <!-- </v-card> -->
    </v-app>
</template>
<script>

    export default{
      data(){
        return{
          itemtoko:[{title:"Dashboard", icon:"dashboard"},{title:"Request", icon:"fa-ticket"},{title:"Profil", icon:"fa-user"}],
          itemcollector:[{title:"Dashboard", icon:"dashboard"},{title:"Status", icon:"fa-money"},{title:"Transfer Bank", icon:"fa-exchange"}],
          items:["Dashboard", "Status", "Transfer Bank", "Change Password", "Logout"],
          navleft:[{title:"Change Password", icon:"fa-key"}, {title:"Logout", icon:"fa-sign-out"}],
          e1: 'Dashboard',
          userinfo:{},
          itemleft:[],
          user:"",
          urls:""
        }
      },
      created : function(){
        this.userinfo = this.$session.get('userinfo')
        if(this.$session.has('userinfo')){
          if(this.userinfo.typeuser == "toko"){
            this.itemleft = this.itemtoko
            this.user = this.userinfo.username
          }else if(this.userinfo.typeuser == "collector"){
            this.itemleft = this.itemcollector
            this.user = this.userinfo.nama
          } 
        }
        
        this.url()
      },
      methods : {
        click(e){
          if(e == "Dashboard"){
            this.$router.push('/dashboard')
          }else if(e == "Status"){
            this.$router.push('/status')
          }else if(e == "Profil"){
            this.$router.push('/toko/profile')
          }else if(e == "Request"){
            this.$router.push('/request')
          }else if(e == "Transfer Bank"){
            this.$router.push('/transfer-bank')
          }else if(e == "Change Password"){
            this.$router.push('/change-password')
          }else if(e == "Logout"){
            this.itemleft = []
            this.$session.destroy()
                this.$router.push('/login')
          }
        },
        url(){
          let uri = this.$config.SERVER +'/api/app-setting';
          this.axios.get(uri,{headers: { 'apikey': this.$config.API}})
          .then(response => {
             if(response.data.status == "success"){
                this.urls = response.data.data.value
             }
          })
        },
        changeloc(){
          // location.href = this.urls
          window.open(
              this.urls,
              '_blank' // <- This is what makes it open in a new window.
            )
        }
      }
    }
</script>