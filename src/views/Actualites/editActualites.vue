<template>
	<div class="edit-actualite-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>Modifier l'actualité</h1>
				<p class="hero-subtitle">
					Mettre à jour les informations et le contenu
				</p>
			</div>
		</section>

		<!-- Form Section -->
		<section class="form-section">
			<div class="container">
				<div class="form-container">
					<div class="form-header">
						<h2><i class="fi fi-rr-edit"></i> Édition d'actualité</h2>
						<router-link to="/actualites" class="back-button">
							<i class="fi fi-rr-arrow-left"></i> Retour à la liste
						</router-link>
					</div>

					<!-- Loading State -->
					<div v-if="loading" class="loading-container">
						<div class="loading-spinner"></div>
						<p>Chargement des données...</p>
					</div>

					<!-- Error State -->
					<div v-else-if="loadError" class="error-message">
						<i class="fi fi-rr-cross-circle"></i>
						<p>{{ loadError }}</p>
						<button @click="fetchActualite" class="retry-button">
							<i class="fi fi-rr-refresh"></i> Réessayer
						</button>
					</div>

					<template v-else>
						<div v-if="successMessage" class="success-message">
							<i class="fi fi-rr-check"></i>
							<p>{{ successMessage }}</p>
						</div>

						<div v-if="errorMessage" class="error-message">
							<i class="fi fi-rr-cross-circle"></i>
							<p>{{ errorMessage }}</p>
						</div>

						<form @submit.prevent="submitForm" class="actualite-form">
							<div class="form-group">
								<label for="title">Titre*</label>
								<input
									type="text"
									id="title"
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
									:class="{ 'input-error': validationErrors.description }"
									placeholder="Contenu de l'actualité..."
									required
								></textarea>
								<p v-if="validationErrors.description" class="error-text">
									{{ validationErrors.description }}
								</p>
							</div>

							<div class="form-group">
								<label for="image">Image (prévisualisation uniquement)</label>
								<div class="image-upload-container">
									<div
										class="image-preview"
										:class="{ 'has-image': imagePreview }"
										:style="
											imagePreview
												? `background-image: url(${getImageUrl(imagePreview)})`
												: ''
										"
									>
										<div v-if="!imagePreview" class="upload-placeholder">
											<i class="fi fi-rr-picture"></i>
											<p>Aucune image disponible</p>
										</div>
									</div>
									<div class="image-info" v-if="imagePreview">
										<p class="info-text">
											<i class="fi fi-rr-info-circle"></i>
											L'image ne peut pas être modifiée après la création de
											l'actualité
										</p>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<router-link to="/actualites" class="cancel-btn">
									<i class="fi fi-rr-cross-circle"></i> Annuler
								</router-link>
								<button
									type="submit"
									class="submit-btn"
									:disabled="isSubmitting"
								>
									<span v-if="isSubmitting">
										<i class="fi fi-rr-spinner"></i> Mise à jour...
									</span>
									<span v-else>
										<i class="fi fi-rr-check"></i> Mettre à jour
									</span>
								</button>
							</div>
						</form>
					</template>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from "axios";

	export default {
		name: "EditActualite",
		data() {
			return {
				actualiteId: null,
				form: {
					title: "",
					description: "",
					date: "",
					img: "",
				},
				originalData: null,
				imagePreview: null,
				loading: true,
				loadError: null,
				isSubmitting: false,
				successMessage: null,
				errorMessage: null,
				validationErrors: {},
				isAuthenticated: false, // Ajout de la propriété d'authentification
			};
		},
		created() {
			this.actualiteId = this.$route.params.id;
			if (!this.actualiteId) {
				this.$router.push("/actualites");
				return;
			}

			// Vérification d'authentification dès le chargement du composant
			this.checkAuthentication();
		},
		mounted() {
			// Ne charger les données que si authentifié
			if (this.isAuthenticated) {
				this.fetchActualite();
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
							this.fetchActualite(); // Charger les données seulement après authentification confirmée
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

			// Modification de fetchActualite pour inclure l'authentification
			fetchActualite() {
				this.loading = true;
				this.loadError = null;

				const token = localStorage.getItem("token");
				if (!token) {
					this.loadError = "Authentification requise";
					this.loading = false;
					this.$router.push("/login");
					return;
				}

				axios
					.get(`${window.config.API_URL}/actualites/${this.actualiteId}`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						const actualite = response.data;
						if (!actualite) {
							this.loadError = "L'actualité demandée n'a pas été trouvée.";
							return;
						}

						// Format date to YYYY-MM-DD for input[type=date]
						let formattedDate = actualite.date;
						if (formattedDate && formattedDate.includes("T")) {
							formattedDate = formattedDate.split("T")[0];
						} else if (formattedDate) {
							const date = new Date(formattedDate);
							formattedDate = date.toISOString().split("T")[0];
						}

						this.form = {
							title: actualite.title || "",
							description: actualite.description || "",
							date: formattedDate || "",
							img: actualite.img || "",
						};

						// Store original data for comparison
						this.originalData = { ...this.form };

						// Set image preview if available
						if (actualite.img) {
							this.imagePreview = actualite.img;
						}
					})
					.catch((error) => {
						console.error("Error fetching actualite:", error);
						if (error.response && error.response.status === 401) {
							this.loadError =
								"Non autorisé. Vous devez être connecté pour accéder à cette page.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/login");
							}, 2000);
						} else {
							this.loadError =
								"Une erreur est survenue lors du chargement de l'actualité. Veuillez réessayer.";
						}
					})
					.finally(() => {
						this.loading = false;
					});
			},

			// Modification de submitForm pour inclure la date du jour
			submitForm() {
				if (!this.validateForm()) return;

				const token = localStorage.getItem("token");
				if (!token) {
					this.errorMessage =
						"Vous devez être connecté pour modifier une actualité.";
					setTimeout(() => {
						this.$router.push("/login");
					}, 2000);
					return;
				}

				// Utiliser la date du jour lors de la soumission
				const today = new Date().toISOString().split("T")[0];
				this.form.date = today;

				// Check if anything has changed (excluding date)
				let hasChanged = false;

				// Compare text fields only
				if (
					this.form.title !== this.originalData.title ||
					this.form.description !== this.originalData.description
				) {
					hasChanged = true;
				}

				if (!hasChanged) {
					this.errorMessage = "Aucune modification n'a été effectuée.";
					return;
				}

				this.isSubmitting = true;
				this.errorMessage = null;
				this.successMessage = null;

				// Création d'un FormData pour l'envoi (sans l'image)
				const formData = new FormData();
				formData.append("title", this.form.title);
				formData.append("description", this.form.description);
				formData.append("date", today); // Toujours utiliser la date du jour
				formData.append("keepExistingImage", "true"); // Toujours conserver l'image existante

				axios
					.put(
						`${window.config.API_URL}/actualites/${this.actualiteId}`,
						formData,
						{
							headers: {
								Authorization: `Bearer ${token}`,
								"Content-Type": "multipart/form-data",
							},
						}
					)
					.then((response) => {
						this.successMessage = "L'actualité a été mise à jour avec succès!";

						// Update original data with new values
						this.originalData = { ...this.form };

						// Redirect after a short delay
						setTimeout(() => {
							this.$router.push("/actualites");
						}, 2000);
					})
					.catch((error) => {
						console.error("Error updating actualite:", error);
						if (error.response && error.response.status === 401) {
							this.errorMessage =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/login");
							}, 2000);
						} else {
							this.errorMessage =
								"Une erreur est survenue lors de la mise à jour de l'actualité. Veuillez réessayer.";
						}
					})
					.finally(() => {
						this.isSubmitting = false;
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
			getImageUrl(path) {
				if (!path) return "";

				// Si le chemin commence déjà par http ou https, le retourner tel quel
				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				// S'assurer que le chemin commence par un slash
				const normalizedPath = path.startsWith("/") ? path : `/${path}`;

				// Retourner l'URL complète
				return `${window.config.API_URL}${normalizedPath}`;
			},
		},
	};
