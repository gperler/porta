<?php

declare(strict_types=1);

namespace Synatos\Porta\Reference;

use Synatos\Porta\Contract\ReferenceLoader;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Reference;

class DefaultReferenceLoader implements ReferenceLoader
{
    const EXCEPTION_REMOTE_PART_MUST_BE_SET = "Remote part must be set";

    const EXCEPTION_FILE_DOES_NOT_EXIST = "File '%s' does not exist. Base path '%s'.";

    const EXCEPTION_FILE_DOES_NOT_CONTAIN_JSON = "File '%s' does not contain JSON. Base path '%s'.";

    const EXCEPTION_LOCAL_PART_NOT_EXISTING = "Local part '%s' does not exist in file '%s'. Base path '%s'.";


    /**
     * @var string
     */
    private $basePath;


    /**
     * DefaultReferenceLoader constructor.
     */
    public function __construct()
    {
        $this->basePath = getcwd();
    }


    /**
     * @param Reference $reference
     *
     * @return array|null
     * @throws InvalidReferenceException
     */
    public function loadReference(Reference $reference): ?array
    {
        $data = $this->loadFile($reference);
        return $this->resolveLocalPart($reference, $data);
    }


    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }


    /**
     * @param Reference $reference
     * @param array $data
     *
     * @return array
     * @throws InvalidReferenceException
     */
    private function resolveLocalPart(Reference $reference, array $data): array
    {
        $localPart = $reference->getLocalPart();
        if ($localPart === null) {
            return $data;
        }
        $partList = $reference->getLocalPartPartList();
        foreach ($partList as $part) {
            if (!isset($data[$part]) || !is_array($data[$part])) {
                $message = sprintf(self::EXCEPTION_LOCAL_PART_NOT_EXISTING, $localPart, $reference->getRemotePart(), $this->basePath);
                throw new InvalidReferenceException($message);
            }
            $data = $data[$part];
        }

        return $data;
    }


    /**
     * @param Reference $reference
     *
     * @return array
     * @throws InvalidReferenceException
     */
    private function loadFile(Reference $reference): array
    {
        $remotePart = $reference->getRemotePart();
        if ($remotePart === null) {
            throw new InvalidReferenceException(self::EXCEPTION_REMOTE_PART_MUST_BE_SET);
        }

        $fileName = $this->basePath . '/' . $remotePart;
        if (!file_exists($fileName)) {
            $this->throwException($reference, self::EXCEPTION_FILE_DOES_NOT_EXIST);
        }

        $content = file_get_contents($fileName);
        $array = json_decode($content, true);
        if ($array === null) {
            $this->throwException($reference, self::EXCEPTION_FILE_DOES_NOT_CONTAIN_JSON);
        }

        return $array;
    }


    /**
     * @param Reference $reference
     * @param string $message
     *
     * @throws InvalidReferenceException
     */
    private function throwException(Reference $reference, string $message)
    {
        $message = sprintf($message, $reference->getRemotePart(), $this->basePath);
        throw new InvalidReferenceException($message);
    }


}