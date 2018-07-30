<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
        <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h4>
                          Add Request 
                      </h4>
                    </center>
                      <div class="form-group">
                        <label>Keterangan</label>
                        <textarea v-model="item.keterangan" class="form-control"></textarea>
                      </div>
                      <button class="btn btn-info" v-on:click="submit" :disabled="dis">Submit</button>
                  </div>
                </div>
            </div>
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
                dis:false
            }
        },
    created : function(){
      let type = this.$session.get('userinfo').typeuser
        if(type == 'collector'){
           this.$router.push('/dashboard')
        }
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
            this.start()
            this.dis=true
            let uri = this.$config.SERVER +'/api/request/create';
                this.axios.post(uri,this.item,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                    if(response.data.status == "error"){
                         console.log(response.data.message); 
                    }else{
                        this.$router.push('/request')
                    }

                    this.finish()
                    this.dis=false
                })
          }
    }
  }
</script>
