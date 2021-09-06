<template>
    <div id="editProduct" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        <form @submit.prevent="editProduct">
        <div class="modal-header">
            <h4 class="modal-title">Edit Product</h4>
        </div>
            <div class="modal-body" v-if="show == true">
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
                    <input type="file" ref="image" class="form-control-file" v-on:change="handleImageUpload()">
                </div>

                <error 
                    v-if="error != undefined"
                    :data="error"
                /> 

                <img :src="image_url(product)" class="image" />
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" @click="$emit('editProductModalClosed')">Close</button>
            <button type="submit" class="btn btn-success">
                <span v-if="!is_editing">Edit</span>
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
            },
            product: {
                type: Object,
            }
        },
        data() {
            return {
                name: '',
                manufactured_at: undefined,
                image: '',
                is_editing: false,
                error: undefined
            }
        },
        watch: {
            show(show) {
                if(show) {
                    this.name = this.product?.name;
                    this.manufactured_at = this.product?.manufactured_at;
                    $("#editProduct").modal();
                }
            },
        },
        methods: {
            async editProduct() {
                this.is_editing = true;
                this.error = undefined;
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('manufactured_at', this.manufactured_at);
                formData.append('image', this.image);
                formData.append('_method', 'PUT');
                try {
                    await axios.post(`/api/products/${this.product.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } catch (error) {
                    this.error = error.response.data;
                    this.is_editing = false;
                    return;
                }
                this.$emit('productEdited');
                $("#editProduct").modal('hide');
                this.is_editing = false;
                this.name = '';
                this.manufactured_at = undefined;
            },
            handleImageUpload() {
                this.image = this.$refs.image.files[0];
            },
            image_url(product) {
                return `/api/products/${product.id}/image?${Math.ceil(Math.random() * 10000)}`;
            }
        },
        components: {Error}
    }
</script>

<style scoped>
.image {
    width: 100%;
    height: auto;
}
</style>
