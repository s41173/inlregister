<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-3">
                <div class="form-group">
                    <label>Cetegory*</label>
                    <select :disabled="disabled" @change="cascade" v-model="jenis_pilihan" class="form-control" name="jenis_pilihan">
                    <option value="Incoming">Incoming</option>
                    <option value="Outgoing">Outgoing</option>
                    </select>
                </div>
          </div>
          <div class="col-5">
                <div class="form-group">
                <label>Contract Number</label>
                <input :disabled="disabled" v-model="nokontrak" type="text" class="form-control" maxlength="50">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                <label>Filter</label>
                <button @click="btnCari" type="button" class="form-control btn btn-danger">Search</button>
                <a @click="btnReset" href="#">Reset</a>
                </div>
            </div>    
        </div>


        <div class="row">
          <div class="col-12"></div>
          <div class="col-12">
        
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Picking</th>
                      <th>Product/ Code</th>
                      <th>Partner</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="user in users.data" :key="user.product_id">
                        <td>
                          <a href="#" @click="detailModal(user)">{{user.picking_name}}</a>
                        </td>
                      <td class="text-capitalize">{{user.product_name}} / {{user.default_code}}</td>
                      <td>{{user.partner_name}}</td>
                      <td v-if='jenis_pilihan == "Incoming" && user.no_dokumen != null && user.no_aju != null'>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                      </td>
                      <td v-if='jenis_pilihan == "Outgoing" && user.no_dokumen != null && user.no_aju != null && user.no_segel_bc != null'>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                      </td>

                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          Keterangan Syarat input data, dokumen harus ada:<br>
        <div class="row">
          <div class="col-6"><b>Incoming</b>
              <br>
            -Jenis Dok<br>
            -No. Pengajuan<br>
            -No. Pendaftaran<br>
          </div>
          <div class="col-6"><b>Outgoing</b>
            <br>
            -Jenis Dok<br>
            -No. Segel BC<br>
            -No. Pengajuan<br>
            -No. Pendaftaran<br>
          </div>
        </div>

          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>


        <div class="modal fade" id="modDetail" tabindex="-1" role="dialog" aria-labelledby="modDetail" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>No. Kontrak</label>
                            <input readonly v-model="form.origin" type="text" name="origin"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('origin') }">
                            <has-error :form="form" field="origin"></has-error>
                        </div>                        
                        <div class="form-group">
                            <label>Jenis Dokumen</label>
                            <input readonly v-model="form.jenis_dokumen" type="text" name="jenis_dokumen"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('jenis_dokumen') }">
                            <has-error :form="form" field="jenis_dokumen"></has-error>
                        </div>                            
                        <div v-if='jenis_pilihan == "Outgoing"' class="form-group">
                            <label>No. Segel BC</label>
                            <input readonly v-model="form.no_segel_bc" type="text" name="no_segel_bc"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('no_segel_bc') }">
                            <has-error :form="form" field="no_segel_bc"></has-error>
                        </div>
                    </div>
                    <div class="col-6">

                        <div class="form-group">
                            <label>No. Pengajuan</label>
                            <input readonly v-model="form.no_aju" type="text" name="no_aju"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('no_aju') }">
                            <has-error :form="form" field="no_aju"></has-error>
                        </div>

                        <div class="form-group">
                            <label>No. Pendaftaran</label>
                            <input readonly v-model="form.no_dokumen" type="text" name="no_dokumen"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('no_dokumen') }">
                            <has-error :form="form" field="no_dokumen"></has-error>
                        </div>

                    </div>
                    </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Registrasi</h5>
                    <h5 class="modal-title" v-show="editmode">Update Registrasi Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser()"> -->
                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-4">
                                <!-- <div class="form-group">
                                    <label>Origin Number</label>
                                    <input readonly v-model="form.origin" type="text" name="origin"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('origin') }">
                                    <has-error :form="form" field="origin"></has-error>
                                </div> -->
                                <!-- <div class="form-group">
                                    <label>Picking Name</label>
                                    <input readonly v-model="form.picking_name" type="text" name="picking_name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('picking_name') }">
                                    <has-error :form="form" field="picking_name"></has-error>
                                </div> -->
                                <div class="form-group">
                                    <label>Contrack Number</label>
                                    <input readonly v-model="form.no_contract" type="text" name="no_contract"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('no_contract') }">
                                    <has-error :form="form" field="no_contract"></has-error>
                                </div>                                

                        
                                <!-- <div class="form-group">
                                    <label>Cetegory*</label>
                                    <select @change="cascade" v-model="jenis" class="form-control" :class="{ 'is-invalid': form.errors.has('jenis') }" name="jenis">
                                    <option disabled value="">Please select one</option>
                                    <option value="Incoming">Incoming</option>
                                    <option value="Outgoing">Outgoing</option>
                                    </select>
                                    <has-error :form="form" field="jenis"></has-error>
                                </div>    -->

                                <div class="form-group">
                                    <label>Material*</label>
                                    <select v-model="form.jenis_material" class="form-control" :class="{ 'is-invalid': form.errors.has('jenis_material') }" name="jenis_material">
                                    <option value="CPO">CPO</option>
                                    <option value="Not CPO">Not CPO</option>
                                    </select>
                                    <has-error :form="form" field="jenis_material"></has-error>
                                </div>   
                                <div class="form-group">
                                    <label>Multiple Contract*</label>
                                    <select @change="onChangeJoin($event)" v-model="def_contract_join" class="form-control" :class="{ 'is-invalid': form.errors.has('contract_join') }" name="contract_join">
                                    <option value="1">Single Contract</option>
                                    <option value="2">Multiple Contract</option>
                                    </select>
                                    <has-error :form="form" field="contract_join"></has-error>
                                </div>                                  
                                <div class="form-group">
                                    <label>Sertifikat*</label>
                                    <select v-model="def_sertifikat" class="form-control" :class="{ 'is-invalid': form.errors.has('sertifikat') }" name="sertifikat">
                                    <option value="RSPO">RSPO</option>
                                    <option value="ISCC">ISCC</option>
                                    <option value="Tidak">Tidak</option>
                                    </select>
                                    <has-error :form="form" field="sertifikat"></has-error>
                                </div>                                   

                                <div class="form-group">
                                    <label>Car Name*</label>
                                    <select v-model="form.nama_kendaraan" class="form-control" :class="{ 'is-invalid': form.errors.has('nama_kendaraan') }" name="nama_kendaraan">
                                    <option disabled value="">Please select one</option>
                                    <option>Box</option>
                                    <option>Dump truck</option>
                                    <option>Forklift</option>
                                    <option>Kontainer</option>
                                    <option>ISO Tank</option>
                                    <option>Tanki</option>
                                    </select>
                                    <has-error :form="form" field="nama_kendaraan"></has-error>
                                </div>   
                                <div class="form-group">
                                    <label>Police Number*</label>
                                    <input v-model="form.no_polisi" type="text" name="no_polisi"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('no_polisi') }">
                                    <has-error :form="form" field="no_polisi"></has-error>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Partner Name</label>
                                    <input readonly v-model="form.partner_name" type="text" name="partner_name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('partner_name') }">
                                    <has-error :form="form" field="partner_name"></has-error>
                                </div>                                   

                                <div class="form-group">
                                    <label>Transporter*</label>
                                    <input v-model="form.transporter" type="text" name="transporter"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('transporter') }">
                                    <has-error :form="form" field="transporter"></has-error>
                                </div>            
                                <div class="form-group">
                                    <label>Driver Name*</label>
                                    <input v-model="form.driver_name" type="text" name="driver_name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('driver_name') }">
                                    <has-error :form="form" field="driver_name"></has-error>
                                </div>            


                                <div class="form-group">
                                    <label>Container Number</label>
                                    <input v-model="form.no_container" type="text" name="no_container"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('no_container') }">
                                    <has-error :form="form" field="no_container"></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Driver SIM </label>
                                    <input v-model="form.sim" type="text" name="sim"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('sim') }">
                                    <has-error :form="form" field="sim"></has-error>
                                </div>  
                                <div class="form-group">
                                    <label>Passport Number / Surat Jalan</label>
                                    <input v-model="form.no_surat_jalan" type="text" name="no_surat_jalan"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('no_surat_jalan') }">
                                    <has-error :form="form" field="no_surat_jalan"></has-error>
                                </div>                                         
                            </div>

                            <div class="col-4">

                                <div class="form-group">
                                    <label>Product</label>
                                    <input readonly v-model="form.product_name" type="text" name="product_name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('product_name') }">
                                    <has-error :form="form" field="product_name"></has-error>
                                </div>
                                
                                <div class="form-group" v-if="showName == 2">
                                    <label>No. DO</label>
                                    <input readonly v-model="form.no_do" type="text" name="no_do"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('no_do') }">
                                    <has-error :form="form" field="no_do"></has-error>
                                </div>                      

                                <div class="form-group" v-if="showName == 2">
                                    <label>Destination</label>
                                    <input v-model="form.destination" type="text" name="destination"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('destination') }">
                                    <has-error :form="form" field="destination"></has-error>
                                </div>   

                                <div class="form-group" v-if="showName == 1">
                                    <label>Bruto From</label>
                                    <input v-model="form.bruto_from" type="text" name="bruto_from"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('bruto_from') }">
                                    <has-error :form="form" field="bruto_from"></has-error>
                                </div>      
                                <div class="form-group" v-if="showName == 1">
                                    <label>Tara From</label>
                                    <input v-model="form.tarra_from" type="text" name="tarra_from"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('tarra_from') }">
                                    <has-error :form="form" field="tarra_from"></has-error>
                                </div>    
                                <div class="form-group" v-if="showName == 1">
                                    <label>Netto From</label>
                                    <input v-model="form.netto_from" type="text" name="netto_from"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('netto_from') }">
                                    <has-error :form="form" field="netto_from"></has-error>
                                </div>                                                                    
                                <div class="form-group">
                                    <label>Remark</label>
                                    <input v-model="form.remark" type="text" name="remark"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('remark') }">
                                    <has-error :form="form" field="remark"></has-error>
                                </div>                                
               
             
                                <div class="form-group">
                                    <label>RFID</label>
                                    <input v-model="form.rfid" type="text" name="rfid"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('rfid') }">
                                    <has-error :form="form" field="rfid"></has-error>
                                </div>         

                            </div>
                        </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Save</button>
                    </div>

                  </form>
                </div>
            </div>
        </div>
    </div>

  </section>
