<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2a1981dbcbcf22f0effc5c2757d2d283
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Src\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Src\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit2a1981dbcbcf22f0effc5c2757d2d283::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2a1981dbcbcf22f0effc5c2757d2d283::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2a1981dbcbcf22f0effc5c2757d2d283::$classMap;

        }, null, ClassLoader::class);
    }
}
