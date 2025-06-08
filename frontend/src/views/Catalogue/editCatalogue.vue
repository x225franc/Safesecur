<template>
	<div class="edit-product-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>Modifier un Produit</h1>
				<p class="hero-subtitle">
					Modifier les détails du produit dans le catalogue
				</p>
			</div>
		</section>

		<!-- Form Section -->
		<section class="form-section">
			<div class="container">
				<div class="form-container">
					<!-- Loading State -->
					<div v-if="loading" class="loading-container">
						<div class="loading-spinner"></div>
						<p>Chargement des informations du produit...</p>
					</div>

					<!-- Error State -->
					<div v-else-if="error" class="error-container">
						<div class="error-icon"><i class="fi fi-rr-exclamation"></i></div>
						<p>{{ error }}</p>
						<router-link to="/catalogue" class="btn-back">
							<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
						</router-link>
					</div>

					<!-- Edit Form -->
					<template v-else>
						<div class="form-header">
							<h2><i class="fi fi-rr-edit"></i> Modifier {{ product.name }}</h2>
							<router-link to="/catalogue" class="back-button">
								<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
							</router-link>
						</div>

						<div v-if="successMessage" class="success-message">
							<i class="fi fi-rr-check"></i>
							<p>{{ successMessage }}</p>
						</div>

						<div v-if="formError" class="error-message">
							<i class="fi fi-rr-cross-circle"></i>
							<p>{{ formError }}</p>
						</div>

						<form
							@submit.prevent="submitForm"
							class="product-form"
							enctype="multipart/form-data"
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
								<label for="price">Prix (€)*</label>
								<input
									type="number"
									id="price"
									v-model="form.price"
									min="0"
									step="0.01"
									:class="{ 'input-error': validationErrors.price }"
									placeholder="0.00"
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
								<label>Image du produit</label>
								<div class="image-info">
									<i class="fi fi-rr-info-circle"></i>
									<p>
										L'image ne peut pas être modifiée. Pour changer l'image,
										supprimez ce produit et créez-en un nouveau.
									</p>
								</div>
								<div class="image-preview-static" v-if="form.img">
									<img :src="getImageUrl(form.img)" :alt="form.name" />
								</div>
								<div v-else class="no-image-notice">
									<i class="fi fi-rr-picture"></i>
									<p>Ce produit n'a pas d'image</p>
								</div>
							</div>

							<div class="form-actions">
								<button type="button" @click="confirmCancel" class="reset-btn">
									Annuler
								</button>
								<button
									type="submit"
									class="submit-btn"
									:disabled="isSubmitting"
								>
									<span v-if="isSubmitting">
										<i class="fi fi-rr-spinner"></i> Enregistrement...
									</span>
									<span v-else>
										<i class="fi fi-rr-check"></i> Enregistrer les modifications
									</span>
								</button>
							</div>
						</form>
					</template>
				</div>
			</div>
		</section>

		<!-- Confirmation Modal -->
		<div
			class="modal-overlay"
			v-if="showCancelModal"
			@click.self="showCancelModal = false"
		>
			<div class="modal-content">
				<div class="modal-header">
					<h3>Confirmation</h3>
					<button class="modal-close" @click="showCancelModal = false">
						<i class="fi fi-rr-cross"></i>
					</button>
				</div>
				<div class="modal-body">
					<p>Êtes-vous sûr de vouloir annuler les modifications ?</p>
					<p class="modal-note">
						Les modifications non enregistrées seront perdues.
					</p>
					<div class="modal-actions">
						<button @click="showCancelModal = false" class="btn-secondary">
							Continuer les modifications
						</button>
						<button @click="cancelEdit" class="btn-primary">
							Annuler les modifications
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";

	export default {
		name: "EditProduct",
		data() {
			return {
				productId: null,
				product: {},
				loading: true,
				error: null,
				formError: null,
				successMessage: null,
				categories: [],
				isSubmitting: false,
				showCancelModal: false,
				validationErrors: {},
				form: {
					name: "",
					description: "",
					price: "",
					category_id: "",
					img: "",
				},
				originalForm: {}, // Pour comparer les modifications
			};
		},
		created() {
			this.checkAuthentication();
			this.productId = this.$route.params.id;
		},
		methods: {
			// Check if user is authenticated
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.error = "Vous devez être connecté pour modifier un produit.";
					setTimeout(() => {
						this.$router.push("/connexion");
					}, 2000);
					return;
				}

				axios
					.get(`${window.config.SERVER_URL}/verify-auth`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						if (response.data && response.data.authenticated) {
							this.fetchCategories();
							this.fetchProductDetails();
						} else {
							this.error = "Accès non autorisé.";
							setTimeout(() => {
								this.$router.push("/connexion");
							}, 2000);
						}
					})
					.catch((error) => {
						console.error("Erreur d'authentification:", error);
						this.error = "Problème d'authentification.";
						localStorage.removeItem("token");
						setTimeout(() => {
							this.$router.push("/connexion");
						}, 2000);
					});
			},

			// Fetch product details
			fetchProductDetails() {
				axios
					.get(`${window.config.SERVER_URL}/catalogue/product/${this.productId}`)
					.then((response) => {
						this.product = response.data;

						// Populate the form with product data
						this.form = {
							name: this.product.name || "",
							description: this.product.description || "",
							price: this.product.price || "",
							category_id: this.product.category_id || "",
							img: this.product.img || "",
						};

						// Save a copy of the original data for comparison
						this.originalForm = { ...this.form };

						// Update page title
						document.title = `Modifier ${this.product.name} - SafeSecur`;
					})
					.catch((error) => {
						console.error("Erreur lors du chargement du produit:", error);
						if (error.response && error.response.status === 404) {
							this.error = "Le produit demandé n'existe pas ou a été supprimé.";
						} else {
							this.error =
								"Une erreur est survenue lors du chargement du produit.";
						}
					})
					.finally(() => {
						this.loading = false;
					});
			},

			// Get categories
			fetchCategories() {
				const token = localStorage.getItem("token");

				axios
					.get(`${window.config.SERVER_URL}/categories`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						this.categories = response.data;
					})
					.catch((error) => {
						console.error("Erreur lors du chargement des catégories:", error);
						this.formError = "Erreur lors du chargement des catégories.";
					});
			},

			// Form validation
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

			// Submit form to update product
			submitForm() {
				if (!this.validateForm()) return;

				const token = localStorage.getItem("token");
				if (!token) {
					this.formError = "Vous devez être connecté pour modifier un produit.";
					setTimeout(() => {
						this.$router.push("/connexion");
					}, 2000);
					return;
				}

				// Check if anything has actually changed
				if (this.isFormUnchanged()) {
					this.successMessage = "Aucune modification détectée.";
					return;
				}

				this.isSubmitting = true;
				this.formError = null;
				this.successMessage = null;

				// Prepare data for update (excluding image which can't be modified)
				const updateData = {
					name: this.form.name,
					description: this.form.description,
					price: this.form.price,
					category_id: this.form.category_id,
					img: this.form.img, // Keep the existing image reference
				};

				axios
					.put(
						`${window.config.SERVER_URL}/catalogue/${this.productId}`,
						updateData,
						{
							headers: {
								Authorization: `Bearer ${token}`,
								"Content-Type": "application/json",
							},
						}
					)
					.then(() => {
						this.successMessage = "Produit mis à jour avec succès!";

						// Update original form to match current state
						this.originalForm = { ...this.form };

						// Redirect after 2 seconds
						setTimeout(() => {
							this.$router.push("/catalogue");
						}, 2000);
					})
					.catch((error) => {
						console.error("Erreur lors de la mise à jour du produit:", error);
						if (error.response && error.response.status === 401) {
							this.formError =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/connexion");
							}, 2000);
						} else {
							this.formError =
								"Une erreur est survenue lors de la mise à jour du produit.";
						}
					})
					.finally(() => {
						this.isSubmitting = false;
					});
			},

			// Check if the form has any changes
			isFormUnchanged() {
				return (
					this.form.name === this.originalForm.name &&
					this.form.description === this.originalForm.description &&
					parseFloat(this.form.price) === parseFloat(this.originalForm.price) &&
					parseInt(this.form.category_id) ===
						parseInt(this.originalForm.category_id)
				);
			},

			// Get image URL
			getImageUrl(path) {
				if (!path || path.trim() === "") {
					return "/img/placeholder-product.png";
				}

				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				const normalizedPath = path.startsWith("/") ? path : `/${path}`;
				return `${window.config.SERVER_URL}${normalizedPath}`;
			},

			// Show cancel confirmation modal
			confirmCancel() {
				if (this.isFormUnchanged()) {
					// No changes, just go back
					this.$router.push("/catalogue");
				} else {
					// Show confirmation modal
					this.showCancelModal = true;
				}
			},

			// Cancel edit and go back
			cancelEdit() {
				this.showCancelModal = false;
				this.$router.push("/catalogue");
			},
		},
	};
