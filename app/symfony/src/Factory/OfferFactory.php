<?php

namespace App\Factory;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use App\Service\Slugify;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Offer>
 *
 * @method static Offer|Proxy createOne(array $attributes = [])
 * @method static Offer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Offer[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Offer|Proxy find(object|array|mixed $criteria)
 * @method static Offer|Proxy findOrCreate(array $attributes)
 * @method static Offer|Proxy first(string $sortedField = 'id')
 * @method static Offer|Proxy last(string $sortedField = 'id')
 * @method static Offer|Proxy random(array $attributes = [])
 * @method static Offer|Proxy randomOrCreate(array $attributes = [])
 * @method static Offer[]|Proxy[] all()
 * @method static Offer[]|Proxy[] findBy(array $attributes)
 * @method static Offer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Offer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OfferRepository|RepositoryProxy repository()
 * @method Offer|Proxy create(array|callable $attributes = [])
 */
final class OfferFactory extends ModelFactory {
    public function __construct(private Slugify $slugger) {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array {

        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(15),
            'price' => self::faker()->randomFloat(2,5),
            'description' => self::faker()->realTextBetween(200, 400),
            'status' => self::faker()->boolean(),
            'user' => UserFactory::random()
        ];
    }

    protected function initialize(): self {
        return $this
            ->afterInstantiate(function (Offer $offer): void {
                $offer->setSlug($this->slugger->slugify($offer->getTitle()));
            });

    }

    protected static function getClass(): string {
        return Offer::class;
    }
}
