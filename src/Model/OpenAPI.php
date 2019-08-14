<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

use Symfony\Component\Yaml\Yaml;
use Synatos\Porta\Exception\InvalidReferenceException;

class OpenAPI extends ArraySerializableModel
{

    /**
     * @var array contains the raw array
     */
    protected $data;

    /**
     * @var string|null
     */
    protected $openapi;

    /**
     * @var Info|null
     */
    protected $info;

    /**
     * @var Server[]
     */
    protected $servers;

    /**
     * @var PathItem[]
     */
    protected $paths;

    /**
     * @var Components|null
     */
    protected $components;

    /**
     * @var SecurityRequirement[]|null
     */
    protected $security;

    /**
     * @var Tag[]|null
     */
    protected $tags;

    /**
     * @var ExternalDocumentation|null
     */
    protected $externalDocs;


    /**
     * OpenAPI constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("openapi"),
            new ModelProperty("info", ModelProperty::TYPE_OBJECT, static function () {
                return new Info();
            }),
            new ModelProperty("servers", ModelProperty::TYPE_ARRAY, static function () {
                return new Server();
            }),
            new ModelProperty("paths", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new PathItem();
            }),
            new ModelProperty("components", ModelProperty::TYPE_OBJECT, static function () {
                return new Components();
            }),
            new ModelProperty("security", ModelProperty::TYPE_ARRAY, static function () {
                return new SecurityRequirement();
            }),
            new ModelProperty("tags", ModelProperty::TYPE_ARRAY, static function () {
                return new Tag();
            }),
            new ModelProperty("externalDocs", ModelProperty::TYPE_ARRAY, static function () {
                return new ExternalDocumentation();
            })
        ]);
    }


    /**
     * @return array
     */
    public function getRouteList(): array
    {
        if ($this->paths === null) {
            return [];
        }
        return array_keys($this->paths);
    }


    /**
     * @param string $path
     *
     * @return PathItem|null
     */
    public function getPathItemByPath(string $path): ?PathItem
    {
        if (!isset($this->paths[$path])) {
            return null;
        }
        return $this->paths[$path];
    }


    /**
     * @param string $path
     * @param PathItem $pathItem
     */
    public function addPathItem(string $path, PathItem $pathItem)
    {
        $this->paths[$path] = $pathItem;
    }


    /**
     * @param Reference $reference
     *
     * @return array
     * @throws InvalidReferenceException
     */
    public function resolveLocalReference(Reference $reference): array
    {
        $data = $this->jsonSerialize();
        $partList = $reference->getLocalPartPartList();
        foreach ($partList as $part) {
            if (!isset($data[$part]) || !is_array($data[$part])) {
                throw new InvalidReferenceException($reference->getReference());
            }
            $data = $data[$part];
        }

        return $data;
    }


    /**
     * @param string $fileName
     */
    public function fromYamlFile(string $fileName)
    {
        $content = file_get_contents($fileName);
        $this->fromYaml($content);
    }


    /**
     * @param string $content
     */
    public function fromYaml(string $content)
    {
        $data = Yaml::parse($content);
        $this->fromArray($data);
    }


    /**
     * @param int $inline
     * @param int $indent
     *
     * @return string
     */
    public function toYaml(int $inline = 200, int $indent = 2): string
    {
        $data = $this->jsonSerialize();
        return Yaml::dump($data, $inline, $indent);
    }


    /**
     * @param string $fileName
     * @param int $inline
     * @param int $indent
     */
    public function toYamlFile(string $fileName, int $inline = 200, int $indent = 2): void
    {
        $content = $this->toYaml($inline, $indent);
        file_put_contents($fileName, $content);
    }


    /**
     * @param string $fileName
     */
    public function fromJSONFile(string $fileName)
    {
        $content = file_get_contents($fileName);
        $this->fromJSON($content);
    }


    /**
     * @param string $content
     */
    public function fromJSON(string $content)
    {
        $array = json_decode($content, true);
        $this->fromArray($array);
    }


    /**
     * @param string $fileName
     */
    public function toJSONFile(string $fileName): void
    {
        $data = $this->jsonSerialize();
        $content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        file_put_contents($fileName, $content);
    }


    /**
     * @param array|null $data
     */
    public function fromArray(array $data = null)
    {
        $this->data = $data;
        parent::fromArray($data);
    }


    /**
     * @return string|null
     */
    public function getOpenapi(): ?string
    {
        return $this->openapi;
    }


    /**
     * @param string|null $openapi
     */
    public function setOpenapi(?string $openapi): void
    {
        $this->openapi = $openapi;
    }


    /**
     * @return Info|null
     */
    public function getInfo(): ?Info
    {
        return $this->info;
    }


    /**
     * @param Info|null $info
     */
    public function setInfo(?Info $info): void
    {
        $this->info = $info;
    }


    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }


    /**
     * @param Server[] $servers
     */
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }


    /**
     * @return PathItem[]
     */
    public function getPaths(): ?array
    {
        return $this->paths;
    }


    /**
     * @param PathItem[] $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }


    /**
     * @return Components|null
     */
    public function getComponents(): ?Components
    {
        return $this->components;
    }


    /**
     * @param Components|null $components
     */
    public function setComponents(?Components $components): void
    {
        $this->components = $components;
    }


    /**
     * @return SecurityRequirement[]|null
     */
    public function getSecurity(): ?array
    {
        return $this->security;
    }


    /**
     * @param SecurityRequirement[]|null $security
     */
    public function setSecurity(?array $security): void
    {
        $this->security = $security;
    }


    /**
     * @return Tag[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }


    /**
     * @param Tag[]|null $tags
     */
    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * @return ExternalDocumentation|null
     */
    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
    }


    /**
     * @param ExternalDocumentation|null $externalDocs
     */
    public function setExternalDocs(?ExternalDocumentation $externalDocs): void
    {
        $this->externalDocs = $externalDocs;
    }


}