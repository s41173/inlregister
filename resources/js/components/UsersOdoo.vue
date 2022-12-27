<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">User List</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
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
                      <th>Level</th>
                      <th>Login</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="user in users.data" :key="user.id">
                      <td class="text-capitalize">{{user.level_ext}}</td>
                      <td class="text-capitalize">{{user.login}}</td>
                      <td>{{user.active}}</td>
                    <td>

                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i>
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
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New User</h5>
                    <h5 class="modal-title" v-show="editmode">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Login</label>
                            <input required v-model="form.login" type="text" name="login"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('login') }">
                            <has-error :form="form" field="login"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input required v-model="form.password_ext" type="password" name="password_ext"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password_ext') }" autocomplete="false">
                            <has-error :form="form" field="password_ext"></has-error>
                        </div>                        
                        <div class="form-group">
                            <label>User Level</label>
                            <select required name="level_ext" v-model="form.level_ext" id="level_ext" class="form-control" :class="{ 'is-invalid': form.errors.has('level_ext') }">
                                <option value="">Select User Role</option>
                                <option value="ADMINWEB">ADMINWEB</option>
                                <option value="CEK_SEGEL">CEK_SEGEL</option>
                                <option value="OP_QCPASS">OP_QCPASS</option>
                                <option value="SPV_QCPASS">SPV_QCPASS</option>
                                <option value="OP_SEGELKEMBALI">OP_SEGELKEMBALI</option>
                                <option value="OP_UNLOADING">OP_UNLOADING</option>
                                <option value="OP_LOADING">OP_LOADING</option>
                                <option value="OP_QCLOADING">OP_QCLOADING</option>

                                <option value="OP_TIMBANG">OP_TIMBANG</option>
                                <option value="OP_REG">OP_REG</option>
                                <option value="OP_LOCIS">OP_LOCIS</option>
                                <option value="OP_TANKI">OP_TANKI</option>

                                <option value="SPV">SPV</option>
                                <option value="MANAGEMEN">MANAGEMEN</option>
                            </select>
                            <has-error :form="form" field="level_ext"></has-error>
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
                users : {},
                form: new Form({
                    id : '',
                    login : '',
                    level_ext: '',
                    active: '',
                    password_ext: ''
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('api/usererp?page=' + page).then(({ data }) => (this.users = data.data));

                  this.$Progress.finish();
            },
            updateUser(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/usererp/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadUsers();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/usererp/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadUsers();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
          loadUsers(){
            this.$Progress.start();

            if(this.$gate.isAdmin()){
              axios.get("api/usererp").then(({ data }) => (this.users = data.data));
            }

            this.$Progress.finish();
          },
          
          createUser(){

              this.form.post('api/usererp')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.loadUsers();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          }

        },
        mounted() {
            console.log('User Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadUsers();
            this.$Progress.finish();
        }
    }
</script>
