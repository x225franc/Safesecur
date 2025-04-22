/**
 * Utilitaire de conversion d'images en PNG
 * Ce module exporte une fonction pour convertir différents formats d'images en PNG
 */

/**
 * Convertit un fichier image en format PNG
 * @param {File} file - Le fichier image à convertir
 * @returns {Promise<File>} - Promise résolue avec le fichier converti au format PNG
 */
export function convertImageToPNG(file) {
	return new Promise((resolve, reject) => {
		// Création d'un FileReader pour lire le contenu du fichier
		const reader = new FileReader();

		reader.onload = function (event) {
			// Création d'une image à partir des données du FileReader
			const img = new Image();

			img.onload = function () {
				// Création d'un canvas pour dessiner l'image
				const canvas = document.createElement("canvas");
				canvas.width = img.width;
				canvas.height = img.height;
				const ctx = canvas.getContext("2d");

				// Dessiner l'image sur le canvas
				ctx.drawImage(img, 0, 0);

				// Obtenir les données de l'image au format PNG
				const dataURL = canvas.toDataURL("image/png");

				// Convertir le dataURL en Blob
				fetch(dataURL)
					.then((res) => res.blob())
					.then((blob) => {
						// Créer un nouveau fichier au format PNG
						const pngFile = new File(
							[blob],
							file.name.replace(/\.[^/.]+$/, "") + ".png",
							{ type: "image/png" }
						);
						resolve(pngFile);
					})
					.catch((error) => reject(error));
			};

			// En cas d'erreur lors du chargement de l'image
			img.onerror = function () {
				reject(new Error("Erreur lors du chargement de l'image"));
			};

			// Définir la source de l'image à partir des données lues
			img.src = event.target.result;
		};

		// En cas d'erreur lors de la lecture du fichier
		reader.onerror = (error) => reject(error);

		// Lire le fichier en tant que Data URL
		reader.readAsDataURL(file);
	});
}

/**
 * Vérifie si le fichier est déjà au format PNG
 * @param {File} file - Le fichier à vérifier
 * @returns {boolean} - true si le fichier est déjà au format PNG
 */
export function isAlreadyPNG(file) {
	return file.type === "image/png";
}
