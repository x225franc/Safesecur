module.exports = [
	{
		ignores: [
			"**/node_modules/**",
			"**/dist/**",
			"**/public/**",
			"**/uploads/**",
			"**/*.min.js",
		],
	},
	{
		languageOptions: {
			ecmaVersion: "latest",
			sourceType: "module",
			globals: {
				// Browser
				window: "readonly",
				document: "readonly",
				navigator: "readonly",
				console: "readonly",
				FileReader: "readonly",
				Image: "readonly",
				File: "readonly",
				fetch: "readonly",
				// Node
				process: "readonly",
				__dirname: "readonly",
				__filename: "readonly",
				module: "readonly",
				require: "readonly",
				exports: "readonly",
				global: "readonly",
				Buffer: "readonly",
				setTimeout: "readonly",
				setInterval: "readonly",
				clearTimeout: "readonly",
				clearInterval: "readonly",
			},
		},
		rules: {
			"no-unused-vars": "warn",
			"no-undef": "error",
		},
	},
];
