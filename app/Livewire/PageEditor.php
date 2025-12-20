<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PageContent;
use Illuminate\Support\Facades\File;

class PageEditor extends Component
{
    use WithFileUploads;

    public $activeTab = 'hero';
    
    // Hero Section
    public $hero_title;
    public $hero_subtitle;
    public $hero_button_text;
    public $hero_image_url;
    public $hero_image;
    
    // Products Section
    public $products_description;
    
    // About Section
    public $about_description;
    public $about_image_url;
    public $about_image;
    
    // Features
    public $feature_1_title;
    public $feature_1_desc;
    public $feature_2_title;
    public $feature_2_desc;
    public $feature_3_title;
    public $feature_3_desc;
    
    // Contact Section
    public $contact_description;
    
    // Social & Contact Info
    public $facebook_url;
    public $instagram_url;
    public $twitter_url;
    public $phone;
    public $email;
    public $address;
    
    public $successMessage = '';
    public $errorMessage = '';

    public function mount()
    {
        $this->loadContent();
    }

    public function loadContent()
    {
        $content = PageContent::first();
        
        if (!$content) {
            $content = PageContent::create([
                'hero_title' => 'Bienvenido a MajoseSport',
                'hero_subtitle' => 'Los mejores artículos deportivos para tu entrenamiento',
                'hero_button_text' => 'Explorar Tienda',
                'products_description' => 'Encuentra una amplia variedad de productos deportivos de calidad.',
                'about_description' => 'En MajoseSport, nos dedicamos a proporcionar los mejores artículos deportivos para atletas de todos los niveles.',
                'feature_1_title' => '✅ Calidad Premium',
                'feature_1_desc' => 'Productos de las mejores marcas del mundo',
                'feature_2_title' => '🚚 Envío Rápido',
                'feature_2_desc' => 'Entrega en 24-48 horas en todo el país',
                'feature_3_title' => '💯 Garantía',
                'feature_3_desc' => '100% garantía de satisfacción del cliente',
                'contact_description' => '¿Preguntas o comentarios? Estamos aquí para ayudarte',
            ]);
        }
        
        $this->hero_title = $content->hero_title ?? '';
        $this->hero_subtitle = $content->hero_subtitle ?? '';
        $this->hero_button_text = $content->hero_button_text ?? '';
        $this->hero_image_url = $content->hero_image_url ?? '';
        
        $this->products_description = $content->products_description ?? '';
        
        $this->about_description = $content->about_description ?? '';
        $this->about_image_url = $content->about_image_url ?? '';

        $this->feature_1_title = $content->feature_1_title ?? '';
        $this->feature_1_desc = $content->feature_1_desc ?? '';
        $this->feature_2_title = $content->feature_2_title ?? '';
        $this->feature_2_desc = $content->feature_2_desc ?? '';
        $this->feature_3_title = $content->feature_3_title ?? '';
        $this->feature_3_desc = $content->feature_3_desc ?? '';
        
        $this->contact_description = $content->contact_description ?? '';
        
        $this->facebook_url = $content->facebook_url ?? '';
        $this->instagram_url = $content->instagram_url ?? '';
        $this->twitter_url = $content->twitter_url ?? '';
        $this->phone = $content->phone ?? '';
        $this->email = $content->email ?? '';
        $this->address = $content->address ?? '';
    }

    public function saveHero()
    {
        $content = PageContent::firstOrCreate([]);
        
        // Guardar imagen si se cargó una nueva
        if ($this->hero_image) {
            $path = $this->hero_image->store('page-images', 'public');
            $this->hero_image_url = '/storage/' . $path;
        }
        
        $content->update([
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_button_text' => $this->hero_button_text,
            'hero_image_url' => $this->hero_image_url,
        ]);
        
        $this->generateHTML();
        $this->successMessage = '✅ Sección Hero guardada exitosamente';
        $this->resetErrorMessage();
    }

    public function saveProducts()
    {
        $content = PageContent::firstOrCreate([]);
        
        $content->update([
            'products_description' => $this->products_description,
        ]);
        
        $this->generateHTML();
        $this->successMessage = '✅ Sección Productos guardada exitosamente';
        $this->resetErrorMessage();
    }

    public function saveAbout()
    {
        $content = PageContent::firstOrCreate([]);
        
        // Guardar imagen si se cargó una nueva
        if ($this->about_image) {
            $path = $this->about_image->store('page-images', 'public');
            $this->about_image_url = '/storage/' . $path;
        }
        
        $content->update([
            'about_description' => $this->about_description,
            'about_image_url' => $this->about_image_url,
            'feature_1_title' => $this->feature_1_title,
            'feature_1_desc' => $this->feature_1_desc,
            'feature_2_title' => $this->feature_2_title,
            'feature_2_desc' => $this->feature_2_desc,
            'feature_3_title' => $this->feature_3_title,
            'feature_3_desc' => $this->feature_3_desc,
        ]);
        
        $this->generateHTML();
        $this->successMessage = '✅ Sección Nosotros guardada exitosamente';
        $this->resetErrorMessage();
    }

