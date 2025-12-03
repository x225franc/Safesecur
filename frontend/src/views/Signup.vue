<template>
	<div class="signup-page">
		<div class="signup-container">
			<div class="signup-box" :class="{ loading: isLoading }">
				<div class="signup-header">
					<h1>SAFESECUR Signup</h1>
					<p class="signup-tagline">Créer un compte</p>
				</div>

				<div v-if="error" class="error-message">
					<i class="fi fi-rr-exclamation-circle"></i>
					<p>{{ error }}</p>
				</div>

				<div v-if="success" class="success-message">
					<i class="fi fi-rr-check-circle"></i>
					<p>{{ success }}</p>
				</div>

				<form @submit.prevent="handleSignup" class="signup-form">
					<div class="form-group">
						<label for="username">Nom d'utilisateur</label>
						<div class="input-container">
							<i class="fi fi-rr-user"></i>
							<input
								type="text"
								id="username"
								v-model="form.username"
								placeholder="Choisissez un identifiant"
								required
								:disabled="isLoading"
							/>
						</div>
					</div>

					<div class="form-group">
						<label for="password">Mot de passe</label>
						<div class="input-container">
							<i class="fi fi-rr-lock"></i>
							<input
								type="password"
								id="password"
								v-model="form.password"
								placeholder="Choisissez un mot de passe"
								required
								:disabled="isLoading"
							/>
						</div>
					</div>

					<button type="submit" class="signup-button" :disabled="isLoading">
						<span v-if="!isLoading">S'inscrire</span>
						<span v-else class="loading-spinner"></span>
					</button>

					<div class="login-link">
						<p>Vous avez déjà un compte ? <router-link to="/login">Connexion</router-link></p>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from "axios";

	export default {
		name: "Signup",
		data() {
			return {
				form: {
					username: "",
					password: "",
				},
				isLoading: false,
				error: null,
				success: null,
			};
		},
		methods: {
			handleSignup() {
				this.isLoading = true;
				this.error = null;
				this.success = null;

				axios
					.post(`${window.config.SERVER_URL}/signup`, this.form)
					.then((response) => {
						if (response.data.success) {
							this.success = "Compte créé avec succès ! Redirection...";
							
							// Stocker le token JWT dans le localStorage
							localStorage.setItem("token", response.data.token);

							// Redirection vers l'accueil après 2 secondes
							setTimeout(() => {
								this.$router.push("/");
							}, 2000);
						}
					})
					.catch((error) => {
						if (
							error.response &&
							error.response.data &&
							error.response.data.message
						) {
							this.error = error.response.data.message;
						} else {
							this.error = "Une erreur est survenue. Veuillez réessayer.";
						}
					})
					.finally(() => {
						this.isLoading = false;
					});
			},
		},
	};
</script>

