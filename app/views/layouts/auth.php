<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Sistema de Reservaciones</title>
    
    <?php
    // Load settings for dynamic styling
    $settingModel = new SettingModel();
    $siteSettings = $settingModel->getAllAsArray();
    $primaryColor = $siteSettings['primary_color'] ?? '#2563eb';
    $secondaryColor = $siteSettings['secondary_color'] ?? '#1e40af';
    $accentColor = $siteSettings['accent_color'] ?? '#3b82f6';
    $siteLogo = $siteSettings['site_logo'] ?? '';
    ?>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= htmlspecialchars($primaryColor) ?>',
                        secondary: '<?= htmlspecialchars($secondaryColor) ?>',
                        accent: '<?= htmlspecialchars($accentColor) ?>'
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .btn-primary { background-color: <?= htmlspecialchars($primaryColor) ?>; }
        .btn-primary:hover { background-color: <?= htmlspecialchars($secondaryColor) ?>; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <div class="w-full max-w-md">
        <?= $content ?>
    </div>
    
    <!-- Footer -->
    <footer class="mt-8 text-center text-sm text-gray-500">
        Solución desarrollada por <a href="https://www.impactosdigitales.com" target="_blank" rel="noopener noreferrer" class="text-primary hover:text-secondary font-medium">ID</a>
    </footer>
</body>
</html>
