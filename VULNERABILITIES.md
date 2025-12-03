## 🐛 Liste des Vulnérabilités

### 1. SQL Injection (SQLi) 💉

**Localisation:** `server/index.js` - Ligne ~964

**Route affectée:** `GET /actualites/:id`

**Description:**
La requête SQL utilise une concaténation directe du paramètre `id` au lieu d'une requête préparée.

**Code vulnérable:**
```javascript
app.get("/actualites/:id", (req, res) => {
    const { id } = req.params;
    // VULNÉRABLE: Utilisation de concaténation au lieu de requête paramétrée
    const query = `SELECT * FROM actualites WHERE id = ${id}`;
    db.query(query, (err, results) => {
        // ...
    });
});
```

**Exploitation possible:**
```bash
# Exemple d'injection
GET /actualites/999%20UNION%20SELECT%201,GROUP_CONCAT(username),GROUP_CONCAT(password),null,null%20FROM%20user HTTP/1.1
```

**Correction:**
```javascript
const query = "SELECT * FROM actualites WHERE id = ?";
db.query(query, [id], (err, results) => {
    // ...
});
```

---

### 2. Path Traversal 📁

**Localisation:** `server/index.js` - Ligne ~112

**Route affectée:** `GET /download/:filename`

**Description:**
Permet de télécharger n'importe quel fichier du système en utilisant des séquences `../` sans validation.

**Code vulnérable:**
```javascript
app.get("/download/:filename", (req, res) => {
    const { filename } = req.params;
    // VULNÉRABLE: Pas de validation du chemin, permet ../../../etc/passwd
    const filePath = path.join(__dirname, "uploads", filename);
    res.sendFile(filePath, (err) => {
        // ...
    });
});
```

**Exploitation possible:**
```bash
# Accéder à des fichiers système
GET localhost:3006/../package.json
```

**Correction:**
```javascript
app.get("/download/:filename", (req, res) => {
    const { filename } = req.params;
    
    // Validation : ne pas accepter les chemins relatifs
    if (filename.includes('..') || filename.includes('/') || filename.includes('\\')) {
        return res.status(400).json({ error: "Invalid filename" });
    }
    
    const filePath = path.join(__dirname, "uploads", filename);
    
    // Vérifier que le chemin résolu est bien dans le dossier uploads
    const uploadsDir = path.join(__dirname, "uploads");
    if (!filePath.startsWith(uploadsDir)) {
        return res.status(403).json({ error: "Access denied" });
    }
    
    res.sendFile(filePath);
});
```

---

### 3. Authentification Faible 🔐

**Localisation:** `server/index.js` - Lignes ~209-230

**Route affectée:** `POST /login`

**Description:**
Deux problèmes majeurs :
1. **Messages d'erreur trop détaillés** qui révèlent si un utilisateur existe ou si le mot de passe est incorrect
2. **Token JWT avec durée d'expiration trop longue** (30 jours)

**Code vulnérable:**
```javascript
// Vérifier si l'utilisateur existe
if (results.length === 0) {
    // VULNÉRABLE: Révèle que l'utilisateur n'existe pas
    return res.status(401).json({ 
        success: false, 
        message: "Utilisateur inexistant", 
        username: username 
    });
}

// ...

if (!match) {
    // VULNÉRABLE: Révèle que le mot de passe est incorrect
    return res.status(401).json({ 
        success: false, 
        message: "Mot de passe incorrect", 
        username: username 
    });
}

// VULNÉRABLE: Token avec durée d'expiration trop longue (30 jours)
const token = jwt.sign(
    { userId: user.id, username: user.username },
    JWT_SECRET,
    { expiresIn: "30d" }
);
```

**Exploitation possible:**
- Énumération des utilisateurs (tester si un username existe)
- Attaques par force brute facilitées
- Session longue durée = fenêtre d'attaque plus large

```
POST /login HTTP/1.1
Host: localhost:3005
Content-Length: 45
sec-ch-ua-platform: "Windows"
Accept-Language: en-US,en;q=0.9
Accept: application/json, text/plain, */*
sec-ch-ua: "Not_A Brand";v="99", "Chromium";v="142"
Content-Type: application/json
sec-ch-ua-mobile: ?0
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36
Origin: http://[::1]:3006
Sec-Fetch-Site: cross-site
Sec-Fetch-Mode: cors
Sec-Fetch-Dest: empty
Referer: http://[::1]:3006/
Accept-Encoding: gzip, deflate, br
Connection: keep-alive

{"username":"x225franc","password":"test123"}
```

