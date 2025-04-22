import { createApp } from "vue";
import App from "./App.vue";
import router from './router';

// Import Bootstrap CSS
import "bootstrap/dist/css/bootstrap.min.css";
// Import Bootstrap JS
import "bootstrap/dist/js/bootstrap.bundle.min.js";
// Import Style CSS
import './assets/styles.css';

const app = createApp(App);

// Met à jour dynamiquement le titre de la page
router.beforeEach((to, from, next) => {
    document.title = to.meta.title || 'safesecur'; // Définit un titre par défaut si aucune meta n'est définie
    next();
});

app.use(router);
app.mount("#app");
