<template>
	<div class="catalogue-page">
		<!-- Hero Section -->
		<section class="page-hero">
			<div class="container">
				<h1>Catalogue de produits</h1>
				<p class="hero-subtitle">Découvrez notre gamme complète de produits</p>
			</div>
		</section>

		<!-- Admin controls - only visible for authenticated users -->
		<div class="admin-controls" v-if="isAuthenticated">
			<div class="container">
				<div class="admin-toolbar">
					<h2><i class="fi fi-rr-settings-sliders"></i> Administration</h2>

					<div class="admin-actions">
						<router-link to="/category" class="btn-manage">
							<i class="fi fi-rr-list"></i> Gérer les catégories
						</router-link>
						<!-- Modifier le bouton comme suit -->
						<button
							v-if="isAuthenticated"
							@click="showCreateProductModal(activeCategory)"
							class="btn-add-inline"
						>
							<i class="fi fi-rr-plus"></i> Ajouter un produit
						</button>
					</div>
				</div>

				<!-- Warning if no categories exist -->
				<div v-if="categories.length === 0 && !loading" class="warning-message">
					<i class="fi fi-rr-info-circle"></i>
					<div>
						<strong>Attention:</strong> Vous devez créer au moins une catégorie
						avant de pouvoir ajouter des produits.
						<router-link to="/category" class="warning-link"
							>Créer une catégorie</router-link
						>
					</div>
				</div>
			</div>
		</div>

		<!-- Content Section -->
		<section class="catalogue-content">
			<div class="container">
				<!-- Loading State -->
				<div v-if="loading" class="loading-container">
					<div class="loading-spinner"></div>
					<p>Chargement du catalogue...</p>
				</div>

				<!-- Error State -->
				<div v-else-if="error" class="error-container">
					<div class="error-icon"><i class="fi fi-rr-exclamation"></i></div>
					<p>{{ error }}</p>
					<button @click="fetchCatalogue" class="btn-retry">
						<i class="fi fi-rr-refresh"></i> Réessayer
					</button>
				</div>

				<!-- Empty State (no categories) -->
				<div v-else-if="categories.length === 0" class="empty-state">
					<div class="empty-icon"><i class="fi fi-rr-box-open"></i></div>
					<h2 v-if="!isAuthenticated">Catalogue en construction</h2>
					<p v-if="!isAuthenticated">Notre catalogue de produits sera bientôt disponible.</p>
					<p v-if="isAuthenticated" class="admin-hint">
						Commencez par créer des catégories pour pouvoir y ajouter des
						produits.
					</p>
				</div>

				<!-- Empty State (no products in categories) -->
				<div v-else-if="totalProducts === 0" class="empty-state">
					<div class="empty-icon"><i class="fi fi-rr-box-open"></i></div>
					<h2 v-if="!isAuthenticated">Catalogue en construction</h2>
					<p v-if="!isAuthenticated">Nos produits seront bientôt disponibles.</p>
					<p v-if="isAuthenticated" class="admin-hint">
						Commencez par ajouter des produits à vos catégories.
					</p>
				</div>

				<!-- Catalogue Content -->
				<div v-else>
					<!-- Category Tabs -->
					<div class="category-tabs">
						<button
							class="tab-btn"
							:class="{ active: activeCategory === 'all' }"
							@click="activeCategory = 'all'"
						>
							Tous les produits
							<span class="count-badge">{{ totalProducts }}</span>
						</button>

						<button
							v-for="category in categories"
							:key="category.id"
							class="tab-btn"
							:class="{ active: activeCategory === category.id }"
							@click="activeCategory = category.id"
						>
							{{ category.name }}
							<span class="count-badge">{{ category.products.length }}</span>
						</button>
					</div>

					<!-- Products Grid -->
					<div class="products-grid">
						<template v-if="activeCategory === 'all'">
							<div
								v-for="product in allProducts"
								:key="product.id"
								class="product-card"
							>
								<div class="product-image">
									<img :src="getImageUrl(product.img)" :alt="product.name" />
									<div class="category-tag">
										{{ getCategoryName(product.category_id) }}
									</div>
								</div>
								<div class="product-info">
									<h3 class="product-title">{{ product.name }}</h3>
									<p class="product-price">{{ formatPrice(product.price) }}</p>

									<!-- Actions -->
									<div class="product-actions">
										<router-link
											:to="`/catalogue/${product.id}`"
											class="btn-details"
										>
											<i class="fi fi-rr-eye"></i> Détails
										</router-link>
										<div v-if="isAuthenticated" class="admin-product-actions">
											<router-link
												:to="`/edit-catalogue/${product.id}`"
												class="btn-edit"
											>
												<i class="fi fi-rr-edit"></i>
											</router-link>
											<button
												@click="confirmDeleteProduct(product)"
												class="btn-delete"
											>
												<i class="fi fi-rr-trash"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</template>

						<template v-else>
							<div
								v-for="product in filteredProducts"
								:key="product.id"
								class="product-card"
							>
								<div class="product-image">
									<img :src="getImageUrl(product.img)" :alt="product.name" />
								</div>
								<div class="product-info">
									<h3 class="product-title">{{ product.name }}</h3>
									<p class="product-price">{{ formatPrice(product.price) }}</p>

									<!-- Actions -->
									<div class="product-actions">
										<router-link
											:to="`/catalogue/${product.id}`"
											class="btn-details"
										>
											<i class="fi fi-rr-eye"></i> Détails
										</router-link>
										<div v-if="isAuthenticated" class="admin-product-actions">
											<router-link
												:to="`/edit-catalogue/${product.id}`"
												class="btn-edit"
											>
												<i class="fi fi-rr-edit"></i>
											</router-link>
											<button
												@click="confirmDeleteProduct(product)"
												class="btn-delete"
											>
												<i class="fi fi-rr-trash"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</template>

						<!-- Empty Category Message -->
						<div
							v-if="filteredProducts.length === 0 && activeCategory !== 'all'"
							class="empty-category"
						>
							<i class="fi fi-rr-info-circle"></i>
							<p>Aucun produit dans cette catégorie pour le moment.</p>
							<button
								v-if="isAuthenticated"
								@click="showCreateProductModal(activeCategory)"
								class="btn-add-inline"
							>
								<i class="fi fi-rr-plus"></i> Ajouter un produit
							</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Delete Product Confirmation Modal -->
		<div
			class="modal-overlay"
			v-if="showDeleteModal"
			@click.self="closeDeleteModal"
		>
			<div class="modal-content delete-modal">
				<div class="modal-header">
					<h3>Confirmer la suppression</h3>
					<button class="modal-close" @click="closeDeleteModal">
						<i class="fi fi-rr-cross"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="delete-warning">
						<i class="fi fi-rr-exclamation warning-icon"></i>
						<p>
							Êtes-vous sûr de vouloir supprimer le produit
							<strong>{{ productToDelete?.name }}</strong> ?
						</p>
						<p class="delete-note">Cette action est irréversible.</p>
					</div>

					<div v-if="deleteError" class="error-message">
						<i class="fi fi-rr-cross-circle"></i>
						<p>{{ deleteError }}</p>
					</div>

					<div class="form-actions">
						<button type="button" @click="closeDeleteModal" class="btn-cancel">
							Annuler
						</button>
						<button
							@click="deleteProduct"
							class="btn-delete-confirm"
							:disabled="isDeleting"
						>
							<span v-if="isDeleting">
								<i class="fi fi-rr-spinner"></i> Suppression...
							</span>
							<span v-else> <i class="fi fi-rr-trash"></i> Supprimer </span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Redirect to create product with pre-selected category -->
		<teleport to="body" v-if="redirectToCreate">
			<div class="redirect-loader">
				<div class="loading-spinner"></div>
				<p>Redirection vers la création de produit...</p>
			</div>
		</teleport>
	</div>
