<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>

                    <div class="card-body">
                        <button class="btn btn-primary mb-4" @click="() => modals.add.show = true">Add Product <i class="fas fa-plus"></i></button>
                        <h3 v-if="products==undefined">Loading...</h3>
                        <h3 v-else-if="products.length==0">You don't have products, please add one!</h3>
                        <table class="table table-hover" v-else>
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Manufactured</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in products" v-bind:key="product.id">
                                <td>{{product.name}}</td>
                                <td>{{product.manufactured_at}}</td>
                                <td>
                                    <button v-if="!loading" class="btn btn-success" @click="() => showViewProductModal(product)">Show</button>
                                    <button v-if="!loading" class="btn btn-warning text-light" @click="() => showEditProductModal(product)">Edit</button>
                                    <button v-if="!loading" class="btn btn-danger" @click="() => deleteProduct(product.id)">
                                        <span v-if="!product.is_deleting">Delete</span>
                                        <span v-else class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <add-product-modal 
            :show="modals.add.show"
            @addProductModalClosed="addProductModalClosed"
            @productAdded="productAdded"
        />
        <view-product-modal 
            :show="modals.view.show"
            @viewProductModalClosed="viewProductModalClosed"
            :product="product"
        />
        <edit-product-modal
            :show="modals.edit.show"
            @editProductModalClosed="editProductModalClosed"
            @productEdited="productEdited"
            :product="product"
        />
    </div>
</template>

<script>
    import AddProductModal from './AddProductModal';
    import ViewProductModal from './ViewProductModal';
    import EditProductModal from './EditProductModal';
    export default {
        data() {
            return {
                products: undefined,
                product: undefined,
                modals: {
                    add: {
                        show: false,
                    },
                    view: {
                        show: false,
                    },
                    edit: {
                        show: false,
                    }
                },
                loading: false,
            }
        },
        async created() {
            this.refreshProducts();
        },
        methods: {
            async refreshProducts() {
                this.loading = true;
                this.products = (await axios.get('/api/products')).data.map(product => ({
                    ...product,
                    is_deleting: false
                }));
                this.loading = false;
            },
            addProductModalClosed() {
                this.modals.add.show = false;
            },
            viewProductModalClosed() {
                this.modals.add.show = false;
            },
            editProductModalClosed() {
                this.modals.edit.show = false;
            },
            productAdded() {
                this.refreshProducts();
                this.modals.add.show = false;
            },
            productEdited() {
                this.refreshProducts();
                this.modals.edit.show = false;
            },
            async deleteProduct(id) {
                this.products.find(product => product.id === id).is_deleting = true;
                await axios.delete('/api/products/' + id);
                this.refreshProducts();
            },
            showViewProductModal(product) {
                this.product = product;
                this.modals.view.show = true;
            },
            showEditProductModal(product) {
                this.product = product;
                this.modals.edit.show = true;
            }
        },
        components: {AddProductModal, ViewProductModal, EditProductModal}
    }
</script>
