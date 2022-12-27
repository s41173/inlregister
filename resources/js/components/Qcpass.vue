<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List QC Pass</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addNew">
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
                      <th>#</th>
                      <th>Kontrak</th>
                      <th>PKS</th>
                      <th>No.Polisi</th>
                      <th>FFA</th>
                      <th>M&I</th>
                      <th>IV</th>
                      <th>Lc</th>
                      <th>Tgl</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="seg in segels.data" :key="seg.id">
                      <td>{{seg.jenis}}</td>
                      <td>{{seg.origin}}</td>
                      <td>{{seg.partner_name}}</td>
                      <td>{{seg.no_polisi}}</td>
                      <td>{{seg.ffa}}</td>
                      <td>{{seg.mni}}</td>          
                      <td>{{seg.iv}}</td>
                      <td>{{seg.lc}}</td>
                      <td>{{seg.tgl}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="segels" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
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
                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input v-model="form.bln" name="bln" type="text" class="form-control datetimepicker-input" placeholder="THN-BLN-TGL">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                      <div class="form-group">
                          <label>Cetegory</label>
                          <select v-model="form.category" class="form-control" :class="{ 'is-invalid': form.errors.has('category') }" name="category">
                          <option selected>Incoming</option>
                          <option>Outgoing</option>
                          </select>
                          <has-error :form="form" field="category"></has-error>
                      </div>                         
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cari</button>
                        <button @click="downloadData()" type="button" class="btn btn-secondary">
                            Download
                        </button>
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
              segels : {},
              form: new Form({
                  bln : new Date().toISOString().substr(0, 10),
                  category : '',
              })
            }
        },
        methods: {
          getResults(page = 1) {
              console.log("getResults");
              this.$Progress.start();
              // axios.get('api/qcpass?page=' + page).then(({ data }) => (this.segels = data.data));
              // this.$Progress.finish();

              axios.get('api/qcpass?page=' + page)
                .then(({ data }) => (this.segels = data.data))
                .catch(function (error) {
                    console.log("qcpass error");
                    console.log(error);
                    if(error.response.status === 401){
                        window.location.href = "login";
                    }
                });
                this.$Progress.finish();
          },
          downloadData(){
            window.location.href = "qc/download-excel/"+this.form.bln+"/"+this.form.category;
          },
          filterData(){
                if(this.form.category == ""){
                  alert("Pilih Category");
                  return;
                }
                this.$Progress.start();
                this.segels = {};
                    axios.get('api/qcpass/'+this.form.bln+"="+this.form.category)
                        .then(response => this.segels = response.data)
                        .catch(error => {
                            if(error.response.status === 401){
                                window.location.href = "login";
                            }
                      });
                console.log(this.segels);
          },
        },
        mounted() {
            
        },
        created() {
            this.getResults();
        }
    }
</script>