</template>

<script>
	import axios from "axios";
	import placeholderImage from "@/assets/logo.png"; // Importez l'image au niveau du script

	export default {
		name: "Catalogue",
		data() {
			return {
				categories: [],
				loading: true,
				error: null,
				isAuthenticated: false,
				activeCategory: "all",
				placeholderPath: placeholderImage, // Stockez le chemin résolu dans une propriété

				// Delete modal
				showDeleteModal: false,
				productToDelete: null,
				deleteError: null,
				isDeleting: false,

				// Create product redirection
				redirectToCreate: false,
			};
		},
		computed: {
			// Get all products flattened into a single array
			allProducts() {
				return this.categories.reduce((products, category) => {
					return [...products, ...category.products];
				}, []);
			},

			// Get filtered products based on active category
			filteredProducts() {
				if (this.activeCategory === "all") {
					return this.allProducts;
				}

				const category = this.categories.find(
					(cat) => cat.id === this.activeCategory
				);
				return category ? category.products : [];
			},

			// Get total number of products
			totalProducts() {
				return this.allProducts.length;
			},
		},
		created() {
			this.checkAuthentication();
			this.fetchCatalogue();
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

			// Get all categories with their products
			fetchCatalogue() {
				this.loading = true;
				this.error = null;

				axios
					.get(`${window.config.SERVER_URL}/catalogue/categories`)
					.then((response) => {
						this.categories = response.data;
					})
					.catch((error) => {
						console.error("Erreur lors du chargement du catalogue:", error);
						this.error =
							"Une erreur est survenue lors du chargement du catalogue.";
					})
					.finally(() => {
						this.loading = false;
					});
			},

			// Get image URL with API base
			getImageUrl(path) {
				// Si aucun chemin d'image n'est fourni, ou s'il est vide
				if (!path || path.trim() === "") {
					// Retourner un chemin statique vers le placeholder
					return this.placeholderPath;
				}

				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				const normalizedPath = path.startsWith("/") ? path : `/${path}`;
				return `${window.config.SERVER_URL}${normalizedPath}`;
			},

			// Format price with currency
			formatPrice(price) {
				if (!price) return "Prix non disponible";

				return new Intl.NumberFormat("fr-FR", {
					style: "currency",
					currency: "XOF",
				}).format(price);
			},

			// Get category name by id
			getCategoryName(categoryId) {
				const category = this.categories.find((cat) => cat.id === categoryId);
				return category ? category.name : "Non catégorisé";
			},

			// Show create product modal/redirect
			showCreateProductModal(categoryId = null) {
				this.redirectToCreate = true;

				// If we have no categories, we should not be able to get here
				if (this.categories.length === 0) {
					this.$router.push("/category");
					return;
				}

				// Redirect to create product page with pre-selected category if provided
				setTimeout(() => {
					const route =
						categoryId && categoryId !== "all"
							? `/create-catalogue?category=${categoryId}`
							: "/create-catalogue";

					this.$router.push(route);
				}, 500);
			},

			// Delete product confirmation
			confirmDeleteProduct(product) {
				this.productToDelete = product;
				this.deleteError = null;
				this.showDeleteModal = true;
			},

			closeDeleteModal() {
				this.showDeleteModal = false;
				setTimeout(() => {
					this.productToDelete = null;
					this.deleteError = null;
				}, 300);
			},

			// Delete product
			deleteProduct() {
				if (!this.productToDelete) return;

				const token = localStorage.getItem("token");
				if (!token) {
					this.deleteError = "Vous devez être connecté pour cette action.";
					return;
				}

				this.isDeleting = true;
				this.deleteError = null;

				axios
					.delete(
						`${window.config.SERVER_URL}/catalogue/${this.productToDelete.id}`,
						{
							headers: {
								Authorization: `Bearer ${token}`,
							},
						}
					)
					.then(() => {
						// Refresh the catalogue data
						this.fetchCatalogue();
						this.closeDeleteModal();
					})
					.catch((error) => {
						console.error("Erreur lors de la suppression:", error);
						if (error.response && error.response.status === 401) {
							this.deleteError =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
						} else {
							this.deleteError =
								"Une erreur est survenue lors de la suppression.";
						}
					})
					.finally(() => {
						this.isDeleting = false;
					});
			},
		},
	};
