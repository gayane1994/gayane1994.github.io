<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Forms Table</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Form Name</th>
                                <th>View Data</th>
                            </tr>
                            <tr v-for="form in forms.data" :key="form.id">
                                <td>{{form.form_name}}</td>
                                <td>
                                    <button class="btn btn-success" @click="viewModal(file)">View</button>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">View File Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" @submit.prevent="!editmode ? createData($event) : ''">
<!--                    <form @submit.prevent="!editmode ? createData() : ''">-->
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Form Name</label>
                                <input v-model="form.form_name" type="text" name="form_name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('form_name') }">
                                <has-error :form="form" field="form_name"></has-error>
                            </div>
                            <div class="box-body box-self-evaluation" v-show="!editmode">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in rows">
                                        <td>
                                            <input type="text" name="file_names[]" v-model="row.name" placeholder="  Name"/>
                                        </td>
                                        <td>
                                            <div class="button success expand radius">
                                                <label class="custom-file-upload">
                                                    <input type="file" class="images[]" accept="image/*" @change="previewImage(index, $event)">
                                                </label>
                                            </div>
                                            <div :class="'images[' + index + ']-preview image-preview'"></div>
                                        </td>


<!--                                        <td>-->
<!--&lt;!&ndash;                                            <div class="file-input">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <label for="file">Select a File</label>&ndash;&gt;-->
<!--                                            <input type="file" ref="file" />-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            <input type="file" class="form-control" v-on:change="onImageChange">&ndash;&gt;-->
<!--                                        </td>-->
<!--                                        <td><input v-model="row.job" type="text" name="file_name" placeholder="  File"></td>-->
<!--                                        <td><a @click="removeRow(index)"> <i class="fa fa-trash red"></i></a></td>-->
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group" v-show="!editmode">
                                <div class="btn btn-success btn-create" @click="addRow()" v-show="rows.length < 10" >Add File <i class="fas fa-plus fa-fw"></i></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editmode" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!editmode" type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                editmode: false,
                forms : {},
                rows: [],
                file: '',
                images: [],
                image : [],
                form : new Form({
                    id :'',
                    form_name : '',
                    file_name : '',
                }),
            }
        },
        methods: {
            addRow(){
                this.rows.push({});
            },
            removeRow(index){
                this.rows.splice(index,1);
            },
            viewModal(file){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(file);
            },
            previewImage: function(index, e) {
                var r = new FileReader(),
                    f = e.target.files[0];

                console.log(f);
                r.addEventListener('load', function() {
                    $('[class~="images[' + index + ']-preview"]', this.el).html(
                        '<img src="' + r.result + '" width="50" class="thumbnail img-responsive">'
                    );
                }, false);

                if (f) {
                    r.readAsDataURL(f);
                }
            },

            // onInputChange(e) {
            //     let files = e.target.files || e.dataTransfer.files;
            //     if (!files.length)
            //         return;
            //     this.createImage(files[0]);
            // },
            // createImage(file) {
            //     let reader = new FileReader();
            //     let vm = this;
            //     reader.onload = (e) => {
            //         vm.image = e.target.result;
            //     };
            //     reader.readAsDataURL(file);
            // },
            // onInputChange(e) {
            //     const files = e.target.files;
            //     Array.from(files).forEach(file => this.addImage(file));
            // },
            // addImage(file) {
            //     if (!file.type.match('image.*')) {
            //         this.$toastr.e(`${file.name} is not an image`);
            //         return;
            //     }
            //     this.files.push(file);
            //     const img = new Image(),
            //         reader = new FileReader();
            //     reader.onload = (e) => this.images.push(e.target.result);
            //     reader.readAsDataURL(file);
            // },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            loadDatas(){
                axios.get("api/file").then(({ data }) => (this.forms = data));
            },
            createData(e) {
                // this.file = this.$refs.file.files[0];
                // console.log(this.file);
                var vm = this;
                var data = new FormData(e.target);

                $('[class~="images[]"]', this.el).each(function(i) {
                    if (i > vm.maxImages - 1) {
                        return; // Max images reached.
                    }

                    data.append('images[' + i + ']', this.files[0]);
                });
               //  console.log(this.image);
               // // this.file = this.$refs.file.files[0];
               //   const formData = new FormData();
               //  //
               //   this.rows.forEach(row => {
               //  //     // this.files.forEach(file => {
               //  //     //     this.image.append('images[]', file, file.name);
               //  //     //     });
               //       formData.append(row.name, this.image)
               //  //     //this.$set(this.images, row.name, '')
               //   });
                // const formData = new FormData();
                // this.files.forEach(file => {
                //     formData.append('images[]', file, file.name);
                // });

                 axios.post('api/file', data)
                //     .then(response => {
                //         //console.log(response);
                //     })
                // console.log(c);
                // console.log(this.form);
                // this.form.post('api/file')
                //     .then(() => {
                //         // Fire.$emit('AfterCreate');
                //         // $('#addNew').modal('hide');
                //         // toast({
                //         //     type: 'success',
                //         //     title: 'File Created in successfully'
                //         // })
                //     })
                     .catch(() => {

                     })
            }
        },
        created() {
            this.loadDatas();
            Fire.$on('AfterCreate',() => {
                this.loadDatas();
            });
        }
    }
</script>
