import { describe, it, expect } from 'vitest';
import router from '../../src/router/index.js';

describe('Configuration Vue Router', () => {
    it('devrait avoir le bon nombre de routes', () => {
        expect(router.getRoutes()).toHaveLength(16);
    });

    it('devrait avoir la route home configurée', () => {
        const homeRoute = router.getRoutes().find(r => r.path === '/');
        expect(homeRoute).toBeDefined();
        expect(homeRoute.name).toBe('Home');
        expect(homeRoute.meta.showHeaderFooter).toBe(true);
    });

    it('devrait avoir la route login sans header/footer', () => {
        const loginRoute = router.getRoutes().find(r => r.path === '/login');
        expect(loginRoute).toBeDefined();
        expect(loginRoute.name).toBe('Login');
        expect(loginRoute.meta.showHeaderFooter).toBe(false);
    });

    it('devrait avoir la route signup configurée', () => {
        const signupRoute = router.getRoutes().find(r => r.path === '/signup');
        expect(signupRoute).toBeDefined();
        expect(signupRoute.name).toBe('Signup');
        expect(signupRoute.meta.title).toBe('Inscription - Safesecur');
    });

    it('devrait avoir la route dynamique catalogue', () => {
        const catalogueRoute = router.getRoutes().find(r => r.path === '/catalogue/:id');
        expect(catalogueRoute).toBeDefined();
        expect(catalogueRoute.name).toBe('OverviewCatalogue');
    });

    it('devrait avoir la route catch-all 404', () => {
        const notFoundRoute = router.getRoutes().find(r => r.path === '/:pathMatch(.*)*');
        expect(notFoundRoute).toBeDefined();
        expect(notFoundRoute.name).toBe('NotFound');
    });

    it('devrait utiliser le mode history', () => {
        expect(router.options.history.base).toBeDefined();
    });
});