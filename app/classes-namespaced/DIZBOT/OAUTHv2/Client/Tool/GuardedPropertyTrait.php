<?php


namespace DIZBOT\OAUTHv2\Client\Tool;

/**
 * Provides support for blacklisting explicit properties from the
 * mass assignment behavior.
 */
trait GuardedPropertyTrait
{
    /**
     * The properties that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Attempts to mass assign the given options to explicitly defined properties,
     * skipping over any properties that are defined in the guarded array.
     *
     * @param array $options
     * @return mixed
     */
    protected function fillProperties(array $options = [])
    {
        if (isset($options['guarded'])) {
            unset($options['guarded']);
        }

        foreach ($options as $option => $value) {
            if (property_exists($this, $option) && !$this->isGuarded($option)) {
                $this->{$option} = $value;
            }
        }
    }

    /**
     * Returns current guarded properties.
     *
     * @return array
     */
    public function getGuarded()
    {
        return $this->guarded;
    }

    /**
     * Determines if the given property is guarded.
     *
     * @param  string  $property
     * @return bool
     */
    public function isGuarded($property)
    {
        return in_array($property, $this->getGuarded());
    }
}
