<template>
	<div class="actualites-page">
		<!-- Hero Section -->
		<section class="page-hero">
			<div class="container">
				<h1>Actualités</h1>
				<p class="hero-subtitle">
					Restez informé des dernières nouvelles et événements de SAFESECUR.
				</p>
			</div>
		</section>

		<!-- News Content Section -->
		<section class="news-section">
			<div class="container">
				<div class="section-header">
					<h2>Nos dernières actualités</h2>
					<router-link
						to="/create-actualites"
						class="add-button"
						v-if="isAuthenticated"
					>
						<i class="fi fi-rr-plus"></i> Ajouter une actualité
					</router-link>
				</div>

				<!-- Loading State -->
				<div v-if="loading" class="loading-container">
					<div class="loading-spinner"></div>
					<p>Chargement des actualités...</p>
				</div>

				<!-- Error State -->
				<div v-else-if="error" class="error-container">
					<i class="fi fi-rr-exclamation"></i>
					<p>{{ error }}</p>
					<button @click="fetchActualites" class="retry-button">
						Réessayer
					</button>
				</div>

				<!-- Empty State -->
				<div v-else-if="actualites.length === 0" class="empty-container">
					<i class="fi fi-rr-info"></i>
					<p>Aucune actualité disponible pour le moment.</p>
				</div>

				<!-- News Grid -->
				<div v-else class="news-grid">
					<div
						v-for="actualite in actualites"
						:key="actualite.id"
						class="news-card"
					>
						<div class="news-image" :style="getBackgroundImage(actualite.img)">
							<span class="news-date">{{ formatDate(actualite.date) }}</span>
						</div>
						<div class="news-content">
							<h3>{{ truncateText(actualite.title, 25) }}</h3>
							<p>{{ truncateText(actualite.description, 40) }}</p>
							<div class="card-actions">
								<router-link
									:to="`/actualites/${actualite.id}`"
									class="read-more"
								>
									Lire la suite <i class="fi fi-rr-arrow-right"></i>
								</router-link>

								<!-- Admin actions - conditionnellement affichées -->
								<div v-if="isAuthenticated" class="admin-actions">
									<router-link
										:to="`/edit-actualites/${actualite.id}`"
										class="edit-btn"
									>
										<i class="fi fi-rr-edit"></i>
									</router-link>
									<button
										@click="confirmDelete(actualite.id)"
										class="delete-btn"
									>
										<i class="fi fi-rr-trash"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Confirmation Dialog -->
		<div v-if="showConfirmation" class="confirmation-dialog">
			<div class="dialog-content">
				<h3>Confirmation</h3>
				<p>Êtes-vous sûr de vouloir supprimer cette actualité ?</p>
				<div class="dialog-actions">
					<button @click="cancelDelete" class="cancel-btn">Annuler</button>
					<button @click="deleteActualite" class="confirm-btn">
						Supprimer
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";
	import placeholderImage from "@/assets/logo.png"; // Importez l'image

	export default {
		name: "Actualites",
		data() {
			return {
				placeholderImage, // Ajoutez la référence dans les données
				actualites: [],
				loading: true,
				error: null,
				showConfirmation: false,
				actualiteToDelete: null,
				isAuthenticated: false,
			};
		},
		mounted() {
			this.fetchActualites();
			this.checkAuthentication();
		},
		methods: {
			fetchActualites() {
				this.loading = true;
				this.error = null;

				axios
					.get(`${window.config.SERVER_URL}/actualites`)
					.then((response) => {
						this.actualites = response.data;
					})
					.catch((error) => {
						console.error("Error fetching actualites:", error);
						this.error =
							"Erreur lors du chargement des actualités. Veuillez réessayer.";
					})
					.finally(() => {
						this.loading = false;
					});
			},
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.isAuthenticated = false;
					return;
				}

				// Vérifier si le token est valide
				axios
					.get(`${window.config.SERVER_URL}/verify-auth`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then((response) => {
						this.isAuthenticated = response.data.authenticated;
					})
					.catch((error) => {
						console.error("Erreur d'authentification:", error);
						this.isAuthenticated = false;
						localStorage.removeItem("token"); // Nettoyer le token invalide
					});
			},
			formatDate(dateString) {
				const options = { day: "2-digit", month: "short", year: "numeric" };
				return new Date(dateString).toLocaleDateString("fr-FR", options);
			},
			truncateText(text, maxLength) {
				if (text && text.length > maxLength) {
					return text.substring(0, maxLength) + "...";
				}
				return text;
			},
			getBackgroundImage(imgPath) {
				if (imgPath) {
					// Si le chemin d'image commence par http ou par / (URL absolue)
					if (imgPath.startsWith("http") || imgPath.startsWith("/")) {
						return {
							backgroundImage: `url(${window.config.SERVER_URL}${imgPath})`,
						};
					} else {
						// Sinon, ajouter l'URL de base de l'API
						return {
							backgroundImage: `url(${window.config.SERVER_URL}/${imgPath})`,
						};
					}
				}
				// Utilisez l'image importée
				return { backgroundImage: `url(${this.placeholderImage})` };
			},
			confirmDelete(id) {
				this.actualiteToDelete = id;
				this.showConfirmation = true;
			},
			cancelDelete() {
				this.showConfirmation = false;
				this.actualiteToDelete = null;
			},
			deleteActualite() {
				if (!this.actualiteToDelete) return;

				const token = localStorage.getItem("token");
				if (!token) {
					// alert("Vous devez être connecté pour effectuer cette action.");
					this.showConfirmation = false;
					return;
				}

				axios
					.delete(
						`${window.config.SERVER_URL}/actualites/${this.actualiteToDelete}`,
						{
							headers: {
								Authorization: `Bearer ${token}`,
							},
						}
					)
					.then(() => {
						// Remove item from local array
						this.actualites = this.actualites.filter(
							(item) => item.id !== this.actualiteToDelete
						);
						this.showConfirmation = false;
						this.actualiteToDelete = null;
					})
					.catch((error) => {
						console.error("Error deleting actualite:", error);
						if (error.response && error.response.status === 401) {
							alert("Non autorisé. Veuillez vous reconnecter.");
							localStorage.removeItem("token");
							this.$router.push("/connexion");
						} else {
							alert("Erreur lors de la suppression. Veuillez réessayer.");
						}
					});
			},
		},
	};
