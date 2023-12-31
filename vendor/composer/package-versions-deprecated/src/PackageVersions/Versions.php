<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'laravel/laravel';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'brick/math' => '0.9.3@ca57d18f028f84f777b2168cd1911b0dee2343ae',
  'coingate/coingate-php' => '3.0.5@c9e7f2c291cf8d5118c73028280aaa25a53c2302',
  'composer/ca-bundle' => '1.3.3@30897edbfb15e784fe55587b4f73ceefd3c4d98c',
  'composer/package-versions-deprecated' => '1.11.99.5@b4f54f74ef3453349c24a845d22392cd31e65f1d',
  'dflydev/dot-access-data' => 'v3.0.1@0992cc19268b259a39e86f296da5f0677841f42c',
  'doctrine/inflector' => '2.0.4@8b7ff3e4b7de6b2c84da85637b59fd2880ecaa89',
  'doctrine/lexer' => '1.2.3@c268e882d4dbdd85e36e4ad69e02dc284f89d229',
  'dragonmantank/cron-expression' => 'v3.3.1@be85b3f05b46c39bbc0d95f6c071ddff669510fa',
  'egulias/email-validator' => '3.2.1@f88dcf4b14af14a98ad96b14b2b317969eab6715',
  'ezyang/htmlpurifier' => 'v4.14.0@12ab42bd6e742c70c0a52f7b82477fcd44e64b75',
  'fruitcake/php-cors' => 'v1.2.0@58571acbaa5f9f462c9c77e911700ac66f446d4e',
  'graham-campbell/result-type' => 'v1.1.0@a878d45c1914464426dc94da61c9e1d36ae262a8',
  'guzzlehttp/guzzle' => '7.4.5@1dd98b0564cb3f6bd16ce683cb755f94c10fbd82',
  'guzzlehttp/promises' => '1.5.1@fe752aedc9fd8fcca3fe7ad05d419d32998a06da',
  'guzzlehttp/psr7' => '2.4.0@13388f00956b1503577598873fffb5ae994b5737',
  'intervention/image' => '2.7.2@04be355f8d6734c826045d02a1079ad658322dad',
  'laminas/laminas-diactoros' => '2.14.0@6cb35f61913f06b2c91075db00f67cfd78869e28',
  'laramin/utility' => 'dev-main@92262547885587e874279a07309b73cfc11ee15b',
  'laravel/framework' => 'v9.23.0@c4eea9060d847b5c93957b203caa8f57544a76ab',
  'laravel/sanctum' => 'v2.15.1@31fbe6f85aee080c4dc2f9b03dc6dd5d0ee72473',
  'laravel/serializable-closure' => 'v1.2.0@09f0e9fb61829f628205b7c94906c28740ff9540',
  'laravel/tinker' => 'v2.7.2@dff39b661e827dae6e092412f976658df82dbac5',
  'laravel/ui' => 'v3.4.6@65ec5c03f7fee2c8ecae785795b829a15be48c2c',
  'lcobucci/clock' => '2.2.0@fb533e093fd61321bfcbac08b131ce805fe183d3',
  'lcobucci/jwt' => '4.0.4@55564265fddf810504110bd68ca311932324b0e9',
  'league/commonmark' => '2.3.5@84d74485fdb7074f4f9dd6f02ab957b1de513257',
  'league/config' => 'v1.1.1@a9d39eeeb6cc49d10a6e6c36f22c4c1f4a767f3e',
  'league/flysystem' => '3.2.0@ed0ecc7f9b5c2f4a9872185846974a808a3b052a',
  'league/mime-type-detection' => '1.11.0@ff6248ea87a9f116e78edd6002e39e5128a0d4dd',
  'mailjet/mailjet-apiv3-php' => 'v1.5.7@414650b079dcec86ada225599e73092177e2d8e5',
  'messagebird/php-rest-api' => 'v1.20.0@f7c7ae490b0b2d9d228bac61b5d855c4623f7fad',
  'mollie/laravel-mollie' => 'v2.19.1@605d1f6038bf112ed88aecdbb10b1fbd8f49ee7e',
  'mollie/mollie-api-php' => 'v2.45.0@43ae5471967a47b34752b6b3a229038a05034527',
  'monolog/monolog' => '2.8.0@720488632c590286b88b80e62aa3d3d551ad4a50',
  'nesbot/carbon' => '2.60.0@00a259ae02b003c563158b54fb6743252b638ea6',
  'nette/schema' => 'v1.2.2@9a39cef03a5b34c7de64f551538cbba05c2be5df',
  'nette/utils' => 'v3.2.7@0af4e3de4df9f1543534beab255ccf459e7a2c99',
  'nikic/php-parser' => 'v4.14.0@34bea19b6e03d8153165d8f30bba4c3be86184c1',
  'nunomaduro/termwind' => 'v1.14.0@10065367baccf13b6e30f5e9246fa4f63a79eb1d',
  'phpmailer/phpmailer' => 'v6.6.3@9400f305a898f194caff5521f64e5dfa926626f3',
  'phpoption/phpoption' => '1.9.0@dc5ff11e274a90cc1c743f66c9ad700ce50db9ab',
  'psr/container' => '1.1.2@513e0666f7216c7459170d56df27dfcefe1689ea',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/http-client' => '1.0.1@2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
  'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11',
  'psr/simple-cache' => '3.0.0@764e0b3939f5ca87cb904f570ef9be2d78a07865',
  'psy/psysh' => 'v0.11.8@f455acf3645262ae389b10e9beba0c358aa6994e',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'ramsey/collection' => '1.2.2@cccc74ee5e328031b15640b51056ee8d3bb66c0a',
  'ramsey/uuid' => '4.3.1@8505afd4fea63b81a85d3b7b53ac3cb8dc347c28',
  'razorpay/razorpay' => 'v2.8.4@3f2edc150f6ca035d15ab81382f7f76417de91f6',
  'rmccue/requests' => 'v2.0.4@62bf29e0f1080b4f0f499d30adb6a382e70e9686',
  'sendgrid/php-http-client' => '3.14.4@6d589564522be290c7d7c18e51bcd8b03aeaf0b6',
  'sendgrid/sendgrid' => '7.11.5@1d2fd3b72687fe82264853a8888b014f8f99e81f',
  'starkbank/ecdsa' => '0.0.5@484bedac47bac4012dc73df91da221f0a66845cb',
  'stella-maris/clock' => '0.1.4@8a0a967896df4c63417385dc69328a0aec84d9cf',
  'stripe/stripe-php' => 'v7.128.0@c704949c49b72985c76cc61063aa26fefbd2724e',
  'symfony/console' => 'v6.1.3@43fcb5c5966b43c56bcfa481368d90d748936ab8',
  'symfony/css-selector' => 'v6.1.3@0dd5e36b80e1de97f8f74ed7023ac2b837a36443',
  'symfony/deprecation-contracts' => 'v3.1.1@07f1b9cc2ffee6aaafcf4b710fbc38ff736bd918',
  'symfony/error-handler' => 'v6.1.3@736e42db3fd586d91820355988698e434e1d8419',
  'symfony/event-dispatcher' => 'v6.1.0@a0449a7ad7daa0f7c0acd508259f80544ab5a347',
  'symfony/event-dispatcher-contracts' => 'v3.1.1@02ff5eea2f453731cfbc6bc215e456b781480448',
  'symfony/finder' => 'v6.1.3@39696bff2c2970b3779a5cac7bf9f0b88fc2b709',
  'symfony/http-foundation' => 'v6.1.3@b03712c93759a81fc243ecc18ec4637958afebdb',
  'symfony/http-kernel' => 'v6.1.3@0692bc185a1dbb54864686a1fc6785667279da70',
  'symfony/mailer' => 'v6.1.3@b2db228a93278863d1567f90d7caf26922dbfede',
  'symfony/mime' => 'v6.1.3@9c0247994fc6584da8591ba64b2bffaace9df87d',
  'symfony/polyfill-ctype' => 'v1.26.0@6fd1b9a79f6e3cf65f9e679b23af304cd9e010d4',
  'symfony/polyfill-intl-grapheme' => 'v1.26.0@433d05519ce6990bf3530fba6957499d327395c2',
  'symfony/polyfill-intl-idn' => 'v1.26.0@59a8d271f00dd0e4c2e518104cc7963f655a1aa8',
  'symfony/polyfill-intl-normalizer' => 'v1.26.0@219aa369ceff116e673852dce47c3a41794c14bd',
  'symfony/polyfill-mbstring' => 'v1.26.0@9344f9cb97f3b19424af1a21a3b0e75b0a7d8d7e',
  'symfony/polyfill-php72' => 'v1.26.0@bf44a9fd41feaac72b074de600314a93e2ae78e2',
  'symfony/polyfill-php80' => 'v1.26.0@cfa0ae98841b9e461207c13ab093d76b0fa7bace',
  'symfony/polyfill-php81' => 'v1.26.0@13f6d1271c663dc5ae9fb843a8f16521db7687a1',
  'symfony/process' => 'v6.1.3@a6506e99cfad7059b1ab5cab395854a0a0c21292',
  'symfony/routing' => 'v6.1.3@ef9108b3a88045b7546e808fb404ddb073dd35ea',
  'symfony/service-contracts' => 'v2.5.2@4b426aac47d6427cc1a1d0f7e2ac724627f5966c',
  'symfony/string' => 'v6.1.3@f35241f45c30bcd9046af2bb200a7086f70e1d6b',
  'symfony/translation' => 'v6.1.3@b042e16087d298d08c1f013ff505d16c12a3b1be',
  'symfony/translation-contracts' => 'v3.1.1@606be0f48e05116baef052f7f3abdb345c8e02cc',
  'symfony/var-dumper' => 'v6.1.3@d5a5e44a2260c5eb5e746bf4f1fbd12ee6ceb427',
  'textmagic/sdk' => 'dev-master@b81cd04df7ab6fd21f0ddd75bf987cf3f65fe9c6',
  'tijsverkoyen/css-to-inline-styles' => '2.2.4@da444caae6aca7a19c0c140f68c6182e337d5b1c',
  'twilio/sdk' => '6.40.0@86aa13d855f4624d07a5e6e8c0b7f2096d7d856c',
  'vlucas/phpdotenv' => 'v5.4.1@264dce589e7ce37a7ba99cb901eed8249fbec92f',
  'voku/portable-ascii' => '2.0.1@b56450eed252f6801410d810c8e1727224ae0743',
  'vonage/client' => '2.4.0@29f23e317d658ec1c3e55cf778992353492741d7',
  'vonage/client-core' => '2.10.1@0e5c6bf4af22cae60a3f1098b75c25d70bac242f',
  'vonage/nexmo-bridge' => '0.1.1@36490dcc5915f12abeaa233c6098e0dce14bbb0a',
  'webmozart/assert' => '1.11.0@11cb2199493b2f8a3b53e7f19068fc6aac760991',
  'laravel/laravel' => '1.0.0+no-version-set@',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (self::composer2ApiUsable()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }

    private static function composer2ApiUsable(): bool
    {
        if (!class_exists(InstalledVersions::class, false)) {
            return false;
        }

        if (method_exists(InstalledVersions::class, 'getAllRawData')) {
            $rawData = InstalledVersions::getAllRawData();
            if (count($rawData) === 1 && count($rawData[0]) === 0) {
                return false;
            }
        } else {
            $rawData = InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return false;
            }
        }

        return true;
    }
}
