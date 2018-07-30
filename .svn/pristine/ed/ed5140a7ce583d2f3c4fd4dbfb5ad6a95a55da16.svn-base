<template>
    <div>
      <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h4>
                          Dashboard 
                      </h4>
                      <br>
                      <h6><a href="javascript:void(0)" v-on:click="dashboard">Dashboard</a>/Detail Register</h6>
                    </center>
                    <v-client-table :data="data" :columns="columns" :options="options">
                    </v-client-table>
                  </div>
                </div>
            </div>
        </div>
      </aside>
    </div>
</template>
<script>
  import DetailNota from './DetailNota.vue';
  export default{
    data(){
            return{
                item:[],
                data:[],
                columns: ['nama','amountTotal','amountPaidTotal','wilayah',"det"],
                options: {
                  sortable: ['nama','wilayah'],
                  templates : {
                      det: DetailNota
                    }
                }
            }
        },
    created : function(){
      this.listRegister()
    },
    methods : {
          listRegister()
            {
              let detail = this.$session.get('nota') 
              let list = [];
              let datas = {};
              detail.map(function(value,key){
                datas = {TokoIDWiser:value.TokoIDWiser,tokoIDISA:value.tokoIDISA,nama:value.nama, amountTotal:value.amountTotal, amountPaidTotal: value.amountPaidTotal, wilayah: value.wilayah,listnota:value.listnota}
                list.push(datas)
              })

              this.data = list
            },
          dashboard(){
            this.$router.push('/dashboard')
          }
    }
  }
</script>
