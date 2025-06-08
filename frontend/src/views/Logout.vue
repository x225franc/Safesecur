<template>
	<div class="logout-page">
		<div class="loader-container">
			<div class="loader"></div>
			<p>Déconnexion en cours...</p>
		</div>
	</div>
</template>

<script>
	// import axios from "axios";

	export default {
		name: "Logout",
		created() {
			this.logout();
		},
		methods: {
			logout() {
				// Supprime le token JWT du localStorage
				localStorage.removeItem("token");

				// Redirection vers la page d'accueil après un court délai
				setTimeout(() => {
					this.$router.push("/");
				}, 1000); // Délai de 1 seconde pour montrer l'animation
			},
		},
	};
</script>

<style scoped>
	.logout-page {
		height: 100vh;
		display: flex;
		justify-content: center;
		align-items: center;
		background-color: #f9fafb;
	}

	.loader-container {
		text-align: center;
	}

	.loader {
		border: 4px solid rgba(0, 0, 0, 0.1);
		border-radius: 50%;
		border-top: 4px solid #d72f3f;
		width: 50px;
		height: 50px;
		margin: 0 auto 20px;
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

	p {
		font-family: "Inter", sans-serif;
		color: #4b5563;
		font-size: 1.1rem;
	}
</style>
