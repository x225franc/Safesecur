<template>
	<div class="create-product-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>Nouveau Produit</h1>
				<p class="hero-subtitle">Ajouter un produit à votre catalogue</p>
			</div>
		</section>

		<!-- Form Section -->
		<section class="form-section">
			<div class="container">
				<div class="form-container">
					<div class="form-header">
						<h2><i class="fi fi-rr-box"></i> Créer un produit</h2>
						<router-link to="/catalogue" class="back-button">
							<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
						</router-link>
					</div>

					<div v-if="successMessage" class="success-message">
						<i class="fi fi-rr-check"></i>
						<p>{{ successMessage }}</p>
					</div>

					<div v-if="errorMessage" class="error-message">
						<i class="fi fi-rr-cross-circle"></i>
						<p>{{ errorMessage }}</p>
					</div>

					<div v-if="noCategoriesWarning" class="warning-message">
						<i class="fi fi-rr-info-circle"></i>
						<div>
							<strong>Attention:</strong> Aucune catégorie disponible. Vous
							devez créer au moins une catégorie avant de pouvoir ajouter des
							produits.
							<router-link to="/category" class="warning-link"
								>Créer une catégorie</router-link
							>
						</div>
					</div>

					<form
						@submit.prevent="submitForm"
						class="product-form"
						enctype="multipart/form-data"
						v-if="!noCategoriesWarning"
					>
						<div class="form-group">
							<label for="name">Nom du produit*</label>
							<input
								type="text"
								id="name"
								maxlength="100"
								v-model="form.name"
								:class="{ 'input-error': validationErrors.name }"
								placeholder="Nom du produit"
								required
							/>
							<p v-if="validationErrors.name" class="error-text">
								{{ validationErrors.name }}
							</p>
						</div>

						<div class="form-group">
							<label for="category">Catégorie*</label>
							<select
								id="category"
								v-model="form.category_id"
								:class="{ 'input-error': validationErrors.category_id }"
								required
							>
								<option value="" disabled>Sélectionnez une catégorie</option>
								<option
									v-for="category in categories"
									:key="category.id"
									:value="category.id"
								>
									{{ category.name }}
								</option>
							</select>
							<p v-if="validationErrors.category_id" class="error-text">
								{{ validationErrors.category_id }}
							</p>
						</div>

						<div class="form-group">
							<label for="price">Prix (XOF)*</label>
							<input
								type="number"
								id="price"
								v-model="form.price"
								min="0"
								step="5"
								:class="{ 'input-error': validationErrors.price }"
								placeholder="0 FCFA"
								required
							/>
							<p v-if="validationErrors.price" class="error-text">
								{{ validationErrors.price }}
							</p>
						</div>

						<div class="form-group">
							<label for="description">Description*</label>
							<textarea
								id="description"
								v-model="form.description"
								rows="6"
								maxlength="1000"
								:class="{ 'input-error': validationErrors.description }"
								placeholder="Description du produit..."
								required
							></textarea>
							<p v-if="validationErrors.description" class="error-text">
								{{ validationErrors.description }}
							</p>
						</div>

						<div class="form-group">
							<label for="image">Image du produit</label>
							<div class="image-upload-container">
								<div
									class="image-preview"
									:class="{ 'has-image': imagePreview }"
									:style="
										imagePreview ? `background-image: url(${imagePreview})` : ''
									"
								>
									<div v-if="!imagePreview" class="upload-placeholder">
										<i class="fi fi-rr-picture"></i>
										<p>Aperçu de l'image</p>
									</div>
									<button
										type="button"
										v-if="imagePreview"
										class="remove-image-btn"
										@click="removeImage"
									>
										<i class="fi fi-rr-trash"></i>
									</button>
								</div>
								<div class="upload-controls">
									<div class="upload-buttons">
										<label for="imageFile" class="file-select-btn">
											<i class="fi fi-rr-upload"></i> Sélectionner
										</label>
										<input
											type="file"
											id="imageFile"
											ref="imageInput"
											@change="handleImageUpload"
											accept="image/*"
											hidden
										/>
									</div>
									<div v-if="conversionInProgress" class="converting-indicator">
										<i class="fi fi-rr-spinner"></i> Conversion de l'image en
										cours...
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<button type="button" @click="resetForm" class="reset-btn">
								Réinitialiser
							</button>
							<button
								type="submit"
								class="submit-btn"
								:disabled="isSubmitting || conversionInProgress"
							>
								<span v-if="isSubmitting">
									<i class="fi fi-rr-spinner"></i> Enregistrement...
								</span>
								<span v-else>
									<i class="fi fi-rr-check"></i> Ajouter au catalogue
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from "axios";
	import {
		convertImageToPNG,
		isAlreadyPNG,
	} from "@/components/utils/ImageConverter";

	export default {
		name: "CreateProduct",
		data() {
			return {
				form: {
					name: "",
					category_id: "",
					description: "",
					price: "",
				},
				categories: [],
				imagePreview: null,
				imageFile: null,
				isSubmitting: false,
				successMessage: null,
				errorMessage: null,
				validationErrors: {},
				isAuthenticated: false,
				conversionInProgress: false,
				noCategoriesWarning: false,
			};
		},
		created() {
			this.checkAuthentication();
		},
		mounted() {
			// Récupérer la catégorie préselectionée via query param, s'il y en a une
			if (this.$route.query.category) {
				this.form.category_id = parseInt(this.$route.query.category) || "";
			}
		},
		methods: {
			// Méthode pour vérifier l'authentification
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.$router.push("/login");
					return;
				}

				axios
					.get(`${window.config.API_URL}/verify-auth`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						if (response.data && response.data.authenticated) {
							this.isAuthenticated = true;
							this.fetchCategories();
						} else {
							this.$router.push("/login");
						}
					})
					.catch((error) => {
						console.error("Erreur d'authentification:", error);
						localStorage.removeItem("token");
						this.$router.push("/login");
					});
			},

			// Récupérer les catégories
			fetchCategories() {
				const token = localStorage.getItem("token");

				axios
					.get(`${window.config.API_URL}/categories`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						this.categories = response.data;

						// Vérifier s'il y a des catégories disponibles
						if (this.categories.length === 0) {
							this.noCategoriesWarning = true;
						} else {
							this.noCategoriesWarning = false;

							// Si une catégorie est préselectionée dans l'URL et qu'elle existe bien
							if (this.$route.query.category) {
								const categoryId = parseInt(this.$route.query.category);
								const categoryExists = this.categories.some(
									(cat) => cat.id === categoryId
								);

								if (categoryExists) {
									this.form.category_id = categoryId;
								} else {
									this.form.category_id = this.categories[0].id; // Par défaut, prendre la première catégorie
								}
							} else if (!this.form.category_id) {
								// Si aucune catégorie n'est sélectionnée, prendre la première par défaut
								this.form.category_id = this.categories[0].id;
							}
						}
					})
					.catch((error) => {
						console.error("Erreur lors du chargement des catégories:", error);
						this.errorMessage = "Erreur lors du chargement des catégories.";
					});
			},

			// Validation du formulaire
			validateForm() {
				this.validationErrors = {};

				if (!this.form.name.trim()) {
					this.validationErrors.name = "Le nom du produit est requis";
				} else if (this.form.name.length > 100) {
					this.validationErrors.name =
						"Le nom ne doit pas dépasser 100 caractères";
				}

				if (!this.form.category_id) {
					this.validationErrors.category_id =
						"Veuillez sélectionner une catégorie";
				}

				if (!this.form.description.trim()) {
					this.validationErrors.description = "La description est requise";
				} else if (this.form.description.length > 1000) {
					this.validationErrors.description =
						"La description ne doit pas dépasser 1000 caractères";
				}

				if (this.form.price === "" || this.form.price === null) {
					this.validationErrors.price = "Le prix est requis";
				} else if (
					isNaN(parseFloat(this.form.price)) ||
					parseFloat(this.form.price) < 0
				) {
					this.validationErrors.price = "Le prix doit être un nombre positif";
				}

				return Object.keys(this.validationErrors).length === 0;
			},

			// Réinitialiser le formulaire
			resetForm() {
				this.form = {
					name: "",
					category_id: this.categories.length > 0 ? this.categories[0].id : "",
					description: "",
					price: "",
				};
				this.removeImage();
				this.validationErrors = {};
				this.errorMessage = null;
				this.successMessage = null;
			},

			// Gérer l'upload d'image
			async handleImageUpload(event) {
				const file = event.target.files[0];
				if (!file) return;

				// Vérification du type de fichier
				if (!file.type.match("image.*")) {
					this.errorMessage = "Seuls les fichiers image sont autorisés.";
					return;
				}

				// Vérification de la taille (max 5MB)
				if (file.size > 5 * 1024 * 1024) {
					this.errorMessage =
						"L'image est trop volumineuse. Taille maximale: 5MB";
					return;
				}

				try {
					this.conversionInProgress = true;

					// Si l'image n'est pas déjà au format PNG, la convertir
					if (!isAlreadyPNG(file)) {
						const pngFile = await convertImageToPNG(file);
						this.imageFile = pngFile;
						console.log("Image convertie avec succès en PNG");
					} else {
						this.imageFile = file;
						console.log("L'image est déjà au format PNG");
					}

					// Prévisualisation de l'image
					const reader = new FileReader();
					reader.onload = (e) => {
						this.imagePreview = e.target.result;
					};
					reader.readAsDataURL(this.imageFile);

					this.errorMessage = null;
				} catch (error) {
					console.error("Erreur lors de la conversion de l'image:", error);
					this.errorMessage =
						"Une erreur est survenue lors du traitement de l'image. Veuillez réessayer.";
				} finally {
					this.conversionInProgress = false;
				}
			},

			// Supprimer l'image sélectionnée
			removeImage() {
				this.imagePreview = null;
				this.imageFile = null;
				if (this.$refs.imageInput) {
					this.$refs.imageInput.value = "";
				}
			},

			// Soumettre le formulaire
			submitForm() {
				if (!this.validateForm()) return;

				if (this.conversionInProgress) {
					this.errorMessage =
						"Conversion d'image en cours. Veuillez patienter...";
					return;
				}

				const token = localStorage.getItem("token");
				if (!token) {
					this.errorMessage = "Vous devez être connecté pour créer un produit.";
					setTimeout(() => {
						this.$router.push("/login");
					}, 2000);
					return;
				}

				this.isSubmitting = true;
				this.errorMessage = null;
				this.successMessage = null;

				// Création d'un FormData pour envoyer des fichiers
				const formData = new FormData();
				formData.append("name", this.form.name);
				formData.append("category_id", this.form.category_id);
				formData.append("description", this.form.description);
				formData.append("price", this.form.price);

				// Ajout de l'image si elle existe
				if (this.imageFile) {
					formData.append("image", this.imageFile);
				}

				// Configuration pour envoyer des fichiers
				const config = {
					headers: {
						Authorization: `Bearer ${token}`,
						"Content-Type": "multipart/form-data",
					},
				};

				axios
					.post(`${window.config.API_URL}/catalogue/create`, formData, config)
					.then((response) => {
						this.successMessage = "Le produit a été ajouté avec succès!";
						this.resetForm();

						// Rediriger vers la page du catalogue après quelques secondes
						setTimeout(() => {
							this.$router.push("/catalogue");
						}, 2000);
					})
					.catch((error) => {
						console.error("Error creating product:", error);
						if (error.response && error.response.status === 401) {
							this.errorMessage =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/login");
							}, 2000);
						} else if (
							error.response &&
							error.response.data &&
							error.response.data.message
						) {
							this.errorMessage = error.response.data.message;
						} else {
							this.errorMessage =
								"Une erreur est survenue lors de la création du produit. Veuillez réessayer.";
						}
					})
					.finally(() => {
						this.isSubmitting = false;
					});
			},
		},
	};
