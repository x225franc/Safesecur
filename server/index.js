const express = require("express");
const mysql = require("mysql");
const cors = require("cors");
const bcrypt = require("bcrypt");
const nodemailer = require("nodemailer");

// importation de dotenv
const dotenv = require("dotenv");
const { join } = require("path");
const jwt = require("jsonwebtoken");

// configuration de dotenv
dotenv.config({ path: join(process.cwd(), "./.env") });
const JWT_SECRET = process.env.JWT_SECRET;

// importation de middleware pour les images
const multer = require("multer");
const path = require("path");
const sharp = require("sharp");
const fs = require("fs");
const { v4: uuidv4 } = require("uuid");

// logo
const logo = "https://safesecur.com/logo.png"; // URL par défaut pour la production

const app = express();
app.use(cors());
app.use(express.json());
app.use(express.static("public"));

// Ajouter cette route pour servir le logo depuis le dossier public
app.get("/logo.png", (req, res) => {
	res.sendFile(path.join(__dirname, "public", "logo.png"));
});

// console.log("SERVER_URL:", process.env.SERVER_URL);
// console.log("Chemin du logo:", logo);

// Configuration de multer pour le stockage des fichiers
const storage = multer.diskStorage({
	destination: (req, file, cb) => {
		// Création du dossier uploads s'il n'existe pas
		const uploadDir = "./uploads/images/actualites";
		if (!fs.existsSync("./uploads")) {
			fs.mkdirSync("./uploads");
		}
		if (!fs.existsSync("./uploads/images")) {
			fs.mkdirSync("./uploads/images");
		}
		if (!fs.existsSync(uploadDir)) {
			fs.mkdirSync(uploadDir);
		}
		cb(null, uploadDir);
	},
	filename: (req, file, cb) => {
		// Génération d'un nom de fichier unique avec uuid
		const uniqueSuffix = uuidv4();
		cb(null, `actualite-${uniqueSuffix}${path.extname(file.originalname)}`);
	},
});

// Configuration de multer pour les images de produits
const catalogueStorage = multer.diskStorage({
	destination: (req, file, cb) => {
		// Création du dossier uploads/images/catalogue s'il n'existe pas
		const uploadDir = "./uploads/images/catalogue";
		if (!fs.existsSync("./uploads")) {
			fs.mkdirSync("./uploads");
		}
		if (!fs.existsSync("./uploads/images")) {
			fs.mkdirSync("./uploads/images");
		}
		if (!fs.existsSync(uploadDir)) {
			fs.mkdirSync(uploadDir);
		}
		cb(null, uploadDir);
	},
	filename: (req, file, cb) => {
		// Génération d'un nom de fichier unique avec uuid
		const uniqueSuffix = uuidv4();
		cb(null, `product-${uniqueSuffix}${path.extname(file.originalname)}`);
	},
});

const fileFilter = (req, file, cb) => {
	if (file.mimetype.startsWith("image/")) {
		cb(null, true);
	} else {
		cb(new Error("Seules les images sont acceptées."), false);
	}
};

const upload = multer({
	storage,
	fileFilter,
	limits: {
		fileSize: 5 * 1024 * 1024, // Limite à 5MB
	},
});

const uploadCatalogue = multer({
	storage: catalogueStorage,
	fileFilter,
	limits: {
		fileSize: 5 * 1024 * 1024, // Limite à 5MB
	},
});

// Middleware pour servir les fichiers statiques
app.use("/images", express.static("uploads/images"));

// MySQL Connection
const db = mysql.createConnection({
	host: process.env.HOST,
	user: process.env.USER,
	password: process.env.PASSWORD,
	database: process.env.DATABASE,
});

// creation du transporter pour envoyer des mails
const transporter = nodemailer.createTransport({
	host: process.env.MAIL_HOST,
	port: process.env.MAIL_PORT,
	secure: false, // use STARTTLS
	requireTLS: true, // force upgrade to secure connection
	auth: {
		user: process.env.MAIL_USER,
		pass: process.env.MAIL_PASSWORD,
	},
});

// Vérifier la configuration du transporteur
// transporter.verify((error, success) => {
// 	if (error) {
// 		console.error("Erreur de configuration SMTP:", error);
// 	} else {
// 		console.log("Serveur prêt à envoyer des emails");
// 	}
// });

db.connect((err) => {
	if (err) {
		console.error("Error connecting to MySQL:", err);
		return;
	}
	console.log("Connected to MySQL database.");
});

