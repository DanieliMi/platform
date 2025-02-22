<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @property Context $context
 *
 * @internal
 */
class IdsCollection
{
    /**
     * @var array<string>
     */
    protected $ids = [];

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * @phpstan-ignore-next-line
     *
     * @deprecated tag:v6.5.0 - Will be removed
     */
    public function __get($name)
    {
        if ($name === 'context') {
            \trigger_deprecation('shopware/core', '', 'IdsCollection->context is deprecated. Use Context::createDefaultContext() instead');

            return Context::createDefaultContext();
        }
    }

    public function create(string $key): string
    {
        if (isset($this->ids[$key])) {
            return $this->ids[$key];
        }

        return $this->ids[$key] = Uuid::randomHex();
    }

    public function get(string $key): string
    {
        return $this->create($key);
    }

    public function getIdArray(array $keys, bool $bytes = false): array
    {
        $list = $this->getList($keys);

        $list = $bytes ? Uuid::fromHexToBytesList($list) : $list;

        $list = \array_map(static function (string $id) {
            return ['id' => $id];
        }, $list);

        return \array_values($list);
    }

    public function getBytes(string $key): string
    {
        return Uuid::fromHexToBytes($this->get($key));
    }

    public function getByteList(array $keys): array
    {
        return Uuid::fromHexToBytesList($this->getList($keys));
    }

    public function getList(array $keys): array
    {
        $ordered = [];
        foreach ($keys as $key) {
            $ordered[$key] = $this->get($key);
        }

        return $ordered;
    }

    public function all(): array
    {
        return $this->ids;
    }

    public function prefixed(string $prefix): array
    {
        $ids = [];
        foreach ($this->ids as $key => $id) {
            if (mb_strpos($key, $prefix) === 0) {
                $ids[$key] = $id;
            }
        }

        return $ids;
    }

    public function set(string $key, string $value): void
    {
        $this->ids[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->ids[$key]);
    }

    public function getKey(string $id): ?string
    {
        foreach ($this->ids as $key => $value) {
            if ($value === $id) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @deprecated tag:v6.5.0 - Will be removed, use Context::createDefaultContext() instead
     */
    public function getContext(): Context
    {
        \trigger_deprecation('shopware/core', '', 'IdsCollection->getContext is deprecated. Use Context::createDefaultContext() instead');

        return Context::createDefaultContext();
    }
}
