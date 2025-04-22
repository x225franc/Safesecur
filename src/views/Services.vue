<template>
	<div class="services-page">
		<!-- Hero Section -->
		<section class="page-hero">
			<div class="container">
				<h1>Nos Services</h1>
				<p class="hero-subtitle">
					Découvrez notre gamme complète de solutions de sécurité
				</p>
			</div>
		</section>

		<section class="services-section">
			<div class="container">
				<div class="row">
					<!-- Colonne gauche -->
					<div class="col-md-6">
						<div class="accordion" id="accordionLeft">
							<!-- Service 1 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseOne')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-shield-check mt-1"></i>
										SÉCURITÉ TRADITIONNELLE
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseOne') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseOne') }"
								>
									<div class="service-body">
										<ul>
											<li>Extincteurs</li>
											<li>
												Matériels blindes (coffre-fort, armoires ignifuges
												réfractaires)
											</li>
											<li>
												Divers matériels d'intervention et de sauvetage
												(pompier)
											</li>
											<li>Réseau incendie –RIA (Robinets incendie Armes)</li>
											<li>Emulseur appareillage à mousse etc.</li>
											<li>
												Une gamme complète des BAES (Bloc Autonome d'éclairage
												et de sécurité) répondant à vos besoins en éclairage de
												sécurité
											</li>
											<li>Fabrication des SAS de sécurité</li>
											<li>Serrurerie</li>
											<li>Porte et matériel blindés</li>
											<li>
												Grilles renforcées ;(vitrage triplex guichet et caisse
												blindes)
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Service 2 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseTwo')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-computer mt-1"></i>
										PRESTATION RÉSEAU ET INFORMATIQUE
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseTwo') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseTwo') }"
								>
									<div class="service-body">
										<ul>
											<li>Déploiement et maintenance de réseau haut débit…</li>
											<li>
												Conseil et dimensionnement, déploiement et maintenance
												de système d'information…
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Service 3 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseThree')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-fingerprint mt-1"></i>
										CONTRÔLE D'ACCÈS ET GESTION DE TEMPS
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseThree') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseThree') }"
								>
									<div class="service-body">
										<p>
											Collaborateurs, visiteurs, intervenants temporaires,
											entreprises extérieures…
										</p>
										<p>
											Chaque jour, nombreuses personnes se déplacent dans les
											entreprises et bâtiments publics. Comment donc distinguer
											l'intervention d'une entreprise extérieure ou une visite
											d'un acte de malveillance en préparation?
										</p>
										<p>
											<strong>SAFESECUR</strong> vous propose alors des systèmes
											performants et simples d'utilisation qui vous permettent
											de filtrer les accès et de définir des zones de protocole.
										</p>
										<img
											src="@/assets/ressources/controle.jpg"
											alt="Contrôle d'accès"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 4 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseFour')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-settings mt-1"></i>
										L'INTÉGRATION DE SYSTÈME DE SÉCURITÉ
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseFour') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseFour') }"
								>
									<div class="service-body">
										<p>Intégration de tous les systèmes de sécurité pour:</p>
										<ul>
											<li>
												L'analyse des risques, la conception du système et la
												consultance
											</li>
											<li>Gestion de la vidéo numérique et analogique</li>
											<li>Analyse vidéo</li>
											<li>Vidéosurveillance en tant que service (VSAAS)</li>
											<li>Surveillance 24/7</li>
										</ul>
										<img
											src="@/assets/ressources/monitor.jpg"
											alt="Monitor de surveillance"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 5 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseFive')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-flame mt-1"></i>
										EXTINCTEURS PORTATIFS ET MOBILES
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseFive') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseFive') }"
								>
									<div class="service-body">
										<p>
											Disposer d'extincteurs dans un bâtiment est chose
											indispensable. En cas de départ d'incendie, le simple fait
											d'en disposer et de s'en servir aide à réduire l'étendue
											du sinistre et sauve même des vies.
										</p>
										<p>
											<strong>SAFESECUR</strong> dans son optique de recherche
											continue de la qualité met à votre disposition des
											équipements de qualité certifiés
											<strong
												>ISO 9000, CE, VERITAS, Marine Division, NF- AFNOR,
												EN3</strong
											>
											selon les équipements, produits par un fabriquant de
											renommée internationale.
										</p>
										<img
											src="@/assets/ressources/extincteurs.jpg"
											alt="Extincteurs"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 6 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseSix')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-bulb mt-1"></i>
										SIGNALISATIONS ET ÉCLAIRAGE DE SÉCURITÉ
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseSix') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseSix') }"
								>
									<div class="service-body">
										<img
											src="@/assets/ressources/securite.png"
											alt="Signalisation"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 7 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseSeven')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-document-signed mt-1"></i>
										CONSEILS ET AUDITS
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseSeven') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseSeven') }"
								>
									<div class="service-body">
										<p>
											La sécurité incendie des entreprises, des établissements
											recevant du public ou la sécurité des personnes lors de
											manifestations ponctuelles ou résidentielles ne peuvent
											être confiées qu'à des spécialistes rompus à ce genre
											d'exercice.
										</p>
										<p>
											Pour répondre au mieux à l'attente de nos clients et
											concilier leurs besoins avec la ou les réglementations en
											vigueur, nous commençons notre intervention par l'analyse
											des risques en vue d'élaborer un audit et conseils en
											sécurité incendie et systèmes de protection afin de
											définir une stratégie d'intervention dont tous les aspects
											sont pris en compte (organisationnels, personnels,
											architecturaux, techniques ou stratégiques). Ces missions
											et vérifications sont réalisées par des techniciens
											expérimentés.
										</p>
										<img
											src="@/assets/ressources/b.jpg"
											alt="Audit"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Colonne droite -->
					<div class="col-md-6">
						<div class="accordion" id="accordionRight">
							<!-- Service 8 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseEight')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-shield mt-1"></i>
										SÉCURITÉ EN AUTOMATISMES ET HAUTES TECHNOLOGIES
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseEight') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseEight') }"
								>
									<div class="service-body">
										<ul>
											<li>
												Système de Détection incendie et alarme Évacuation
											</li>
											<li>Extinction automatique à gaz</li>
											<li>Extinction a eau et poudre</li>
											<li>Contrôle d'accès</li>
											<li>Appel Infirmier</li>
											<li>Interphonie et vidéophonie sur IP</li>
											<li>Gestion du temps et du personnel</li>
											<li>Gestion des paiements</li>
											<li>
												Vidéosurveillance analogique ou IP, Télésurveillance
											</li>
											<li>Détection intrusion et vol (risques courants)</li>
										</ul>
									</div>
								</div>
							</div>

							<!-- Service 9 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseNine')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-hospital mt-1"></i>
										SÉCURITÉ DES SERVICES MÉDICAUX ET DES PATIENTS
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseNine') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseNine') }"
								>
									<div class="service-body">
										<p>
											Une illustration d'un centre de santé avec tout le système
											de sécurité que nous fournissons.
										</p>
										<img
											src="@/assets/ressources/c.jpg"
											alt="Services médicaux"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 10 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseTen')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-grid mt-1"></i>
										L'INTÉGRATION AVEC LA GESTION DES INFORMATIONS
										<i
											class="toggle-icon"
											:class="{ active: activeItems.includes('collapseTen') }"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseTen') }"
								>
									<div class="service-body">
										<img
											src="@/assets/ressources/d.jpg"
											alt="Gestion des informations"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 11 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseEleven')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-video-camera mt-1"></i>
										VIDÉOSURVEILLANCE IP
										<i
											class="toggle-icon"
											:class="{
												active: activeItems.includes('collapseEleven'),
											}"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseEleven') }"
								>
									<div class="service-body">
										<p>
											Quasiment complémentaire au contrôle d'accès, la
											vidéosurveillance vous permet d'avoir littéralement un œil
											sur tout mouvement à l'intérieur et à l'extérieur du
											bâtiment. Un système de vidéo surveillance constitue donc
											un allié sûr et fiable qui permet d'observer tout
											mouvement suspect à l'intérieur du bâtiment et d'anticiper
											d'éventuels intrusions et incidents.
										</p>
										<img
											src="@/assets/ressources/e.jpg"
											alt="Vidéosurveillance"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 12 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseTwelve')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-water-bottle mt-1"></i>
										ROBINETTERIE ET MATÉRIEL D'INTERVENTION INCENDIE
										<i
											class="toggle-icon"
											:class="{
												active: activeItems.includes('collapseTwelve'),
											}"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseTwelve') }"
								>
									<div class="service-body">
										<p>
											Pour agir rapidement et efficacement sur les zones à
											risques, nous mettons à votre disposition quantité de
											systèmes, pompes et robinets incendie armés conçus par la
											marque référence en élaboration de ces types de systèmes.
										</p>
										<img
											src="@/assets/ressources/f.jpg"
											alt="Robinetterie incendie"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 13 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseThirteen')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-user-helmet-safety mt-1"></i>
										ÉQUIPEMENTS DE PROTECTION INDIVIDUELLE
										<i
											class="toggle-icon"
											:class="{
												active: activeItems.includes('collapseThirteen'),
											}"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseThirteen') }"
								>
									<div class="service-body">
										<p>
											Un Équipement de Protection Individuelle (E.P.I.) est un
											dispositif ou un moyen destiné à être porté ou être tenu
											par une personne en vue de la protéger contre un ou
											plusieurs risques susceptibles de menacer sa santé ainsi
											que sa sécurité. Les EPI vont du casque aux chaussures de
											sécurité en passant par les lunettes, les masques de
											protection respiratoire, les bouchons d'oreille, les
											gants, …
										</p>
										<p>
											<strong>SAFESECUR</strong> vous fournit des équipements
											fiables conçus par des fabricants de référence.
										</p>
										<img
											src="@/assets/ressources/g.jpg"
											alt="Equipements de protection"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>

							<!-- Service 14 -->
							<div class="service-card">
								<div
									class="service-header"
									@click="toggleAccordion('collapseFourteen')"
								>
									<h2 class="service-title">
										<i class="fi fi-rr-users-class mt-1"></i>
										FORMATIONS
										<i
											class="toggle-icon"
											:class="{
												active: activeItems.includes('collapseFourteen'),
											}"
										></i>
									</h2>
								</div>
								<div
									class="collapse-content"
									:class="{ active: activeItems.includes('collapseFourteen') }"
								>
									<div class="service-body">
										<p>
											Afin de permettre au client de se familiariser à
											l'utilisation et la manipulation du matériel ainsi qu'aux
											mesures d'urgence, SAFESECUR organise des formations
											auprès des entreprises, ses clients, pour leur apporter le
											meilleur service possible.
										</p>
										<img
											src="@/assets/ressources/h.jpg"
											alt="Formations"
											class="img-fluid"
										/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Section CTA -->
		<section class="cta-section">
			<div class="container">
				<div class="cta-content">
					<h2>
						<i class="fi fi-rr-headset mt-1"></i>
						Besoin d'un service sur mesure ?
					</h2>
					<p>
						Contactez nos experts pour une analyse personnalisée de vos besoins
						en sécurité
					</p>
					<router-link to="/contacts" class="cta-button">
						Contactez-nous
						<i class="fi fi-rr-envelope mt-1"></i>
					</router-link>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	export default {
		name: "Services",
		data() {
			return {
				activeItems: [],
			};
		},
		methods: {
			toggleAccordion(id) {
				if (this.activeItems.includes(id)) {
					this.activeItems = this.activeItems.filter((item) => item !== id);
				} else {
					this.activeItems.push(id);
				}
			},
		},
	};
