import pt from "https://cdn.jsdelivr.net/npm/vuetify@2.5.8/lib/locale/pt.js";

new Vue({
  el: "#app",

  vuetify: new Vuetify({
    lang: {
      locales: {
        pt,
      },
      current: "pt",
    },
  }),

  data() {
    return {
      products: [],
      productModal: null,
      loading: true,
      tags: [],
      autocompleteText: "",
      dialogDeleteProduct: false,
    };
  },

  methods: {
    async getProductsApi() {
      await baseFetch("api/products").then((res) => {
        this.products = res.data.products;
        this.tags = res.data.tags;
      });

      this.loading = false;
    },

    async saveProduct() {
      if (!this.$refs["productForm"].validate()) {
        return;
      }

      this.loading = true;

      let tags_add = [],
        tags_rm = [],
        product;

      if (this.productModal.id > 0) {
        product = this.products.find(
          (product) => product.id === this.productModal.id
        );

        tags_add = this.productModal.tags.filter(
          (tag) => !product.tags.includes(tag)
        );
        tags_rm = product.tags.filter(
          (tag) => !this.productModal.tags.includes(tag)
        );
      } else {
        tags_add = this.productModal.tags;
      }

      await baseFetch("api/products", {
        method: "POST",
        body: JSON.stringify({
          id: this.productModal.id,
          name: this.productModal.name,
          tags_add,
          tags_rm,
        }),
      });

      await this.getProductsApi();
      this.resetModal();
    },

    resetModal() {
      this.productModal = {
        id: null,
        name: null,
        tags: [],
      };
    },

    openModal(item) {
      this.productModal = JSON.parse(JSON.stringify(item));
    },

    async deleteProduct() {
      this.loading = true;
      await baseFetch("api/products/" + this.productModal.id, {
        method: "DELETE",
      });

      await this.getProductsApi();
      this.resetModal();
      this.dialogDeleteProduct = false;
    },
  },

  created() {
    this.resetModal();
  },

  mounted() {
    this.getProductsApi();
  },

  watch: {
    "productModal.id": {
      handler(newV) {
        if (!newV) {
          this.$refs["productForm"].resetValidation();
        }
      },
    },
  },
});
