<?php $pageTitle = 'Configuración de Pagos'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Configuración de métodos de pago</p>
    </div>
</div>

<!-- Tabs de navegación -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto">
            <a href="<?= BASE_URL ?>/admin/settings" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">General</a>
            <a href="<?= BASE_URL ?>/admin/settings/appearance" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Apariencia</a>
            <a href="<?= BASE_URL ?>/admin/settings/mail" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Correo</a>
            <a href="<?= BASE_URL ?>/admin/settings/payment" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">Pagos</a>
            <a href="<?= BASE_URL ?>/admin/settings/opentable" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">OpenTable</a>
            <a href="<?= BASE_URL ?>/admin/settings/policies" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Políticas del Sitio</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Usuarios</a>
        </nav>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings/payment" class="p-6 space-y-6">
        <!-- PayPal -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4">Configuración de PayPal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Client ID</label>
                    <input type="text" name="paypal_client_id" value="<?= htmlspecialchars($settings['paypal_client_id'] ?? '') ?>"
                           placeholder="Tu Client ID de PayPal"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Secret</label>
                    <input type="password" name="paypal_secret" value="<?= htmlspecialchars($settings['paypal_secret'] ?? '') ?>"
                           placeholder="Tu Secret de PayPal"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modo</label>
                    <select name="paypal_mode"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="sandbox" <?= ($settings['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' ?>>Sandbox (Pruebas)</option>
                        <option value="live" <?= ($settings['paypal_mode'] ?? 'sandbox') === 'live' ? 'selected' : '' ?>>Live (Producción)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Moneda -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Configuración General de Pagos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Moneda</label>
                    <select name="payment_currency"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="MXN" <?= ($settings['payment_currency'] ?? 'MXN') === 'MXN' ? 'selected' : '' ?>>MXN - Peso Mexicano</option>
                        <option value="USD" <?= ($settings['payment_currency'] ?? 'MXN') === 'USD' ? 'selected' : '' ?>>USD - Dólar Estadounidense</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Depósito requerido (%)</label>
                    <input type="number" name="deposit_percentage" value="<?= htmlspecialchars($settings['deposit_percentage'] ?? '0') ?>"
                           min="0" max="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <p class="text-xs text-gray-500 mt-1">Porcentaje del total a pagar como depósito. 0 para desactivar.</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
