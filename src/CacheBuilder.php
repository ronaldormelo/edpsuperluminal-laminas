<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Scanner\FileScanner;

class CacheBuilder
{
    protected $knownClasses = [];

    /**
     * @var CacheCodeGenerator
     */
    protected $cacheCodeGenerator;

    /**
     * @var ShouldCacheClassSpecification
     */
    protected $shouldCacheClass;

    /**
     * @param CacheCodeGenerator $cacheCodeGenerator
     * @param ShouldCacheClassSpecification $shouldCacheClass
     */
    public function __construct(CacheCodeGenerator $cacheCodeGenerator, ShouldCacheClassSpecification $shouldCacheClass)
    {
        $this->cacheCodeGenerator = $cacheCodeGenerator;
        $this->shouldCacheClass = $shouldCacheClass;
    }

    private function visit($sKey, &$aSources, &$aSorted)
    {
        // If source has not been visited
        if (!$aSources[$sKey]['visited']) {
            $aSources[$sKey]['visited'] = true;
            foreach ($aSources[$sKey]['depends'] as $sDepends) {
                // Call this function for each source
                if (isset($aSources[$sDepends])) {
                    $this->visit($sDepends, $aSources, $aSorted);
                } else {
                    if (substr($sDepends, 0, 7) == "Laminas") {
                        printf("The source '%s' depends on '%s' but there are no source with that name <br >",
                            $aSources['name'], $sDepends);
                    }
                }
            }
            // Add source to sorted array
            $aSorted[] = $aSources[$sKey];
        }
    }

    public function sort($aSources)
    {
        $aSorted = [];

        // Reset
        foreach ($aSources as &$oSource) {
            $oSource['visited'] = false;
        }
        // Loop through each source
        foreach ($aSources as $sKey => $oSource) {
            // Set visited to true
            $this->visit($sKey, $aSources, $aSorted);
        }

        // Just return sources
        return $aSorted;
    }

    /**
     * Cache declared interfaces and classes to a single file
     * @param string
     * @return void
     * @todo - extract the file_put_contents / php_strip_whitespace calls or figure out a way to mock the filesystem
     *
     */
    public function cache($classCacheFilename)
    {
        if (file_exists($classCacheFilename)) {
            $this->reflectClassCache($classCacheFilename);
            $code = file_get_contents($classCacheFilename);
        } else {
            $code = "<?php\n";
        }

        $traits = get_declared_traits();
        $interfaces = get_declared_interfaces();
        $classes = get_declared_classes();
        sort($traits);
        sort($interfaces);
        sort($classes);
        $classes = array_merge($traits, $interfaces, $classes);

        // Create Dependancy List
        $aClasses = [];
        foreach ($classes as $sClass) {

            $oReflection = new ClassReflection($sClass);
            if (!$this->shouldCacheClass->isSatisfiedBy($oReflection)) {
                continue;
            }
            // Skip any classes we already know about
            if (array_key_exists($oReflection->getName(), $aClasses)) {
                continue;
            }
            $aClasses[$sClass] = [
                // We lose our key during sorting process!
                'name' => $sClass,
                'depends' => array_filter(array_merge(
                    array_map(function ($o) {
                        return $o->getName();
                    }, $oReflection->getInterfaces()),
                    [($oReflection->getParentClass() ? $oReflection->getParentClass()->getName() : '')],
                    array_map(function ($o) {
                        return $o->getName();
                    }, $oReflection->getTraits())
                )),
            ];
        }

        $aClasses[] = [
            'name' => '',
            'depends' => [],
        ];

        // Sort By Dependancy
        $aClassesSortedDuplicados = $this->sort($aClasses);

        $aClassesSorted = [];
        foreach ($aClassesSortedDuplicados as $oClass) {

            foreach ($aClassesSorted as $key => $aClasse) {
                if (isset($aClasse['name']) && $aClasse['name'] == $oClass['name']) {

                    unset($aClassesSorted[$key]);
                }
            }

            $aClassesSorted[] = $oClass;
        }

        $aDone = [];
        foreach ($aClassesSorted as $oClass) {
            if (in_array($oClass['name'], $aDone, true)) {
                printf("Already done " . $oClass['name'] . "<br />");
                continue;
            }
            foreach ($oClass['depends'] as $sDepends) {
                if (isset($aClasses[$sDepends]) && !in_array($sDepends, $aDone, true)) {
                    printf("Not yet done $sDepends for " . $oClass['name'] . " Sort Function is incomplete? <br />");
                }
            }
            $oClassReflection = new ClassReflection($oClass['name']);
            $aDone[] = $oClass['name'];
            $code .= $this->cacheCodeGenerator->getCacheCode($oClassReflection);
        }

        file_put_contents($classCacheFilename, $code);
        // minify the file
        file_put_contents($classCacheFilename, php_strip_whitespace($classCacheFilename));
    }

    /**
     * Determine what classes are present in the cache
     *
     * @param $classCacheFilename
     * @return void
     */
    protected function reflectClassCache($classCacheFilename)
    {
        $scanner = new FileScanner($classCacheFilename);
        $this->knownClasses = array_unique($scanner->getClassNames());
    }
}
