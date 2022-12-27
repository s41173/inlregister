<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List Registrasi</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addFilter">
                      <i class="fa fa-plus-square"></i>
                      Filter
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Jenis</th>
                      <th>Masuk</th>
                      <th>Antrian</th>
                      <th>Polisi</th>
                      <th>Partner</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="user in DataRegistrasi.data" :key="user.id">
                      <td><a href="#" @click="editModal(user)">{{user.jenis}}</a></td>
                      <td>{{user.tgl_masuk_truk}}</td>
                      <td>{{user.no_ticket}}</td>
                      <td>{{user.no_polisi}}</td>
                      <td>{{user.partner_name}}</td>
                      <td>
                        <a v-if='user.jenis == "Outgoing"' href="#" @click="PrintPreviewSJ(user.id)">
                            <button type="button" class="btn btn-info">SJ</button>
                        </a>
                        <a v-if='user.jenis == "Outgoing"' href="#" @click="PrintPreviewCoa(user.id)">
                            <button type="button" class="btn btn-info">COA</button>
                        </a>                        
                        <a href="#" @click="PrintPreviewSlip(user.id)">
                            <button type="button" class="btn btn-info">SLIP</button>
                        </a>
                        <a v-if='user.type == "IN"' href="#" @click="OutRegister(user.id)">
                            <button type="button" class="btn btn-danger">OUT</button>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="DataRegistrasi" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <div class="modal fade" id="addFilter" tabindex="-1" role="dialog" aria-labelledby="addFilter" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="filterData()">
                    <div class="modal-body">

                    <div class="form-group">
                        <label>No.Polisi</label>
                        <input class="form-control" v-model="bkfilter" type="text" name="bkfilter">
                    </div>    
                    <div class="form-group">
                        <label>RFID</label>
                        <input class="form-control" v-model="rfidfilter" type="text" name="rfidfilter">
                    </div>                  
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cari</button>
                    </div>
                  </form>
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

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? cetakSurat() : createUser()">
                    <div class="modal-body">

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Origin/No.Kontrak</label>
                                <input readonly v-model="form.origin" type="text" name="origin"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('origin') }">
                                <has-error :form="form" field="origin"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Picking Name</label>
                                <input readonly v-model="form.picking_name" type="text" name="picking_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('picking_name') }">
                                <has-error :form="form" field="picking_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Partner Name</label>
                                <input readonly v-model="form.partner_name" type="text" name="partner_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('partner_name') }">
                                <has-error :form="form" field="partner_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Product</label>
                                <input readonly v-model="form.product_name" type="text" name="product_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('product_name') }">
                                <has-error :form="form" field="product_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Jeni</label>
                                <input readonly v-model="form.jenis" type="text" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('jenis') }">
                                <has-error :form="form" field="jenis"></has-error>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>No Container</label>
                                <input readonly v-model="form.no_container" type="text" name="no_container"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('no_container') }">
                                <has-error :form="form" field="no_container"></has-error>
                            </div>
                            <div class="form-group">
                                <label>No Polisi</label>
                                <input readonly v-model="form.no_polisi" type="text" name="no_polisi"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('no_polisi') }">
                                <has-error :form="form" field="no_polisi"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Transporter</label>
                                <input readonly v-model="form.transporter" type="text" name="transporter"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('transporter') }">
                                <has-error :form="form" field="transporter"></has-error>
                            </div>            
                            <div class="form-group">
                                <label>Driver Name</label>
                                <input readonly v-model="form.driver_name" type="text" name="driver_name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('driver_name') }">
                                <has-error :form="form" field="driver_name"></has-error>
                            </div>            

                            <div class="form-group">
                                <label>No Surat Jalan</label>
                                <input readonly v-model="form.no_surat_jalan" type="text" name="no_surat_jalan"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('no_surat_jalan') }">
                                <has-error :form="form" field="no_surat_jalan"></has-error>
                            </div>   

                        </div>

                        <div class="col-4">

                            <div class="form-group">
                                <label>Nama Kendaraan</label>
                                <input readonly v-model="form.nama_kendaraan" type="text" name="nama_kendaraan"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('nama_kendaraan') }">
                                <has-error :form="form" field="nama_kendaraan"></has-error>
                            </div>    
                            <div class="form-group">
                                <label>Destination (Outgoing)</label>
                                <input readonly v-model="form.destination" type="text" name="destination"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('destination') }">
                                <has-error :form="form" field="destination"></has-error>
                            </div>
                            <div class="form-group">
                                <label>No. DO (Outgoing)</label>
                                <input readonly v-model="form.no_do" type="text" name="no_do"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('no_do') }">
                                <has-error :form="form" field="no_do"></has-error>
                            </div>    
                            <div class="form-group">
                                <label>RFID</label>
                                <input readonly v-model="form.rfid" type="text" name="rfid"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('rfid') }">
                                <has-error :form="form" field="rfid"></has-error>
                            </div>  
                            <div class="form-group">
                                <label>SIM Driver</label>
                                <input readonly v-model="form.sim" type="text" name="sim"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('sim') }">
                                <has-error :form="form" field="sim"></has-error>
                            </div>  


                        </div>
                    </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                editmode: false,
                bkfilter : '',
                rfidfilter : '',
                datafilter : '',
                form: new Form({
                    product_uom_qty : '',
                    origin : '',
                    default_code : '',
                    product_name : '',
                    keterangan : '',
                    proses : '',
                    jenis : '',
                    sim : '',
                    rfid : '',
                    id : '',
                    picking_id: '',
                    picking_name: '',
                    product_id: '',
                    product_uom_id: '',
                    location_id: '',
                    location_dest_id: '',
                    no_do: '',
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
                }),
                DataKontrak : {},
                DataRegistrasi : {},
            }
        },
        methods: {
          filterData(){
                this.$Progress.start();
                this.DataRegistrasi = {};
                if(this.rfidfilter != ""){
                    this.datafilter = this.rfidfilter
                }else{
                    this.datafilter = this.bkfilter
                }
                axios.get('api/registrasi/'+this.datafilter)
                    .then(response => this.DataRegistrasi = response.data)
                    .catch(error => {
                        if(error.response.status === 401){
                            window.location.href = "login";
                        }
                    });
                this.$Progress.finish();
          },
            getResults(page = 1) {
                console.log("getResults");
                this.$Progress.start();
                // axios.get('api/registrasi?page=' + page).then(({ data }) => (this.DataRegistrasi = data.data));
                // this.$Progress.finish();

                axios.get('api/registrasi?page=' + page)
                .then(({ data }) => (this.DataRegistrasi = data.data))
                .catch(function (error) {
                    console.log("registrasi error");
                    console.log(error);
                    if(error.response.status === 401){
                        window.location.href = "login";
                    }
                });
                this.$Progress.finish();


                // axios.get('api/registrasi?page=' + page)
                // .then((response) => {
                //     this.DataRegistrasi = response.data
                //     this.$Progress.finish();
                // })
                // .catch(error => {
                //     if(error.response.status === 401){
                //         window.location.href = "login";
                //     }
                //     his.errorMessage = error.message;
                // });
            },
            editModal(user){
                console.log("editModal");
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                console.log("newModal");
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            PrintPreviewSJ(id){
                window.open('/preview-sj/'+id,'_blank');
            },
            PrintPreviewSlip(id){
                window.open('/preview-slip/'+id,'_blank');
            },            
            PrintPreviewCoa(id){
                window.open('/preview-coa/'+id,'_blank');
            },            
            OutRegister(id){
                this.$Progress.start();
                Swal.fire({
                    title: 'Perhatian!!!',
                    text: "Kendaraan akan keluar dari Gedung!, data akan dikirim ke ERP",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, kirim data sekarang!'
                    }).then((result) => {
                         if (result.value) {
                                this.form.get('api/register/out/'+id).then(()=>{
                                        Swal.fire(
                                        'Informasi!',
                                        'Terimakasih, Data sudah masuk ke ERP & Kendaraan sudah keluar.',
                                        'success'
                                        );
                                    this.loadUsers();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
                    .catch(error => {
                    if(error.response.status === 401){
                        window.location.href = "login";
                    }
                });
                this.$Progress.finish();
            },
            deleteUser(id){
                
            },
          loadUsers(){
            console.log("loadUsers");
            this.getResults();
            //if(this.$gate.isAdmin()){
              //axios.get("api/registrasi").then(({ data }) => (this.DataRegistrasi = data.data));
            //}
          },
          
          createUser(){
            console.log("createUser");
          }

        },
        mounted() {
            console.log('User Component mounted.')
        },
        created() {
            console.log("created");
            this.loadUsers();
        }
    }
</script>
