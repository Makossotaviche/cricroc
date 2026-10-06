<?php

/**
 * Script d'annotation des fichiers du projet Cricroc.
 *
 * Ce script ajoute un commentaire d'entête dans tous les fichiers
 * PHP et Blade view qui n'en ont pas déjà, pour expliquer leur rôle.
 *
 * Exécution : php comment_project_files.php
 */

$root = realpath(__DIR__);
$exclude = ['vendor', 'storage', 'bootstrap/cache'];
$includeDirs = ['app/Http/Controllers', 'app/Models', 'database/seeders', 'resources/views'];
$directory = new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directory);
$changed = [];

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $path = $file->getPathname();
    $relative = str_replace('\\', '/', substr($path, strlen($root) + 1));
    $parts = explode(DIRECTORY_SEPARATOR, substr($path, strlen($root) + 1));

    if (count(array_intersect($exclude, $parts)) > 0) {
        continue;
    }

    $inIncludeDir = false;
    foreach ($includeDirs as $dir) {
        if (str_starts_with($relative, $dir)) {
            $inIncludeDir = true;
            break;
        }
    }

    if (!$inIncludeDir) {
        continue;
    }

    if (str_ends_with($path, '.blade.php')) {
        $content = file_get_contents($path);
        if (trim($content) === '' || str_starts_with(ltrim($content), '{{--')) {
            continue;
        }

        $comment = "{{--\n    Vue : $relative\n    Cette vue affiche les données de l'application Cricroc.\n--}}\n\n";
        file_put_contents($path, $comment . $content);
        $changed[] = $relative;
        continue;
    }

    if (str_ends_with($path, '.php')) {
        $content = file_get_contents($path);
        if (preg_match('/^<\?php\s*(\/\*\*|\/\/|#)/', $content)) {
            continue;
        }

        if (preg_match('/^(<\?php\s*)/', $content, $matches)) {
            $comment = "/**\n * Fichier : $relative\n * Description : composant du projet Cricroc.\n */\n\n";
            $newContent = preg_replace('/^(<\?php\s*)/', '$1' . $comment, $content, 1);

            if ($newContent !== null && $newContent !== $content) {
                file_put_contents($path, $newContent);
                $changed[] = $relative;
            }
        }
    }
}

echo 'Fichiers modifiés : ' . count($changed) . "\n";
foreach ($changed as $file) {
    echo "- $file\n";
}
