<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
        <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
        <div class="row col-md-10">
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h4>
                          Add Transfer 
                      </h4>
                    </center>
                    <div class="form-group">
                      <v-text-field
                              v-model="item.nominal"
                              label="Nominal"
                              required
                            ></v-text-field>
                    </div>
                    <div class="form-group">
                      <v-text-field
                              v-model="item.berita"
                              label="Berita"
                              required
                            ></v-text-field>
                    </div>
                    <div class="form-group">
                      <v-text-field
                              v-model="item.nama_bank"
                              label="Nama Bank"
                              required
                            ></v-text-field>
                    </div>
                    <div class="form-group">
                      <v-text-field
                              v-model="item.tanggal"
                              label="Tanggal"
                              required
                            ></v-text-field>
                    </div>
                    <button class="btn btn-info" v-on:click="submit">Submit</button>
                  </div>
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
            }
        },
    created : function(){      
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
            let data = {"setoruang" : [this.item]}
            let uri = this.$config.SERVER +'/api/sync';
            console.log(uri)
                this.axios.post(uri,data,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                    if(response.data.status == "error"){
                         console.log(response.data.message);
                        // this.bus.$emit('notify-me', {
                        //     status: 'is-danger',
                        //     data: {
                        //         title: 'Transfer',
                        //         text: response.data.message
                        //     }
                      
                        //   });    
                    }else{
                        // this.bus.$emit('notify-me', {
                        //     status: 'is-success',
                        //     data: {
                        //         title: 'Transfer',
                        //         text: response.data.message
                        //     }
                      
                        //   });
                        this.$router.push('/transfer-bank')
                    }
                    
                })
          }
    }
  }
</script>
