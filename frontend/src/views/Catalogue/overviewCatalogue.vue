<template>
	<div class="product-details-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>{{ productName }}</h1>
				<p class="hero-subtitle">Détails du produit</p>
			</div>
		</section>

		<!-- Content Section -->
		<section class="details-section">
			<div class="container">
				<!-- Loading State -->
				<div v-if="loading" class="loading-container">
					<div class="loading-spinner"></div>
					<p>Chargement des détails du produit...</p>
				</div>

				<!-- Error State -->
				<div v-else-if="error" class="error-container">
					<div class="error-icon"><i class="fi fi-rr-exclamation"></i></div>
					<p>{{ error }}</p>
					<router-link to="/catalogue" class="btn-back">
						<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
					</router-link>
				</div>

				<!-- Product Details -->
				<div v-else-if="product" class="product-details-container">
					<div class="product-navigation">
						<router-link to="/catalogue" class="btn-navigation">
							<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
						</router-link>
						<div v-if="isAuthenticated" class="admin-actions">
							<router-link
								:to="`/edit-product/${product.id}`"
								class="btn-edit-product"
							>
								<i class="fi fi-rr-edit"></i> Modifier
							</router-link>
						</div>
					</div>

					<div class="product-details-grid">
						<!-- Product Image -->
						<div class="product-image-container">
							<img
								:src="getImageUrl(product.img)"
								:alt="product.name"
								class="product-image"
							/>
						</div>

						<!-- Product Info -->
						<div class="product-info">
							<h2 class="product-title">{{ product.name }}</h2>

							<div class="product-price">
								{{ formatPrice(product.price) }}
							</div>

							<div class="product-category">
								<span class="info-label">Catégorie:</span>
								<router-link
									:to="`/catalogue?category=${product.category_id}`"
									class="category-link"
								>
									{{ categoryName }}
								</router-link>
							</div>

							<div class="product-description">
								<h3>Description</h3>
								<div class="description-content">
									{{ product.description }}
								</div>
							</div>

							<div class="contact-cta" v-if="!isAuthenticated">
								<h3>Intéressé par ce produit?</h3>
								<p>
									Contactez-nous pour plus d'informations ou pour passer une
									commande.
								</p>
								<router-link to="/contacts" class="btn-contact">
									<i class="fi fi-rr-phone-call"></i> Nous contacter
								</router-link>
							</div>
						</div>
					</div>

					<!-- Related Products Section -->
					<div
						v-if="relatedProducts.length > 0"
						class="related-products-section"
					>
						<h2 class="section-title">Produits similaires</h2>
						<div class="related-products-grid">
							<div
								v-for="relatedProduct in relatedProducts"
								:key="relatedProduct.id"
								class="related-product-card"
							>
								<router-link
									:to="`/catalogue/${relatedProduct.id}`"
									class="related-product-link"
								>
									<div class="related-product-image">
										<img
											:src="getImageUrl(relatedProduct.img)"
											:alt="relatedProduct.name"
										/>
									</div>
									<div class="related-product-info">
										<h4 class="related-product-title">
											{{ relatedProduct.name }}
										</h4>
										<p class="related-product-price">
											{{ formatPrice(relatedProduct.price) }}
										</p>
									</div>
								</router-link>
							</div>
						</div>
					</div>
				</div>

				<!-- Product Not Found -->
				<div v-else class="not-found-container">
					<div class="not-found-icon"><i class="fi fi-rr-exclamation"></i></div>
					<h2>Produit introuvable</h2>
					<p>Le produit que vous recherchez n'existe pas ou a été supprimé.</p>
					<router-link to="/catalogue" class="btn-back-to-catalogue">
						<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
					</router-link>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	import axios from "axios";
	import placeholderImage from "@/assets/logo.png";

	export default {
		name: "ProductDetails",
		data() {
			return {
				product: null,
				loading: true,
				error: null,
				categoryName: "",
				relatedProducts: [],
				isAuthenticated: false,
				placeholderPath: placeholderImage,
			};
		},
		computed: {
			productName() {
				return this.product ? this.product.name : "Détails du produit";
			},
			productId() {
				return this.$route.params.id;
			},
		},
		created() {
			this.checkAuthentication();
			this.fetchProductDetails();
		},
		watch: {
			// Reload data when route changes (for navigation between related products)
			$route(to, from) {
				if (to.params.id !== from.params.id) {
					this.loading = true;
					this.fetchProductDetails();
				}
			},
		},
		methods: {
			// Check if user is authenticated
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.isAuthenticated = false;
					return;
				}

				axios
					.get(`${window.config.SERVER_URL}/verify-auth`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						this.isAuthenticated = response.data && response.data.authenticated;
					})
					.catch(() => {
						this.isAuthenticated = false;
						localStorage.removeItem("token");
					});
			},

			// Get product details
			fetchProductDetails() {
				this.error = null;
				this.loading = true;

				// Utiliser la route générique du catalogue avec filtre par ID
				axios
					.get(`${window.config.SERVER_URL}/catalogue/product/${this.productId}`)
					.then((response) => {
						if (response.data) {
							this.product = response.data;

							// Get category name
							this.fetchCategoryName();

							// Get related products from same category
							this.fetchRelatedProducts();

							// Update document title
							document.title = `${this.product.name} - SafeSecur`;
						} else {
							this.error = "Produit introuvable.";
						}
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

			// Get category name for this product
			fetchCategoryName() {
				if (!this.product || !this.product.category_id) {
					this.categoryName = "Non catégorisé";
					return;
				}

				// Utilisation des catégories déjà chargées dans le catalogue
				axios
					.get(
						`${window.config.SERVER_URL}/catalogue/category-name/${this.product.category_id}`
					)
					.then((response) => {
						if (response.data && response.data.name) {
							this.categoryName = response.data.name;
						} else {
							this.categoryName = "Non catégorisé";
						}
					})
					.catch(() => {
						this.categoryName = "Non catégorisé";
					});
			},

			// Get related products (same category, excluding current product)
			fetchRelatedProducts() {
				if (!this.product || !this.product.category_id) {
					this.relatedProducts = [];
					return;
				}

				// Utiliser la route des produits filtrée par catégorie
				axios
					.get(
						`${window.config.SERVER_URL}/catalogue/by-category/${this.product.category_id}`
					)
					.then((response) => {
						// Filter out current product and limit to 4 products
						this.relatedProducts = response.data
							.filter((prod) => prod.id !== parseInt(this.productId))
							.slice(0, 4);
					})
					.catch((error) => {
						console.error(
							"Erreur lors du chargement des produits similaires:",
							error
						);
						this.relatedProducts = [];
					});
			},

			// Get image URL
			getImageUrl(path) {
				// Si aucun chemin d'image n'est fourni, ou s'il est vide
				if (!path || path.trim() === "") {
					// Retourner le chemin de l'image importée
					return this.placeholderPath;
				}

				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				const normalizedPath = path.startsWith("/") ? path : `/${path}`;
				return `${window.config.SERVER_URL}${normalizedPath}`;
			},

			// Format price
			formatPrice(price) {
				if (!price) return "Prix non disponible";

				return new Intl.NumberFormat("fr-FR", {
					style: "currency",
					currency: "XOF",
					minimumFractionDigits: 2,
				}).format(price);
			},
		},
	};
</script>

<style scoped>
	/* Styles identiques à la version précédente */
	.product-details-page {
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

	/* Hero Section */
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

	/* Details Section */
	.details-section {
		background-color: var(--secondary-color);
	}

	/* Loading State */
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

	/* Error State */
	.error-container,
	.not-found-container {
		padding: 60px 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 20px;
		text-align: center;
		max-width: 600px;
		margin: 0 auto;
	}

	.error-icon,
	.not-found-icon {
		font-size: 3rem;
		color: var(--error-color);
	}

	.not-found-container h2 {
		margin: 0;
		font-size: 2rem;
	}

	.btn-back,
	.btn-back-to-catalogue {
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

	.btn-back:hover,
	.btn-back-to-catalogue:hover {
		background-color: #004494;
	}

	/* Product Details Container */
	.product-details-container {
		background-color: white;
		border-radius: 10px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
		padding: 30px;
		margin-bottom: 40px;
	}

	.product-navigation {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 30px;
	}

	.btn-navigation {
		padding: 10px 15px;
		background-color: var(--secondary-color);
		color: var(--text-color);
		border-radius: 4px;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: 500;
		transition: background-color 0.3s ease;
	}

	.btn-navigation:hover {
		background-color: #e2e6ea;
	}

	.admin-actions {
		display: flex;
		gap: 10px;
	}

	.btn-edit-product {
		padding: 10px 15px;
		background-color: #e8f2ff;
		color: var(--primary-color);
		border-radius: 4px;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: 500;
		transition: background-color 0.3s ease;
	}

	.btn-edit-product:hover {
		background-color: #d1e6ff;
	}

	/* Product Details Grid */
	.product-details-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 40px;
		margin-bottom: 40px;
	}

	.product-image-container {
		position: relative;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
	}

	.product-image {
		width: 100%;
		height: 400px;
		object-fit: cover;
		display: block;
	}

	.category-badge {
		position: absolute;
		top: 15px;
		left: 15px;
		background-color: var(--primary-color);
		color: white;
		padding: 5px 12px;
		border-radius: 20px;
		font-size: 0.8rem;
		font-weight: 600;
	}

	.product-info {
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.product-title {
		font-size: 2.2rem;
		margin: 0;
		font-weight: 700;
		color: var(--text-color);
	}

	.product-price {
		font-size: 2rem;
		font-weight: 700;
		color: var(--primary-color);
		margin-bottom: 10px;
	}

	.product-category {
		margin-bottom: 10px;
	}

	.info-label {
		font-weight: 600;
		margin-right: 8px;
	}

	.category-link {
		color: var(--primary-color);
		text-decoration: none;
		font-weight: 500;
		transition: color 0.3s ease;
	}

	.category-link:hover {
		text-decoration: underline;
	}

	.product-description {
		margin-top: 10px;
	}

	.product-description h3 {
		font-size: 1.3rem;
		margin-bottom: 15px;
		font-weight: 600;
	}

	.description-content {
		line-height: 1.6;
		white-space: pre-line;
	}

	.contact-cta {
		margin-top: 20px;
		background-color: #f8f9fa;
		padding: 20px;
		border-radius: 8px;
		border-left: 4px solid var(--primary-color);
	}

	.contact-cta h3 {
		margin-top: 0;
		font-size: 1.3rem;
	}

	.contact-cta p {
		margin-bottom: 15px;
	}

	.btn-contact {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 25px;
		background-color: var(--primary-color);
		color: white;
		border-radius: 4px;
		text-decoration: none;
		font-weight: 600;
		transition: background-color 0.3s ease;
	}

	.btn-contact:hover {
		background-color: #004494;
	}

	/* Related Products Section */
	.related-products-section {
		margin-top: 60px;
	}

	.section-title {
		font-size: 1.8rem;
		margin-bottom: 30px;
		position: relative;
		padding-bottom: 10px;
	}

	.section-title::after {
		content: "";
		position: absolute;
		bottom: 0;
		left: 0;
		width: 60px;
		height: 3px;
		background-color: var(--primary-color);
	}

	.related-products-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
		gap: 25px;
	}

	.related-product-card {
		background-color: white;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
		transition: transform 0.3s, box-shadow 0.3s;
	}

	.related-product-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
	}

	.related-product-link {
		text-decoration: none;
		color: inherit;
		display: block;
	}

	.related-product-image {
		height: 180px;
		overflow: hidden;
	}

	.related-product-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s;
	}

	.related-product-card:hover .related-product-image img {
		transform: scale(1.1);
	}

	.related-product-info {
		padding: 15px;
	}

	.related-product-title {
		margin: 0 0 10px 0;
		font-size: 1.1rem;
		font-weight: 600;
		color: var(--text-color);
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.related-product-price {
		color: var(--primary-color);
		font-weight: 700;
		margin: 0;
	}

	/* Responsive Styles */
	@media (max-width: 900px) {
		.product-details-grid {
			grid-template-columns: 1fr;
		}

		.product-image {
			height: 350px;
		}

		.page-hero h1 {
			font-size: 2.2rem;
		}
	}

	@media (max-width: 600px) {
		.product-navigation {
			flex-direction: column;
			align-items: flex-start;
			gap: 15px;
		}

		.admin-actions {
			width: 100%;
		}

		.btn-edit-product {
			width: 100%;
			justify-content: center;
		}

		.related-products-grid {
			grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		}

		.product-image {
			height: 300px;
		}
	}
</style>