</script>

<style scoped>
	.edit-actualite-page {
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

	/* Loading container */
	.loading-container {
		text-align: center;
		padding: 40px 0;
	}

	.loading-spinner {
		display: inline-block;
		width: 50px;
		height: 50px;
		border: 4px solid rgba(0, 0, 0, 0.1);
		border-radius: 50%;
		border-top-color: var(--primary-color);
		animation: spin 1s ease-in-out infinite;
		margin-bottom: 20px;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
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

	.retry-button {
		background-color: var(--primary-color);
		color: white;
		border: none;
		padding: 8px 15px;
		border-radius: 4px;
		margin-left: 10px;
		cursor: pointer;
		font-size: 0.9rem;
		display: flex;
		align-items: center;
		gap: 5px;
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

	.image-info {
		background-color: #e7f3ff;
		padding: 10px 15px;
		border-radius: 5px;
		margin-top: 10px;
	}

	.info-text {
		color: #0056b3;
		display: flex;
		align-items: center;
		gap: 8px;
		margin: 0;
		font-size: 0.9rem;
	}

	.info-text i {
		font-size: 1.1rem;
	}

	/* Form actions */
	.form-actions {
		display: flex;
		justify-content: flex-end;
		gap: 15px;
		margin-top: 40px;
	}

	.cancel-btn,
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
		text-decoration: none;
	}

	.cancel-btn {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.cancel-btn:hover {
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

		.cancel-btn,
		.submit-btn {
			width: 100%;
			justify-content: center;
		}
	}
</style>