<style scoped>
	.signup-page {
		--primary-color: #d72f3f;
		--primary-hover: #d72f4088;
		--secondary-color: #f3f4f6;
		--accent-color: #3b82f6;
		--text-color: #1f2937;
		--light-text: #6b7280;
		--dark-bg: #111827;
		--error-color: #ef4444;
		--success-color: #10b981;
		--input-border: #e5e7eb;
		--shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
		--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
			0 2px 4px -2px rgba(0, 0, 0, 0.1);
		--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
			0 4px 6px -4px rgba(0, 0, 0, 0.1);
		--rounded-md: 8px;
		--rounded-lg: 12px;
		--rounded-full: 9999px;

		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, #d72f3f 0%, #ff6b6b 100%);
		padding: 20px;
		font-family: "Inter", "Roboto", sans-serif;
		position: relative;
		overflow: hidden;
	}

	/* Background animated elements */
	.signup-page::before,
	.signup-page::after {
		content: "";
		position: absolute;
		border-radius: 50%;
		opacity: 0.1;
	}

	.signup-page::before {
		width: 600px;
		height: 600px;
		background: radial-gradient(circle, #fff 0%, transparent 70%);
		top: -200px;
		right: -200px;
		animation: float 30s infinite ease-in-out;
	}

	.signup-page::after {
		width: 400px;
		height: 400px;
		background: radial-gradient(circle, #fff 0%, transparent 70%);
		bottom: -100px;
		left: -100px;
		animation: float 20s infinite ease-in-out reverse;
	}

	@keyframes float {
		0%,
		100% {
			transform: translateY(0) scale(1);
		}
		50% {
			transform: translateY(-30px) scale(1.05);
		}
	}

	.signup-container {
		width: 100%;
		max-width: 450px;
		position: relative;
		z-index: 10;
	}

	.signup-box {
		background-color: white;
		border-radius: var(--rounded-lg);
		box-shadow: var(--shadow-lg);
		padding: 40px;
		transition: transform 0.3s ease, box-shadow 0.3s ease;
	}

	.signup-box:hover {
		transform: translateY(-5px);
		box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
			0 8px 10px -6px rgba(0, 0, 0, 0.1);
	}

	.signup-header {
		text-align: center;
		margin-bottom: 35px;
	}

	.signup-header h1 {
		color: var(--text-color);
		font-size: 2.2rem;
		font-weight: 800;
		margin-bottom: 10px;
		letter-spacing: -0.5px;
		background: linear-gradient(to right, #d72f3f, #ff6b6b);
		-webkit-background-clip: text;
		background-clip: text;
		-webkit-text-fill-color: transparent;
	}

	.signup-tagline {
		color: var(--light-text);
		margin: 0;
		font-size: 0.95rem;
		font-weight: 400;
	}

	.error-message {
		background-color: rgba(239, 68, 68, 0.1);
		color: var(--error-color);
		padding: 14px;
		border-radius: var(--rounded-md);
		margin-bottom: 25px;
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 0.95rem;
		border-left: 4px solid var(--error-color);
	}

	.success-message {
		background-color: rgba(16, 185, 129, 0.1);
		color: var(--success-color);
		padding: 14px;
		border-radius: var(--rounded-md);
		margin-bottom: 25px;
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 0.95rem;
		border-left: 4px solid var(--success-color);
	}

	.form-group {
		margin-bottom: 24px;
	}

	label {
		display: block;
		margin-bottom: 8px;
		font-weight: 600;
		font-size: 0.9rem;
		color: var(--text-color);
		letter-spacing: 0.02em;
	}

	.input-container {
		position: relative;
		display: flex;
		align-items: center;
	}

	.input-container i {
		position: absolute;
		left: 16px;
		color: var(--light-text);
		font-size: 1rem;
	}

	input[type="text"],
	input[type="password"] {
		width: 100%;
		padding: 14px 16px 14px 42px;
		border: 1px solid var(--input-border);
		border-radius: var(--rounded-md);
		font-size: 0.95rem;
		transition: all 0.3s ease;
		background-color: var(--secondary-color);
		color: var(--text-color);
	}

	input[type="text"]:focus,
	input[type="password"]:focus {
		outline: none;
		border-color: var(--accent-color);
		box-shadow: 0 0 0 3px rgba(215, 47, 63, 0.25);
		background-color: white;
	}

	.signup-button {
		width: 100%;
		background-color: var(--primary-color);
		color: white;
		border: none;
		padding: 14px 0;
		border-radius: var(--rounded-md);
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.signup-button::before {
		content: "";
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(
			to right,
			transparent,
			rgba(255, 255, 255, 0.3),
			transparent
		);
		transform: translateX(-100%);
	}

	.signup-button:hover {
		background-color: var(--primary-hover);
		box-shadow: var(--shadow-md);
		transform: translateY(-1px);
	}

	.signup-button:hover::before {
		transform: translateX(100%);
		transition: transform 0.8s ease;
	}

	.loading-spinner {
		display: inline-block;
		width: 20px;
		height: 20px;
		border: 3px solid rgba(255, 255, 255, 0.3);
		border-radius: 50%;
		border-top-color: white;
		animation: spin 1s ease-in-out infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	.signup-box.loading {
		opacity: 0.7;
		pointer-events: none;
	}

	.login-link {
		text-align: center;
		margin-top: 20px;
	}

	.login-link p {
		color: var(--light-text);
		font-size: 0.9rem;
		margin: 0;
	}

	.login-link a {
		color: var(--primary-color);
		font-weight: 600;
		text-decoration: none;
		transition: color 0.2s ease;
	}

	.login-link a:hover {
		color: #ff6b6b;
		text-decoration: underline;
	}

	@media (max-width: 500px) {
		.signup-box {
			padding: 30px 20px;
		}

		.signup-header h1 {
			font-size: 1.8rem;
		}

		.signup-button {
			padding: 12px 0;
		}
	}
</style>
