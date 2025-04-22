<template>
	<div class="create-actualite-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>Nouvelle actualité</h1>
				<p class="hero-subtitle">
					Créer un nouveau contenu pour informer vos clients
				</p>
			</div>
		</section>

		<!-- Form Section -->
		<section class="form-section">
			<div class="container">
				<div class="form-container">
					<div class="form-header">
						<h2><i class="fi fi-rr-edit"></i> Créer une actualité</h2>
						<router-link to="/actualites" class="back-button">
							<i class="fi fi-rr-arrow-left"></i> Retour à la liste
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

					<form
						@submit.prevent="submitForm"
						class="actualite-form"
						enctype="multipart/form-data"
					>
						<div class="form-group">
							<label for="title">Titre*</label>
							<input
								type="text"
								id="title"
								maxlength="150"
								max="150"
								v-model="form.title"
								:class="{ 'input-error': validationErrors.title }"
								placeholder="Titre de l'actualité"
								required
							/>
							<p v-if="validationErrors.title" class="error-text">
								{{ validationErrors.title }}
							</p>
						</div>

						<div class="form-group" style="display: none">
							<label for="date">Date*</label>
							<input
								type="date"
								id="date"
								v-model="form.date"
								:class="{ 'input-error': validationErrors.date }"
								required
							/>
							<p v-if="validationErrors.date" class="error-text">
								{{ validationErrors.date }}
							</p>
						</div>

						<div class="form-group">
							<label for="description">Description*</label>
							<textarea
								id="description"
								v-model="form.description"
								rows="8"
								maxlength="1000"
								:class="{ 'input-error': validationErrors.description }"
								placeholder="Contenu de l'actualité..."
								required
							></textarea>
							<p v-if="validationErrors.description" class="error-text">
								{{ validationErrors.description }}
							</p>
						</div>

						<div class="form-group">
							<label for="image">Image</label>
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
									<i class="fi fi-rr-check"></i> Publier l'actualité
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
		name: "CreateActualite",
		data() {
			return {
				form: {
					title: "",
					description: "",
					date: new Date().toISOString().split("T")[0], // Today as default
				},
				imagePreview: null,
				imageFile: null,
				isSubmitting: false,
				successMessage: null,
				errorMessage: null,
				validationErrors: {},
				isAuthenticated: false,
				conversionInProgress: false,
			};
		},
		created() {
			// Vérification d'authentification dès le chargement du composant
			this.checkAuthentication();
		},
		mounted() {},
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

			validateForm() {
				this.validationErrors = {};

				if (!this.form.title.trim()) {
					this.validationErrors.title = "Le titre est requis";
				} else if (this.form.title.length > 150) {
					this.validationErrors.title =
						"Le titre ne doit pas dépasser 150 caractères";
				}

				if (!this.form.description.trim()) {
					this.validationErrors.description = "La description est requise";
				}

				if (!this.form.date) {
					this.validationErrors.date = "La date est requise";
				}

				return Object.keys(this.validationErrors).length === 0;
			},

			resetForm() {
				this.form = {
					title: "",
					description: "",
					date: new Date().toISOString().split("T")[0],
				};
				this.removeImage();
				this.validationErrors = {};
				this.errorMessage = null;
				this.successMessage = null;
			},

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

			removeImage() {
				this.imagePreview = null;
				this.imageFile = null;
				if (this.$refs.imageInput) {
					this.$refs.imageInput.value = "";
				}
			},

			submitForm() {
				if (!this.validateForm()) return;

				if (this.conversionInProgress) {
					this.errorMessage =
						"Conversion d'image en cours. Veuillez patienter...";
					return;
				}

				const token = localStorage.getItem("token");
				if (!token) {
					this.errorMessage =
						"Vous devez être connecté pour créer une actualité.";
					setTimeout(() => {
						this.$router.push("/admin");
					}, 2000);
					return;
				}

				this.isSubmitting = true;
				this.errorMessage = null;
				this.successMessage = null;

				// Création d'un FormData pour envoyer des fichiers
				const formData = new FormData();
				formData.append("title", this.form.title);
				formData.append("description", this.form.description);
				formData.append("date", this.form.date);

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
					.post(`${window.config.API_URL}/actualites`, formData, config)
					.then((response) => {
						this.successMessage = "L'actualité a été créée avec succès!";
						this.resetForm();
						setTimeout(() => {
							this.$router.push("/actualites");
						}, 2000);
					})
					.catch((error) => {
						console.error("Error creating actualite:", error);
						if (error.response && error.response.status === 401) {
							this.errorMessage =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/admin");
							}, 2000);
						} else {
							this.errorMessage =
								"Une erreur est survenue lors de la création de l'actualité. Veuillez réessayer.";
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
	.create-actualite-page {
		--primary-color: #d72f3f;
		--secondary-color: #f8f9fa;
		--accent-color: #ffc107;
		--text-color: #333;
		--light-text: #6c757d;
		--dark-bg: #343a40;
		--error-color: #dc3545;
		--success-color: #28a745;
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
			url("/img/news-header.jpg") center/cover;
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
	.error-message {
		padding: 15px;
		border-radius: 5px;
		margin-bottom: 20px;
		display: flex;
		align-items: center;
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

	.form-group {
		margin-bottom: 25px;
	}

	label {
		display: block;
		margin-bottom: 8px;
		font-weight: 600;
	}

	input[type="text"],
	input[type="date"],
	textarea {
		width: 100%;
		padding: 12px 15px;
		border: 1px solid #ddd;
		border-radius: 4px;
		font-size: 1rem;
		transition: border-color 0.3s ease;
	}

	input:focus,
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

	.submit-btn:hover {
		background-color: #004494;
	}

	.submit-btn:disabled {
		background-color: #6c757d;
		cursor: not-allowed;
	}

	/* Spinner animation */
	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	.fi-rr-spinner {
		animation: spin 1s linear infinite;
	}

	/* Responsive adjustments */
	@media (max-width: 768px) {
		.form-container {
			padding: 20px;
		}

		.form-header {
			flex-direction: column;
			align-items: stretch;
		}

		.form-actions {
			flex-direction: column;
		}

		.reset-btn,
		.submit-btn {
			width: 100%;
			justify-content: center;
		}
	}
</style>
