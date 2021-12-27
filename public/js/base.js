import pt from "https://cdn.jsdelivr.net/npm/vuetify@2.5.8/lib/locale/pt.js";
new Vue({
  el: "#app-base",
  vuetify: new Vuetify({
    lang: {
      locales: {
        pt,
      },
      current: "pt",
    },
  }),
});