</script>

<style scoped>
	.edit-product-page {
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
		background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
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

	/* Loading and Error States */
	.loading-container {
		padding: 60px 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 20px;
	}

	.loading-spinner {
		width: 50px;
		height: 50px;
		border: 5px solid #f3f3f3;
		border-top: 5px solid var(--primary-color);
		border-radius: 50%;
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

	.error-container {
		padding: 60px 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 20px;
		text-align: center;
	}

	.error-icon {
		font-size: 3rem;
		color: var(--error-color);
	}

	.btn-back {
		padding: 10px 20px;
		background-color: var(--primary-color);
		color: white;
		border-radius: 4px;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: 500;
		transition: background-color 0.3s ease;
	}

	.btn-back:hover {
		background-color: #004494;
	}

	/* Form Header */
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

	/* Form Messages */
	.success-message,
	.error-message {
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

	/* Form Elements */
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

	/* Image Preview */
	.image-info {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		background-color: #f8f9fa;
		padding: 15px;
		border-radius: 4px;
		margin-bottom: 15px;
	}

	.image-info i {
		color: var(--primary-color);
		font-size: 1.2rem;
	}

	.image-info p {
		margin: 0;
		font-size: 0.9rem;
		color: var(--light-text);
	}

	.image-preview-static {
		width: 100%;
		height: 200px;
		border-radius: 5px;
		overflow: hidden;
	}

	.image-preview-static img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.no-image-notice {
		width: 100%;
		height: 200px;
		background-color: #f8f9fa;
		border: 2px dashed #ddd;
		border-radius: 5px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		gap: 15px;
		color: var(--light-text);
	}

	.no-image-notice i {
		font-size: 2.5rem;
	}

	.no-image-notice p {
		margin: 0;
	}

	/* Form Actions */
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

	/* Modal */
	.modal-overlay {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 1000;
	}

	.modal-content {
		background-color: white;
		border-radius: 8px;
		width: 100%;
		max-width: 500px;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
		overflow: hidden;
	}

	.modal-header {
		padding: 20px;
		border-bottom: 1px solid #eee;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.modal-header h3 {
		margin: 0;
		font-size: 1.5rem;
	}

	.modal-close {
		background: none;
		border: none;
		font-size: 1.2rem;
		cursor: pointer;
		color: var(--light-text);
	}

	.modal-body {
		padding: 20px;
	}

	.modal-note {
		color: var(--light-text);
		font-size: 0.9rem;
	}

	.modal-actions {
		display: flex;
		justify-content: flex-end;
		gap: 10px;
		margin-top: 20px;
	}

	.btn-secondary,
	.btn-primary {
		padding: 10px 15px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: 500;
	}

	.btn-secondary {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.btn-primary {
		background-color: var(--primary-color);
		color: white;
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

		.modal-actions {
			flex-direction: column;
			gap: 15px;
		}

		.btn-secondary,
		.btn-primary {
			width: 100%;
		}
	}
</style>
