<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6c90809f9be3a342282b2d270117139d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PAMI\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PAMI\\' => 
        array (
            0 => __DIR__ . '/..' . '/marcelog/pami/src/PAMI',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6c90809f9be3a342282b2d270117139d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6c90809f9be3a342282b2d270117139d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6c90809f9be3a342282b2d270117139d::$classMap;

        }, null, ClassLoader::class);
    }
}