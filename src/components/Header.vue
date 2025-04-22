<template>
	<header
		class="header_safesecur sticky-top shadow-sm"
		:class="{ hidden: isHidden }"
	>
		<!-- Main Header -->
		<nav class="navbar navbar-expand-lg navbar-light">
			<div class="container d-flex align-items-center justify-content-between">
				<!-- Logo -->
				<router-link
					class="navbar-brand d-flex align-items-center me-auto"
					to="/"
				>
					<img src="@/assets/logo.png" alt="safesecur" height="60" class="mx-2"/>
					<span class="fw-bold fs-4 fstext-uppercase" style="color: var(--d)">
						SAFESECUR
					</span>
				</router-link>

				<!-- Burger Menu Button -->
				<button
					class="navbar-toggler mx-2"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#navbarNav"
					aria-controls="navbarNav"
					aria-expanded="false"
					aria-label="Toggle navigation"
				>
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- Categories -->
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								to="/"
								@click.native="closeMenu"
							>
								<span class="icon fi-rr-home"></span>
								<span class="text mb-1 ms-1">Accueil</span>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								to="services"
								@click.native="closeMenu"
							>
								<span class="icon fi-rr-briefcase"></span>
								<span class="text mb-1 ms-1">Services</span>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								to="catalogue"
								@click.native="closeMenu"
							>
								<span class="icon fi-rr-folder"></span>
								<span class="text mb-1 ms-1">Catalogue</span>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								to="actualites"
								@click.native="closeMenu"
							>
								<span class="icon fi-rr-file-invoice"></span>
								<span class="text mb-1 ms-1">Actualités</span>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								to="contacts"
								@click.native="closeMenu"
							>
								<span class="icon fi-rr-envelope"></span>
								<span class="text mb-1 ms-1">Contacts</span>
							</router-link>
						</li>
						<li class="nav-item d-block d-lg-none">
							<router-link
								class="nav-link btn btn-outline-dark d-flex align-items-center justify-content-start"
								@click="toggleChat"
								@click.native="closeMenu"
								to="/Chat"
							>
								<span class="icon fi-rr-comment"></span>
								<span class="text mb-1 ms-1">Chat</span>
							</router-link>
						</li>
					</ul>
				</div>
				
			</div>
		</nav>

	</header>
</template>

<script>
	export default {
		name: "Header",
		data() {
			return {
				isChatOpen: false, // État pour afficher ou masquer la fenêtre de chat
				isHidden: false, // État pour masquer ou afficher le header
				lastScrollY: 0, // Position précédente du scroll
			};
		},
		methods: {
			closeMenu() {
				// Close the burger menu on mobile after clicking a link
				const navbarCollapse = document.getElementById("navbarNav");
				if (navbarCollapse.classList.contains("show")) {
					const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
						toggle: true,
					});
					bsCollapse.hide();
				}
			},
			handleScroll() {
				const currentScrollY = window.scrollY;
				this.isHidden =
					currentScrollY > this.lastScrollY && currentScrollY > 50; // Cache le header si on défile vers le bas
				this.lastScrollY = currentScrollY;
			},
		},
		mounted() {
			window.addEventListener("scroll", this.handleScroll); // Ajoute un écouteur pour le défilement
		},
		beforeUnmount() {
			window.removeEventListener("scroll", this.handleScroll); // Supprime l'écouteur lors de la destruction du composant
		},
	};
</script>

<style scoped>
	.header_safesecur {
		position: sticky;
		top: 0;
		z-index: 1030;
		background-color: var(--transparent-color, rgba(255, 255, 255, 0.8));
		transition: transform 0.3s ease-in-out;
	}

	.header_safesecur.hidden {
		transform: translateY(-100%);
	}

	.nav-link {
		font-size: 0.9rem;
		font-weight: 500;
		padding: 0.5rem 1rem;
		transition: color 0.3s ease;
		display: flex;
		align-items: center;
		justify-content: center;
		color: white; /* Assure que la couleur reste inchangée */
	}

	.nav-link span {
		font-size: 1rem;
	}

	.nav-link:hover {
		color: white !important; /* Supprime l'effet de hover bleu */
	}

	/* Styles pour les icônes et le texte */
	.nav-link {
		display: flex;
		align-items: center;
	}

	.nav-link .icon {
		font-size: 1.2rem; /* Taille des icônes */
		display: none; /* Hide icons by default on desktop */
		margin-right: 10px;
	}

	/* Show icons only on mobile */
	@media (max-width: 992px) {
		.nav-link .icon {
			display: inline-block; /* Show icons on mobile */
		}
	}

	.nav-link .text {
		font-size: 0.9rem; /* Taille du texte */
	}

	/* Version mobile */
	@media (max-width: 992px) {
		.nav-link .text {
			font-size: 0.9rem; /* Ajuste la taille du texte pour mobile */
		}

		.d-flex.align-items-center.d-none.d-lg-flex {
			position: fixed;
			bottom: 20px;
			right: 20px;
			z-index: 1050;
			display: flex !important; /* Assure que le bouton est visible */
		}

		.d-flex.align-items-center.d-none.d-lg-flex button {
			border-radius: 50px; /* Bouton arrondi */
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre pour un effet flottant */
		}
	}

	/* Styles pour la fenêtre de chat */
	.chat-window {
		position: fixed;
		bottom: 20px;
		right: 20px;
		width: 300px;
		height: 80%;
		background-color: white;
		border-radius: 10px;
		overflow: hidden;
		z-index: 1050;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	}

	.chat-header {
		background-color: var(--da); /* Utilisez votre variable de couleur primaire */
		color: white;
		padding: 10px;
	}

	.chat-body {
		padding: 10px;
		font-size: 0.9rem;
	}

	@media (min-width: 992px) {
		.chat-window {
			height: 60%;
		}
	}
</style>
