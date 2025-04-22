import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import Services from '@/views/Services.vue';
import Category from '@/views/Catalogue/Category.vue';
import Catalogue from '@/views/Catalogue/Catalogue.vue';
import createCatalogue from '@/views/Catalogue/createCatalogue.vue';
import editCatalogue from '@/views/Catalogue/editCatalogue.vue';
import overviewCatalogue from '@/views/Catalogue/overviewCatalogue.vue';
import Actualites from '@/views/Actualites/Actualites.vue';
import createActualites from '@/views/Actualites/createActualites.vue';
import editActualites from '@/views/Actualites/editActualites.vue';
import viewActualites from '@/views/Actualites/viewActualites.vue';
import Contacts from '@/views/Contacts.vue';
import Login from '@/views/Login.vue';
import Logout from '@/views/Logout.vue';

const routes = [
    { path: '/', name: 'Home', component: Home, meta: { title: 'Accueil - Safesecur', showHeaderFooter: true } },
    { path: '/services', name: 'Sercices', component: Services, meta: { title: 'Services - Safesecur', showHeaderFooter: true } },
    { path: '/category', name: 'Category', component: Category, meta: { title: 'Catégorie - Safesecur', showHeaderFooter: true } },
    { path: '/catalogue', name: 'Catalogue', component: Catalogue, meta: { title: 'Catalogue - Safesecur', showHeaderFooter: true } },
    { path: '/catalogue/:id', name: 'OverviewCatalogue', component: overviewCatalogue, meta: { title: 'Détails du produit - Safesecur', showHeaderFooter: true } },
    { path: '/create-catalogue', name: 'CreateCatalogue', component: createCatalogue, meta: { title: 'Créer un produit - Safesecur', showHeaderFooter: true } },
    { path: '/edit-catalogue/:id', name: 'EditCatalogue', component: editCatalogue, meta: { title: 'Modifier un produit - Safesecur', showHeaderFooter: true } },
    { path: '/actualites', name: 'Actualites', component: Actualites, meta: { title: 'Actualités - Safesecur', showHeaderFooter: true } },
    { path: '/create-actualites', name: 'CreateActualites', component: createActualites, meta: { title: 'Créer une actualité - Safesecur', showHeaderFooter: true } },
    { path: '/edit-actualites/:id', name: 'EditActualites', component: editActualites, meta: { title: 'Modifier une actualité - Safesecur', showHeaderFooter: true } },
    { path: '/actualites/:id', name: 'ViewActualites', component: viewActualites, meta: { title: 'Voir une actualité - Safesecur', showHeaderFooter: true } },
    { path: '/contacts', name: 'Contacts', component: Contacts, meta: { title: 'Contacts - Safesecur', showHeaderFooter: true } },
    { path: '/login', name: 'Login', component: Login, meta: { title: 'Login - Safesecur', showHeaderFooter: false } },
    { path: '/logout', name: 'Logout', component: Logout, meta: { title: 'Déconnexion - Safesecur', showHeaderFooter: false } },
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: () => import('@/views/Notfound.vue'), meta: { title: '404 - Page non trouvée', showHeaderFooter: false } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;