# 🚀 Guide de Déploiement - Garage Backoffice

## 📋 Table des matières

1. [Prérequis](#prérequis)
2. [Configuration](#configuration)
3. [Déploiement](#déploiement)
4. [Sécurité](#sécurité)
5. [Monitoring](#monitoring)
6. [Maintenance](#maintenance)

---

## 🔧 **Prérequis**

### **Infrastructure requise**
- 🐳 **Docker** et **Docker Compose**
- 🌐 **Nom de domaine** avec DNS configuré
- 🔒 **SSL/TLS** certificat (recommandé)
- 💾 **Espace disque** : minimum 10GB
- 🖥️ **RAM** : minimum 2GB

### **Services externes**
- 🗄️ **Base de données** : MySQL 8.0
- 📦 **Cache** : Redis 7
- 🔥 **Firebase** : Configuré et fonctionnel

---

## ⚙️ **Configuration**

### **1. Variables d'environnement**

Copiez le fichier de configuration production :
```bash
cp .env.production .env
```

### **2. Mettez à jour les variables critiques**

#### **Sécurité**
```bash
# Base de données
DB_ROOT_PASSWORD=VotreMotDePasseRootTresSecurise123!
DB_PASSWORD=VotreMotDePasseDB456!

# Redis
REDIS_PASSWORD=VotreMotDePasseRedis789!

# Application
APP_URL=https://votre-domaine.com
```

#### **Firebase**
- ✅ Vérifiez que les clés Firebase sont correctes
- ✅ Testez la connexion : `php artisan tinker` → `app(NotificationService::class)->testConnection()`

#### **Email**
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-fournisseur.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@votre-domaine.com
MAIL_PASSWORD=VotreMotDePasseEmail
```

### **3. Configuration SSL**

Créez le dossier SSL :
```bash
mkdir -p nginx/ssl
```

Ajoutez vos certificats :
```bash
# Certificat SSL
cp votre-certificat.crt nginx/ssl/
cp votre-cle-privee.key nginx/ssl/
```

---

## 🚀 **Déploiement**

### **Étape 1 : Build des images**

```bash
# Build l'image de production
docker-compose -f docker-compose.prod.yml build
```

### **Étape 2 : Démarrage des services**

```bash
# Démarrer tous les services
docker-compose -f docker-compose.prod.yml up -d

# Vérifier le statut
docker-compose -f docker-compose.prod.yml ps
```

### **Étape 3 : Initialisation de la base de données**

```bash
# Exécuter les migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Optimiser le cache
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### **Étape 4 : Création de l'administrateur**

```bash
# Accéder au conteneur
docker-compose -f docker-compose.prod.yml exec app bash

# Lancer Artisan
php artisan tinker

# Créer l'utilisateur admin
App\Models\User::create([
    'name' => 'Admin Garage',
    'email' => 'admin@votre-domaine.com',
    'password' => Hash::make('VotreMotDePasseAdmin123!')
]);
```

### **Étape 5 : Vérification**

```bash
# Test de l'application
curl -I https://votre-domaine.com

# Test Firebase
curl https://votre-domaine.com/test/firebase
```

---

## 🔒 **Sécurité**

### **1. Configuration HTTPS**

Configurez nginx pour SSL :
```nginx
# Dans nginx/nginx.conf
server {
    listen 443 ssl http2;
    server_name votre-domaine.com;
    
    ssl_certificate /etc/nginx/ssl/votre-certificat.crt;
    ssl_certificate_key /etc/nginx/ssl/votre-cle-privee.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;
}
```

### **2. Headers de sécurité**

```nginx
# Headers déjà configurés dans nginx.prod.conf
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
```

### **3. Firewall**

Configurez le firewall :
```bash
# Autoriser uniquement les ports nécessaires
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw enable
```

### **4. Mises à jour de sécurité**

```bash
# Mettre à jour les packages
docker-compose -f docker-compose.prod.yml pull

# Redémarrer avec les nouvelles images
docker-compose -f docker-compose.prod.yml up -d
```

---

## 📊 **Monitoring**

### **1. Logs de l'application**

```bash
# Logs Laravel
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log

# Logs Nginx
docker-compose -f docker-compose.prod.yml exec nginx tail -f /var/log/nginx/access.log

# Logs PHP-FPM
docker-compose -f docker-compose.prod.yml exec app tail -f /var/log/php8.1-fpm.log
```

### **2. Surveillance des services**

```bash
# Statut des conteneurs
docker-compose -f docker-compose.prod.yml ps

# Utilisation des ressources
docker stats

# Espace disque
df -h
```

### **3. Health checks**

Ajoutez des health checks dans `docker-compose.prod.yml` :
```yaml
healthcheck:
  test: ["CMD", "curl", "-f", "http://localhost"]
  interval: 30s
  timeout: 10s
  retries: 3
```

---

## 🔧 **Maintenance**

### **1. Sauvegardes**

#### **Base de données**
```bash
# Sauvegarde automatique quotidienne
docker-compose -f docker-compose.prod.yml exec db mysqldump \
  -u root -p garage_db > backup_$(date +%Y%m%d).sql
```

#### **Fichiers**
```bash
# Sauvegarder les fichiers importants
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/
```

### **2. Mises à jour de l'application**

```bash
# 1. Sauvegarder
./backup.sh

# 2. Mettre à jour le code
git pull origin main

# 3. Rebuild
docker-compose -f docker-compose.prod.yml build --no-cache

# 4. Migrer
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 5. Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
```

### **3. Nettoyage**

```bash
# Nettoyer les vieux conteneurs
docker system prune -f

# Nettoyer les logs anciens
find storage/logs -name "*.log" -mtime +30 -delete
```

---

## 🚨 **Dépannage**

### **Problèmes courants**

#### **Page blanche**
```bash
# Vérifier les permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache

# Vider le cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
```

#### **Erreur 502 Bad Gateway**
```bash
# Redémarrer PHP-FPM
docker-compose -f docker-compose.prod.yml restart app

# Vérifier les logs
docker-compose -f docker-compose.prod.yml logs app
```

#### **Connexion Firebase échouée**
```bash
# Test de connexion
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> app(NotificationService::class)->testConnection()
```

#### **Base de données inaccessible**
```bash
# Vérifier la connexion
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \DB::connection()->getPdo()
```

### **Scripts utiles**

#### **Script de santé** (`health.sh`)
```bash
#!/bin/bash
echo "🔍 Vérification de santé du Garage Backoffice..."

# Test application
if curl -f -s http://localhost > /dev/null; then
    echo "✅ Application accessible"
else
    echo "❌ Application inaccessible"
    exit 1
fi

# Test base de données
if docker-compose -f docker-compose.prod.yml exec -T db mysql -u root -p$DB_ROOT_PASSWORD -e "SELECT 1" > /dev/null 2>&1; then
    echo "✅ Base de données accessible"
else
    echo "❌ Base de données inaccessible"
    exit 1
fi

# Test Redis
if docker-compose -f docker-compose.prod.yml exec -T redis redis-cli ping > /dev/null 2>&1; then
    echo "✅ Redis accessible"
else
    echo "❌ Redis inaccessible"
    exit 1
fi

echo "🎉 Tous les services sont opérationnels!"
```

---

## 📈 **Performance**

### **Optimisations**

#### **OPcache**
Déjà configuré dans `Dockerfile.prod` :
- Memory: 256MB
- Max files: 10000
- Revalidate: 0 (production)

#### **Redis Cache**
```bash
# Vérifier l'utilisation du cache
docker-compose -f docker-compose.prod.yml exec redis redis-cli info memory
```

#### **Nginx Gzip**
Activé avec compression level 6 pour les fichiers statiques.

### **Monitoring performance**

```bash
# Temps de réponse
curl -w "@curl-format.txt" -o /dev/null -s https://votre-domaine.com

# Format curl-format.txt
#      time_namelookup:  %{time_namelookup}\n
#         time_connect:  %{time_connect}\n
#      time_appconnect:  %{time_appconnect}\n
#     time_pretransfer:  %{time_pretransfer}\n
#        time_redirect:  %{time_redirect}\n
#   time_starttransfer:  %{time_starttransfer}\n
#                      ----------\n
#           time_total:  %{time_total}\n
```

---

## 🎯 **Checklist de déploiement**

### **Avant le déploiement**
- [ ] Variables d'environnement configurées
- [ ] Certificats SSL en place
- [ ] Firebase testé et fonctionnel
- [ ] Backup de la base de données existante
- [ ] DNS configuré

### **Après le déploiement**
- [ ] Application accessible via HTTPS
- [ ] Login administrateur fonctionnel
- [ ] Base de données synchronisée
- [ ] Firebase connecté
- [ ] Export PDF/Excel fonctionnel
- [ ] Monitoring configuré
- [ ] Sauvegardes automatisées

---

## 📞 **Support**

### **Contact en cas de problème**
1. 📋 **Consultez ce guide**
2. 🔍 **Vérifiez les logs**
3. 🧪 **Testez les services individuellement**
4. 📧 **Contactez le support technique**

### **Ressources utiles**
- 📖 [Documentation Laravel](https://laravel.com/docs)
- 🐳 [Documentation Docker](https://docs.docker.com)
- 🔥 [Documentation Firebase](https://firebase.google.com/docs)

---

## 🎉 **Conclusion**

Votre **Garage Backoffice** est maintenant déployé en production !

### **Prochaines étapes**
1. 📊 **Configurez le monitoring**
2. 🔒 **Mettez en place les sauvegardes**
3. 📈 **Surveillez les performances**
4. 🔄 **Planifiez les mises à jour**

**Félicitations pour votre déploiement réussi !** 🚀✨

---

*Guide de déploiement version 1.0 - Dernière mise à jour : 3 Février 2026*
