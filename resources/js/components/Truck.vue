<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Truck List</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addNew" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Add New
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>BK</th>
                      <th>Quota</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="seg in segels.data" :key="seg.id">

                      <td>{{seg.id}}</td>
                      <td>{{seg.bk}}</td>
                      <td>{{seg.quota}}</td>
                      <td>
                        <a href="#" @click="editModal(seg)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(seg.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New</h5>
                    <h5 class="modal-title" v-show="editmode">Update Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>No.Polisi</label>
                            <input v-model="form.bk" type="text" name="bk"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('bk') }">
                            <has-error :form="form" field="bk"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Quota</label>
                            <input v-model="form.quota" type="number" name="quota"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('quota') }">
                            <has-error :form="form" field="quota"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
              // Create a new form instance
              form: new Form({
                  id : '',
                  bk: '',
                  quota: '',
              })
            }
        },
        methods: {

          loadSegels(){
            // if(this.$gate.isAdmin()){
              axios.get("api/truck").then(({ data }) => (this.segels = data.data));
            // }
          },
        updateUser(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/truck/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                    //  Fire.$emit('AfterCreate');

                  this.loadSegels();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
          createUser(){
              this.$Progress.start();
              this.form.post('api/truck')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.loadSegels();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
        },
        editModal(seg){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(seg);
        },
        newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
        },
        deleteUser(id){
                Swal.fire({
                    title: 'Are you sure Delete ?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/truck/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadSegels();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();

            this.loadSegels();

            this.$Progress.finish();
        }
    }
</script>
