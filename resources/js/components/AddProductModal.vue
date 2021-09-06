<template>
    <div id="addProducts" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        <form @submit.prevent="addProduct">
        <div class="modal-header">
            <h4 class="modal-title">Add Product</h4>
        </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" v-model="name" required>
                </div>

                <div class="form-group">
                    <label>Manufactured:</label>
                    <input type="number" min="1901" max="2021" class="form-control" v-model="manufactured_at" required>
                </div>

                <div class="form-group">
                    <label>Image:</label>
                    <input type="file" ref="image" class="form-control-file" v-on:change="handleImageUpload()" required>
                </div>
                <error 
                    v-if="error != undefined"
                    :data="error"
                /> 
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" @click="addProductModalClosed">Close</button>
            <button type="submit" class="btn btn-success">
                <span v-if="adding===false">Add</span>
                <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        </div>
        </form>
        </div>

    </div>
    </div>
</template>

<script>
import Error from './Error.vue';
    export default {
        props: {
            show: {
                type: Boolean,
            }
        },
        data() {
            return {
                name: '',
                manufactured_at: undefined,
                image: '',
                adding: false,
                error: undefined
            }
        },
        watch: {
            show(show) {
                if(show)$("#addProducts").modal()
            }
        },
        methods: {
            addProductModalClosed() {
                this.$refs.image.value = null;
                this.$emit('addProductModalClosed');
            },
            async addProduct() {
                if(this.adding) return;
                this.error = undefined;
                this.adding = true;
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('name', this.name);
                formData.append('manufactured_at', this.manufactured_at);
                try {
                    await axios.post('/api/products', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } catch (error) {
                    this.error = error.response.data;
                    this.adding = false;
                    return;
                }
                this.$emit('productAdded');
                $("#addProducts").modal('hide');
                this.adding = false;
                this.name = '';
                this.manufactured_at = undefined;
                this.$refs.image.value = null;
            },
            handleImageUpload() {
                this.image = this.$refs.image.files[0];
            }
        },
        components: {Error}
    }
</script>
