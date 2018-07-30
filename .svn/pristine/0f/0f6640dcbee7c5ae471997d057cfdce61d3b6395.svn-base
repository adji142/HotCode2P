<template>
    <div class="container">
        <vue-progress-bar></vue-progress-bar>
        <div class="row col-md-10" >
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h4>
                          Status 
                      </h4>
                    </center>
                      <h5>TUNAI</h5><br>
                      <label>Tunai Ditangan : {{tunaiDiTangan}}</label><br>
                      <label>Tunai di transfer : {{items.tunaiDiTransfer}}</label><br>
                      <label>Tunai disetor : {{items.tunaiDiSetor}}</label><br>
                      <br>
                      <h5>TRANSFER</h5><br>
                      <div v-for="item in items.transfer">
                        <label>Bank : {{item.bankid}}</label><br>
                        <label>Nominal Transfer : {{item.nominaltransaksi}}</label><br>
                        <hr>
                      </div>
                      <h5>GIRO</h5><br>
                      <div v-for="item in items.giro">
                        <label>Nomer Giro : {{item.nomorGiro}}</label><br>
                        <label>Nominal Giro : {{item.nominal}}</label><br>
                        <hr>
                      </div>
                  </div>
                </div>
            </div>
        </div>
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
                tunaiDiTangan:0
            }
        },
    created : function(){
      this.$root.e1 = "Status"
      let type = this.$session.get('userinfo').typeuser
        if(type == 'collector'){
          this.status()
          console.log(this.items)
        }else if(type == 'toko'){
          this.$router.push('/dashboard')
        }
    },
    methods : {
          status()
            {
              this.start()
              let uri = this.$config.SERVER +'/api/pembayaran/status';
                this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      this.items.giro = response.data.data.giro.isi
                      this.items.transfer = response.data.data.transfer.isi
                      this.tunaiDiTangan = response.data.data.tunai.isi.tunaiDiTangan
                      this.items.tunaiDiSetor = response.data.data.tunai.isi.tunaiDiSetor
                      this.items.tunaiDiTransfer = response.data.data.tunai.isi.tunaiDiTransfer
                      this.finish()
                   }
                })
            },
          start () {
              this.$Progress.start()
          },
          set (num) {
              this.$Progress.set(num)
          },
          increase (num) {
              this.$Progress.increase(num)
          },
          decrease (num) {
              this.$Progress.decrease(num)
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