</script>

<style scoped>
	.catalogue-page {
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

	/* Admin Controls */
	.admin-controls {
		background-color: #f0f5ff;
		padding: 20px 0;
		border-bottom: 1px solid #dce4fd;
	}

	.admin-toolbar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 20px;
	}

	.admin-toolbar h2 {
		margin: 0;
		font-size: 1.3rem;
		display: flex;
		align-items: center;
		gap: 10px;
		color: var(--primary-color);
	}

	.admin-actions {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.btn-manage,
	.btn-add {
		padding: 10px 20px;
		border-radius: 4px;
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: 600;
		transition: all 0.3s;
		text-decoration: none;
	}

	.btn-manage {
		background-color: white;
		color: var(--primary-color);
		border: 1px solid var(--primary-color);
	}

	.btn-manage:hover {
		background-color: #f0f7ff;
	}

	.btn-add {
		background-color: var(--primary-color);
		color: white;
		border: none;
		cursor: pointer;
	}

	.btn-add:hover:not(:disabled) {
		background-color: #004494;
	}

	.btn-add:disabled {
		opacity: 0.6;
		cursor: not-allowed;
	}

	.warning-message {
		margin-top: 20px;
		padding: 15px;
		background-color: #fff7e6;
		border-left: 4px solid var(--warning-color);
		display: flex;
		align-items: flex-start;
		gap: 15px;
		border-radius: 4px;
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

	/* Catalogue Content */
	.catalogue-content {
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

	.btn-retry {
		padding: 10px 20px;
		background-color: var(--secondary-color);
		color: var(--text-color);
		border: none;
		border-radius: 4px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: 600;
		transition: background-color 0.3s;
	}

	.btn-retry:hover {
		background-color: #e2e6ea;
	}

	/* Empty State */
	.empty-state {
		padding: 60px 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 15px;
		text-align: center;
	}

	.empty-icon {
		font-size: 4rem;
		color: #ccd0d4;
	}

	.empty-state h2 {
		margin: 0;
		font-size: 1.8rem;
	}

	.admin-hint {
		color: var(--light-text);
		margin-top: 10px;
		font-style: italic;
	}

	/* Category Tabs */
	.category-tabs {
		display: flex;
		overflow-x: auto;
		gap: 10px;
		padding-bottom: 20px;
		margin-bottom: 30px;
		scrollbar-width: thin;
	}

	.category-tabs::-webkit-scrollbar {
		height: 5px;
	}

	.category-tabs::-webkit-scrollbar-thumb {
		background-color: #ddd;
		border-radius: 10px;
	}

	.category-tabs::-webkit-scrollbar-track {
		background-color: #f5f5f5;
		border-radius: 10px;
	}

	.tab-btn {
		padding: 10px 20px;
		background-color: white;
		border: 1px solid #ddd;
		border-radius: 30px;
		color: var(--light-text);
		cursor: pointer;
		font-weight: 500;
		transition: all 0.3s;
		white-space: nowrap;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.tab-btn.active {
		background-color: var(--primary-color);
		color: white;
		border-color: var(--primary-color);
	}

	.tab-btn:hover:not(.active) {
		background-color: #f5f5f5;
		border-color: #ccc;
	}

	.count-badge {
		background-color: rgba(0, 0, 0, 0.1);
		color: inherit;
		padding: 2px 8px;
		border-radius: 10px;
		font-size: 0.8rem;
		font-weight: 500;
	}

	.tab-btn.active .count-badge {
		background-color: rgba(255, 255, 255, 0.3);
	}

	/* Products Grid */
	.products-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
		gap: 30px;
		padding-bottom: 50px;
	}

	.product-card {
		background-color: white;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
		transition: transform 0.3s, box-shadow 0.3s;
		display: flex;
		flex-direction: column;
	}

	.product-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
	}

	.product-image {
		height: 200px;
		position: relative;
	}

	.product-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s;
	}

	.product-card:hover .product-image img {
		transform: scale(1.05);
	}

	.category-tag {
		position: absolute;
		top: 15px;
		left: 15px;
		background-color: var(--primary-color);
		color: white;
		font-size: 0.8rem;
		padding: 4px 10px;
		border-radius: 4px;
		font-weight: 500;
		opacity: 0.8;
	}

	.product-info {
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	.product-title {
		margin: 0 0 10px 0;
		font-size: 1.2rem;
		line-height: 1.3;
		font-weight: 600;
	}

	.product-price {
		color: var(--primary-color);
		font-weight: 700;
		font-size: 1.3rem;
		margin-bottom: 20px;
	}

	.product-actions {
		margin-top: auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.btn-details {
		padding: 8px 16px;
		border-radius: 4px;
		background-color: var(--primary-color);
		color: white;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 8px;
		transition: background-color 0.3s;
		font-weight: 500;
	}

	.btn-details:hover {
		background-color: #004494;
	}

	.admin-product-actions {
		display: flex;
		gap: 8px;
	}

	.btn-edit,
	.btn-delete {
		width: 36px;
		height: 36px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border: none;
		cursor: pointer;
		transition: background-color 0.3s;
	}

	.btn-edit {
		background-color: rgba(0, 86, 179, 0.1);
		color: var(--primary-color);
	}

	.btn-edit:hover {
		background-color: rgba(0, 86, 179, 0.2);
	}

	.btn-delete {
		background-color: rgba(220, 53, 69, 0.1);
		color: var(--error-color);
	}

	.btn-delete:hover {
		background-color: rgba(220, 53, 69, 0.2);
	}

	/* Empty Category */
	.empty-category {
		grid-column: 1 / -1;
		padding: 40px;
		text-align: center;
		background-color: white;
		border-radius: 8px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 15px;
	}

	.empty-category i {
		font-size: 2rem;
		color: var(--light-text);
	}

	.btn-add-inline {
		padding: 10px 20px;
		background-color: var(--primary-color);
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 8px;
		transition: background-color 0.3s;
	}

	.btn-add-inline:hover {
		background-color: #004494;
	}

	/* Modal Styles */
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

	.error-message {
		padding: 15px;
		border-radius: 5px;
		background-color: rgba(220, 53, 69, 0.1);
		color: var(--error-color);
		margin-bottom: 20px;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.form-actions {
		display: flex;
		justify-content: flex-end;
		gap: 15px;
		margin-top: 30px;
	}

	.btn-cancel,
	.btn-delete-confirm {
		padding: 12px 25px;
		border-radius: 4px;
		cursor: pointer;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 8px;
		border: none;
		transition: background-color 0.3s;
	}

	.btn-cancel {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.btn-cancel:hover {
		background-color: #e2e6ea;
	}

	.btn-delete-confirm {
		background-color: var(--error-color);
		color: white;
	}

	.btn-delete-confirm:hover:not(:disabled) {
		background-color: #c82333;
	}

	.btn-delete-confirm:disabled {
		opacity: 0.7;
		cursor: not-allowed;
	}

	/* Delete Modal */
	.delete-modal {
		max-width: 450px;
	}

	.delete-warning {
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
		padding: 10px;
	}

	.warning-icon {
		font-size: 2.5rem;
		color: var(--warning-color);
		margin-bottom: 15px;
	}

	.delete-note {
		color: var(--light-text);
		font-size: 0.9rem;
		margin-top: 5px;
	}

	/* Redirect Loader */
	.redirect-loader {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(255, 255, 255, 0.9);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		z-index: 2000;
		gap: 20px;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.page-hero h1 {
			font-size: 2.5rem;
		}

		.admin-toolbar {
			flex-direction: column;
			align-items: flex-start;
			gap: 15px;
		}

		.admin-actions {
			width: 100%;
		}

		.btn-manage,
		.btn-add {
			flex: 1;
			justify-content: center;
		}

		.products-grid {
			grid-template-columns: 1fr;
		}
	}
</style>