**Correction:**
```javascript
// Messages génériques
if (results.length === 0 || !match) {
    return res.status(401).json({ 
        success: false, 
        message: "Identifiants incorrects" 
    });
}

// Token avec expiration courte
const token = jwt.sign(
    { userId: user.id, username: user.username },
    JWT_SECRET,
    { expiresIn: "1h" }  // ou 15min pour plus de sécurité
);

// Ajouter un rate limiting
// npm install express-rate-limit
const rateLimit = require("express-rate-limit");
const loginLimiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 minutes
    max: 5 // 5 tentatives max
});
app.post("/login", loginLimiter, async (req, res) => { /*...*/ });
```

---

### 4. Cross-Site Scripting (XSS) 🎭

**Localisation:** `frontend/src/views/Actualites/viewActualites.vue` - Lignes ~79 et ~198

**Description:**
Utilisation de `v-html` sans sanitisation pour afficher le contenu des actualités, permettant l'injection de code JavaScript malveillant.

**Code vulnérable:**

**Template (ligne ~79):**
```vue
<div class="actualite-text">
    <div
        class="content-description"
        v-html="formatText(actualite.description)"
    ></div>
</div>
```

**Script (ligne ~198):**
```javascript
formatText(text) {
    if (!text) return "";
    // VULNÉRABLE: Retourne le HTML sans sanitisation
    // Permet l'injection de scripts malveillants
    return text.replace(/\n/g, "<br>");
}
```

**Exploitation possible:**
```html
<!-- Dans le champ description d'une actualité -->
<script>alert('XSS')</script>
<img src=x onerror="alert('XSS')">
<iframe src="javascript:alert('XSS')"></iframe>
```

**Correction:**

**Option 1: Utiliser l'interpolation simple**
```vue
<!-- Remplacer v-html par {{ }} -->
<div class="content-description">
    {{ actualite.description }}
</div>
```

**Option 2: Sanitiser avec DOMPurify**
```bash
npm install dompurify
```

```javascript
import DOMPurify from 'dompurify';

formatText(text) {
    if (!text) return "";
    // Sanitiser le HTML avant de l'afficher
    const dirty = text.replace(/\n/g, "<br>");
    return DOMPurify.sanitize(dirty);
}
```

---

## 🛠️ Tests des Vulnérabilités

### Tester la SQL Injection
```bash
curl "http://localhost:3000/actualites/1%20OR%201=1"
```

### Tester le Path Traversal
```bash
curl "http://localhost:3000/download/../../../package.json"
```

### Tester l'Authentification
```bash
# Tester avec un utilisateur inexistant
curl -X POST http://localhost:3000/login \
  -H "Content-Type: application/json" \
  -d '{"username":"user_inexistant","password":"test"}'

# Observer le message révélant que l'utilisateur n'existe pas
```

### Tester le XSS
1. Se connecter à l'admin
2. Créer/Modifier une actualité
3. Dans la description, ajouter: `<img src=x onerror="alert('XSS')">`
4. Visualiser l'actualité et observer l'exécution du script

---

## 📋 Prochaines Étapes

Pour mettre en place le CI/CD qui détectera ces vulnérabilités, vous devrez configurer :

1. **GitHub Actions** - Pipeline de sécurité automatisé
2. **Outils d'analyse** :
   - `npm audit` - Vulnérabilités des dépendances
   - `Semgrep` - Analyse statique de code
   - `ESLint` avec plugins security - Détection de patterns dangereux
   - `Snyk` - Scan de sécurité complet

3. **Corrections** à appliquer après détection :
   - Requêtes SQL paramétrées
   - Validation stricte des entrées
   - Sanitisation du HTML
   - Rate limiting
   - Tokens courte durée

---

## 📚 Ressources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [Path Traversal](https://owasp.org/www-community/attacks/Path_Traversal)

---

**Créé pour le projet SAFESECUR - Formation CI/CD Sécurité**
