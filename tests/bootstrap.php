<?php

declare(strict_types=1);

/**
 * PHPUnit bootstrap — single Composer autoload only.
 *
 * Inside the symfinity monorepo, never combine /app/vendor with
 * packages/form-ui-extensions-bundle/vendor — that fatals on Psr\Container redeclare.
 */

$packageRoot = dirname(__DIR__);
$monorepoRoot = dirname($packageRoot, 2);
$packageAutoload = $packageRoot.'/vendor/autoload.php';
$monorepoAutoload = $monorepoRoot.'/vendor/autoload.php';
$isMonorepo = is_file($monorepoRoot.'/mono.json') && is_file($monorepoAutoload);
$packageVendorDir = realpath($packageRoot.'/vendor') ?: $packageRoot.'/vendor';

$packageAutoloadActive = false;
foreach (spl_autoload_functions() ?: [] as $loader) {
    if (!\is_array($loader)) {
        continue;
    }

    if (!\array_key_exists(0, $loader)) {
        continue;
    }

    $classLoader = $loader[0];
    if (!$classLoader instanceof \Composer\Autoload\ClassLoader) {
        continue;
    }

    $ref = new \ReflectionClass($classLoader);
    $fileName = $ref->getFileName();
    if ($fileName === false) {
        continue;
    }

    $vendorDir = realpath(dirname($fileName, 2)) ?: '';

    if ($vendorDir === $packageVendorDir) {
        $packageAutoloadActive = true;
        break;
    }
}

if ($isMonorepo && is_file($packageAutoload) && $packageAutoloadActive) {
    fwrite(STDERR, <<<'TXT'
Unsafe test bootstrap: symfinity monorepo vendor AND packages/form-ui-extensions-bundle/vendor are both present,
and vendor/bin/phpunit already loaded the package autoload. PHPUnit will fatal on Psr\Container.

Use instead:
  docker compose --env-file .env.docker run --rm -T -w /app php vendor/bin/phpunit packages/form-ui-extensions-bundle/tests/
  docker compose --env-file .env.docker run --rm -T -w /app/packages/form-ui-extensions-bundle php vendor/bin/phpunit
  rm -rf packages/form-ui-extensions-bundle/vendor

TXT);

    exit(1);
}

if (!class_exists(\Composer\Autoload\ClassLoader::class, false)) {
    if ($isMonorepo) {
        require $monorepoAutoload;
    } elseif (is_file($packageAutoload)) {
        require $packageAutoload;
    } else {
        fwrite(STDERR, "Composer autoload not found — run composer install in the package or monorepo.\n");
        exit(1);
    }
}
