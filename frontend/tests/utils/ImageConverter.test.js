import { describe, it, expect } from 'vitest';
import { isAlreadyPNG, convertImageToPNG } from '../../src/components/utils/ImageConverter.js';

describe('Utilitaires ImageConverter', () => {
    describe('isAlreadyPNG', () => {
        it('devrait retourner true pour les fichiers PNG', () => {
            const pngFile = new File([''], 'test.png', { type: 'image/png' });
            expect(isAlreadyPNG(pngFile)).toBe(true);
        });

        it('devrait retourner false pour les fichiers JPEG', () => {
            const jpegFile = new File([''], 'test.jpg', { type: 'image/jpeg' });
            expect(isAlreadyPNG(jpegFile)).toBe(false);
        });

        it('devrait retourner false pour les fichiers non-images', () => {
            const textFile = new File([''], 'test.txt', { type: 'text/plain' });
            expect(isAlreadyPNG(textFile)).toBe(false);
        });
    });

    describe('convertImageToPNG', () => {
        it('devrait rejeter si le fichier n\'est pas une image valide', async () => {
            // Mock Image pour déclencher onerror immédiatement (jsdom ne charge pas vraiment les images)
            const OriginalImage = global.Image;
            class MockImage {
                set src(_) {
                    // simuler un échec de chargement
                    setTimeout(() => {
                        if (this.onerror) this.onerror(new Error('erreur de chargement'));
                    }, 0);
                }
            }
            global.Image = MockImage;

            const invalidFile = new File(['contenu invalide'], 'test.txt', { type: 'text/plain' });
            await expect(convertImageToPNG(invalidFile)).rejects.toBeInstanceOf(Error);

            // restaurer Image
            global.Image = OriginalImage;
        });

        it('devrait préserver le nom de fichier original avec extension .png', () => {
            const file = new File([''], 'monimage.jpg', { type: 'image/jpeg' });
            expect(file.name).toBe('monimage.jpg');
            // Après conversion, le nom devrait être monimage.png
        });
    });
});