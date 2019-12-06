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
                                    <button class="btn btn-success" @click="viewModal(form)">View</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editMode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editMode" id="addNewLabel">View Form Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" @submit.prevent="!editMode ? createData($event) : ''">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Form Name</label>
                                <input v-model="form.form_name" type="text" name="form_name" placeholder="Name" class="form-control">
                            </div>
                            <div id="upload_files" v-show="editMode">
                                <p v-for="item in files">
                                    <img :src="'images/uploads/' + item.file" :title="item.file_name" width="120" height="120">
                                </p>
                            </div>
                            <div class="input-group input-group_table" v-show="!editMode">
                                <table class="table">
                                    <thead>
                                    <tr v-if="rows.length > 0">
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
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group" v-show="!editMode">
                                <div class="btn btn-success btn-create" @click="addRow()" v-show="rows.length < 10">Add File <i class="fas fa-plus fa-fw"></i></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editMode" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!editMode" type="submit" class="btn btn-success">Save</button>
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
                editMode : false,
                forms    : {},
                files    : {},
                rows     : [],
                form     : new Form({
                    id        : '',
                    form_name : '',
                    file      : '',
                }),
            }
        },
        methods: {
            addRow() {
                this.rows.push({});
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            },
            viewModal(form) {
                this.files    = JSON.parse(form.file);
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(form);
            },
            previewImage: function (index, event) {
                var reader      = new FileReader(),
                    previewFile = event.target.files[0];

                reader.addEventListener('load', function () {
                    $('[class~="images[' + index + ']-preview"]', this.el).html(
                        '<img src="' + reader.result + '" width="50" class="thumbnail img-responsive">'
                    );
                }, false);

                if (previewFile) {
                    reader.readAsDataURL(previewFile);
                }
            },
            newModal() {
                this.editMode = false;
                this.rows     = [];

                this.form.reset();
                $('#addNew').modal('show');
            },
            loadDatas() {
                axios.get('api/file').then(({data}) => (
                    this.forms = data
                ));
            },
            createData(e) {
                var self = this;
                var data = new FormData(e.target);

                $('[class~="images[]"]', this.el).each(function (i) {
                    if (i > self.maxImages - 1) {
                        return;
                    }

                    data.append('images[' + i + ']', this.files[0]);
                });

                axios.post('api/file', data).then(() => {
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');
                    toast({
                        type  : 'success',
                        title : 'Form Created in successfully'
                    })
                }).catch(() => {

                });
            },
        },
        created() {
            this.loadDatas();

            Fire.$on('AfterCreate', () => {
                this.loadDatas();
            });
        }
    }
</script>