</template>


<script>
    export default {
        data () {
            return {
                user_login: false,
                showName: 3,
                editmode: false,
                users : {},
                disabled: false,
                jenis_pilihan: "Incoming",
                nokontrak : '01/KJ-INL/MGCR/IX/2022',
                form: new Form({
                    product_uom_qty : '',
                    create_by : '1',
                    create_name : '2',
                    id : '',
                    picking_id: '',
                    picking_name: '',
                    product_id: '',
                    product_uom_id: '',
                    location_id: '',
                    location_dest_id: '',
                    stock_move_id: '',
                    no_do: '',
                    no_po: '',
                    no_dokumen: '',
                    no_dokumen_do: '',
                    no_segel_bc: '',
                    no_aju: '',
                    jenis_dokumen : '',
                    no_contract : '',
                    origin : '',
                    default_code : '',
                    product_name : '',
                    keterangan : '',
                    proses : '',
                    jenis : '',
                    nama_kendaraan: '',
                    no_container: '',
                    no_polisi: '',
                    transporter: '',
                    driver_name: '',
                    asal_pks: '',
                    destination: '',
                    partner_name: '',
                    no_karcis_timbangan: '',
                    no_surat_jalan: '',
                    sim: '',
                    rfid: '',
                    alamat: '',
                    sertifikat: '',
                    contract_join: '',
                }),
                selected: '',
                DataKontrak : {},
                DataRegistrasi : {},
                def_contract_join: '1',
                def_sertifikat: 'Tidak',
            }
        },
        methods: {
            onChangeJoin(e) {
                this.form.contract_join = e.target.value;
            },
            cascade: function(e){
                //Show or hide via combo
                console.log("Show "+e.target.value+ " fields")
                if (e.target.value == 'Outgoing'){
                    this.showName = 2;
                }else{
                    this.showName = 1;
                }
            },

            getResults(page = 1) {
                console.log("getResults");
            },
            updateUser(){
                this.$Progress.start();
                this.form.proses = "IN"
                this.form.user_login = "1"
                this.form.jenis = this.jenis_pilihan
                this.form.contract_join = this.def_contract_join
                
                this.form.post('api/register_send')
                    .then((response) => {

                    if(response.data.status == true){
                        $('#addNew').modal('hide');
                        this.users= {};
                    }
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            editModal(user){
                console.log("editModal");
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);

                if (this.jenis_pilihan == 'Outgoing'){
                    this.showName = 2;
                }else{
                    this.showName = 1;
                }

            },
            detailModal(user){
                $('#modDetail').modal('show');
                this.form.fill(user);
            },            
            newModal(){
                console.log("newModal");
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            btnReset(){
                console.log("btnReset");
                this.disabled = false
                this.users= {};
                this.form.reset();
            },
            btnCari(){
                this.disabled = true
                this.users= {};
                const dtPos = { no: this.nokontrak };

                if(this.jenis_pilihan == ""){
                    alert("Category harus diisi!")
                    this.disabled = false
                    return;
                }
                this.$Progress.start();

                    axios.post('api/src-contract-number', dtPos)
                        .then((response) => {
                            this.users = response.data
                            if(response.data.status == false){
                                this.disabled = false
                                Toast.fire({
                                    icon: 'success',
                                    title: response.data.message
                                });
                                this.users= {};
                            }
                            this.$Progress.finish();
                        })
                        .catch(error => {
                            if(error.response.status === 401){
                                window.location.href = "login";
                            }
                            his.errorMessage = error.message;
                        });
            },
            
            deleteUser(id){
                
            },
            loadUsers(){
                console.log("loadUsers");
            },          
            createUser(){
                console.log("createUser");
            }

        },
        mounted() {
            console.log('User Component mounted.')
        },
        created() {
            this.showName = 3;
            console.log("created");
        }
    }
</script>
