<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdccb5eb67dc750aff8a815310c511bbf
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rsu\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rsu\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Rsu',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdccb5eb67dc750aff8a815310c511bbf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdccb5eb67dc750aff8a815310c511bbf::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
