<template>
    <div>
      <aside class="js-offcanvas" data-offcanvas-options='{ "modifiers": "left,overlay" }' id="off-canvas">
        <div class="row">
          <div v-show="foto">
              <vue-webcam ref='webcam'></vue-webcam>
              <hr/>
              <img :src="photo" alt="" style="width:400px;height:300px" />
              <hr/>
              <button class="teal" type="button" @click="take_photo">Take Photo</button>
          </div>
            <div class="col-md-10">
                <div class="panel panel-default">
                  <div class="col-md-10">
                    <center>
                      <h4>
                          Dashboard 
                      </h4>
                      <br>
                      <h6><a href="javascript:void(0)" v-on:click="detail">Detail Register</a>/Daftar Nota</h6>
                      <v-icon>fa-user</v-icon> {{namatoko}}
                    </center>
                    <div class="pembayaran" v-show="pembayar">
                      <v-form v-model="valid" ref="form">
                        <div class="form-group" v-show="!giro">
                            <v-text-field
                              v-model="bayar.tgl_bayar"
                              label="Tanggal Bayar"
                              readonly
                            ></v-text-field>
                        </div>
                        <div class="form-group">
                            <v-text-field
                              v-model="bayar.keterangan"
                              label="Keterangan Bayar"
                              required
                            ></v-text-field>
                        </div>
                        <div class="form-group">
                            <v-text-field
                              v-model="total_bayar"
                              label="Total Bayar"
                              :rules="totalByarRules"
                              required
                            ></v-text-field>
                        </div>
                        <div class="form-group">
                            <!-- <label>Jenis Pembayaran</label>
                            <select class="form-control" v-on:change="change" v-model="bayar.jenis_pembayaran">
                              <option value="KAS">Tunai</option>
                              <option value="BGC">BGC</option>
                              <option value="TRN">Transfer</option>
                            </select> -->
                            <v-select  v-bind:items="jenispem"
                              v-model="bayar.jenis_pembayaran"
                              v-on:input="change"
                              label="Jenis Pembayaran">
                            </v-select>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-select  v-bind:items="daftarBank"
                              v-model="select"
                              label="Select"
                              v-on:input="onChange()">
                            </v-select>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-text-field
                              v-model="bayar.tgl_bgc"
                              label="Tanggal BGC"
                            ></v-text-field>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-text-field
                              v-model="bayar.no_bgc"
                              label="No BGC"
                            ></v-text-field>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-text-field
                              v-model="bayar.jenis_bgc"
                              label="Jenis BGC"
                            ></v-text-field>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-text-field
                              v-model="bayar.tgl_jatuh_tempo_bgc"
                              label="Tgl Jatuh Tempo BGC"
                            ></v-text-field>
                        </div>
                        <div class="form-group" v-show="giro">
                            <v-text-field
                              v-model="bayar.no_account"
                              label="No Acount"
                            ></v-text-field>
                        </div>
                        
                        <div class="form-group" v-show="trf">
                            <v-text-field
                              v-model="bayar.tgl_transfer"
                              label="Tanggal Transfer"
                              readonly
                            ></v-text-field>
                        </div>
                        <div class="form-group" v-show="trf">
                            <v-text-field
                              v-model="bayar.tujuan_transfer"
                              label="Tujuan Transfer"
                            ></v-text-field>
                        </div>
                        <button class="btn btn-success" v-on:click="submit" :class="{ blue: valid , red: !valid}"> Save</button>
                      </v-form>
                    </div>
                    <div v-show="dt">
                      <button class="btn btn-success pull-right" v-on:click="byr">Bayar</button>
                      <button class="btn btn-success pull-right" v-on:click="ques" v-if="totalBay > 0">verify</button>
                      <v-client-table :data="data" :columns="columns" :options="options">
                      </v-client-table>
                    </div>

                    <div v-show="question">
                      <button class="btn btn-success" v-on:click="verify">Password</button>
                      <button class="btn btn-success" v-on:click="poto">Take Foto</button>
                    </div>

                    <div v-show="pss">
                      <input type="password" name="" v-model="pass" class="form-control">
                      <button class="btn btn-success" v-on:click="verify">verify</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </aside>
    </div>
</template>
<script>
import moment from 'vue-moment'
import Vue from 'vue';
import Check from './checkbox.vue';
import VueWebcam from 'vue-webcam';

