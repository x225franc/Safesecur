<template>
	<div class="contact-page">
		<!-- Hero Section -->
		<section class="page-hero">
			<div class="container">
				<h1>Nous contacter</h1>
				<p class="hero-subtitle">
					Envoyez-nous une demande ou demandez un devis personnalisé
				</p>
			</div>
		</section>

		<!-- Contact Form Section -->
		<section class="contact-form-section">
			<div class="container">
				<div class="contact-grid">
					<!-- Contact Info Panel -->
					<div class="contact-info-panel">
						<div class="info-header">
							<h2>Informations de contact</h2>
							<p>
								Nous sommes à votre disposition pour vous aider et répondre à
								vos questions.
							</p>
						</div>

						<div class="info-item">
							<div class="info-icon">
								<i class="fi fi-rr-marker mt-2"></i>
							</div>
							<div class="info-content">
								<h3>Adresse</h3>
								<p>
									Riviera palmeraie, derrière la sodeci - Abidjan (Côte
									d'Ivoire)
								</p>
							</div>
						</div>

						<div class="info-item">
							<div class="info-icon">
								<i class="fi fi-rr-phone-call mt-2"></i>
							</div>
							<div class="info-content">
								<h3>Téléphone</h3>
								<p>+225 27 22 49 50 18</p>
							</div>
						</div>

						<div class="info-item">
							<div class="info-icon">
								<i class="fi fi-rr-phone-call mt-2"></i>
							</div>
							<div class="info-content">
								<h3>Mobile</h3>
								<p>+225 07 07 52 62 72</p>
							</div>
						</div>

						<div class="info-item">
							<div class="info-icon">
								<i class="fi fi-rr-envelope mt-2"></i>
							</div>
							<div class="info-content">
								<h3>E-mail</h3>
								<p>contact@safesecur.com</p>
							</div>
						</div>

						<div class="info-item">
							<div class="info-icon">
								<i class="fi fi-rr-time-check mt-2"></i>
							</div>
							<div class="info-content">
								<h3>Horaires d'ouverture</h3>
								<p>Lundi - Vendredi: 9h - 18h<br />Samedi: 9h - 12h</p>
							</div>
						</div>
					</div>

					<!-- Contact Form -->
					<div class="form-container">
						<h2>Formulaire de contact</h2>
						<p v-if="!isAuthenticated" class="form-intro">
							Remplissez le formulaire ci-dessous pour nous envoyer une demande
							ou demander un devis personnalisé.
							<span class="required-text">* Champs obligatoires</span>
						</p>

						<!-- Ajouter cette alerte avant le formulaire -->
						<div v-if="isAuthenticated" class="auth-alert">
							<div class="auth-content">
								<h3>Vous êtes connecté</h3>
								<p>
									En tant qu'administrateur, vous ne pouvez pas utiliser le
									formulaire de contact. Ce formulaire est destiné uniquement
									aux visiteurs du site.
								</p>
							</div>
						</div>

						<!-- Success Message -->
						<div v-if="formSubmitted" class="success-message">
							<div class="success-icon">
								<i class="fi fi-rr-checkbox mt-2"></i>
							</div>
							<div class="success-content">
								<h3>Message envoyé avec succès!</h3>
								<p>
									Nous avons bien reçu votre demande et nous vous répondrons
									dans les plus brefs délais.
								</p>
							</div>
						</div>

						<!-- Error Message -->
						<div v-if="errorMessage" class="error-message">
							<div class="error-icon">
								<i class="fi fi-rr-exclamation"></i>
							</div>
							<div class="error-content">
								<h3>Une erreur est survenue</h3>
								<p>{{ errorMessage }}</p>
							</div>
						</div>

						<!-- Contact Form -->
						<form
							v-if="!formSubmitted && !isAuthenticated"
							@submit.prevent="submitForm"
							class="contact-form"
						>
							<!-- Personal Info -->
							<div class="form-group">
								<label for="name">Nom complet *</label>
								<input
									type="text"
									id="name"
									v-model="form.name"
									placeholder="Votre nom"
									:class="{ 'input-error': validationErrors.name }"
									required
								/>
								<p v-if="validationErrors.name" class="error-text">
									{{ validationErrors.name }}
								</p>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label for="email">Email *</label>
									<input
										type="email"
										id="email"
										v-model="form.email"
										placeholder="Votre email"
										:class="{ 'input-error': validationErrors.email }"
										required
									/>
									<p v-if="validationErrors.email" class="error-text">
										{{ validationErrors.email }}
									</p>
								</div>

								<div class="form-group">
									<label for="phone">Téléphone</label>
									<input
										type="tel"
										id="phone"
										v-model="form.phone"
										placeholder="Votre numéro de téléphone"
										:class="{ 'input-error': validationErrors.phone }"
									/>
									<p v-if="validationErrors.phone" class="error-text">
										{{ validationErrors.phone }}
									</p>
								</div>
							</div>

							<!-- Request Info -->
							<div class="form-group">
								<label for="subject">Objet *</label>
								<input
									type="text"
									id="subject"
									v-model="form.subject"
									placeholder="L'objet de votre demande"
									:class="{ 'input-error': validationErrors.subject }"
									required
								/>
								<p v-if="validationErrors.subject" class="error-text">
									{{ validationErrors.subject }}
								</p>
							</div>

							<!-- Product Selection (Optional) -->
							<div
								v-if="categories.length > 0"
								class="form-group product-selection"
							>
								<label for="catalogue-select"
									>Produit du catalogue concerné (optionnel)</label
								>
								<p class="product-help-text">
									Sélectionnez le produit concerné par votre demande pour
									obtenir un devis personnalisé
								</p>

								<div class="hierarchical-select-container">
									<select
										id="catalogue-select"
										v-model="selectedProductOption"
										@change="handleProductSelection"
										size="8"
										class="hierarchical-select"
									>
										<optgroup label="Sélectionnez un produit">
											<option value="">-- Aucun produit sélectionné --</option>
										</optgroup>

										<template v-for="category in categories" :key="category.id">
											<optgroup :label="category.name">
												<template
													v-if="
														categoryProducts[category.id] &&
														categoryProducts[category.id].length > 0
													"
												>
													<option
														v-for="product in categoryProducts[category.id]"
														:key="product.id"
														:value="`product-${product.id}`"
													>
														{{ product.name }}
													</option>
												</template>
												<option v-else disabled>
													Aucun produit disponible
												</option>
											</optgroup>
										</template>
									</select>
								</div>
							</div>

							<!-- Selected Product Preview -->
							<div v-if="selectedProduct" class="selected-product-preview">
								<div class="preview-header">
									<h4>Produit sélectionné:</h4>
									<button
										type="button"
										class="btn-remove-selection"
										@click="clearProductSelection"
									>
										<i class="fi fi-rr-cross-small"></i> Supprimer la sélection
									</button>
								</div>
								<div class="product-card">
									<div class="product-image">
										<img
											:src="getImageUrl(selectedProduct.img)"
											:alt="selectedProduct.name"
										/>
									</div>
									<div class="product-details">
										<h4>{{ selectedProduct.name }}</h4>
										<p class="product-category">
											{{ getSelectedCategoryName() }}
										</p>
										<p class="product-price">
											{{ formatPrice(selectedProduct.price) }}
										</p>
									</div>
								</div>
							</div>

							<!-- Message -->
							<div class="form-group">
								<label for="message">Message *</label>
								<textarea
									id="message"
									v-model="form.message"
									rows="6"
									placeholder="Détaillez votre demande ici..."
									:class="{ 'input-error': validationErrors.message }"
									required
								></textarea>
								<p v-if="validationErrors.message" class="error-text">
									{{ validationErrors.message }}
								</p>
							</div>

							<!-- Submit Button -->
							<div class="form-submit">
								<button
									type="submit"
									class="submit-button"
									:disabled="isSubmitting"
								>
									<span v-if="isSubmitting">
										<i class="fi fi-rr-spinner"></i> Envoi en cours...
									</span>
									<span v-else class="">
										<i class="fi fi-rr-paper-plane"></i> Envoyer ma demande
									</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>

		<!-- Map Section -->
		<section class="map-section">
			<div class="map-container">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3972.212233905061!2d-3.9689629250161103!3d5.3845859945943895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNcKwMjMnMDQuNSJOIDPCsDU3JzU5LjAiVw!5e0!3m2!1sen!2sfr!4v1745269031192!5m2!1sen!2sfr"
					width="100%"
					height="450"
					style="border: 0"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
				></iframe>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from "axios";
	import placeholderImage from "@/assets/logo.png";

	export default {
		name: "Contacts",
		data() {
			return {
				form: {
					name: "",
					email: "",
					phone: "",
					subject: "",
					message: "",
					product_id: "",
				},
				validationErrors: {},
				errorMessage: null,
				isSubmitting: false,
				formSubmitted: false,
				categories: [],
				products: [],
				selectedCategoryId: "",
				isLoadingProducts: false,
				placeholderPath: placeholderImage,
				lastSubmitTime: 0, // Ajout pour anti-spam
				selectedProductOption: "", // pour le sélecteur hiérarchique
				categoryProducts: {}, // stockage des produits par catégorie
				isAuthenticated: false, // Ajouter cette propriété
			};
		},
		computed: {
			filteredProducts() {
				if (!this.selectedCategoryId) return [];
				return this.products.filter(
					(product) => product.category_id === parseInt(this.selectedCategoryId)
				);
			},
			selectedProduct() {
				if (!this.form.product_id) return null;
				return this.products.find(
					(product) => product.id === parseInt(this.form.product_id)
				);
			},
		},
		created() {
			this.checkAuthentication();
			this.fetchCategories();
			this.fetchProducts();
		},
		methods: {
			// Fetch categories for dropdown
			fetchCategories() {
				axios
					.get(`${window.config.API_URL}/categories/public`)
					.then((response) => {
						this.categories = response.data;
					})
					.catch((error) => {
						console.error("Erreur lors du chargement des catégories:", error);
					});
			},

			// Fetch all products
			fetchProducts() {
				axios
					.get(`${window.config.API_URL}/catalogue`)
					.then((response) => {
						this.products = response.data;

						// Organiser les produits par catégorie
						this.categoryProducts = {};
						this.products.forEach((product) => {
							if (!this.categoryProducts[product.category_id]) {
								this.categoryProducts[product.category_id] = [];
							}
							this.categoryProducts[product.category_id].push(product);
						});
					})
					.catch((error) => {
						console.error("Erreur lors du chargement des produits:", error);
					});
			},

			// Update products list when category changes
			updateProductsList() {
				this.form.product_id = "";
			},

			// Get category name for selected product
			getSelectedCategoryName() {
				if (!this.selectedProduct || !this.selectedCategoryId) return "";
				const category = this.categories.find(
					(cat) => cat.id === parseInt(this.selectedCategoryId)
				);
				return category ? category.name : "";
			},

			// Clear product selection
			clearProductSelection() {
				this.selectedProductOption = "";
				this.selectedCategoryId = "";
				this.form.product_id = "";
			},

			// Format price with currency
			formatPrice(price) {
				if (!price) return "Prix non disponible";

				return new Intl.NumberFormat("fr-FR", {
					style: "currency",
					currency: "XOF",
					minimumFractionDigits: 2,
				}).format(price);
			},

			// Get image URL with placeholder support
			getImageUrl(path) {
				if (!path || path.trim() === "") {
					return this.placeholderPath;
				}

				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				const normalizedPath = path.startsWith("/") ? path : `/${path}`;
				return `${window.config.API_URL}${normalizedPath}`;
			},

			// Form validation
			validateForm() {
				this.validationErrors = {};

				if (!this.form.name.trim()) {
					this.validationErrors.name = "Le nom est requis";
				}

				if (!this.form.email.trim()) {
					this.validationErrors.email = "L'email est requis";
				} else if (!this.isValidEmail(this.form.email)) {
					this.validationErrors.email = "Veuillez fournir un email valide";
				}

				if (this.form.phone && !this.isValidPhone(this.form.phone)) {
					this.validationErrors.phone = "Numéro de téléphone invalide";
				}

				if (!this.form.subject.trim()) {
					this.validationErrors.subject = "L'objet est requis";
				}

				if (!this.form.message.trim()) {
					this.validationErrors.message = "Le message est requis";
				} else if (this.form.message.length < 10) {
					this.validationErrors.message =
						"Le message doit contenir au moins 10 caractères";
				}

				return Object.keys(this.validationErrors).length === 0;
			},

			// Email validation helper
			isValidEmail(email) {
				const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				return re.test(email);
			},

			// Phone validation helper
			isValidPhone(phone) {
				// Very basic validation - can be enhanced as needed
				return phone.trim().length >= 8;
			},

			// Reset form to initial state
			resetForm() {
				this.form = {
					name: "",
					email: "",
					phone: "",
					subject: "",
					message: "",
					product_id: "",
				};
				this.validationErrors = {};
				this.errorMessage = null;
				this.formSubmitted = false;
				this.selectedCategoryId = "";
			},

			// Submit form
			submitForm() {
				// Vérifier si un envoi récent a été effectué (anti-spam)
				const now = Date.now();
				const timeSinceLastSubmit = now - this.lastSubmitTime;
				const minTimeBetweenSubmits = 30000; // 30 secondes minimum entre chaque envoi

				if (timeSinceLastSubmit < minTimeBetweenSubmits) {
					this.errorMessage = `Veuillez patienter ${Math.ceil(
						(minTimeBetweenSubmits - timeSinceLastSubmit) / 1000
					)} secondes avant de soumettre un nouveau message.`;
					// Scroll to error message
					this.$nextTick(() => {
						const errorMessage = document.querySelector(".error-message");
						if (errorMessage) {
							errorMessage.scrollIntoView({
								behavior: "smooth",
								block: "center",
							});
						}
					});
					return;
				}

				if (!this.validateForm()) {
					// Scroll to the first error
					this.$nextTick(() => {
						const firstError = document.querySelector(".error-text");
						if (firstError) {
							firstError.scrollIntoView({
								behavior: "smooth",
								block: "center",
							});
						}
					});
					return;
				}

				this.isSubmitting = true;
				this.errorMessage = null;

				// Prepare submission data
				const formData = {
					...this.form,
				};

				// Add product details if a product is selected
				if (this.selectedProduct) {
					formData.product_details = {
						id: this.selectedProduct.id,
						name: this.selectedProduct.name,
						category: this.getSelectedCategoryName(),
						price: this.selectedProduct.price,
					};
				}

				// Send to backend
				axios
					.post(`${window.config.API_URL}/contact`, formData)
					.then((response) => {
						this.formSubmitted = true;
						// Enregistrer l'heure d'envoi
						this.lastSubmitTime = Date.now();
						// Scroll to success message
						this.$nextTick(() => {
							const successMessage = document.querySelector(".success-message");
							if (successMessage) {
								successMessage.scrollIntoView({
									behavior: "smooth",
									block: "center",
								});
							}
						});
					})
					.catch((error) => {
						console.error("Erreur lors de l'envoi du formulaire:", error);
						this.errorMessage =
							"Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.";
						// Scroll to error message
						this.$nextTick(() => {
							const errorMessage = document.querySelector(".error-message");
							if (errorMessage) {
								errorMessage.scrollIntoView({
									behavior: "smooth",
									block: "center",
								});
							}
						});
					})
					.finally(() => {
						this.isSubmitting = false;
					});
			},

			// Nouvelle méthode pour gérer la sélection dans le sélecteur hiérarchique
			handleProductSelection() {
				if (!this.selectedProductOption || this.selectedProductOption === "") {
					this.form.product_id = "";
					return;
				}

				// Format de la valeur: "product-123"
				const productId = parseInt(this.selectedProductOption.split("-")[1]);
				if (productId) {
					this.form.product_id = productId;
					// Trouver la catégorie du produit sélectionné
					const product = this.products.find((p) => p.id === productId);
					if (product) {
						this.selectedCategoryId = product.category_id;
					}
				}
			},

			// Ajouter cette méthode pour vérifier l'authentification
			checkAuthentication() {
				const token = localStorage.getItem("token");
				if (!token) {
					this.isAuthenticated = false;
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
							this.isAuthenticated = false;
						}
					})
					.catch((error) => {
						console.error("Erreur de vérification d'authentification:", error);
						this.isAuthenticated = false;
					});
			},
		},
	};