</script>

<style scoped>
	.actualites-page {
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

	/* News Section */
	.section-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 40px;
	}

	.section-header h2 {
		font-size: 2.2rem;
		font-weight: 700;
		position: relative;
		margin: 0;
	}

	.section-header h2:after {
		content: "";
		position: absolute;
		bottom: -15px;
		left: 0;
		width: 60px;
		height: 4px;
		background-color: var(--accent-color);
	}

	.add-button {
		background-color: var(--primary-color);
		color: white;
		border: none;
		border-radius: 4px;
		padding: 10px 20px;
		font-size: 1rem;
		display: flex;
		align-items: center;
		gap: 8px;
		cursor: pointer;
		text-decoration: none;
		transition: background-color 0.3s ease;
	}

	.add-button:hover {
		background-color: #004494;
	}

	/* News Grid */
	.news-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
		gap: 30px;
		margin-top: 40px;
	}

	.news-card {
		background-color: white;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
		transition: transform 0.3s ease, box-shadow 0.3s ease;
	}

	.news-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
	}

	.news-image {
		height: 200px;
		background-color: var(--secondary-color);
		background-size: cover; /* Important pour couvrir toute la zone */
		background-position: center; /* Centre l'image */
		position: relative;
	}

	.news-date {
		position: absolute;
		bottom: 0;
		right: 0;
		background-color: rgba(0, 0, 0, 0.7);
		color: white;
		padding: 8px 15px;
		border-top-left-radius: 8px;
		font-size: 0.9rem;
	}

	.news-content {
		padding: 25px;
	}

	.news-content h3 {
		margin-top: 0;
		margin-bottom: 15px;
		font-size: 1.4rem;
		color: var(--primary-color);
	}

	.news-content p {
		color: var(--light-text);
		margin-bottom: 20px;
		line-height: 1.6;
	}

	.card-actions {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-top: 1px solid #eee;
		padding-top: 15px;
	}

	.read-more {
		color: var(--primary-color);
		text-decoration: none;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 5px;
		transition: color 0.3s ease;
	}

	.read-more:hover {
		color: #004494;
	}

	.admin-actions {
		display: flex;
		gap: 10px;
	}

	.edit-btn,
	.delete-btn {
		width: 35px;
		height: 35px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		border: none;
		cursor: pointer;
		transition: background-color 0.3s ease;
	}

	.edit-btn {
		background-color: var(--secondary-color);
		color: var(--primary-color);
		text-decoration: none;
	}

	.edit-btn:hover {
		background-color: #e9ecef;
	}

	.delete-btn {
		background-color: #fee;
		color: var(--error-color);
	}

	.delete-btn:hover {
		background-color: #fdd;
	}

	/* Loading, Error and Empty States */
	.loading-container,
	.error-container,
	.empty-container {
		text-align: center;
		padding: 60px 0;
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

	.error-container i,
	.empty-container i {
		font-size: 3rem;
		color: var(--error-color);
		margin-bottom: 15px;
	}

	.empty-container i {
		color: var(--light-text);
	}

	.retry-button {
		background-color: var(--primary-color);
		color: white;
		border: none;
		padding: 10px 20px;
		border-radius: 4px;
		cursor: pointer;
		margin-top: 15px;
		transition: background-color 0.3s ease;
	}

	.retry-button:hover {
		background-color: #004494;
	}

	/* Confirmation Dialog */
	.confirmation-dialog {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 1000;
	}

	.dialog-content {
		background-color: white;
		padding: 30px;
		border-radius: 8px;
		max-width: 400px;
		width: 90%;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
	}

	.dialog-content h3 {
		margin-top: 0;
		font-size: 1.5rem;
	}

	.dialog-actions {
		display: flex;
		justify-content: flex-end;
		margin-top: 25px;
		gap: 15px;
	}

	.cancel-btn,
	.confirm-btn {
		padding: 10px 20px;
		border-radius: 4px;
		cursor: pointer;
		font-weight: 600;
		border: none;
	}

	.cancel-btn {
		background-color: #f2f2f2;
		color: var(--dark-bg);
	}

	.confirm-btn {
		background-color: var(--error-color);
		color: white;
	}

	/* Responsive Adjustments */
	@media (max-width: 768px) {
		.page-hero h1 {
			font-size: 2.5rem;
		}

		.section-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 20px;
		}

		.news-grid {
			grid-template-columns: 1fr;
		}

		.section-header h2:after {
			left: 50%;
			transform: translateX(-50%);
		}
	}
</style>