</script>

<style scoped>
	.services-page {
		--primary-color: #e60000;
		--primary-dark: #c00000;
		--secondary-color: #212529;
		--background-light: #f8f9fa;
		--text-color: #333;
		--light-text: #fff;
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

	/* Section des services */
	.services-section {
		background-color: var(--background-light);
		padding: 60px 0;
	}

	.accordion {
		margin-bottom: 30px;
	}

	.service-card {
		background-color: white;
		border-radius: 8px;
		overflow: hidden;
		margin-bottom: 20px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
		transition: all 0.3s ease;
	}

	.service-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
	}

	.service-header {
		background: var(--primary-color);
		padding: 12px 20px;
		cursor: pointer;
		position: relative;
		transition: all 0.3s ease;
	}

	.service-header:hover {
		background: var(--primary-dark);
	}

	.service-title {
		color: white;
		margin: 0;
		font-size: 1.15rem;
		font-weight: 600;
		display: flex;
		align-items: center;
	}

	.service-title i {
		margin-right: 10px;
		font-size: 1.3rem;
	}

	.toggle-icon {
		margin-left: auto;
		transition: transform 0.3s ease;
		position: relative;
		width: 20px;
		height: 20px;
	}

	.toggle-icon:before,
	.toggle-icon:after {
		content: "";
		position: absolute;
		background-color: white;
		transition: all 0.3s ease;
	}

	.toggle-icon:before {
		width: 20px;
		height: 2px;
		top: 9px;
		left: 0;
	}

	.toggle-icon:after {
		width: 2px;
		height: 20px;
		left: 9px;
		top: 0;
	}

	.toggle-icon.active:after {
		transform: rotate(90deg);
		opacity: 0;
	}

	.collapse-content {
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.5s ease;
	}

	.collapse-content.active {
		max-height: 2000px;
	}

	.service-body {
		padding: 20px;
		color: var(--text-color);
		background-color: #212529;
		color: white;
	}

	.service-body p {
		margin-bottom: 1rem;
		line-height: 1.6;
	}

	.service-body ul {
		padding-left: 20px;
		margin-bottom: 1rem;
	}

	.service-body ul li {
		margin-bottom: 8px;
	}

	.service-body img {
		width: 100%;
		border-radius: 5px;
		margin-top: 15px;
		transition: transform 0.3s ease;
	}

	.service-body img:hover {
		transform: scale(1.02);
	}

	/* Section CTA */
	.cta-section {
		background: linear-gradient(to right,rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.7)),
			url("@/assets/ressources/2.jpg") center/cover;
		padding: 60px 0;
		color: white;
		text-align: center;
	}

	.cta-content {
		max-width: 800px;
		margin: 0 auto;
	}

	.cta-content h2 {
		font-size: 2.2rem;
		font-weight: 700;
		margin-bottom: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 15px;
	}

	.cta-content p {
		font-size: 1.2rem;
		margin-bottom: 30px;
	}

	.cta-button {
		background-color: white;
		color: var(--primary-color);
		padding: 12px 30px;
		font-size: 1.1rem;
		font-weight: 600;
		border-radius: 30px;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		transition: all 0.3s ease;
	}

	.cta-button i {
		margin-left: 10px;
	}

	.cta-button:hover {
		background-color: var(--secondary-color);
		color: white;
		transform: translateY(-3px);
		box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
	}

	/* Responsive adjustments */
	@media (max-width: 768px) {
		.page-hero h1 {
			font-size: 2.5rem;
		}

		.hero-subtitle {
			font-size: 1.2rem;
		}

		.service-title {
			font-size: 1rem;
		}

		.service-header {
			padding: 10px 15px;
		}

		.cta-content h2 {
			font-size: 1.8rem;
		}
	}
</style>