</script>

<style scoped>
	.contact-page {
		--primary-color: #b30000;
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

	.page-hero {
		background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
			url("@/assets/ressources/background.jpg") center/cover;
		color: white;
		text-align: center;
		padding: 80px 0;
	}

	.page-hero h1 {
		font-size: 3.5rem;
		margin-bottom: 15px;
		font-weight: 700;
		text-transform: uppercase;
	}

	.hero-subtitle {
		font-size: 1.4rem;
		max-width: 800px;
		margin: 0 auto;
	}

	/* Contact Form Section */
	.contact-form-section {
		background-color: var(--secondary-color);
	}

	.contact-grid {
		display: grid;
		grid-template-columns: 1fr 2fr;
		gap: 40px;
	}

	/* Contact Info Panel */
	.contact-info-panel {
		background-color: var(--primary-color);
		color: white;
		padding: 40px;
		border-radius: 10px;
		height: fit-content;
	}

	.info-header {
		margin-bottom: 30px;
	}

	.info-header h2 {
		font-size: 1.8rem;
		margin-bottom: 10px;
	}

	.info-header p {
		opacity: 0.8;
		line-height: 1.6;
	}

	.info-item {
		display: flex;
		margin-bottom: 25px;
		align-items: flex-start;
	}

	.info-icon {
		font-size: 1.5rem;
		margin-right: 15px;
		background-color: rgba(255, 255, 255, 0.2);
		height: 40px;
		width: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.info-content h3 {
		font-size: 1.2rem;
		margin: 0 0 5px 0;
		font-weight: 600;
	}

	.info-content p {
		margin: 0;
		opacity: 0.8;
		line-height: 1.6;
	}

	/* Form Container */
	.form-container {
		background-color: #fff;
		padding: 40px;
		border-radius: 10px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
	}

	.form-container h2 {
		font-size: 2rem;
		margin-bottom: 10px;
	}

	.form-intro {
		margin-bottom: 25px;
		color: var(--light-text);
		line-height: 1.6;
	}

	.required-text {
		font-size: 0.9rem;
		color: var(--error-color);
		margin-left: 5px;
	}

	/* Form Elements */
	.contact-form {
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.form-group {
		margin-bottom: 5px;
	}

	.form-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
	}

	label {
		display: block;
		margin-bottom: 8px;
		font-weight: 600;
	}

	input[type="text"],
	input[type="email"],
	input[type="tel"],
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
		outline: none;
		border-color: var(--primary-color);
	}

	.input-error {
		border-color: var(--error-color) !important;
	}

	.error-text {
		color: var(--error-color);
		font-size: 0.9rem;
		margin-top: 5px;
		margin-bottom: 0;
	}

	.info-text {
		color: var(--light-text);
		font-size: 0.9rem;
		margin-top: 5px;
	}

	/* Product Selection */
	.product-selection {
		border: 1px solid #ffb3b3;
		padding: 20px;
		border-radius: 8px;
		background-color: #ffeaea;
	}

	.product-help-text {
		color: var(--light-text);
		font-size: 0.9rem;
		margin-bottom: 15px;
	}

	.product-select-container {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
	}

	/* Hierarchical Select Styling */
	.hierarchical-select-container {
		position: relative;
		margin-bottom: 15px;
	}

	.hierarchical-select {
		width: 100%;
		padding: 10px;
		border: 1px solid #b30000;
		border-radius: 4px;
		font-size: 1rem;
		background-color: white;
		overflow-y: auto;
	}

	.hierarchical-select optgroup {
		font-weight: bold;
		color: var(--ds);
		background-color: var(--t);
		padding: 5px;
	}

	.hierarchical-select option {
		padding: 8px 15px;
	}

	.hierarchical-select option:hover {
		background-color: #ffeaea;
	}

	/* Augmenter la hauteur sur mobile */
	@media (max-width: 768px) {
		.hierarchical-select {
			height: 200px;
		}
	}

	/* Selected Product Preview */
	.selected-product-preview {
		background-color: #ffeaea;
		border-radius: 8px;
		padding: 20px;
		margin-top: 10px;
	}

	.preview-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 15px;
	}

	.preview-header h4 {
		margin: 0;
		font-size: 1.1rem;
	}

	.btn-remove-selection {
		background: none;
		border: none;
		color: var(--error-color);
		font-size: 0.9rem;
		cursor: pointer;
		padding: 5px 10px;
		display: flex;
		align-items: center;
		gap: 5px;
	}

	.product-card {
		display: flex;
		gap: 15px;
		background-color: white;
		border: 1px solid #eaeaea;
		border-radius: 6px;
		overflow: hidden;
	}

	.product-image {
		width: 100px;
		height: 100px;
		overflow: hidden;
		flex-shrink: 0;
	}

	.product-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.product-details {
		padding: 10px 10px 10px 0;
	}

	.product-details h4 {
		margin: 0 0 5px 0;
		font-size: 1rem;
	}

	.product-category {
		color: var(--light-text);
		margin: 0 0 5px 0;
		font-size: 0.9rem;
	}

	.product-price {
		font-weight: bold;
		color: var(--primary-color);
		margin: 0;
	}

	.checkbox-container {
		display: flex;
		position: relative;
		padding-left: 35px;
		cursor: pointer;
		font-weight: normal;
		user-select: none;
		align-items: center;
	}

	.checkbox-container input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
		height: 0;
		width: 0;
	}

	.checkmark {
		position: absolute;
		top: 0;
		left: 0;
		height: 20px;
		width: 20px;
		border: 2px solid #ccc;
		border-radius: 4px;
	}

	.checkbox-container:hover input ~ .checkmark {
		border-color: var(--primary-color);
	}

	.checkbox-container input:checked ~ .checkmark {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	.checkbox-container input:checked ~ .checkmark:after {
		display: block;
	}

	.checkbox-container .checkmark:after {
		left: 6px;
		top: 2px;
		width: 5px;
		height: 10px;
		border: solid white;
		border-width: 0 2px 2px 0;
		transform: rotate(45deg);
	}

	/* Submit Button */
	.form-submit {
		margin-top: 20px;
	}

	.submit-button {
		width: 100%;
		padding: 14px 25px;
		background-color: var(--primary-color);
		color: white;
		border: none;
		border-radius: 4px;
		font-size: 1.1rem;
		font-weight: 600;
		cursor: pointer;
		transition: background-color 0.3s ease;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
	}

	.submit-button:hover:not(:disabled) {
		background-color: #a50000;
	}

	.submit-button:disabled {
		background-color: #ca7979;
		cursor: not-allowed;
	}

	/* Success Message */
	.success-message {
		background-color: #ebf9f0;
		border: 1px solid #bfe5d0;
		padding: 25px;
		border-radius: 8px;
		display: flex;
		gap: 20px;
		margin-bottom: 30px;
	}

	.success-icon {
		width: 50px;
		height: 50px;
		background-color: var(--success-color);
		color: white;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		flex-shrink: 0;
	}

	.success-content h3 {
		margin: 0 0 10px 0;
		color: var(--success-color);
		font-size: 1.5rem;
	}

	.success-content p {
		margin: 0 0 15px 0;
		line-height: 1.6;
	}

	.btn-new-message {
		background-color: var(--success-color);
		color: white;
		border: none;
		padding: 10px 20px;
		border-radius: 4px;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		font-weight: 500;
		transition: background-color 0.3s;
	}

	.btn-new-message:hover {
		background-color: #1f7a39;
	}

	/* Error Message */
	.error-message {
		background-color: #fbeeef;
		border: 1px solid #ecc8cb;
		padding: 20px;
		border-radius: 8px;
		display: flex;
		gap: 15px;
		margin-bottom: 30px;
	}

	.error-icon {
		width: 50px;
		height: 50px;
		background-color: var(--error-color);
		color: white;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		flex-shrink: 0;
	}

	.error-content h3 {
		margin: 0 0 10px 0;
		color: var(--error-color);
		font-size: 1.5rem;
	}

	.error-content p {
		margin: 0;
		line-height: 1.6;
	}

	/* Map Section */
	.map-section {
		padding: 0;
	}

	.map-container {
		height: 450px;
		width: 100%;
	}

	iframe {
		border: 0;
		width: 100%;
		height: 100%;
	}

	/* Responsive Design */
	@media (max-width: 992px) {
		.contact-grid {
			grid-template-columns: 1fr;
		}

		.product-select-container {
			grid-template-columns: 1fr;
			gap: 15px;
		}
	}

	@media (max-width: 768px) {
		.page-hero h1 {
			font-size: 2.5rem;
		}

		.hero-subtitle {
			font-size: 1.2rem;
		}

		.form-container {
			padding: 25px;
		}

		.form-row {
			grid-template-columns: 1fr;
			gap: 15px;
		}

		.success-message,
		.error-message {
			flex-direction: column;
			align-items: center;
			text-align: center;
		}

		.auth-alert {
			flex-direction: column;
			align-items: center;
			text-align: center;
		}
	}

	@media (max-width: 480px) {
		.contact-info-panel {
			padding: 25px;
		}

		.info-item {
			flex-direction: column;
			align-items: center;
			text-align: center;
			padding-bottom: 15px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		}

		.info-icon {
			margin-right: 0;
			margin-bottom: 10px;
		}
	}

	/* Ajouter ces styles à la fin du bloc de style */
	.auth-alert {
		background-color: #e6f0ff;
		border: 1px solid #b3d1ff;
		padding: 25px;
		border-radius: 8px;
		display: flex;
		gap: 20px;
		margin-bottom: 30px;
	}

	.auth-icon {
		width: 50px;
		height: 50px;
		background-color: var(--primary-color);
		color: white;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		flex-shrink: 0;
	}

	.auth-content h3 {
		margin: 0 0 10px 0;
		color: var(--primary-color);
		font-size: 1.5rem;
	}

	.auth-content p {
		margin: 0 0 15px 0;
		line-height: 1.6;
	}

	.btn-dashboard {
		background-color: var(--primary-color);
		color: white;
		border: none;
		padding: 10px 20px;
		border-radius: 4px;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		font-weight: 500;
		transition: background-color 0.3s;
	}

	.btn-dashboard:hover {
		background-color: #004494;
	}
</style>
