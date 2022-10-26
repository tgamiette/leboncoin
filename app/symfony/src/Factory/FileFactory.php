<?php

namespace App\Factory;

use App\Entity\File;
use App\Repository\FileRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<File>
 *
 * @method static File|Proxy createOne(array $attributes = [])
 * @method static File[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static File[]|Proxy[] createSequence(array|callable $sequence)
 * @method static File|Proxy find(object|array|mixed $criteria)
 * @method static File|Proxy findOrCreate(array $attributes)
 * @method static File|Proxy first(string $sortedField = 'id')
 * @method static File|Proxy last(string $sortedField = 'id')
 * @method static File|Proxy random(array $attributes = [])
 * @method static File|Proxy randomOrCreate(array $attributes = [])
 * @method static File[]|Proxy[] all()
 * @method static File[]|Proxy[] findBy(array $attributes)
 * @method static File[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static File[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FileRepository|RepositoryProxy repository()
 * @method File|Proxy create(array|callable $attributes = [])
 */
final class FileFactory extends ModelFactory {
    public function __construct() {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'fileName' => self::faker()->uuid(),
            'offer'=> OfferFactory::random(),
            'path' => self::faker()->imageUrl(1600, 900, 'product'),
        ];
    }

    protected function initialize(): self {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this->afterInstantiate(function (File $file): void {
        });
    }

    protected static function getClass(): string {
        return File::class;
    }
}