    public function saveContact()
    {
        $content = PageContent::firstOrCreate([]);
        
        $content->update([
            'contact_description' => $this->contact_description,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);
        
        $this->generateHTML();
        $this->successMessage = '✅ Sección Contacto guardada exitosamente';
        $this->resetErrorMessage();
    }

    public function generateHTML()
    {
        // Obtener contenido actualizado
        $content = PageContent::first();
        
        // Generar HTML dinámico
        $html = $this->buildHTML($content);
        
        // Guardar en el archivo
        $filePath = 'e:\\PPI\\PaginamajoseSport\\index.html';
        File::put($filePath, $html);
    }

    private function buildHTML($content)
    {
        $phoneHtml = $content->phone ? "<p>📞 {$content->phone}</p>" : '';
        $emailHtml = $content->email ? "<p>📧 {$content->email}</p>" : '';
        $addressHtml = $content->address ? "<p>📍 {$content->address}</p>" : '';
        $facebookHtml = $content->facebook_url ? '<a href="' . $content->facebook_url . '">Facebook</a>' : '';
        $instagramHtml = $content->instagram_url ? '<a href="' . $content->instagram_url . '">Instagram</a>' : '';
        $twitterHtml = $content->twitter_url ? '<a href="' . $content->twitter_url . '">Twitter</a>' : '';
        
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MajoseSport - Tu Tienda de Artículos Deportivos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <h1>🏃 MajoseSport</h1>
            </div>
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#productos">Productos</a></li>
                <li><a href="#nosotros">Nosotros</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <!-- Sección Hero -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h2>{$content->hero_title}</h2>
            <p>{$content->hero_subtitle}</p>
            <button class="btn btn-primary">{$content->hero_button_text}</button>
        </div>
    </section>

    <!-- Sección de Productos Destacados -->
    <section id="productos" class="products">
        <div class="container">
            <h2>Productos Destacados</h2>
            {$content->products_description}
            <div class="product-grid">
                <div class="product-card">
                    <div class="product-image">🏋️</div>
                    <h3>Mancuernas</h3>
                    <p>Conjunto completo de mancuernas ajustables</p>
                    <p class="price">\$99.99</p>
                </div>
                <div class="product-card">
                    <div class="product-image">👟</div>
                    <h3>Zapatillas Deportivas</h3>
                    <p>Zapatillas cómodas y durables</p>
                    <p class="price">\$79.99</p>
                </div>
                <div class="product-card">
                    <div class="product-image">⚽</div>
                    <h3>Balones Deportivos</h3>
                    <p>Balones de alta calidad para cualquier deporte</p>
                    <p class="price">\$49.99</p>
                </div>
                <div class="product-card">
                    <div class="product-image">🎾</div>
                    <h3>Raquetas</h3>
                    <p>Raquetas profesionales para tenis y badminton</p>
                    <p class="price">\$89.99</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Nosotros -->
    <section id="nosotros" class="about">
        <div class="container">
            <h2>Sobre Nosotros</h2>
            <p>
                {$content->about_description}
            </p>
            <div class="features">
                <div class="feature">
                    <h3>{$content->feature_1_title}</h3>
                    <p>{$content->feature_1_desc}</p>
                </div>
                <div class="feature">
                    <h3>{$content->feature_2_title}</h3>
                    <p>{$content->feature_2_desc}</p>
                </div>
                <div class="feature">
                    <h3>{$content->feature_3_title}</h3>
                    <p>{$content->feature_3_desc}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Contacto -->
    <section id="contacto" class="contact">
        <div class="container">
            <h2>Contáctanos</h2>
            <p>{$content->contact_description}</p>
            <form id="contactForm" class="contact-form">
                <input type="text" placeholder="Tu Nombre" required>
                <input type="email" placeholder="Tu Email" required>
                <textarea placeholder="Tu Mensaje" rows="5" required></textarea>
                <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 MajoseSport. Todos los derechos reservados.</p>
            <div class="contact-info">
                $phoneHtml
                $emailHtml
                $addressHtml
            </div>
            <div class="social-links">
                $facebookHtml
                $instagramHtml
                $twitterHtml
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
HTML;
    }

    public function resetErrorMessage()
    {
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.page-editor');
    }
}
