<?php

if (! function_exists('theme_component')) {
    /**
     * Build the full Blade view path for a theme component.
     *
     * Usage: theme_component($theme, 'nav.solid')
     *        → 'themes.saas.apex-saas.components.nav.solid'
     */
    function theme_component(\App\Models\Theme $theme, string $path): string
    {
        return $theme->view_path . '.components.' . $path;
    }
}
