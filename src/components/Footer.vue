<template>
	<footer class="footer_safesecur pt-4 text-white shadow-sm">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<!-- NAVIGATION -->
					<h6 class="fw-bold text-uppercase">
						Votre sécurité , notre priorité
					</h6>
					<p>
						SAFESECUR - partenaire certifié de fabricants leaders dans leurs
						domaines.
					</p>
					<i class="text-white mb-0"
						>© {{ new Date().getFullYear() }} Tous droits réservés</i
					>
					<!-- Logo -->
					<router-link class="navbar-brand d-flex align-items-center me-auto" to="/">
						<img
							src="@/assets/logo.png"
							alt="safesecur"
							height="60"
							class="mx-2 my-2"
						/>
						<span
							class="fw-bold fs-4 fstext-uppercase"
							style="color: var(--d)"
						>
							SAFESECUR
						</span>
					</router-link>
				</div>

				<!-- NAVIGATION -->
				<div class="col-md-3">
					<h6 class="fw-bold text-uppercase">Navigation</h6>
					<ul class="list-unstyled">
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-home me-2"></span
							><router-link to="/" class="text-white">Accueil</router-link>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-briefcase me-2"></span
							><router-link to="services" class="text-white"
								>Services</router-link
							>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-folder me-2"></span
							><router-link to="catalogue" class="text-white"
								>Catalogue</router-link
							>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-file-invoice me-2"></span
							><router-link to="actualites" class="text-white">Actualités</router-link>
						</li>
						<!-- <li class="d-flex align-items-center mb-2">
							<span class="fi-rr-user me-2"></span
							><router-link to="/Admin" class="text-white">Espace Admin</router-link>
						</li> -->
					</ul>
				</div>

				<!-- CONTACT -->
				<div class="col-md-4">
					<router-link
						to="/contacts"
						class="fw-bold text-uppercase text-white text-decoration-none"
						>Contacts</router-link
					>
					<ul class="list-unstyled">
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-phone-call me-2"></span
							><a href="tel:+2252722495018" class="text-white"
								>+225 27 22 49 50 18</a
							>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-smartphone me-2"></span
							><a href="tel:+2250707526272" class="text-white"
								>+225 07 07 52 62 72</a
							>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-at me-2"></span
							><a href="mailto:contact@safesecur.com" class="text-white"
								>contact@safesecur.com</a
							>
						</li>
						<li class="d-flex align-items-center mb-2">
							<span class="fi-rr-home-location-alt me-2"></span>
							<a
								href="https://goo.gl/maps/rpZncQoDU9PPaDFeA"
								class="text-white"
								target="_blank"
							>
								Riviera palmeraie, derrière la sodeci - Abidjan (Côte d'Ivoire)
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
</template>

<script>
	export default {
		name: "Footer",
	};
</script>

<style scoped>
	.footer_safesecur {
		border-top: 1px solid #dee2e6;
		background-color: var(--da);
		width: 100%; /* Assure que le footer occupe toute la largeur */
		bottom: 0; /* Place le footer en bas */
		left: 0; /* Aligne le footer à gauche */
		z-index: 1030; /* Assure que le footer reste au-dessus des autres éléments */
		padding: 10px 0; /* Ajoute un peu d'espace intérieur */
	}

	.footer_safesecur a {
		text-decoration: none;
		transition: color 0.3s ease; /* Ajoute une transition pour l'effet de hover */
		color: var(--da); /* Couleur par défaut */
	}

	.footer_safesecur a:hover {
		color: var(--p) !important; /* Change la couleur au survol */
	}

	body {
		padding-bottom: 100px; /* Ajoute un espace pour éviter que le contenu ne soit masqué par le footer */
	}
</style>
