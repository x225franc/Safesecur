<template>
	<div class="view-actualite-page">
		<!-- Hero Section with Actualité Title -->
		<section
			class="page-hero actualite-hero"
			v-if="actualite"
			:style="
				actualite.img
					? `background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('${getImageUrl(
							actualite.img )}') center/cover`
					: ''
			"
		>
			<div class="hero-overlay"></div>
			<div class="container">
				<div class="hero-content">
					<div class="actualite-meta">
						<span class="actualite-date">{{ formatDate(actualite.date) }}</span>
					</div>
					<h1 class="actualite-title">{{ actualite.title }}</h1>
				</div>
			</div>
		</section>

		<!-- Actualité Content -->
		<section class="actualite-content">
			<div class="container">
				<!-- Loading State -->
				<div v-if="loading" class="loading-container">
					<div class="loading-spinner"></div>
					<p>Chargement de l'actualité...</p>
				</div>

				<!-- Error State -->
				<div v-else-if="error" class="error-container">
					<i class="fi fi-rr-exclamation"></i>
					<p>{{ error }}</p>
					<div class="error-actions">
						<button @click="fetchActualite" class="retry-button">
							<i class="fi fi-rr-refresh"></i> Réessayer
						</button>
						<router-link to="/actualites" class="back-link">
							<i class="fi fi-rr-arrow-left"></i> Retour aux actualités
						</router-link>
					</div>
				</div>

				<!-- Actualité Content -->
				<div v-else-if="actualite" class="actualite-container">
					<div class="navigation-bar">
						<router-link to="/actualites" class="back-link">
							<i class="fi fi-rr-arrow-left"></i> Retour aux actualités
						</router-link>

						<!-- Admin actions - conditionnellement affichées -->
						<div v-if="isAuthenticated" class="admin-actions">
							<router-link
								:to="`/edit-actualites/${actualite.id}`"
								class="edit-btn"
							>
								<i class="fi fi-rr-edit"></i> Modifier
							</router-link>
							<button @click="confirmDelete" class="delete-btn">
								<i class="fi fi-rr-trash"></i> Supprimer
							</button>
						</div>
					</div>

					<div class="content-layout">
						<div class="main-content">
							<!-- Image -->
							<div v-if="actualite.img" class="actualite-image">
								<img :src="getImageUrl(actualite.img)" :alt="actualite.title" />
							</div>

							<!-- Text Content -->
							<!-- Remplacer cette partie dans le template -->
							<div class="actualite-text">
								<div
									class="content-description"
									v-html="formatText(actualite.description)"
								></div>
							</div>
						</div>

						<div class="sidebar">
							<div class="publication-info">
								<h3>Informations</h3>
								<div class="info-item">
									<i class="fi fi-rr-calendar"></i>
									<div>
										<span class="label">Date de publication</span>
										<span class="value">{{ formatDate(actualite.date) }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div v-else class="error-container">
					<i class="fi fi-rr-info"></i>
					<p>Cette actualité n'existe pas ou a été supprimée.</p>
					<router-link to="/actualites" class="back-link">
						<i class="fi fi-rr-arrow-left"></i> Retour aux actualités
					</router-link>
				</div>
			</div>
		</section>

		<!-- Confirmation Dialog -->
		<div v-if="showConfirmation" class="confirmation-dialog">
			<div class="dialog-content">
				<h3>Confirmation de suppression</h3>
				<p>Êtes-vous sûr de vouloir supprimer cette actualité ?</p>
				<p class="warning-text">
					<i class="fi fi-rr-exclamation-triangle"></i> Cette action est
					irréversible.
				</p>
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

	export default {
		name: "ViewActualite",
		data() {
			return {
				actualite: null,
				loading: true,
				error: null,
				showConfirmation: false,
				isAuthenticated: false, // Ajout de cette propriété
			};
		},
		mounted() {
			this.fetchActualite();
			this.checkAuthentication(); // Appel à la nouvelle méthode
		},
		methods: {
			// Méthode pour construire l'URL complète de l'image
			getImageUrl(path) {
				if (!path) return "";

				// Si le chemin commence déjà par http, le retourner tel quel
				if (path.startsWith("http://") || path.startsWith("https://")) {
					return path;
				}

				// S'assurer que le chemin commence par un slash
				const normalizedPath = path.startsWith("/") ? path : `/${path}`;

				// Retourner l'URL complète
				return `${window.config.API_URL}${normalizedPath}`;
			},

			fetchActualite() {
				this.loading = true;
				this.error = null;
				const id = this.$route.params.id;

				axios
					.get(`${window.config.API_URL}/actualites/${id}`)
					.then((response) => {
						this.actualite = response.data;
					})
					.catch((error) => {
						console.error("Error fetching actualite details:", error);
						if (error.response && error.response.status === 404) {
							this.error = "L'actualité demandée n'a pas été trouvée.";
						} else {
							this.error =
								"Une erreur est survenue lors du chargement de l'actualité. Veuillez réessayer.";
						}
					})
					.finally(() => {
						this.loading = false;
					});
			},
			formatDate(dateString) {
				const options = {
					weekday: "long",
					day: "numeric",
					month: "long",
					year: "numeric",
				};
				return new Date(dateString).toLocaleDateString("fr-FR", options);
			},
			formatText(text) {
				if (!text) return "";
				// Convertit les sauts de ligne en balises <br>
				return text.replace(/\n/g, "<br>");
			},
			confirmDelete() {
				this.showConfirmation = true;
			},
			cancelDelete() {
				this.showConfirmation = false;
			},
			// Méthode de suppression modifiée pour inclure le token
			deleteActualite() {
				if (!this.actualite) return;

				const token = localStorage.getItem("token");
				if (!token) {
					alert("Vous devez être connecté pour effectuer cette action.");
					this.showConfirmation = false;
					return;
				}

				axios
					.delete(`${window.config.API_URL}/actualites/${this.actualite.id}`, {
						headers: {
							Authorization: `Bearer ${token}`,
						},
					})
					.then(() => {
						this.showConfirmation = false;
						// Redirect to actualites list
						this.$router.push("/actualites");
					})
					.catch((error) => {
						console.error("Error deleting actualite:", error);
						if (error.response && error.response.status === 401) {
							alert("Non autorisé. Veuillez vous reconnecter.");
							localStorage.removeItem("token");
							this.$router.push("/login");
						} else {
							alert("Erreur lors de la suppression. Veuillez réessayer.");
						}
					});
			},
			// Nouvelle méthode pour vérifier l'authentification
			checkAuthentication() {
				const token = localStorage.getItem("token");

				if (!token) {
					this.isAuthenticated = false;
					return;
				}

				// Vérifier si le token est valide
				axios
					.get(`${window.config.API_URL}/verify-auth`, {
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
		},
	};
</script>

<style scoped>
	.view-actualite-page {
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
		padding: 40px 0;
	}

	/* Hero Section */
	.page-hero.actualite-hero {
		min-height: 300px;
		background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
			url("/img/news-header.jpg") center/cover;
		color: white;
		position: relative;
		display: flex;
		align-items: center;
		padding: 80px 0;
	}

	.hero-overlay {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: linear-gradient(
			to bottom,
			rgba(0, 0, 0, 0.5),
			rgba(0, 0, 0, 0.8)
		);
	}

	.hero-content {
		position: relative;
		z-index: 2;
		max-width: 900px;
	}

	.actualite-meta {
		margin-bottom: 20px;
	}

	.actualite-date {
		background-color: var(--accent-color);
		color: var(--dark-bg);
		padding: 6px 12px;
		border-radius: 4px;
		font-weight: 600;
		font-size: 0.9rem;
	}

	.actualite-title {
		font-size: 3rem;
		font-weight: 700;
		margin: 0;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
		word-wrap: break-word;
		overflow-wrap: break-word;
		max-width: 100%;
	}

	.page-hero h1 {
		font-size: 3rem;
		font-weight: 700;
		margin: 0;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
	}

	/* Actualité Content Section */
	.actualite-content {
		background-color: white;
		padding: 60px 0;
	}

	/* Loading State */
	.loading-container {
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

	/* Error State */
	.error-container {
		text-align: center;
		padding: 60px 0;
	}

	.error-container i {
		font-size: 3rem;
		color: var(--error-color);
		margin-bottom: 15px;
	}

	.error-actions {
		display: flex;
		justify-content: center;
		gap: 15px;
		margin-top: 20px;
		flex-wrap: wrap;
	}

	.retry-button,
	.back-link {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 20px;
		border-radius: 4px;
		text-decoration: none;
		font-weight: 500;
		transition: background-color 0.3s ease;
	}

	.retry-button {
		background-color: var(--primary-color);
		color: white;
		border: none;
		cursor: pointer;
	}

	.retry-button:hover {
		background-color: #004494;
	}

	.back-link {
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	.back-link:hover {
		background-color: #e2e6ea;
	}

	/* Actualité Container */
	.actualite-container {
		background-color: white;
	}

	.navigation-bar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 30px;
		flex-wrap: wrap;
		gap: 15px;
	}

	.admin-actions {
		display: flex;
		gap: 10px;
	}

	.edit-btn,
	.delete-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 15px;
		border-radius: 4px;
		font-weight: 600;
		font-size: 0.9rem;
		cursor: pointer;
		transition: background-color 0.3s ease;
		text-decoration: none;
		border: none;
	}

	.edit-btn {
		background-color: var(--secondary-color);
		color: var(--primary-color);
	}

	.edit-btn:hover {
		background-color: #e2e6ea;
	}

	.delete-btn {
		background-color: #fee;
		color: var(--error-color);
	}

	.delete-btn:hover {
		background-color: #fdd;
	}

	/* Content Layout */
	.content-layout {
		display: flex;
		gap: 40px;
		margin-top: 30px;
	}

	.main-content {
		flex: 1 1 700px;
	}

	.sidebar {
		flex: 0 0 300px;
	}

	/* Image */
	.actualite-image {
		margin-bottom: 30px;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
	}

	.actualite-image img {
		width: 100%;
		display: block;
		height: auto;
	}

	/* Text Content - Correction des styles mal placés */
	.actualite-text {
		margin-bottom: 40px;
	}

	.content-description {
		font-size: 1.1rem;
		line-height: 1.8;
		overflow-wrap: break-word;
		word-wrap: break-word;
		word-break: break-word;
		max-width: 100%;
		white-space: pre-line;
	}

	.content-description p {
		margin-bottom: 20px;
	}

	.content-description img {
		max-width: 100%;
		height: auto;
	}

	/* Sidebar */
	.publication-info,
	.related-news {
		background-color: var(--secondary-color);
		border-radius: 8px;
		padding: 25px;
		margin-bottom: 30px;
	}

	.publication-info h3,
	.related-news h3 {
		margin-top: 0;
		margin-bottom: 20px;
		font-size: 1.3rem;
		position: relative;
		padding-bottom: 10px;
	}

	.publication-info h3::after,
	.related-news h3::after {
		content: "";
		position: absolute;
		bottom: 0;
		left: 0;
		width: 50px;
		height: 3px;
		background-color: var(--accent-color);
	}

	.info-item {
		display: flex;
		gap: 15px;
		margin-bottom: 15px;
	}

	.info-item i {
		font-size: 1.5rem;
		color: var(--primary-color);
	}

	.info-item .label {
		display: block;
		font-size: 0.9rem;
		color: var(--light-text);
		margin-bottom: 3px;
	}

	.info-item .value {
		font-weight: 600;
	}

	.sidebar-message {
		color: var(--light-text);
		font-style: italic;
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
		max-width: 450px;
		width: 90%;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
	}

	.dialog-content h3 {
		margin-top: 0;
		font-size: 1.5rem;
		color: var(--error-color);
	}

	.warning-text {
		background-color: rgba(220, 53, 69, 0.1);
		padding: 10px;
		border-radius: 4px;
		color: var(--error-color);
		display: flex;
		align-items: center;
		gap: 8px;
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
	@media (max-width: 992px) {
		.content-layout {
			flex-direction: column;
		}

		.sidebar {
			flex: 0 0 100%;
		}

		.page-hero h1 {
			font-size: 2.5rem;
		}
	}

	@media (max-width: 768px) {
		.page-hero h1 {
			font-size: 2rem;
		}

		.page-hero {
			padding: 50px 0;
		}

		.navigation-bar {
			flex-direction: column;
			align-items: flex-start;
		}

		.admin-actions {
			width: 100%;
			justify-content: space-between;
		}

		.edit-btn,
		.delete-btn {
			flex: 1;
			justify-content: center;
		}
	}
</style>
