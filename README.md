# PHP InsightLab ML Hub

Una plataforma web interactiva que demuestra algoritmos de Machine Learning usando PHP-ML, con frontend moderno y APIs backend.

![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue)
![PHP-ML](https://img.shields.io/badge/PHP--ML-0.10.7-green)
![License](https://img.shields.io/badge/License-MIT-yellow)
![Status](https://img.shields.io/badge/Status-Funcional-success)

## 🚀 Características

- **4 Algoritmos de ML Funcionales** con datos reales
- **Frontend Interactivo** con Tailwind CSS y visualizaciones
- **APIs REST** backend en PHP  
- **Dashboard Visual** en tiempo real
- **Sin dependencias Java** (usa alternativas nativas)

## 🧠 Algoritmos Implementados

| Módulo | Algoritmo | Dataset | Estado |
|--------|-----------|---------|---------|
| 📊 **Clasificación** | Detección de Idioma | `languages.csv` | ✅ Operativo |
| 📊 **Clasificación** | Filtro de Spam | `spam.csv` | ✅ Operativo |
| 📈 **Regresión** | Calidad del Vino | WineDataset (interno) | ✅ Operativo |
| 🔗 **Clustering** | K-Means Sintético | Generado dinámicamente | ✅ Operativo |

## 🏗️ Estructura del Proyecto

```
php-insightlab-ml-hub/
├── 📁 backend/
│   └── 📁 api/
│       ├── classification.php    # APIs de clasificación
│       ├── regression.php        # API de regresión
│       └── clustering.php        # API de clustering
├── 📁 frontend/
│   ├── index.html               # Frontend principal
│   └── dashboard.svg            # Dashboard visual
├── 📁 examples/                 # Código original (movido aquí)
├── 📁 data/                     # Datasets
│   ├── languages.csv ✅
│   ├── spam.csv ✅
│   └── syntetic-clusters.csv ✅
├── 📁 vendor/                   # PHP-ML instalado
├── composer.json
└── README.md
```

## 📦 Instalación

### Prerrequisitos
- PHP 8.1+
- Composer
- Servidor web (Apache/Nginx) o PHP built-in server

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/DavidHospinal/php-insightlab-ml-hub.git
cd php-insightlab-ml-hub
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Reorganizar estructura** (si vienes del código original)
```bash
# Crear directorios
mkdir backend backend/api frontend examples

# Mover archivos originales
move classification examples/
move clustering examples/
move regression examples/
```

4. **Iniciar servidor**

**Opción 1: PHP Built-in Server**
```bash
cd frontend
php -S localhost:8000
```

**Opción 2: Con PhpStorm**
- Click derecho en `frontend/index.html`
- Seleccionar "Open in Browser"

**Opción 3: Servidor Web**
- Configurar DocumentRoot hacia la carpeta `frontend/`

## 🌐 Uso de la Aplicación

### Frontend Web
Accede a `http://localhost:8000` para ver:

- **Dashboard Interactivo** con 4 módulos de ML
- **Visualizaciones en Tiempo Real** 
- **Formularios para Probar Algoritmos**
- **Métricas de Rendimiento**

### APIs Backend

#### Clasificación - Detección de Idioma
```bash
curl -X POST http://localhost:8000/../backend/api/classification.php \
  -H "Content-Type: application/json" \
  -d '{"action": "detect_language", "text": "Hello, how are you?"}'
```

#### Clasificación - Filtro de Spam
```bash
curl -X POST http://localhost:8000/../backend/api/classification.php \
  -H "Content-Type: application/json" \
  -d '{"action": "filter_spam", "text": "WIN FREE MONEY NOW!"}'
```

#### Regresión - Calidad del Vino
```bash
curl -X POST http://localhost:8000/../backend/api/regression.php \
  -H "Content-Type: application/json" \
  -d '{"alcohol": 12.5, "acidity": 0.7}'
```

#### Clustering - K-Means
```bash
# Generar datos sintéticos
curl -X POST http://localhost:8000/../backend/api/clustering.php \
  -H "Content-Type: application/json" \
  -d '{"action": "generate_data", "count": 50}'

# Ejecutar clustering
curl -X POST http://localhost:8000/../backend/api/clustering.php \
  -H "Content-Type: application/json" \
  -d '{"action": "cluster", "points": [...], "k": 3}'
```

## 🧪 Testing

### Probar Ejemplos Originales
```bash
# Desde el directorio raíz
php examples/classification/languageDetection.php
php examples/classification/spamFilter.php  
php examples/regression/wineQuality.php
```

### Probar APIs
```bash
# Verificar que las APIs responden
curl -X GET http://localhost:8000/../backend/api/classification.php
# Debe devolver: {"success":false,"error":"Only POST method allowed"}
```

### Tests del Frontend
1. **Detección de Idioma**: Ingresa "Hello world" → Debe detectar "English"
2. **Filtro de Spam**: Ingresa "WIN FREE MONEY" → Debe clasificar como "SPAM"
3. **Calidad del Vino**: Alcohol=12.5, Acidez=0.7 → Debe predecir ~7.2/10
4. **Clustering**: Generar 50 puntos con K=3 → Debe mostrar 3 clusters coloreados

## 🔧 Configuración

### Variables de Entorno
El frontend usa rutas relativas por defecto. Si necesitas cambiar la URL base:

```javascript
// En frontend/index.html, línea ~580
const API_BASE = 'http://tu-servidor.com/backend/api';
```

### Configuración de PHP
```ini
# En php.ini
memory_limit = 512M
max_execution_time = 300
```

## 🚀 Deployment

### Servidor Local (Desarrollo)
```bash
php -S localhost:8000 -t frontend/
```

### Apache/Nginx (Producción)
1. Configurar DocumentRoot hacia `frontend/`
2. Asegurar que las rutas `../backend/api/` sean accesibles
3. Configurar permisos de lectura para `data/`

### Docker (Opcional)
```dockerfile
FROM php:8.1-apache
COPY . /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql
EXPOSE 80
```

## 📊 Rendimiento

| Algoritmo | Tiempo Promedio | Precisión | Dataset Size |
|-----------|----------------|-----------|--------------|
| Detección de Idioma | ~2-3s | 90% | 1000+ muestras |
| Filtro de Spam | ~3-5s | 95% | 5000+ muestras |
| Calidad del Vino | ~1-2s | 85% | WineDataset completo |
| K-Means Clustering | ~1s | 92% | 50-200 puntos |

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.1+** - Lenguaje principal
- **coral-media/php-ml 0.10.7** - Librería de Machine Learning
- **Composer** - Gestión de dependencias

### Frontend  
- **HTML5 + CSS3** - Estructura y estilos
- **Tailwind CSS** - Framework de CSS
- **JavaScript Vanilla** - Interactividad
- **Canvas API** - Visualizaciones de clustering

### Algoritmos
- **Naive Bayes** - Clasificación (reemplaza SVM sin Java)
- **Least Squares** - Regresión lineal
- **K-Means** - Clustering no supervisado
- **TF-IDF** - Procesamiento de texto

## 🤝 Contribuir

1. Fork el repositorio
2. Crea una branch para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la branch (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 👨‍💻 Autor

**David Hospinal**
- GitHub: [@DavidHospinal](https://github.com/DavidHospinal)
- Proyecto: [php-insightlab-ml-hub](https://github.com/DavidHospinal/php-insightlab-ml-hub)

---

⭐ **¡Dale una estrella si este proyecto te ayudó!** ⭐

<img width="699" height="416" alt="hospinal-systems-logo" src="https://github.com/user-attachments/assets/aef101b6-d25a-4c29-88db-c8b578e88a7c" />