Vue.use(moment);
  export default{
    components: { VueWebcam },
    data(){
            return{
                valid: false,
                question:false,
                item:[],
                data:[],
                total_bayar:'',
                totalByarRules: [
                  (v) => !!v || 'total bayar harus diisi'
                ],
                columns: ['nonota','rptagih','tglJthtempo','tglNota',"bayar", "Assign"],
                options: {
                  sortable: ['nonota','tglNota','tglJthtempo'],
                  templates : {
                      Assign: Check
                    }
                },
                giro:false,
                trf:false,
                tunai:true,
                pembayar:false,
                dt:true,
                bayar:{},
                bayars:{},
                total:{},
                tgl:{},
                pass:"",
                pss:false,
                tampung:[],
                namatoko:"",
                photo: null,
                foto:false,
                totalBay:0,
                daftarBank:[],
                select: null,
                jenispem:[{value:'KAS', text:'Tunai'},{value:'BGC', text:'BGC'},{value:'TRN', text:'Transfer'}]
            }
        },
    created : function(){
      this.$session.set('amountBayar', 0)
      this.listRegister()
      this.bayar.idtoko = this.$session.get('idtoko')
      this.tgl = Vue.moment().format('Y-MM-DD')
      this.bayar.tgl_bayar = this.tgl
      this.bayar.tgl_transfer = this.tgl
      this.total = 0
      this.columns.splice(5, 1);
      this.namatoko = this.$session.get('namatoko')
    },
    methods : {
          listRegister()
            {
              this.bayar
              let detail = this.$session.get('listnota') 
              let list = [];
              let datas = {};
              detail.map(function(value,key){
                datas = {nonota:value.nonota,rptagih:value.rptagih,tglJthtempo:value.tglJthtempo, tglNota:value.tglNota, bayar: value.bayar, tagihanid:value.tagihanid, kartupiutangid:value.kartupiutangid}
                list.push(datas)
              })
              this.data = list
              list = [];
              let uri = this.$config.SERVER +'/api/bank';
              this.axios.get(uri,{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                   if(response.data.status == "success"){
                      response.data.data.map(function(val,ki){
                        list.push({value:val.id,text:val.namabankdankota})
                      })
                   }
                })

              this.daftarBank = list
              console.log(this.daftarBank)
            },
          logout(){
            this.$session.destroy()
            this.$router.push('/login')
          },
          detail(){
            this.$router.push('/detail')
          },
          change(e){
            let type = this.bayar.jenis_pembayaran
            console.log(type)
            if(type == "BGC"){
              this.trf = false
              this.tunai = false
              this.giro = true
            }else if(type == "TRN"){
              this.trf = true
              this.tunai = false
              this.giro = false
            }else if(type == "KAS"){
              this.trf = false
              this.tunai = true
              this.giro = false
            }
          },
          onChange(obj) {
             this.bayar.bank_name = this.select
          },
          byr(){
            this.pembayar=true
            this.dt=false
          },
          submit(){
            if (this.$refs.form.validate()) {
              console.log("aa")
            }
            // let totalBayar = parseFloat(this.$session.get('amountBayar'))
            // totalBayar = totalBayar + parseFloat(this.bayar.total_bayar)
            // this.totalBay = totalBayar
            // this.$session.set('amountBayar', totalBayar)

            // if(this.bayar.tgl_bgc != "" && this.bayar.tgl_bgc != null){
            //   this.bayar.tgl_bayar = this.bayar.tgl_bgc
            // }

            // this.bayars.data = [this.bayar]
            // this.tampung.push(this.bayar)
            // this.bayars = {}
            // this.bayar = {}
            // this.bayars.idtoko = this.$session.get('idtoko')


            // this.pembayar=false
            // this.dt=true
            // let detail = this.$session.get('listnota') 
            // let list = [];
            // let datas = {};
            // let tot = parseFloat(this.$session.get('amountBayar'))
            // let sisa = tot
            // detail.map(function(value,key){
            //   if(sisa > 0){
            //     if(value.rptagih > sisa){
            //       datas = {nonota:value.nonota,rptagih:value.rptagih,tglJthtempo:value.tglJthtempo, tglNota:value.tglNota, bayar: value.bayar,bayarSementara: sisa, tagihanid:value.tagihanid, kartupiutangid:value.kartupiutangid}
            //       list.push(datas)
            //       sisa = 0
            //     }else if(value.rptagih < sisa){
            //       datas = {nonota:value.nonota,rptagih:value.rptagih,tglJthtempo:value.tglJthtempo, tglNota:value.tglNota, bayar: value.bayar,bayarSementara: value.rptagih, tagihanid:value.tagihanid, kartupiutangid:value.kartupiutangid}
            //       list.push(datas)
            //       sisa = sisa - value.rptagih
            //     }
            //   }else{
            //     datas = {nonota:value.nonota,rptagih:value.rptagih,tglJthtempo:value.tglJthtempo, tglNota:value.tglNota, bayar: value.bayar,bayarSementara: 0, tagihanid:value.tagihanid, kartupiutangid:value.kartupiutangid}
            //       list.push(datas)
            //   }
              
            // })
            
            // this.data = list
            // this.$session.set('listnota', list) 

            // if(this.columns[5] != "undefined"){
            //   this.columns.splice(5, 1);
            // }            
            // this.columns.push('Assign')
          },
          verify(){
            this.question = false
            this.pss = true
            this.pembayar=false
            this.dt =true
            let pwd = this.$session.get('pwdnya')
            //&& this.pass == pwd && this.pass != "undefined"
            if(this.pass != "" || this.photo != null){
              this.pass = ""
              this.pss = false
              this.pembayar=false
              this.dt=true
              let detail = this.$session.get('listnota') 
              let list = [];
              let datas = {};
              let amt = ""
              let tgl = this.tgl
              let k = this.$session
              let tot = this.$session.get('amountBayar')
              let sisa = 0
              let send = true
              detail.map(function(value,key){
                if(k.has(value.nonota)){
                  if(parseFloat(k.get(value.nonota)) > 0){
                    sisa = sisa + parseFloat(k.get(value.nonota))
                    if(sisa > tot){
                      alert("melebihi jumlah pembayaran")
                      send = false
                    }else{
                      datas = {"registerid":value.tagihanid,
                               "kartupiutangid":value.kartupiutangid,
                               "nominaliden": k.get(value.nonota),
                               "keterangantagih":"",
                               "created_at":tgl
                              }
                      list.push(datas)
                    }
                  } 
                }else{
                  if(parseFloat(value.bayarSementara) > 0){
                    sisa = sisa + parseFloat(value.bayarSementara)
                    if(sisa > tot){
                      alert("melebihi jumlah pembayaran")
                      send = false
                    }else{
                      datas = {"registerid":value.tagihanid,
                               "kartupiutangid":value.kartupiutangid,
                               "nominaliden": parseFloat(value.bayarSementara),
                               "keterangantagih":"",
                               "created_at":tgl
                              }
                      list.push(datas)
                    }
                  } 
                }
              })

              if(tot > sisa){
                send = false
                alert("amount bayar harus habis")
              }else if(tot < sisa){
                send = false
                alert("melebihi jumlah pembayaran")
              }else{
                send = true
              }

              if(send){
                let ses = this.$session
                let uri1 = this.$config.SERVER +'/api/sync';
                let fot = this.photo
                let tamp =  this.tampung;
                if(this.photo != null){
                  tamp.map(function(value,key){
                   tamp[key].foto = fot
                  })
                }

                this.axios.post(uri1,{"bayar" : tamp},{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                    if(response.data.status == "fail"){
                         console.log(response.data.message); 
                    }else{
                        ses.set('amountBayar', this.bayar.total_bayar)
                        console.log(response)
                    }
                    
                })


                let uri = this.$config.SERVER +'/api/sync';
                this.axios.post(uri,{"assignTagihan" : list},{headers: { 'accesstoken': this.$session.get('token')}})
                .then(response => {
                    if(response.data.status == "error"){
                         console.log(response.data.message); 
                    }else{
                        this.$router.push('/dashboard')
                    }
                    
                })
              }
            }
            // else if(this.pass != ""){
            //   alert("Password Salah")
            // } 
          },
          take_photo () {
            this.question = false
            this.photo = this.$refs.webcam.getPhoto();
            // console.log(this.photo)
            this.verify()
          },
          ques(){
            this.question = true
            this.pss = false
            this.foto = false
          },
          poto(){
            this.foto = true
          }
    }
  }
</script>