</script>

<style scoped>
	.create-product-page {
		--primary-color: #d72f3f;
		--secondary-color: #f8f9fa;
		--accent-color: #ffc107;
		--text-color: #333;
		--light-text: #6c757d;
		--dark-bg: #343a40;
		--error-color: #dc3545;
		--success-color: #28a745;
		--warning-color: #fd7e14;
		font-family: "Roboto", "Arial", sans-serif;
		color: var(--text-color);
	}

	.container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 20px;
	}

	section {
		padding: 60px 0;
	}

	/* Hero Section - Smaller for form pages */
	.page-hero.small-hero {
		background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
			url("/img/catalogue-header.jpg") center/cover;
		color: white;
		text-align: center;
		padding: 50px 0;
	}

	.page-hero h1 {
		font-size: 2.8rem;
		margin-bottom: 10px;
		font-weight: 700;
	}

	.hero-subtitle {
		font-size: 1.2rem;
	}

	/* Form Styling */
	.form-section {
		background-color: var(--secondary-color);
	}

	.form-container {
		background-color: white;
		border-radius: 10px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
		padding: 40px;
		max-width: 800px;
		margin: 0 auto;
	}

	.form-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 30px;
		flex-wrap: wrap;
		gap: 15px;
	}

	.form-header h2 {
		margin: 0;
		font-size: 1.8rem;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.back-button {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		background-color: var(--secondary-color);
		color: var(--text-color);
		border-radius: 4px;
		text-decoration: none;
		transition: background-color 0.3s ease;
	}

	.back-button:hover {
		background-color: #e2e6ea;
	}

	.success-message,
	.error-message,
	.warning-message {
		padding: 15px;
		border-radius: 5px;
		margin-bottom: 20px;
		display: flex;
		align-items: flex-start;
		gap: 10px;
	}

	.success-message {
		background-color: rgba(40, 167, 69, 0.1);
		color: var(--success-color);
	}

	.error-message {
		background-color: rgba(220, 53, 69, 0.1);
		color: var(--error-color);
	}

	.warning-message {
		background-color: #fff7e6;
		border-left: 4px solid var(--warning-color);
		color: var(--text-color);
	}

	.warning-message i {
		color: var(--warning-color);
		font-size: 1.2rem;
		margin-top: 2px;
	}

	.warning-link {
		margin-left: 5px;
		color: var(--primary-color);
		font-weight: 600;
		text-decoration: none;
	}

	.warning-link:hover {
		text-decoration: underline;
	}

	.form-group {
		margin-bottom: 25px;
	}

	label {
		display: block;
		margin-bottom: 8px;
		font-weight: 600;
	}

	input[type="text"],
	input[type="number"],
	select,
	textarea {
		width: 100%;
		padding: 12px 15px;
		border: 1px solid #ddd;
		border-radius: 4px;
		font-size: 1rem;
		transition: border-color 0.3s ease;
	}

	input:focus,
	select:focus,
	textarea:focus {
		border-color: var(--primary-color);
		outline: none;
	}

	.input-error {
		border-color: var(--error-color);
	}

	.error-text {
		color: var(--error-color);
		font-size: 0.9rem;
		margin-top: 5px;
		margin-bottom: 0;
	}

	/* Image upload styling */
	.image-upload-container {
		display: flex;
		flex-direction: column;
		gap: 15px;
	}

	.image-preview {
		width: 100%;
		height: 200px;
		background-color: #f8f9fa;
		border: 2px dashed #ddd;
		border-radius: 5px;
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;
		background-size: cover;
		background-position: center;
	}

	.image-preview.has-image {
		border: none;
	}

	.upload-placeholder {
		text-align: center;
		color: var(--light-text);
	}

	.upload-placeholder i {
		font-size: 2.5rem;
		margin-bottom: 10px;
	}

	.remove-image-btn {
		position: absolute;
		top: 10px;
		right: 10px;
		background-color: rgba(255, 255, 255, 0.8);
		color: var(--error-color);
		border: none;
		width: 36px;
		height: 36px;
		border-radius: 50%;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.upload-controls {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.upload-buttons {
		display: flex;
		gap: 10px;
	}

	.file-select-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 20px;
		background-color: var(--secondary-color);
		color: var(--text-color);
		border-radius: 4px;
		cursor: pointer;
		transition: background-color 0.3s ease;
	}

	.file-select-btn:hover {
		background-color: #e2e6ea;
	}

	.converting-indicator {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-top: 10px;
		color: var(--primary-color);
		font-size: 0.9rem;
	}

	.converting-indicator i {
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	/* Form actions */
	.form-actions {
		display: flex;
		justify-content: flex-end;
		gap: 15px;
		margin-top: 40px;
	}

	.reset-btn,
	.submit-btn {
		padding: 12px 25px;
		border-radius: 4px;
		cursor: pointer;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 8px;
		border: none;
		transition: background-color 0.3s ease;
	}

	.reset-btn {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.reset-btn:hover {
		background-color: #e2e6ea;
	}

	.submit-btn {
		background-color: var(--primary-color);
		color: white;
	}

	.submit-btn:hover:not(:disabled) {
		background-color: #004494;
	}

	.submit-btn:disabled {
		opacity: 0.7;
		cursor: not-allowed;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.form-header {
			flex-direction: column;
			align-items: flex-start;
		}

		.form-container {
			padding: 25px;
		}

		.page-hero h1 {
			font-size: 2.2rem;
		}

		.form-actions {
			flex-direction: column-reverse;
		}

		.reset-btn,
		.submit-btn {
			width: 100%;
			justify-content: center;
		}
	}
</style>
