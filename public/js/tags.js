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
      productModal: null,
      loading: true,
      tags: [],
      dialogDeleteProduct: false,
    };
  },

  methods: {
    async getProductsApi() {
      await baseFetch("api/tags").then((res) => {
        this.tags = res.data.tags;
      });

      this.loading = false;
    },

    async saveProduct() {
      if (!this.$refs["productForm"].validate()) {
        return;
      }

      this.loading = true;

      await baseFetch("api/tags", {
        method: "POST",
        body: JSON.stringify({
          id: this.productModal.id,
          name: this.productModal.name,
        }),
      });

      await this.getProductsApi();
      this.resetModal();
    },

    resetModal() {
      this.productModal = {
        id: null,
        name: null,
      };
    },

    openModal(item) {
      this.productModal = JSON.parse(JSON.stringify(item));
    },

    async deleteProduct() {
      this.loading = true;
      await baseFetch("api/tags/" + this.productModal.id, {
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
