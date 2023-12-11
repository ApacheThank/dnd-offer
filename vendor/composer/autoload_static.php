<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3227e59004c48f9ee2829b27d331cfb8
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Khasanov\\DndOffer\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Khasanov\\DndOffer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3227e59004c48f9ee2829b27d331cfb8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3227e59004c48f9ee2829b27d331cfb8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3227e59004c48f9ee2829b27d331cfb8::$classMap;

        }, null, ClassLoader::class);
    }
}
