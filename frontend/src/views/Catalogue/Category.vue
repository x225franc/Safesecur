<template>
	<div class="category-page">
		<!-- Hero Section -->
		<section class="page-hero small-hero">
			<div class="container">
				<h1>Gestion des Catégories</h1>
				<p class="hero-subtitle">
					Créez et gérez les catégories de votre catalogue de produits
				</p>
			</div>
		</section>

		<!-- Content Section -->
		<section class="categories-section">
			<div class="container">
				<div class="card">
					<div class="card-header">
						<router-link class="btn btn-light" to="/catalogue">
							<i class="fi fi-rr-arrow-left"></i> Retour au catalogue
						</router-link>
						<button class="btn btn-primary" @click="showCreateModal">
							<i class="fi fi-rr-plus"></i> Nouvelle catégorie
						</button>
					</div>

					<!-- Loading State -->
					<div v-if="loading" class="loading-container">
						<div class="loading-spinner"></div>
						<p>Chargement des catégories...</p>
					</div>

					<!-- Error State -->
					<div v-else-if="error" class="error-container">
						<div class="error-icon"><i class="fi fi-rr-exclamation"></i></div>
						<p>{{ error }}</p>
						<button @click="fetchCategories" class="btn-retry">
							<i class="fi fi-rr-refresh"></i> Réessayer
						</button>
					</div>

					<!-- Empty State -->
					<div v-else-if="categories.length === 0" class="empty-state">
						<div class="empty-icon"><i class="fi fi-rr-inbox"></i></div>
						<p>Aucune catégorie disponible</p>
						<p class="empty-subtitle">
							Commencez par créer votre première catégorie
						</p>
						<button @click="showCreateModal" class="btn-add-empty">
							<i class="fi fi-rr-plus"></i> Créer une catégorie
						</button>
					</div>

					<!-- Categories Table -->
					<div v-else class="table-container">
						<table class="categories-table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nom</th>
									<th>Produits</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="category in categories" :key="category.id">
									<td class="id-cell">{{ category.id }}</td>
									<td class="name-cell">{{ category.name }}</td>
									<td class="count-cell">
										{{ category.productCount || 0 }}
										<span class="product-label">produit(s)</span>
									</td>
									<td class="actions-cell">
										<button class="btn-edit" @click="showEditModal(category)">
											<i class="fi fi-rr-edit"></i>
										</button>
										<button
											class="btn-delete"
											@click="showDeleteConfirmation(category)"
											:title="
												category.productCount > 0
													? 'Tous les produits associés seront également supprimés'
													: ''
											"
										>
											<i class="fi fi-rr-trash"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>

		<!-- Create/Edit Modal -->
		<div class="modal-overlay" v-if="showModal" @click.self="closeModal">
			<div class="modal-content">
				<div class="modal-header">
					<h3>
						{{ isEditing ? "Modifier la catégorie" : "Nouvelle catégorie" }}
					</h3>
					<button class="modal-close" @click="closeModal">
						<i class="fi fi-rr-cross"></i>
					</button>
				</div>
				<div class="modal-body">
					<div v-if="modalError" class="error-message">
						<i class="fi fi-rr-cross-circle"></i>
						<p>{{ modalError }}</p>
					</div>

					<form @submit.prevent="submitCategoryForm">
						<div class="form-group">
							<label for="categoryName">Nom de la catégorie*</label>
							<input
								type="text"
								id="categoryName"
								v-model="categoryForm.name"
								placeholder="Nom de la catégorie"
								:class="{ 'input-error': validationErrors.name }"
								required
							/>
							<p v-if="validationErrors.name" class="error-text">
								{{ validationErrors.name }}
							</p>
						</div>

						<div class="form-actions">
							<button type="button" @click="closeModal" class="btn-cancel">
								Annuler
							</button>
							<button type="submit" class="btn-submit" :disabled="isSubmitting">
								<span v-if="isSubmitting">
									<i class="fi fi-rr-spinner"></i> Enregistrement...
								</span>
								<span v-else>
									<i class="fi fi-rr-check"></i>
									{{ isEditing ? "Enregistrer" : "Créer" }}
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Delete Confirmation Modal -->
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
							Êtes-vous sûr de vouloir supprimer la catégorie
							<strong>{{ categoryToDelete?.name }}</strong> ?
						</p>
						<p
							v-if="categoryToDelete?.productCount > 0"
							class="delete-warning-products"
						>
							<i class="fi fi-rr-info-circle"></i>
							Attention ! Cette catégorie contient
							<strong>{{ categoryToDelete.productCount }}</strong> produit(s).
							Tous ces produits et leurs images seront également supprimés.
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
							@click="deleteCategory"
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
	</div>
