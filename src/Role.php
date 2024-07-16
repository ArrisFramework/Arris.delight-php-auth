<?php

namespace Arris\DelightAuth\Auth;

final class Role
{

    public const ADMIN = 1;
    public const AUTHOR = 2;
    public const COLLABORATOR = 4;
    public const CONSULTANT = 8;
    public const CONSUMER = 16;
    public const CONTRIBUTOR = 32;
    public const COORDINATOR = 64;
    public const CREATOR = 128;
    public const DEVELOPER = 256;
    public const DIRECTOR = 512;
    public const EDITOR = 1024;
    public const EMPLOYEE = 2048;
    public const MAINTAINER = 4096;
    public const MANAGER = 8192;
    public const MODERATOR = 16384;
    public const PUBLISHER = 32768;
    public const REVIEWER = 65536;
    public const SUBSCRIBER = 131072;
    public const SUPER_ADMIN = 262144;
    public const SUPER_EDITOR = 524288;
    public const SUPER_MODERATOR = 1048576;
    public const TRANSLATOR = 2097152;
    // const XYZ = 4194304;
    // const XYZ = 8388608;
    // const XYZ = 16777216;
    // const XYZ = 33554432;
    // const XYZ = 67108864;
    // const XYZ = 134217728;
    // const XYZ = 268435456;
    // const XYZ = 536870912;

    private function __construct()
    {
    }

    /**
     * Returns an array mapping the numerical role values to their descriptive names
     *
     * @return array
     */
    public static function getMap(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return \array_flip($reflectionClass->getConstants());
    }

    /**
     * Returns the descriptive role names
     *
     * @return string[]
     */
    public static function getNames(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return \array_keys($reflectionClass->getConstants());
    }

    /**
     * Returns the numerical role values
     *
     * @return int[]
     */
    public static function getValues(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return \array_values($reflectionClass->getConstants());
    }

}