// Fonction à exécuter au démarrage du serveur
function ensureDirectoriesExist() {
	const dirs = ["./uploads", "./uploads/images", "./uploads/images/actualites"];

	dirs.forEach((dir) => {
		if (!fs.existsSync(dir)) {
			try {
				fs.mkdirSync(dir, { recursive: true });
				console.log(`Dossier créé: ${dir}`);
			} catch (error) {
				console.error(`Erreur lors de la création du dossier ${dir}:`, error);
			}
		}
	});
}

// Appeler cette fonction avant de démarrer le serveur
ensureDirectoriesExist();

// Routes d'authentification
// Route de connexion - génère un token JWT
app.post("/login", (req, res) => {
	const { username, password } = req.body;

	// Trouver l'utilisateur dans la base de données
	const query = "SELECT * FROM user WHERE username = ?";
	db.query(query, [username], async (err, results) => {
		if (err) {
			console.error(err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur du serveur" });
		}

		// Vérifier si l'utilisateur existe
		if (results.length === 0) {
			return res
				.status(401)
				.json({ success: false, message: "Identifiants incorrects" });
		}

		const user = results[0];

		// Comparer le mot de passe
		try {
			const match = await bcrypt.compare(password, user.password);
			if (!match) {
				return res
					.status(401)
					.json({ success: false, message: "Identifiants incorrects" });
			}

			// Générer un token JWT
			const token = jwt.sign(
				{ userId: user.id, username: user.username },
				JWT_SECRET,
				{ expiresIn: "24h" }
			);

			return res.status(200).json({
				success: true,
				token,
				user: { id: user.id, username: user.username },
			});
		} catch (error) {
			console.error(error);
			return res
				.status(500)
				.json({ success: false, message: "Erreur du serveur" });
		}
	});
});

// Middleware pour vérifier le token JWT
const verifyToken = (req, res, next) => {
	const authHeader = req.headers.authorization;

	if (!authHeader || !authHeader.startsWith("Bearer ")) {
		return res
			.status(401)
			.json({ authenticated: false, message: "Non autorisé: token manquant" });
	}

	const token = authHeader.split(" ")[1];

	try {
		const decoded = jwt.verify(token, JWT_SECRET);
		req.user = decoded;
		next();
	} catch (error) {
		return res
			.status(401)
			.json({ authenticated: false, message: "Non autorisé: token invalide" });
	}
};

// Route pour vérifier si l'utilisateur est authentifié
app.get("/verify-auth", verifyToken, (req, res) => {
	return res.status(200).json({
		authenticated: true,
		user: { id: req.user.userId, username: req.user.username },
	});
});

// Déconnexion
app.post("/logout", (req, res) => {
	res.clearCookie("auth_session");
	return res.status(200).json({ success: true });
});

// CRUD for Category
// Route pour récupérer toutes les catégories avec le nombre de produits associés
app.get("/categories", verifyToken, (req, res) => {
	const query = `
    SELECT c.*, (SELECT COUNT(*) FROM catalogue WHERE category_id = c.id) as productCount
    FROM category c
    ORDER BY c.id ASC`;

	db.query(query, (err, results) => {
		if (err) {
			console.error("Erreur lors de la récupération des catégories:", err);
			return res.status(500).json({
				success: false,
				message: "Erreur lors de la récupération des catégories",
			});
		}

		res.json(results);
	});
});

// Route pour créer une catégorie
app.post("/categories", verifyToken, (req, res) => {
	const { name } = req.body;

	if (!name || name.trim() === "") {
		return res
			.status(400)
			.json({ success: false, message: "Le nom de la catégorie est requis" });
	}

	// Vérifier si une catégorie avec ce nom existe déjà
	const checkQuery = "SELECT id FROM category WHERE name = ?";
	db.query(checkQuery, [name], (err, results) => {
		if (err) {
			console.error("Erreur lors de la vérification de la catégorie:", err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		if (results.length > 0) {
			return res.status(409).json({
				success: false,
				message: "Une catégorie avec ce nom existe déjà",
			});
		}

		// Créer la catégorie
		const insertQuery = "INSERT INTO category (name) VALUES (?)";
		db.query(insertQuery, [name], (err, results) => {
			if (err) {
				console.error("Erreur lors de la création de la catégorie:", err);
				return res
					.status(500)
					.json({ success: false, message: "Erreur serveur" });
			}

			res.status(201).json({
				success: true,
				id: results.insertId,
				name,
				message: "Catégorie créée avec succès",
			});
		});
	});
});

// Route pour mettre à jour une catégorie
app.put("/categories/:id", verifyToken, (req, res) => {
	const { id } = req.params;
	const { name } = req.body;

	if (!name || name.trim() === "") {
		return res
			.status(400)
			.json({ success: false, message: "Le nom de la catégorie est requis" });
	}

	// Vérifier si une autre catégorie avec ce nom existe déjà
	const checkQuery = "SELECT id FROM category WHERE name = ? AND id != ?";
	db.query(checkQuery, [name, id], (err, results) => {
		if (err) {
			console.error("Erreur lors de la vérification de la catégorie:", err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		if (results.length > 0) {
			return res.status(409).json({
				success: false,
				message: "Une catégorie avec ce nom existe déjà",
			});
		}

		// Mettre à jour la catégorie
		const updateQuery = "UPDATE category SET name = ? WHERE id = ?";
		db.query(updateQuery, [name, id], (err, results) => {
			if (err) {
				console.error("Erreur lors de la mise à jour de la catégorie:", err);
				return res
					.status(500)
					.json({ success: false, message: "Erreur serveur" });
			}

			if (results.affectedRows === 0) {
				return res
					.status(404)
					.json({ success: false, message: "Catégorie non trouvée" });
			}

			res.json({
				success: true,
				id: parseInt(id),
				name,
				message: "Catégorie mise à jour avec succès",
			});
		});
	});
});

// Route pour supprimer une catégorie avec suppression en cascade des produits associés
app.delete("/categories/:id", verifyToken, (req, res) => {
	const { id } = req.params;

	// 1. D'abord, récupérez tous les produits associés à cette catégorie pour leurs images
	const getProductsQuery =
		"SELECT id, img FROM catalogue WHERE category_id = ?";

	db.query(getProductsQuery, [id], (err, products) => {
		if (err) {
			console.error("Erreur lors de la récupération des produits:", err);
			return res.status(500).json({
				success: false,
				message: "Erreur serveur lors de la récupération des produits",
			});
		}

		// 2. Supprimer les produits de la base de données
		if (products.length > 0) {
			const deleteProductsQuery = "DELETE FROM catalogue WHERE category_id = ?";

			db.query(deleteProductsQuery, [id], (err, deleteResults) => {
				if (err) {
					console.error("Erreur lors de la suppression des produits:", err);
					return res.status(500).json({
						success: false,
						message: "Erreur serveur lors de la suppression des produits",
					});
				}

				console.log(
					`${deleteResults.affectedRows} produits supprimés pour la catégorie ${id}`
				);

				// 3. Supprimer les images associées aux produits
				products.forEach((product) => {
					if (product.img) {
						// Normalisation du chemin de l'image
						let imagePath;
						const imgPath = product.img;

						console.log(
							`Tentative de suppression de l'image du produit ${product.id}: ${imgPath}`
						);

						// Analyse et normalisation du chemin
						if (imgPath.includes("/images/catalogue/")) {
							imagePath = `uploads${imgPath.replace("/images", "")}`;
						} else if (imgPath.includes("/catalogue/")) {
							imagePath = `uploads/images${imgPath}`;
						} else if (imgPath.startsWith("/")) {
							imagePath = `uploads/images/catalogue${imgPath}`;
						} else {
							imagePath = `uploads/images/catalogue/${imgPath}`;
						}

						// Supprimer l'image
						if (fs.existsSync(imagePath)) {
							try {
								fs.unlinkSync(imagePath);
								console.log(`Image ${imagePath} supprimée avec succès`);
							} catch (unlinkError) {
								console.error(
									`Erreur lors de la suppression de l'image: ${unlinkError}`
								);
								// Ne pas bloquer le processus en cas d'erreur sur une image
							}
						} else {
							// Essayer des chemins alternatifs
							const alternativePaths = [
								`uploads/images/catalogue/${imgPath.split("/").pop()}`,
								`uploads/catalogue/${imgPath.split("/").pop()}`,
								`uploads/${imgPath}`,
							];

							let deleted = false;
							for (const altPath of alternativePaths) {
								if (fs.existsSync(altPath)) {
									try {
										fs.unlinkSync(altPath);
										console.log(
											`Image alternative ${altPath} supprimée avec succès`
										);
										deleted = true;
										break;
									} catch (e) {
										console.error(
											`Erreur avec le chemin alternatif ${altPath}:`,
											e
										);
									}
								}
							}

							if (!deleted) {
								console.log(
									`Impossible de trouver l'image pour le produit ${product.id}`
								);
							}
						}
					}
				});

				// 4. Une fois les produits et images supprimés, supprimer la catégorie
				const deleteCategoryQuery = "DELETE FROM category WHERE id = ?";
				db.query(deleteCategoryQuery, [id], (err, results) => {
					if (err) {
						console.error(
							"Erreur lors de la suppression de la catégorie:",
							err
						);
						return res.status(500).json({
							success: false,
							message: "Erreur serveur lors de la suppression de la catégorie",
						});
					}

					if (results.affectedRows === 0) {
						return res.status(404).json({
							success: false,
							message: "Catégorie non trouvée",
						});
					}

					res.json({
						success: true,
						message: `Catégorie supprimée avec succès, y compris ${products.length} produits associés`,
					});
				});
			});
		} else {
			// Pas de produits associés, supprimer directement la catégorie
			const deleteCategoryQuery = "DELETE FROM category WHERE id = ?";
			db.query(deleteCategoryQuery, [id], (err, results) => {
				if (err) {
					console.error("Erreur lors de la suppression de la catégorie:", err);
					return res.status(500).json({
						success: false,
						message: "Erreur serveur lors de la suppression de la catégorie",
					});
				}

				if (results.affectedRows === 0) {
					return res.status(404).json({
						success: false,
						message: "Catégorie non trouvée",
					});
				}

				res.json({
					success: true,
					message: "Catégorie supprimée avec succès (aucun produit associé)",
				});
			});
		}
	});
});

// CRUD for Catalogue
// recuperer les articles du catalogue
app.get("/catalogue", (req, res) => {
	const query = "SELECT * FROM catalogue";
	db.query(query, (err, results) => {
		if (err) {
			console.error(err);
			res.status(500).send("Error fetching catalogue");
		} else {
			res.json(results);
		}
	});
});

// Route pour récupérer toutes les catégories avec leurs produits
app.get("/catalogue/categories", (req, res) => {
	const query = `
    SELECT c.id,c.name,p.id as product_id,p.name as product_name,p.description,p.price,p.img,p.category_id
    FROM category c
    LEFT JOIN catalogue p ON c.id = p.category_id
    ORDER BY p.id DESC`;

	db.query(query, (err, results) => {
		if (err) {
			console.error("Erreur lors du chargement du catalogue:", err);
			return res.status(500).send("Erreur lors du chargement du catalogue");
		}

		// Transformer les résultats en structure hiérarchique
		const categories = [];
		const categoriesMap = {};

		// Première passe: créer les catégories
		results.forEach((row) => {
			if (!categoriesMap[row.id]) {
				const category = {
					id: row.id,
					name: row.name,
					products: [],
				};

				categories.push(category);
				categoriesMap[row.id] = category;
			}
		});

		// Deuxième passe: ajouter les produits à leurs catégories
		results.forEach((row) => {
			if (row.product_id) {
				const product = {
					id: row.product_id,
					name: row.product_name,
					description: row.description,
					price: row.price,
					img: row.img,
					category_id: row.id,
				};

				categoriesMap[row.id].products.push(product);
			}
		});

		res.json(categories);
	});
});

// Route pour récupérer un produit spécifique par ID
app.get("/catalogue/product/:id", (req, res) => {
	const { id } = req.params;
	const query = "SELECT * FROM catalogue WHERE id = ?";

	db.query(query, [id], (err, results) => {
		if (err) {
			console.error("Erreur lors de la récupération du produit:", err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		if (results.length === 0) {
			return res
				.status(404)
				.json({ success: false, message: "Produit non trouvé" });
		}

		res.json(results[0]);
	});
});

// Route pour récupérer le nom d'une catégorie par ID
app.get("/catalogue/category-name/:id", (req, res) => {
	const { id } = req.params;
	const query = "SELECT name FROM category WHERE id = ?";

	db.query(query, [id], (err, results) => {
		if (err) {
			console.error("Erreur lors de la récupération de la catégorie:", err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		if (results.length === 0) {
			return res
				.status(404)
				.json({ success: false, message: "Catégorie non trouvée" });
		}

		res.json({ name: results[0].name });
	});
});

// Route pour récupérer les produits d'une catégorie spécifique
app.get("/catalogue/by-category/:categoryId", (req, res) => {
	const { categoryId } = req.params;
	const query =
		"SELECT * FROM catalogue WHERE category_id = ? ORDER BY id DESC";

	db.query(query, [categoryId], (err, results) => {
		if (err) {
			console.error(
				"Erreur lors de la récupération des produits par catégorie:",
				err
			);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		res.json(results);
	});
});

// modifier un article du catalogue
app.put("/catalogue/:id", (req, res) => {
	const { id } = req.params;
	const { name, category_id, description, price, img } = req.body;
	const query =
		"UPDATE catalogue SET name = ?, category_id = ?, description = ?, price = ?, img = ? WHERE id = ?";
	db.query(
		query,
		[name, category_id, description, price, img, id],
		(err, results) => {
			if (err) {
				console.error(err);
				res.status(500).send("Error updating catalogue item");
			} else {
				res.send("Catalogue item updated successfully");
			}
		}
	);
});

// Route pour créer un produit avec image
app.post(
	"/catalogue/create",
	verifyToken,
	uploadCatalogue.single("image"),
	async (req, res) => {
		try {
			const { name, description, category_id, price } = req.body;

			// Validation des données
			if (!name || !description || !category_id || price === undefined) {
				return res.status(400).json({
					success: false,
					message: "Tous les champs obligatoires doivent être remplis.",
				});
			}

			// Vérifier que la catégorie existe
			const categoryCheckQuery = "SELECT id FROM category WHERE id = ?";
			db.query(
				categoryCheckQuery,
				[category_id],
				async (err, categoryResults) => {
					if (err) {
						console.error(
							"Erreur lors de la vérification de la catégorie:",
							err
						);
						return res.status(500).json({
							success: false,
							message: "Erreur lors de la vérification de la catégorie",
						});
					}

					if (categoryResults.length === 0) {
						return res.status(404).json({
							success: false,
							message: "La catégorie spécifiée n'existe pas",
						});
					}

					let imagePath = null;

					// Traitement de l'image si elle existe
					if (req.file) {
						// Générer un ID unique
						const uniqueId = uuidv4();
						const originalPath = req.file.path;
						const newImageName = `${uniqueId}.png`;
						const outputPath = `uploads/images/catalogue/${newImageName}`;

						// Redimensionner l'image
						try {
							await sharp(originalPath)
								.resize({ width: 800 })
								.toFile(outputPath);

							// Supprimer le fichier original après traitement
							setTimeout(() => {
								try {
									if (fs.existsSync(originalPath)) {
										fs.unlinkSync(originalPath);
										console.log(
											`Fichier original ${originalPath} supprimé avec succès`
										);
									}
								} catch (unlinkError) {
									console.error(
										`Erreur lors de la suppression du fichier original: ${unlinkError}`
									);
								}
							}, 1000);

							// Chemin d'accès pour le frontend
							imagePath = `/images/catalogue/${newImageName}`;
						} catch (sharpError) {
							console.error(
								`Erreur lors du traitement de l'image: ${sharpError}`
							);
							return res.status(500).json({
								success: false,
								message: "Erreur lors du traitement de l'image",
							});
						}
					}

					// Insertion du produit dans la base de données
					const query =
						"INSERT INTO catalogue (name, description, price, category_id, img) VALUES (?, ?, ?, ?, ?)";
					db.query(
						query,
						[name, description, price, category_id, imagePath],
						(err, results) => {
							if (err) {
								console.error("Erreur lors de la création du produit:", err);
								return res.status(500).json({
									success: false,
									message: "Erreur lors de la création du produit",
								});
							}

							res.status(201).json({
								success: true,
								id: results.insertId,
								message: "Produit créé avec succès",
							});
						}
					);
				}
			);
		} catch (error) {
			console.error("Erreur:", error);
			res.status(500).json({
				success: false,
				message: "Erreur serveur lors de la création du produit",
			});
		}
	}
);

// Route pour supprimer un produit
app.delete("/catalogue/:id", verifyToken, (req, res) => {
	const { id } = req.params;

	// Si le produit a une image, récupérer son chemin pour la supprimer
	db.query("SELECT img FROM catalogue WHERE id = ?", [id], (err, results) => {
		if (err) {
			console.error("Erreur lors de la récupération du produit:", err);
			return res
				.status(500)
				.json({ success: false, message: "Erreur serveur" });
		}

		// Supprimer le produit de la base de données
		db.query(
			"DELETE FROM catalogue WHERE id = ?",
			[id],
			(err, deleteResults) => {
				if (err) {
					console.error("Erreur lors de la suppression du produit:", err);
					return res
						.status(500)
						.json({ success: false, message: "Erreur serveur" });
				}

				if (deleteResults.affectedRows === 0) {
					return res
						.status(404)
						.json({ success: false, message: "Produit non trouvé" });
				}

				// Si le produit avait une image, la supprimer du système de fichiers
				if (results.length > 0 && results[0].img) {
					// Normalisation du chemin de l'image
					let imagePath;
					const imgPath = results[0].img;

					console.log(`Chemin de l'image en base de données: ${imgPath}`);

					// Analyse et normalisation du chemin
					if (imgPath.includes("/images/catalogue/")) {
						// Format correct "/images/catalogue/filename.png"
						imagePath = `uploads${imgPath.replace("/images", "")}`;
					} else if (imgPath.includes("/catalogue/")) {
						// Format incorrect sans 'images': "/catalogue/filename.png"
						imagePath = `uploads/images${imgPath}`;
					} else if (imgPath.startsWith("/")) {
						// Autre format avec slash initial
						imagePath = `uploads/images/catalogue${imgPath}`;
					} else {
						// Format sans slash initial
						imagePath = `uploads/images/catalogue/${imgPath}`;
					}

					console.log(`Chemin normalisé pour suppression: ${imagePath}`);

					// Vérifier si le fichier existe et le supprimer
					if (fs.existsSync(imagePath)) {
						try {
							fs.unlinkSync(imagePath);
							console.log(`Image ${imagePath} supprimée avec succès`);
						} catch (unlinkError) {
							console.error(
								`Erreur lors de la suppression de l'image: ${unlinkError}`
							);
							// Ne pas bloquer la réponse en cas d'erreur
						}
					} else {
						// Essayer d'autres chemins possibles si le fichier n'est pas trouvé
						const alternativePaths = [
							`uploads/images/catalogue/${imgPath.split("/").pop()}`,
							`uploads/catalogue/${imgPath.split("/").pop()}`,
							`uploads/${imgPath}`,
						];

						console.log(
							"Tentative de suppression avec des chemins alternatifs:"
						);

						let deleted = false;
						for (const altPath of alternativePaths) {
							console.log(`Essai avec: ${altPath}`);
							if (fs.existsSync(altPath)) {
								try {
									fs.unlinkSync(altPath);
									console.log(`Image ${altPath} supprimée avec succès`);
									deleted = true;
									break;
								} catch (e) {
									console.error(
										`Erreur avec le chemin alternatif ${altPath}:`,
										e
									);
								}
							}
						}

						if (!deleted) {
							console.log(
								`L'image n'a pas pu être trouvée pour la suppression`
							);
						}
					}
				}

				res.json({
					success: true,
					message: "Produit supprimé avec succès",
				});
			}
		);
	});
});

// CRUD for Actualites
// recuperer les actualites
app.get("/actualites", (req, res) => {
	const query = "SELECT * FROM actualites ORDER BY id DESC";
	db.query(query, (err, results) => {
		if (err) {
			console.error(err);
			res.status(500).send("Error fetching actualites");
		} else {
			res.json(results);
		}
	});
});

// recuperer une actualite par id
app.get("/actualites/:id", (req, res) => {
	const { id } = req.params;
	const query = "SELECT * FROM actualites WHERE id = ?";
	db.query(query, [id], (err, results) => {
		if (err) {
			console.error(err);
			res.status(500).send("Error fetching actualite");
		} else if (results.length === 0) {
			res.status(404).send("Actualite not found");
		} else {
			res.json(results[0]);
		}
	});
});

// Route pour créer une actualité avec image
app.post(
	"/actualites",
	verifyToken,
	upload.single("image"),
	async (req, res) => {
		try {
			const { title, description, date } = req.body;
			let imagePath = null;

			// Traitement de l'image si elle existe
			if (req.file) {
				// Générer un ID unique
				const uniqueId = uuidv4();
				const originalPath = req.file.path;
				const newImageName = `${uniqueId}.png`;
				const outputPath = `uploads/images/actualites/${newImageName}`;

				// Redimensionner l'image (sans reconversion puisque c'est déjà un PNG)
				try {
					await sharp(originalPath).resize({ width: 800 }).toFile(outputPath);

					// Supprimer le fichier original après traitement
					setTimeout(() => {
						try {
							if (fs.existsSync(originalPath)) {
								fs.unlinkSync(originalPath);
								console.log(
									`Fichier original ${originalPath} supprimé avec succès`
								);
							}
						} catch (unlinkError) {
							console.error(
								`Erreur lors de la suppression du fichier original: ${unlinkError}`
							);
						}
					}, 1000);

					// Chemin d'accès pour le frontend
					imagePath = `/images/actualites/${newImageName}`;
				} catch (sharpError) {
					console.error(`Erreur lors du traitement de l'image: ${sharpError}`);
					return res.status(500).send("Erreur lors du traitement de l'image");
				}
			}

			const query =
				"INSERT INTO actualites (title, description, date, img) VALUES (?, ?, ?, ?)";
			db.query(
				query,
				[title, description, date || new Date(), imagePath],
				(err, results) => {
					if (err) {
						console.error("Erreur création actualité:", err);
						return res
							.status(500)
							.send("Erreur lors de l'ajout de l'actualité");
					}
					res.status(201).json({
						success: true,
						id: results.insertId,
						message: "Actualité créée avec succès",
					});
				}
			);
		} catch (error) {
			console.error("Erreur:", error);
			res.status(500).send("Erreur serveur lors de la création de l'actualité");
		}
	}
);

// Route pour mettre à jour une actualité
app.put(
	"/actualites/:id",
	verifyToken,
	upload.single("image"),
	async (req, res) => {
		try {
			const { id } = req.params;
			const { title, description, date } = req.body;

			// Vérifier si l'actualité existe et récupérer son image actuelle
			db.query(
				"SELECT img FROM actualites WHERE id = ?",
				[id],
				async (err, results) => {
					if (err) {
						console.error(err);
						return res
							.status(500)
							.send("Erreur lors de la récupération de l'actualité");
					}

					if (results.length === 0) {
						return res.status(404).send("Actualité non trouvée");
					}

					// Toujours conserver l'image existante
					const imagePath = results[0].img;

					// Ne mettre à jour que les champs texte
					const query =
						"UPDATE actualites SET title = ?, description = ?, date = ? WHERE id = ?";
					const queryParams = [title, description, date, id];

					// Exécution de la requête de mise à jour
					db.query(query, queryParams, (err, results) => {
						if (err) {
							console.error(err);
							return res
								.status(500)
								.send("Erreur lors de la mise à jour de l'actualité");
						}
						res.json({
							success: true,
							message: "Actualité mise à jour avec succès",
							imagePath: imagePath,
						});
					});
				}
			);
		} catch (error) {
			console.error("Erreur:", error);
			res
				.status(500)
				.send("Erreur serveur lors de la mise à jour de l'actualité");
		}
	}
);

// Route pour supprimer une actualité et son image
app.delete("/actualites/:id", verifyToken, (req, res) => {
	const { id } = req.params;

	// D'abord récupérer l'image associée à l'actualité
	db.query("SELECT img FROM actualites WHERE id = ?", [id], (err, results) => {
		if (err) {
			console.error(err);
			return res
				.status(500)
				.send("Erreur lors de la récupération de l'actualité");
		}

		// Supprimer l'actualité de la base de données
		db.query(
			"DELETE FROM actualites WHERE id = ?",
			[id],
			(err, deleteResults) => {
				if (err) {
					console.error(err);
					return res
						.status(500)
						.send("Erreur lors de la suppression de l'actualité");
				}

				// Supprimer l'image si elle existe
				if (
					results.length > 0 &&
					results[0].img &&
					!results[0].img.includes("placeholder")
				) {
					const imagePath = `uploads${results[0].img.replace("/images", "")}`;

					// Vérifier d'abord si le fichier existe
					if (fs.existsSync(imagePath)) {
						try {
							// Utiliser la version synchrone pour une meilleure gestion
							fs.unlinkSync(imagePath);
							console.log(`Image ${imagePath} supprimée avec succès`);
						} catch (unlinkError) {
							console.error(
								`Erreur lors de la suppression de l'image: ${unlinkError}`
							);
							// Ne pas bloquer la réponse en cas d'erreur
						}
					} else {
						console.log(`L'image ${imagePath} n'existe pas`);
					}
				}

				res.send("Actualité supprimée avec succès");
			}
		);
	});
});

// Route pour traiter le formulaire de contact (envoi d'email direct)
app.post("/contact", (req, res) => {
	console.log("Requête reçue sur /contact:", req.body);

	// Extraire les données du body
	const { name, email, phone, subject, message, product_details } =
		req.body || {};

	console.log("Données extraites:", { name, email, phone, subject });

	// Validation basique côté serveur
	if (!name || !email || !subject || !message) {
		console.log("Validation échouée:", { name, email, subject, message });
		return res.status(400).json({
			success: false,
			message: "Veuillez remplir tous les champs obligatoires",
		});
	}

	// Anti-spam: vérifier la dernière soumission par IP
	const clientIP = req.ip || req.connection.remoteAddress;
	const now = Date.now();

	// Utilisation d'une Map en mémoire pour stocker les derniers envois par IP
	if (!global.lastSubmissions) {
		global.lastSubmissions = new Map();
	}

	const lastSubmitTime = global.lastSubmissions.get(clientIP) || 0;
	const minTimeBetweenSubmits = 30000; // 30 secondes

	if (now - lastSubmitTime < minTimeBetweenSubmits) {
		return res.status(429).json({
			success: false,
			message: "Trop de demandes. Veuillez réessayer plus tard.",
		});
	}

	try {
		// Préparation du contenu de l'email directement en HTML simple
		const currentYear = new Date().getFullYear();

		// Logo fixe en tant que texte, sans dépendance externe
		const logoHTML = `<div align='center'>
		<h3 style="color: #0056b3; margin: 10px 0;">SAFESECUR</h3>
		<p style="margin: 0; font-size: 12px;">Votre sécurité , notre priorit</p>
		</div>`;

		// Création d'un template d'email HTML simple et autonome
		const emailHTML = `
		<!DOCTYPE html>
		<html>
			<head>
			<meta charset='utf-8' />
			</head>
			<body style='font-family: Arial, sans-serif;'>
			<div style='max-width: 600px; margin: 0 auto; border: 1px solid #eaeaea; border-radius: 5px; padding: 20px;'>
				<div style='background-color: #f9f9f9; padding: 15px; text-align: center; margin-bottom: 20px;'>
				<h2 style='color: #0056b3; margin: 0;'>Nouveau message de contact</h2>
				</div>
				
				<div style='padding: 15px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);'>
				<p><strong>Nom:</strong> ${name}</p>
				<p><strong>Email:</strong> ${email}</p>
				${phone ? `<p><strong>Téléphone:</strong> ${phone}</p>` : ""}
				<p><strong>Objet:</strong> ${subject}</p>
				
				<hr style='border: none; border-top: 1px solid #eaeaea; margin: 20px 0;'>
				
				<p><strong>Message:</strong></p>
				<div style='background-color: #f9f9f9; padding: 15px; border-left: 3px solid #0056b3;'>
					${message.replace(/\n/g, "<br>")}
				</div>
				
				${
					product_details
						? `
				<hr style='border: none; border-top: 1px solid #eaeaea; margin: 20px 0;'>
				<p><strong>Produit concerné:</strong></p>
				<div style='background-color: #f9f9f9; padding: 15px;'>
					<p><strong>Nom:</strong> ${product_details.name}</p>
					<p><strong>Catégorie:</strong> ${product_details.category}</p>
					<p><strong>Prix:</strong> ${new Intl.NumberFormat("fr-FR", {
						style: "currency",
						currency: "XOF",
					}).format(product_details.price)}</p>
				</div>
				`
						: ""
				}
				</div>
				
				<div style='text-align: center; margin-top: 20px; padding: 15px; background-color: #f9f9f9;'>
				<p style='margin: 5px 0; font-size: 12px; color: #666;'>SAFESECUR © ${currentYear} - Tous droits réservés</p>
				</div>
			</div>
			
			${logoHTML}
			</body>
		</html>
    `;

		// Configuration du mail
		const mailOptions = {
			from: `"Contact SAFESECUR" <${process.env.MAIL_USER}>`,
			to: process.env.MAIL_USER, // email de destination configuré dans .env
			replyTo: email, // Pour que les réponses aillent directement au client
			subject: `Safesecur - Nouvelle demande: ${subject}`,
			html: emailHTML,
		};

		// Utiliser le transporteur SMTP
		transporter.sendMail(mailOptions, (err, info) => {
			if (err) {
				console.error("Erreur d'envoi d'email détaillée:");
				console.error("Code:", err.code);
				console.error("Message:", err.message);
				console.error("Response:", err.response);
				console.error("Stack:", err.stack);
				return res.status(500).json({
					success: false,
					message: "Erreur lors de l'envoi de l'email: " + err.message,
				});
			}

			console.log("Email envoyé avec succès:", info.response);

			// Enregistrer l'heure d'envoi pour l'anti-spam
			global.lastSubmissions.set(clientIP, now);

			// Nettoyage des anciennes entrées dans la Map anti-spam
			const oneHourAgo = now - 3600000;
			for (const [ip, time] of global.lastSubmissions.entries()) {
				if (time < oneHourAgo) {
					global.lastSubmissions.delete(ip);
				}
			}

			return res.status(200).json({
				success: true,
				message: "Votre message a été envoyé avec succès",
			});
		});
	} catch (error) {
		console.error("Erreur générale:", error);
		return res.status(500).json({
			success: false,
			message: "Une erreur s'est produite lors du traitement de votre demande",
			error: error.message,
		});
	}
});

// Route pour récupérer toutes les catégories (sans authentification) pour le formulaire de contact
app.get("/categories/public", (req, res) => {
	const query = `SELECT id, name FROM category ORDER BY name ASC`;

	db.query(query, (err, results) => {
		if (err) {
			console.error("Erreur lors de la récupération des catégories:", err);
			return res.status(500).json({
				success: false,
				message: "Erreur lors de la récupération des catégories",
			});
		}

		res.json(results);
	});
});

if (process.env.ONLINE == "false") {
	const PORT = process.env.PORT;
	app.listen(PORT, () => {
		console.log(`Server running on local port : ${PORT}`);
	});
} else {
	app.listen();
	console.log("Server running online");
	app.get("/", (req, res) => {
		res.redirect("https://safesecur.com");
		// 	// res.send(generateMetaTags(staticMetaTags));
		// 	// res.send("Hello, world!");
	});
}