</template>

<script>
	import axios from "axios";

	export default {
		name: "CategoryManager",
		data() {
			return {
				categories: [],
				loading: true,
				error: null,

				// Form and modal data
				showModal: false,
				isEditing: false,
				categoryForm: {
					id: null,
					name: "",
				},
				validationErrors: {},
				modalError: null,
				isSubmitting: false,

				// Delete modal
				showDeleteModal: false,
				categoryToDelete: null,
				deleteError: null,
				isDeleting: false,
			};
		},
		created() {
			this.checkAuthentication();
		},
		methods: {
			// Authentication Check
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.$router.push("/connexion");
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
						} else {
							this.$router.push("/connexion");
						}
					})
					.catch((error) => {
						console.error("Erreur d'authentification:", error);
						localStorage.removeItem("token");
						this.$router.push("/connexion");
					});
			},

			// Fetch all categories
			fetchCategories() {
				this.loading = true;
				this.error = null;

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
						this.error =
							"Une erreur est survenue lors du chargement des catégories.";
					})
					.finally(() => {
						this.loading = false;
					});
			},

			// Modal management
			showCreateModal() {
				this.isEditing = false;
				this.categoryForm = {
					id: null,
					name: "",
				};
				this.validationErrors = {};
				this.modalError = null;
				this.showModal = true;
			},

			showEditModal(category) {
				this.isEditing = true;
				this.categoryForm = {
					id: category.id,
					name: category.name,
				};
				this.validationErrors = {};
				this.modalError = null;
				this.showModal = true;
			},

			closeModal() {
				this.showModal = false;
				setTimeout(() => {
					this.categoryForm = {
						id: null,
						name: "",
					};
					this.validationErrors = {};
					this.modalError = null;
				}, 300);
			},

			// Form validation
			validateForm() {
				this.validationErrors = {};

				if (!this.categoryForm.name.trim()) {
					this.validationErrors.name = "Le nom de la catégorie est requis";
				} else if (this.categoryForm.name.length > 100) {
					this.validationErrors.name =
						"Le nom ne doit pas dépasser 100 caractères";
				}

				return Object.keys(this.validationErrors).length === 0;
			},

			// Create or Edit a category
			submitCategoryForm() {
				if (!this.validateForm()) return;

				const token = localStorage.getItem("token");
				if (!token) {
					this.modalError = "Vous devez être connecté pour cette action.";
					return;
				}

				this.isSubmitting = true;
				this.modalError = null;

				const url = this.isEditing
					? `${window.config.SERVER_URL}/categories/${this.categoryForm.id}`
					: `${window.config.SERVER_URL}/categories`;

				const method = this.isEditing ? "put" : "post";

				axios({
					method,
					url,
					data: {
						name: this.categoryForm.name,
					},
					headers: {
						Authorization: `Bearer ${token}`,
					},
				})
					.then((response) => {
						this.fetchCategories();
						this.closeModal();
					})
					.catch((error) => {
						console.error("Erreur lors de l'enregistrement:", error);
						if (error.response && error.response.status === 409) {
							this.modalError = "Une catégorie avec ce nom existe déjà.";
						} else if (error.response && error.response.status === 401) {
							this.modalError =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/connexion");
							}, 2000);
						} else {
							this.modalError =
								"Une erreur est survenue lors de l'enregistrement.";
						}
					})
					.finally(() => {
						this.isSubmitting = false;
					});
			},

			// Delete confirmation
			showDeleteConfirmation(category) {
				 // Supprimer cette condition qui bloque l'affichage de la modal
				// if (category.productCount > 0) {
				//   return; // Button should be disabled anyway
				// }

				// Afficher la modal dans tous les cas
				this.categoryToDelete = category;
				this.deleteError = null;
				this.showDeleteModal = true;
			},

			closeDeleteModal() {
				this.showDeleteModal = false;
				setTimeout(() => {
					this.categoryToDelete = null;
					this.deleteError = null;
				}, 300);
			},

			// Delete a category
			deleteCategory() {
				if (!this.categoryToDelete) return;

				const token = localStorage.getItem("token");
				if (!token) {
					this.deleteError = "Vous devez être connecté pour cette action.";
					return;
				}

				this.isDeleting = true;
				this.deleteError = null;

				axios
					.delete(
						`${window.config.SERVER_URL}/categories/${this.categoryToDelete.id}`,
						{
							headers: {
								Authorization: `Bearer ${token}`,
							},
						}
					)
					.then((response) => {
						this.fetchCategories();
						this.closeDeleteModal();
					})
					.catch((error) => {
						console.error("Erreur lors de la suppression:", error);
						if (error.response && error.response.status === 409) {
							this.deleteError =
								"Impossible de supprimer une catégorie contenant des produits.";
						} else if (error.response && error.response.status === 401) {
							this.deleteError =
								"Non autorisé. Vous devez être connecté pour cette action.";
							localStorage.removeItem("token");
							setTimeout(() => {
								this.$router.push("/connexion");
							}, 2000);
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
	.category-page {
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

	/* Categories Section */
	.categories-section {
		background-color: var(--secondary-color);
	}

	.card {
		background-color: white;
		border-radius: 10px;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
		overflow: hidden;
	}

	.card-header {
		padding: 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-bottom: 1px solid #eee;
	}

	.card-header h2 {
		margin: 0;
		font-size: 1.5rem;
		display: flex;
		align-items: center;
		gap: 10px;
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
		font-size: 3rem;
		color: var(--light-text);
	}

	.empty-subtitle {
		color: var(--light-text);
		margin-top: -10px;
	}

	.btn-add-empty {
		padding: 12px 25px;
		background-color: var(--primary-color);
		color: white;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: 600;
		transition: background-color 0.3s;
		margin-top: 10px;
	}

	.btn-add-empty:hover {
		background-color: #004494;
	}

	/* Table Styles */
	.table-container {
		overflow-x: auto;
	}

	.categories-table {
		width: 100%;
		border-collapse: collapse;
	}

	.categories-table th,
	.categories-table td {
		padding: 16px 20px;
		text-align: left;
		border-bottom: 1px solid #eee;
	}

	.categories-table th {
		background-color: #f8f9fa;
		font-weight: 600;
		color: var(--light-text);
	}

	.categories-table tr:last-child td {
		border-bottom: none;
	}

	.categories-table tr:hover {
		background-color: #f9f9f9;
	}

	.id-cell {
		font-family: monospace;
		color: var(--light-text);
		width: 80px;
	}

	.name-cell {
		font-weight: 500;
	}

	.count-cell {
		width: 120px;
	}

	.product-label {
		color: var(--light-text);
		font-size: 0.9rem;
	}

	.actions-cell {
		width: 120px;
		text-align: right;
		white-space: nowrap;
	}

	.btn-edit,
	.btn-delete {
		width: 36px;
		height: 36px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin-left: 5px;
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

	.btn-delete:hover:not(:disabled) {
		background-color: rgba(220, 53, 69, 0.2);
	}

	.btn-delete:disabled {
		opacity: 0.5;
		cursor: not-allowed;
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

	.form-group {
		margin-bottom: 20px;
	}

	label {
		display: block;
		margin-bottom: 8px;
		font-weight: 600;
	}

	input[type="text"] {
		width: 100%;
		padding: 12px 15px;
		border: 1px solid #ddd;
		border-radius: 4px;
		font-size: 1rem;
		transition: border-color 0.3s ease;
	}

	input:focus {
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

	.form-actions {
		display: flex;
		justify-content: flex-end;
		gap: 15px;
		margin-top: 30px;
	}

	.btn-cancel,
	.btn-submit,
	.btn-delete-confirm {
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

	.btn-cancel {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.btn-cancel:hover {
		background-color: #e2e6ea;
	}

	.btn-submit {
		background-color: var(--primary-color);
		color: white;
	}

	.btn-submit:hover:not(:disabled) {
		background-color: #004494;
	}

	.btn-submit:disabled {
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

	@media (max-width: 768px) {
		.card-header {
			flex-direction: column;
			gap: 15px;
			align-items: stretch;
		}

		.btn-add {
			text-align: center;
			justify-content: center;
		}

		.categories-table th:nth-child(3),
		.categories-table td:nth-child(3) {
			display: none;
		}

		.actions-cell {
			text-align: center;
		}
	}
</style>
